<?php

class GoogleAnalytics
{
    protected $view_id;
    protected $config;
    protected $cacheLifeTimeInMinutes = 0;
    protected $ci;

    public function __construct($config = array(), $view_id = null)
    {
        $this->config = $config;
        $this->view_id = $view_id;
        $this->ci =& get_instance();
        $this->ci->load->driver('cache', array('adapter' => 'file'));
    }

    public function getAnalyticsService()
    {
        return $this->initializeAnalytics();
    }

    public function setCacheLifeTimeInMinutes($cacheLifeTimeInMinutes)
    {
        $this->cacheLifeTimeInMinutes = $cacheLifeTimeInMinutes;
        return $this;
    }

    public function setViewId($viewId)
    {
        $this->view_id = $viewId;
        return $this;
    }

    public function fetchVisitorsAndPageViews($startDate, $endDate)
    {
        $result = [];
        $response = $this->performQuery(
            $startDate,
            $endDate,
            'ga:users,ga:pageviews',
            ['dimensions' => 'ga:date,ga:pageTitle']
        );
        if ($response->getTotalResults() > 0) {
            $result = array_map(function ($item) {
                return array(
                    'data'      => date_create_from_format('Ymd', $item[0]),
                    'pageTitle' => $item[1],
                    'visitors'  => $item[2],
                    'pageViews' => $item[3]
                );
            }, $response->getRows());
        }
        return $result;
    }

    public function fetchTotalVisitorsAndPagesViews($startDate, $endDate)
    {
        $result = [];
        $response = $this->performQuery(
            $startDate,
            $endDate,
            'ga:users,ga:pageviews',
            ['dimensions' => 'ga:date']
        );

        if ($response->count()) {
            $result = array_map(function ($item) {
                return array(
                    'date'      => date_create_from_format('Ymd', $item[0]),
                    'visitors'  => (int)$item[1],
                    'pageViews' => (int)$item[2],
                );
            }, $response->getRows());
        }
        return $result;
    }

    public function fetchMostVisitedPages($startDate, $endDate, $maxResults = 20)
    {
        $result = [];
        $response = $this->performQuery(
            $startDate,
            $endDate,
            'ga:pageviews',
            [
                'dimensions'  => 'ga:pagePath,ga:pageTitle',
                'sort'        => '-ga:pageviews',
                'max-results' => $maxResults,
            ]
        );

        if ($response->count()) {
            $result = array_map(function ($item) {
                return array(
                    'url'       => $item[0],
                    'pageTitle' => $item[1],
                    'pageViews' => (int)$item[2],
                );
            }, $response->getRows());
        }
        return $result;
    }

    public function fetchTopReferrers($startDate, $endDate, $maxResults)
    {
        $result = [];
        $response = $this->performQuery(
            $startDate,
            $endDate,
            'ga:pageviews',
            [
                'dimensions'  => 'ga:fullReferrer',
                'sort'        => '-ga:pageviews',
                'max-results' => $maxResults,
            ]);
        if ($response->getTotalResults() > 0) {
            $result = array_map(function ($item) {
                return array(
                    'url'       => $item[0],
                    'pageViews' => (int)$item[1]
                );
            }, $response->getRows());
        }
        return $result;
    }

    public function fetchUserTypes($startDate, $endDate)
    {
        $result = [];
        $response = $this->performQuery(
            $startDate,
            $endDate,
            'ga:sessions',
            [
                'dimensions' => 'ga:userType',
            ]
        );
        if ($response->count()) {
            $result = array_map(function ($item) {
                return array(
                    'type'    => $item[0],
                    'session' => (int)$item[1]
                );
            }, $response->getRows());
        }
        return $result;
    }

    public function fetchTopBrowser($startDate, $endDate, $maxResults)
    {
        $result = [];
        $response = $this->performQuery(
            $startDate,
            $endDate,
            'ga:sessions',
            [
                'dimensions' => 'ga:browser',
                'sort'       => '-ga:sessions',
            ]
        );
        if ($response->count()) {
            $result = array_map(function ($item) {
                return [
                    'browser'  => $item[0],
                    'sessions' => (int)$item[1],
                ];
            }, $response->getRows());
        }
        if ($response->count() > $maxResults) {
            return $this->summarizeTopBrowsers($result, $maxResults);
        }
        return $result;
    }

    public function performQuery($startDate, $endDate, $metrics, $others = [])
    {
        $cache_name = $this->determineCacheName(func_get_args());
        $data=$this->ci->cache->get($cache_name);
        if (!$data) {
            $google_analytic = $this->initializeAnalytics();
            $startDate = $this->formatDate($startDate, 'Y-m-d');
            $endDate = $this->formatDate($endDate, 'Y-m-d');
            $result = $google_analytic->data_ga->get('ga:' . $this->view_id, $startDate, $endDate, $metrics, $others);
            while ($nextLink = $result->getNextLink()) {
                if (isset($others['max-results']) && count($result->rows) >= $others['max-results']) {
                    break;
                }
                $options = [];
                parse_str(substr($nextLink, strpos($nextLink, '?') + 1), $options);
                $response = $this->getAnalyticsService()->data_ga->call('get', [$options], 'Google_Service_Analytics_GaData');
                if ($response->rows) {
                    $result->rows = array_merge($result->rows, $response->rows);
                }
                $result->nextLink = $response->nextLink;
            }
          $data=$result;
            $this->ci->cache->save($cache_name, $result, $this->cacheLifeTimeInMinutes*30);
        }
        return $data;
    }

    private function summarizeTopBrowsers($data, $maxResults)
    {
        $result = array_slice($data, 0, ($maxResults - 1));
        array_push($result, [
            'browser'  => 'Others',
            'sessions' => array_sum(array_column(array_slice($data, ($maxResults - 1)), 'sessions'))
        ]);
        return $result;
    }

    private function initializeAnalytics()
    {
        $client = new Google_Client();
        $client->setApplicationName('APS_Analytics');
        $client->setAuthConfig($this->config);
        $client->setScopes([Google_Service_Analytics::ANALYTICS_READONLY]);
        return new Google_Service_Analytics($client);
    }

    private function isDate($datetime)
    {
        return ((strpos($datetime, '0000-00-00') !== false) || (strpos($datetime, '1970-01-01') !== false)) ? false : true;
    }

    private function formatDate($datetime, $format = 'Y-m-d')
    {
        if (!$this->isDate($datetime)) {
            return date($format, time());
        }
        return date($format, strtotime($datetime));
    }

    protected function determineCacheName(array $properties)
    {
        return 'aps_google_analytics_' . md5(serialize($properties));
    }
}
