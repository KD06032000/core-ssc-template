<?php

namespace APS_Momo;

use APS_Momo\Models\MomoException;
use APS_Momo\Models\Parameter;
use APS_Momo\Models\QueryStatusRequest;
use APS_Momo\Models\QueryStatusResponse;
use APS_Momo\Support\Converter;
use APS_Momo\Support\HttpRequest;

class QueryStatusTransaction extends Process
{
    public function __construct()
    {
        parent::__construct();
    }

    public function process($orderId, $requestId)
    {
        try {
            $queryStatusRequest = $this->createQueryStatusRequest($orderId, $requestId);
            return $this->execute($queryStatusRequest);
        } catch (MoMoException $exception) {
            log_message('error', $exception->getErrorMessage());
        }
        return null;
    }

    public function createQueryStatusRequest($orderId, $requestId)
    {
        $raw_data = [
            Parameter::PARTNER_CODE => $this->partnerInfo->getPartnerCode(),
            Parameter::ACCESS_KEY => $this->partnerInfo->getAccessKey(),
            Parameter::ORDER_ID => $orderId,
            Parameter::REQUEST_ID => $requestId
        ];
        return new QueryStatusRequest($raw_data);
    }

    public function execute(QueryStatusRequest $queryStatusRequest)
    {
        try {
            $httpRequest = new HttpRequest();
            $this->getSignatureParameters(
                ['partnerCode', 'accessKey', 'requestId', 'orderId', 'requestType']
            );
            $data = Converter::objectToArray($queryStatusRequest);
            $queryStatusRequest->setSignature($this->generateSignature($data));
            $data = Converter::objectToJsonStrNoNull($queryStatusRequest);
            $response = $httpRequest->execute($this->endpointUri.Parameter::PAY_GATE_URI, $data);
            if ($response->getStatusCode() != 200) {
                throw new MoMoException('[CaptureMoMoIPNRequest][' . $queryStatusRequest->getOrderId() . '] -> Error API');
            }
            $queryStatusResponse = new QueryStatusResponse(json_decode($response->getBody(), true));
            return $this->checkResponse($queryStatusResponse);
        } catch (MomoException $exception) {
            log_message('error', $exception->getErrorMessage());
        }
        return null;
    }

    public function checkResponse(QueryStatusResponse $queryStatusResponse)
    {
        try {
            $this->getSignatureParameters(
                ['partnerCode', 'accessKey', 'requestId', 'orderId', 'errorCode', 'transId', 'amount', 'message', 'localMessage', 'requestType', 'payType', 'extraData']
            );
            $data = Converter::objectToArray($queryStatusResponse);
            if ($this->validateSignature($data, $queryStatusResponse->getSignature()))
                return $queryStatusResponse;
            else
                throw new MoMoException("Wrong signature from MoMo side - please contact with us");
        } catch (MoMoException $exception) {
            log_message('error', '[QueryStatusResponse][' . $queryStatusResponse->getOrderId() . '] -> ' . $exception->getErrorMessage());
        }
        return $queryStatusResponse;
    }
}