<?php


if (!function_exists('renderBillCode')) {
    function renderBillCode($id)
    {
        $_this = &get_instance();
        $_this->load->config('payment');
        $number = (int)strlen($_this->config->item('prefix_bill')['number']);
        $length = strlen(strval($id));

        $code = '';
        for ($i = $number - $length; $i > 0; $i--) {
            $code .= '0';
        }
        return $_this->config->item('prefix_bill')['document'] . $code . $id;
    }
}

if (!function_exists('exportIdByBillCode')) {
    function exportIdByBillCode($billcode)
    {
        $_this = &get_instance();
        $_this->load->config('payment');
        $prefix_bill = $_this->config->item('prefix_bill')['document'];
        return (int)str_replace($prefix_bill, '', $billcode);
    }
}