<?php

namespace APS_Momo\Models;

class PartnerInfo
{
  private $accessKey;
  private $partnerCode;
  private $secretKey;
  /**
   * PartnerInfo constructor.
   * @param $accessKey
   * @param $partnerCode
   * @param $secretKey
   */
  public function __construct()
  {

    $this->ci = get_instance();
    $this->ci->load->config('payment');
    $config = $this->ci->config->item('momo');
    $this->accessKey = $config['access_key'];
    $this->partnerCode = $config['partner_code'];
    $this->secretKey = $config['secret_key'];

  }

  /**
   * @return mixed
   */
  public function getAccessKey()
  {
    return $this->accessKey;
  }


  /**
   * @return mixed
   */
  public function getPartnerCode()
  {
    return $this->partnerCode;
  }


  /**
   * @return mixed
   */
  public function getSecretKey()
  {
    return $this->secretKey;
  }


}
