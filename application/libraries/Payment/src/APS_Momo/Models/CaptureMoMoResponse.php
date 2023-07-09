<?php

namespace APS_Momo\Models;

class CaptureMoMoResponse extends AIOResponse
{
    private $payUrl;
    private $deeplink;
    private $deeplinkWebInApp;
    private $qrCodeUrl;

    public function __construct($params = array())
    {
        parent::__construct($params);
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (array_key_exists($key, $params)) {
                $this->{$key} = $params[$key];
            }
        }

        $this->setRequestType('captureMoMoWallet');
    }

    /**
     * @return mixed
     */
    public function getPayUrl()
    {
        return $this->payUrl;
    }

    /**
     * @param mixed $payUrl
     */
    public function setPayUrl($payUrl)
    {
        $this->payUrl = $payUrl;
    }

    /**
     * @return mixed
     */
    public function getDeeplink()
    {
        return $this->deeplink;
    }

    /**
     * @param mixed $deeplink
     */
    public function setDeeplink($deeplink)
    {
        $this->deeplink = $deeplink;
    }

    /**
     * @return mixed
     */
    public function getDeeplinkWebInApp()
    {
        return $this->deeplinkWebInApp;
    }

    /**
     * @param mixed $deeplinkWebInApp
     */
    public function setDeeplinkWebInApp($deeplinkWebInApp)
    {
        $this->deeplinkWebInApp = $deeplinkWebInApp;
    }

    /**
     * @return mixed
     */
    public function getQrCodeUrl()
    {
        return $this->qrCodeUrl;
    }

    /**
     * @param mixed $qrCodeUrl
     */
    public function setQrCodeUrl($qrCodeUrl)
    {
        $this->qrCodeUrl = $qrCodeUrl;
    }
}