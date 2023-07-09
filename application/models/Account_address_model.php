<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account_address_model extends APS_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = "account_address";
    $this->column_order = array("$this->table.account_id", "$this->table.full_name"); //thiết lập cột sắp xếp
    $this->column_search = array("$this->table.full_name"); //thiết lập cột search
    $this->order_default = array("$this->table.id" => "DESC"); //cột sắp xếp mặc định
  }
  public function _where_custom($args)
  {
    extract($args);
    if(!empty($account_id)) $this->db->where('account_id',$account_id);
  }

  public function getAddressDeaultByAccountId($account_id){
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('account_id',$account_id);
    $this->db->where('is_default',1);
    return $this->db->get()->row();
  }
}