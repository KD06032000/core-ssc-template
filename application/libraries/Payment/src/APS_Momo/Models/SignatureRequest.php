<?php

namespace APS_Momo\Models;

use APS_Momo\Support\Signature;

class SignatureRequest
{
    protected $partnerInfo;
    protected $parameter;
    protected $signature;

    public function __construct()
    {
        $this->partnerInfo = new PartnerInfo();
        $this->signature = new Signature($this->partnerInfo->getSecretKey());
    }

    public function generateSignature($data)
    {
        $rawData = [];
        try {
            foreach ($this->parameter as $item) {
                if (isset($data[$item])) {
                    $rawData[$item] = $data[$item];
                } else {
                    throw new MomoException(sprintf('The `%s` parameter is required', $item));
                }
            }
        } catch (MomoException $exception) {
            log_message('error', $exception->getErrorMessage());
        }
        return $this->signature->generate($rawData);
    }

    public function validateSignature($data, $expect)
    {
        $actual = $this->generateSignature($data);
        return $this->signature->validate($actual, $expect);
    }

    public function getSignatureParameters($parameter)
    {
        $this->parameter = $parameter;
    }
}