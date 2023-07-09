<?php
/**
 * Cấu hình thông tin thanh toán MOMO vs VnPay
 */

$config = [
    'prefix_bill' => [
        'document' => 'TTPN-DOCS-',
        'number' => '1000000000'
    ],
    'viettelpay' => [
        'url_redirect' => 'https://sandbox.viettel.vn/PaymentGateway/payment?',
        'access_code' => 'd41d8cd98f00b204e9800998ecf8427e5bcfd3ee9d81dc591093ccccb89e33fc',
        'hash_key' => 'd41d8cd98f00b204e9800998ecf8427eb81dbbbe11ab77d47dbd123066bd5af2',
        'merchant_code' => 'TTPNPT1',
    ],
    'momo' => [
        'environment' => 'development', //development|production
        'access_key' => '7M1eJ2aDncAOxIvt',
        'partner_code' => 'MOMOAHND20191210',
        'secret_key' => 'MPb5VDurMartszsgpdNhL6PH6z0JbJhQ',
        'uri_endpoint_production' => 'https://payment.momo.vn',
        'uri_endpoint_development' => 'https://test-payment.momo.vn'
    ],
    'vnpay' => [
        'payment_vnPay_tmnCode' => '84RACE01',
        'payment_vnPay_hashSecret' => 'LBBGMOLSGBUMHFLVJRBLCNDGEJUJCXRE',
        'payment_vnPay_apiSend' => 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'payment_vnPay_apiResponse' => 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html',
        'payment_vnPay_return' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment/payment_vnpay_callback',
        'payment_vnPay_type' => 150000,
    ]
];