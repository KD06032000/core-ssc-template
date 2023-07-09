<?php

namespace APS_Momo;

use APS_Momo\Models\PartnerInfo;
use APS_Momo\Models\SignatureRequest;
use APS_Momo\Support\Signature;

class Process extends SignatureRequest
{
  protected $endpointUri;
  protected $ci;

  public function __construct()
  {
    parent::__construct();
    $this->ci = get_instance();
    $this->ci->load->config('payment');
    $config = $this->ci->config->item('momo');
    if (strtolower($config['environment']) === 'development') {
      $this->endpointUri = $config['uri_endpoint_development'];
    } else {
      $this->endpointUri = $config['uri_endpoint_production'];
    }
  }
}