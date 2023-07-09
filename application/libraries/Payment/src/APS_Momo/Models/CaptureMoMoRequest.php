<?php

namespace APS_Momo\Models;

class CaptureMoMoRequest extends AIORequest
{
    public function __construct($params = array())
    {
        parent::__construct($params);
        $this->setRequestType('captureMoMoWallet');
    }
}