<?php

namespace APS_Momo\Support;

class RSAEncrypt
{
    /**
     * Khóa dùng để mã hóa dữ liệu.
     *
     * @var string
     */
    protected $publicKey;
    /**
     * Khởi tạo đối tượng DataEncrypt.
     *
     * @param  string  $publicKey
     */
    public function __construct($publicKey)
    {
        $this->publicKey = $publicKey;
    }
    /**
     * Trả về chuỗi mã hóa của dữ liệu truyền vào.
     *
     * @param  array  $data
     * @return string
     */
    public function encrypt($data)
    {
        $data = json_encode($data);
        openssl_public_encrypt($data, $dataEncrypted, $this->publicKey, OPENSSL_PKCS1_PADDING);
        return base64_encode($dataEncrypted);
    }
}