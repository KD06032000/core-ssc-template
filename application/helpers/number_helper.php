<?php
/**
 * User: linhth
 * Date: 13/03/2019
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('formatMoney')) {
  /*
  * hàm định dạng tiền tệ
  * number: số tiền
  * unit:   đơn vị
  * prefix: đợn vị đặt ở đầu hay cuối ví dụ như $ đặt ở đầu
  * decimals: số sau dấu phảy
  */
  function formatMoney($number, $unit = 'đ', $prefix = true, $decimals = 0)
  {
    return $prefix ? number_format($number, $decimals, ',', '.') . ' ' . $unit : $unit . number_format($number, $decimals, ',', '0');
  }

}

if (!function_exists('status_display')) {
  function status_display($type)
  {

    switch ($type) {
      case 0:
        $title = lang('text_status_0');
        break;
      case 1:
        $title = lang('text_status_1');
        break;
      case 2:
        $title = lang('text_status_2');
        break;
      default:
        $title = 'text_status_0';
        break;
    }
    return $title;
  }
}