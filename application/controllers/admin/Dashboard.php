<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{

    protected $_post;
    protected $_users;
    protected $_account;
    protected $_property;
    protected $_calendar;
    protected $google_analytics;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('GoogleAnalytics');
        $this->lang->load('dash_lang');
        $this->load->model(['post_model','users_model','account_model','property_model']);
        $this->_post = new Post_model();
        $this->_users = new Users_model();
        $this->_account = new Account_model();
        $this->_property = new Property_model();
        $this->google_analytics = new GoogleAnalytics(json_decode($this->get_setting('service_account_credentials'), true), $this->get_setting('view_id'));
        $this->google_analytics->setCacheLifeTimeInMinutes(5);
        $this->flag_delete = false;
    }
    public function index()
    {
        $data['heading_title'] = ucfirst($this->router->fetch_class());
        $data['heading_description'] = 'Tổng quan CMS';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        if($this->session->userdata['user_id'] != 1){
            $data['main_content'] = $this->load->view($this->template_path . 'dashboard/index2', $data, TRUE);
        }else{
            $data['main_content'] = $this->load->view($this->template_path . 'dashboard/index', $data, TRUE);
        }
        $this->load->view($this->template_main, $data);
    }
    function delete_modules($modules = ''){
        if($this->flag_delete){
            if(empty($modules)) die;
            $files = [
                'controller'=>APPPATH .'controllers/admin/'.$modules.'.php',
                'model'=>APPPATH.'models/'.$modules.'_model.php',
                'view'=> APPPATH.'views/admin/' . $modules,
                'js'=>  FCPATH.'/public/admin/js/pages/'.$modules.'.js',

            ];
            foreach ($files as $key => $item) {
                if(file_exists($item)){
                    $this->delete_file($item);
                }
            }
            echo '<pre>';
            var_dump($files);
            echo '</pre>';
            echo 'Xóa thành công ' .$modules;
        }

    }
    function delete_file($path)
    {
        if($this->flag_delete){
            if (is_dir($path) === true)
            {
                $files = array_diff(scandir($path), array('.', '..'));

                foreach ($files as $file)
                {
                    $this->delete_file(realpath($path) . '/' . $file);
                }

                return rmdir($path);
            }

            else if (is_file($path) === true)
            {
                return unlink($path);
            }

            return false;
        }
    }
    public function ajax_total()
    {
        sleep(1);
        $output['total_post'] = $this->_post->getTotalAll();
        $output['total_user'] = $this->_users->getTotalAll();
        $output['total_calendar'] = $this->_calendar->getTotalAll();

        echo json_encode($output);
        exit;
    }

    public function notPermission()
    {
        $data['heading_title'] = 'Cấm truy cập !';
        $data['heading_description'] = 'Trang không được quyền truy cập !';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . 'not_permission', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function notFound()
    {
        $data['heading_title'] = '404 Not found !';
        $data['heading_description'] = 'Trang không tồn tại !';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . '404', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    public function analytic_false(){
        $this->returnJson('<p class="text-center">Vui lòng nhập thông tin google analytic trong cấu hình </p>');
    }
    public function general_data()
    {
        try {
            $request = $this->convertData();
            $data = [];
            $data['country_stats'] = $this->get_country_stats($request['startDate'], $request['endDate']);
            $data['visitor_stats'] = $this->get_visitor_stats($request['startDate'], $request['endDate'], $request['dimensions'], $request['rangeDate']);
            $data['general_total'] = $this->get_total_data($request['startDate'], $request['endDate']);
            $html = $this->load->view($this->template_path . 'dashboard/block/general', $data, TRUE);
            return $this->returnJson($html);
        } catch (Exception $e) {
            $this->analytic_false();
        }

    }

    private function get_visitor_stats($startDate, $endDate, $dimensions, $dateRange)
    {
        try {
            $response = $this->google_analytics->performQuery($startDate, $endDate, 'ga:visits,ga:pageviews', ['dimensions' => 'ga:' . $dimensions]);
            if ($response->rows == null) {
                $this->returnJson($response);
            }
            $visitorData = [];
            if ($dimensions == 'hour') {
                $visitorData = array_map(function ($item) {
                    return [
                        'axis'      => (int)$item[0] . 'h',
                        'visitors'  => $item[1],
                        'pageViews' => $item[2],
                    ];
                },$response->getRows());
            } else {
                foreach ($response->getRows() as $key => $item) {
                    array_push($visitorData, [
                        'axis'      => $dateRange[$key],
                        'visitors'  => $item[1],
                        'pageViews' => $item[2],
                    ]);
                }
            }
            return $visitorData;
        } catch (Exception $e) {
            $this->analytic_false();
        }

    }


    private function get_country_stats($startDate, $endDate)
    {
        try {
            $country_stats = [];
            $response = $this->google_analytics->performQuery($startDate, $endDate, 'ga:sessions', ['dimensions' => 'ga:countryIsoCode'])->getRows();
            if (count($response)) {
                foreach ($response as $item) {
                    $country_stats[$item[0]] = $item[1];
                }
            }
            return $country_stats;
        } catch (Exception $e) {
            $this->analytic_false();
        }
    }

    private function get_total_data($startDate, $endDate)
    {
        return $this->google_analytics->performQuery(
            $startDate,
            $endDate,
            'ga:sessions, ga:users, ga:pageviews, ga:percentNewSessions, ga:bounceRate, ga:pageviewsPerVisit, ga:avgSessionDuration, ga:newUsers'
        )->getTotalsForAllResults();
    }

    public function get_top_visit_page()
    {
        try {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
            $top_visit = $this->google_analytics->fetchMostVisitedPages($startDate, $endDate, 10);
            $html = $this->load->view($this->template_path . 'dashboard/block/top_visited_page', ['top_visit' => $top_visit], TRUE);
            $this->returnJson($html);

        } catch (Exception $e) {
            $this->analytic_false();
        }
    }

    public function get_top_browser()
    {
        try {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
            $top_browser = $this->google_analytics->fetchTopBrowser($startDate, $endDate, 10);
            $html = $this->load->view($this->template_path . 'dashboard/block/top_browser', ['top_browser' => $top_browser], TRUE);
            $this->returnJson($html);
        } catch (Exception $e) {
            $this->analytic_false();
        }
    }

    public function get_top_referrers()
    {
        try {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
            $top_referrers = $this->google_analytics->fetchTopReferrers($startDate, $endDate, 10);
            $html = $this->load->view($this->template_path . 'dashboard/block/top_referrers', ['top_referrers' => $top_referrers], TRUE);
            $this->returnJson($html);
        } catch (Exception $e) {
            $this->analytic_false();
        }
    }

    private function convertData()
    {
        $this->checkRequestGetAjax();
        $request = $this->input->get();
        $data = [];
        if (!empty($request['startDate']) && $request['endDate']) {
            $data['startDate'] = $request['startDate'];
            $data['endDate'] = $request['endDate'];
        } else {
            $data['startDate'] = date('Y-m-d');
            $data['endDate'] = date('Y-m-d');
        }
        $interval = date_diff(date_create($data['startDate']), date_create($data['endDate']));
        if ($interval->days !== 0) {
            $data['dimensions'] = 'day';
            $data['rangeDate'] = date_range($data['startDate'], $data['endDate'], true, 'd-m-Y');
        } else {
            $data['dimensions'] = 'hour';
            $data['rangeDate'] = [];
        }
        return $data;
    }
}
