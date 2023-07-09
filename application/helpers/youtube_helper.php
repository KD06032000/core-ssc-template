<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Youtube
{
    protected static function getData($url, $part)
    {
        parse_str( parse_url( $url, PHP_URL_QUERY ), $matches );
        if (empty($matches)) {
            return false;
        }
        $id = $matches['v'];
        $response = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=$part&id=$id&key=" . GG_YOUTUBE_API_KEY);
        return json_decode($response, true);
    }

    public static function getThumbnail($url)
    {
        $data = self::getData($url, 'snippet');

        if (!$data) return MEDIA_NAME . getSettings('image_show_default');

        return $data['items'][0]['snippet']['thumbnails']['standard']['url'];
    }

    public static function getDuration($url)
    {
        $data = self::getData($url, 'contentDetails');

        if (!$data) return '00:00';

        $start = new DateTime('@0'); // Unix epoch
        $start->add(new DateInterval($data['items'][0]['contentDetails']['duration']));
        return $start->format('i:s');
    }
}