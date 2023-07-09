<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class GoogleCalendar
{
    public static function checkClient()
    {
        $message = '';

        try {
            $client = new Google_Client();
            $client->setApplicationName('Google Calendar API GNH');
            $client->setScopes(Google_Service_Calendar::CALENDAR);
            $client->setAuthConfig('credentials.json');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $tokenPath = 'token.json';
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
                $message = 'exist';

                goto end;
            }

            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    if (!file_exists(dirname($tokenPath))) {
                        mkdir(dirname($tokenPath), 0777, true);
                    }
                    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
                } else {
                    // Request authorization from the user.
                    $message = $client->createAuthUrl();
                }
            }

            end:
            return [
                "error" => false,
                "message" => $message
            ];
        } catch (Exception $e) {

            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function applyToken($authCode)
    {
        $message = '';

        try {
            $client = new Google_Client();
            $client->setApplicationName('Google Calendar API GNH');
            $client->setScopes(Google_Service_Calendar::CALENDAR);
            $client->setAuthConfig('credentials.json');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $tokenPath = 'token.json';

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }

            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0777, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));

            end:
            return [
                "error" => false,
                "message" => $message
            ];
        } catch (Exception $e) {

            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }

    protected static function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API GNH');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                throw new Exception('Bạn chưa kích hoạt API');
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0777, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public static function InsertData($data)
    {
        try {
            $calendar = self::ConvertData($data);
            $client = self::getClient();
            $service = new Google_Service_Calendar($client);
            $event = new Google_Service_Calendar_Event($calendar);

            $calendarId = 'primary';
            $event = $service->events->insert($calendarId, $event);
            return $event->id;
        } catch (Exception $e) {
            return '';
        }
    }

    public static function UpdateData($data, $event_id)
    {
        try {
            $calendar = self::ConvertData($data);
            $client = self::getClient();
            $service = new Google_Service_Calendar($client);
            $event = new Google_Service_Calendar_Event($calendar);

            $calendarId = 'primary';
            return $service->events->update($calendarId, $event_id, $event);
        } catch (Exception $e) {
            return '';
        }
    }

    public static function DeleteData($event_id)
    {
        try {
            $client = self::getClient();
            $service = new Google_Service_Calendar($client);
            $calendarId = 'primary';
            return $service->events->delete($calendarId, $event_id);

        } catch (Exception $e) {
            return '';
        }
    }

    protected static function ConvertData($data)
    {
        $title = !empty($data['title']['vi']) ? $data['title']['vi'] : '----';
        $description = !empty($data['description']['vi']) ? $data['description']['vi'] : '';

        $start = date("c", strtotime($data['start_date'] . ' ' . $data['start_time']));;
        $end = date("c", strtotime($data['end_date'] . ' ' . $data['end_time']));

        return [
            'summary' => $title,
            'description' => $description,
            'start' => array(
                'dateTime' => $start,
                'timeZone' => 'Asia/Ho_Chi_Minh',
            ),
            'end' => array(
                'dateTime' => $end,
                'timeZone' => 'Asia/Ho_Chi_Minh',
            )
        ];
    }
}