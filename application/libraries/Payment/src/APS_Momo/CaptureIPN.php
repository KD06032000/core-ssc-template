<?php

namespace APS_Momo;

use APS_Momo\Models\CaptureIPNRequest;
use APS_Momo\Models\CaptureIPNResponse;
use APS_Momo\Models\MomoException;
use APS_Momo\Models\Parameter;
use APS_Momo\Support\Converter;

class CaptureIPN extends Process
{
    public function __construct()
    {
        parent::__construct();
    }

    public function process($rawData)
    {
        $captureIPNRequest = $this->getIPNInformation($rawData);
        header("Content-Type: application/json;charset=UTF-8");
        if (empty($captureIPNRequest)) {
            http_response_code(400);
            header(' 400 Bad Request');
            $payload = json_encode(array("message" => "Bad Request"));
        } else {
            http_response_code(200);
            header(' 200 OK');
            $this->getSignatureParameters(
                ['partnerCode', 'accessKey', 'requestId', 'orderId', 'errorCode', 'message', 'responseTime', 'extraData']
            );
            $responseIpn = new CaptureIPNResponse($rawData);
            $data = Converter::objectToArray($responseIpn);
            $data[Parameter::SIGNATURE] = $this->generateSignature($data);
            $payload = json_encode($data, true);
        }
        return $payload;
    }

    public function getIPNInformation($result)
    {
        $ipn = new CaptureIPNRequest($result);
        try {
            if ($this->partnerInfo->getPartnerCode() != $ipn->getPartnerCode()) {
                throw new MomoException('Wrong PartnerCode');
            }
            if ($this->partnerInfo->getAccessKey() != $ipn->getAccessKey()) {
                throw new MomoException('Wrong AccessKey');
            }
            $this->getSignatureParameters([
                'partnerCode', 'accessKey', 'requestId', 'amount', 'orderId', 'orderInfo', 'orderType', 'transId',
                'message', 'localMessage', 'responseTime', 'errorCode', 'payType', 'extraData'
            ]);
            $data = Converter::objectToArray($ipn);
            if ($this->validateSignature($data, $ipn->getSignature())) {
                throw new MomoException('Wrong signature form MoMo site');
            }
            return $ipn;
        } catch (MomoException $exception) {
            log_message('error', $exception->getErrorMessage());
        }
        return null;
    }
}