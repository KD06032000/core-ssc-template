<?php

namespace APS_Momo;

use APS_Momo\Models\CaptureMoMoRequest;
use APS_Momo\Models\CaptureMoMoResponse;
use APS_Momo\Models\MomoException;
use APS_Momo\Models\Parameter;
use APS_Momo\Support\Converter;
use APS_Momo\Support\HttpRequest;

class CaptureMoMo extends Process
{
    public function __construct()
    {
        parent::__construct();
    }

    public function process($data)
    {
        $captureMoMoRequest = new CaptureMoMoRequest($data);
        return $this->execute($captureMoMoRequest);
    }

    public function execute(CaptureMoMoRequest $captureMoMoRequest)
    {
        try {
            $httpRequest = new HttpRequest();
            $this->getSignatureParameters(
                ['partnerCode', 'accessKey', 'requestId', 'amount', 'orderId', 'orderInfo', 'returnUrl', 'notifyUrl', 'extraData']
            );
            $captureMoMoRequest->setPartnerCode($this->partnerInfo->getPartnerCode());
            $captureMoMoRequest->setAccessKey($this->partnerInfo->getAccessKey());
            $data = Converter::objectToArray($captureMoMoRequest);
            $captureMoMoRequest->setSignature($this->generateSignature($data));
            $data = Converter::objectToJsonStrNoNull($captureMoMoRequest);
            $response = $httpRequest->execute($this->endpointUri . Parameter::PAY_GATE_URI, $data);
            if ($response->getStatusCode() != 200) {
                throw new MoMoException('[CaptureMoMoResponse][' . $captureMoMoRequest->getOrderId() . '] -> Error API');
            }
            $captureMoMoResponse = new CaptureMoMoResponse(json_decode($response->getBody(), true));
            return $this->checkResponse($captureMoMoResponse);
        } catch (MomoException $exception) {
            log_message('error', $exception->getErrorMessage());
        }
        return null;
    }

    public function checkResponse(CaptureMoMoResponse $captureMoMoResponse)
    {
        try {
            $this->getSignatureParameters(
                ['requestId', 'orderId', 'message', 'localMessage', 'payUrl', 'errorCode', 'requestType']
            );
            $data = Converter::objectToArray($captureMoMoResponse);
            if ($this->validateSignature($data, $captureMoMoResponse->getSignature())) {
                return $captureMoMoResponse;
            }
            throw new MoMoException("Wrong signature from MoMo side - please check your data");
        } catch (MomoException $exception) {
            log_message('error', $exception->getErrorMessage());
        }
        return null;
    }
}