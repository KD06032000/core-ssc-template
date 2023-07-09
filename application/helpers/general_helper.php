<?php

/**
 * User: linhth
 * Date: 25/03/2019
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_list_position_banner()
{
    return [
        1 => 'Banner trang chủ',
        2 => 'Banner Gioi thiệu',
        3 => 'Banner Khách hàng',
        4 => 'Banner Bảng giá',
        5 => 'Banner Hỗ trợ'
        ];

}

function _isMobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function record_sort($records, $field, $reverse = false)
{
    $hash = array();

    foreach ($records as $record) {
        $record = (array)$record;
        $hash[$record[$field]] = $record;
    }

    ($reverse) ? krsort($hash) : ksort($hash);

    $records = array();

    foreach ($hash as $record) {
        $records [] = $record;
    }

    return $records;
}

