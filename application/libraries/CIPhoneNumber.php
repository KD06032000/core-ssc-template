<?php
// CodeIgniter cannot load Library with namespace
//namespace CodeIgniterPhoneNumber;
defined('BASEPATH') OR exit('No direct script access allowed');

use libphonenumber\NumberParseException;
use \libphonenumber\PhoneNumber;
use \libphonenumber\PhoneNumberUtil;
use \libphonenumber\PhoneNumberFormat;

class CIPhoneNumber
{
    public function __construct()
    {
        log_message('info', get_class($this) . " Library Class Initialized");
    }
    public function parse($phoneNumber, $countryIso = NULL)
    {
        try{
           return (PhoneNumberUtil::getInstance()->parse($phoneNumber, $countryIso));
        } catch (NumberParseException $e) {
            return null;
        }
    }
    public function isValidNumber(PhoneNumber $phoneNumberInstance)
    {
        return ( PhoneNumberUtil::getInstance()->isValidNumber($phoneNumberInstance) );
    }
    public function formatNationalNumber($phoneNumber, $countryIso = NULL)
    {
        $phoneNumberInstance = $this->parse($phoneNumber, $countryIso);
        if(empty($phoneNumberInstance)) return $phoneNumber;
        return ( PhoneNumberUtil::getInstance()->formatNationalNumberWithCarrierCode($phoneNumberInstance, '') );
    }
    public function formatInternationalNumber($phoneNumber, $countryIso = NULL)
    {
        $phoneNumberInstance = $this->parse($phoneNumber, $countryIso);
        log_message('error',print_r($phoneNumberInstance,true));
        if(empty($phoneNumberInstance)) return $phoneNumber;
        return ( PhoneNumberUtil::getInstance()->format($phoneNumberInstance, PhoneNumberFormat::INTERNATIONAL  ));
    }
    public function validation_phone_number($phoneNumber)
    {
        $phoneNumberInstance = $this->parse($phoneNumber);
        if($phoneNumberInstance==null)
            return false;
        return($this->isValidNumber($phoneNumberInstance));
    }
}