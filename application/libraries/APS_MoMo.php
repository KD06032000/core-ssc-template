<?php
(! defined('BASEPATH')) and exit('No direct script access allowed');
require( dirname(__FILE__) . '/'.'Payment/src/autoload.php');

use APS_Momo\CaptureIPN;
use APS_Momo\CaptureMoMo;
use APS_Momo\QueryStatusTransaction;

class APS_MoMo
{
    public function processCapture($data)
    {
        $captureMoMo = new CaptureMoMo();
        return $captureMoMo->process($data);
    }

    public function processIPN($data)
    {
        $captureIPN = new CaptureIPN();
        return $captureIPN->process($data);
    }

    public function processStatus($orderId, $requestId)
    {
        $queryStatus = new QueryStatusTransaction();
        return $queryStatus->process($orderId, $requestId);
    }
}