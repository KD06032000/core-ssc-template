<?php
// Kiểm tra user đó có được sử dụng hay ko
 function receivingedAccount($voucher_id, $account_id,$orderModel)
{
  $_this=$_this =& get_instance();
  $order_voucher = $orderModel->receivingedAccount($voucher_id, $account_id);
  if (!empty($order_voucher)) {
    $message = array(
      'message' => lang('you_have_used'),
      'type' => 'warning'
    );
    die(json_encode($message));
  }
  return true;
}

// Kiểm tra ngày sử dụng của voucher
 function check_date($start_time, $end_time)
{
  $_this=$_this =& get_instance();
  $start_time = date('Y-m-d', strtotime($start_time));
  $end_time = date('Y-m-d', strtotime($end_time));
  $today = date('Y-m-d');

  if ($today > $end_time) {
    $message = array(
      'message' => lang('voucher_expired'),
      'type' => 'warning'
    );
    die(json_encode($message));
  } else if ($end_time > $today && $start_time > $today) {
    $message = array(
      'message' => lang('voucher_not_time'),
      'type' => 'warning'
    );
    die(json_encode($message));
  } else {
    return true;
  }
}

// Kiểm tra voucher đó còn lượt sử dụng hay là không
 function check_total_use($remaining_use, $total_use)
{
  $_this=$_this =& get_instance();
  if ($remaining_use >= $total_use) {
    $message = array(
      'message' => lang('voucher_has_been'),
      'type' => 'warning'
    );
    die(json_encode($message));
  }
  return true;
}
