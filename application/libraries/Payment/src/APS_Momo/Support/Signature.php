<?php

namespace APS_Momo\Support;

class Signature
{
    protected $secretKey;

    /**
     * Khởi tạo đối tượng DataSignature.
     * @param string $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Trả về chữ ký dữ liệu của dữ liệu truyền vào.
     * @param array $data
     * @return string
     */
    public function generate($data)
    {
        $url = urldecode(http_build_query($data));
        return hash_hmac('sha256', $url, $this->secretKey);
    }

    /**
     * Kiểm tra tính hợp lệ của chữ ký dữ liệu so với dữ liệu truyền vào.
     * @param $signature
     * @param string $expect
     * @return bool
     */
    public function validate($signature, $expect)
    {
        return 0 === strcasecmp($signature, $expect);
    }
}