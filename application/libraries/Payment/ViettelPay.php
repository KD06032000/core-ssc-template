<?php

class ViettelPay
{
    protected $CI;
    protected $url_redirect;
    protected $access_code;
    protected $hash_key;
    protected $merchant_code;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->config('payment');

        $this->url_redirect = $this->CI->config->item('viettelpay')['url_redirect'];
        $this->access_code = $this->CI->config->item('viettelpay')['access_code'];
        $this->hash_key = $this->CI->config->item('viettelpay')['hash_key'];
        $this->merchant_code = $this->CI->config->item('viettelpay')['merchant_code'];
    }

    public function get_url($data)
    {
        $order_id = $data['order_id'];
        $billcode = $data['billcode'];
        $command = "PAYMENT";
        $desc = $data['desc'];
        $locale = "Vi";
        $trans_amount = $data['trans_amount'];
        $version = "2.0";
        $login_msisdn = "";
        $customer_bill_info = "";
        $other_info = "{'Type':'No VAT'}";

        $return_url = $data['return_url'];
        $cancel_url = $data['cancel_url'];

        //dữ liệu check sum
        $data = $this->access_code . $billcode . $command . $this->merchant_code . $order_id . $trans_amount . $version;

        $check_sum = $this->hash_password($data, $this->hash_key);
        //chuyển theo đúng định dạng của viettel
        $check_sum = str_replace(" ", "+", $check_sum);
        $check_sum = str_replace("%3d", "=", $check_sum);
        $check_sum = str_replace("%3D", "=", $check_sum);

        //tạo URL post thông tin sang viettel
        $redirect = $this->url_redirect
            . "billcode=" . $billcode
            . "&command=" . $command
            . "&desc=" . $desc
            . "&locale=" . $locale
            . "&merchant_code=" . $this->merchant_code
            . "&order_id=" . $order_id
            . "&return_url=" . $return_url
            . "&cancel_url=" . $cancel_url
            . "&trans_amount=" . $trans_amount
            . "&version=" . $version
            . "&login_msisdn=" . $login_msisdn
            . "&check_sum=" . $check_sum;

        //gửi dữ liệu để thanh toán QRCODE
        $_SESSION['billcode'] = $order_id;
        $_SESSION['amount'] = $trans_amount;
        return $redirect;
    }

    public function hash_password($data, $hash_key)
    {
        return base64_encode(hash_hmac("sha1", $data, $hash_key, $raw_output = true));
    }
}