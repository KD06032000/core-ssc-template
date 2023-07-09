<?php

namespace APS_Momo\Models;

class QueryStatusRequest extends AIOResponse
{
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->setRequestType('transactionStatus');
    }
}