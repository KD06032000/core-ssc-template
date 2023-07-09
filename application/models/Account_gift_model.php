<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account_gift_model extends APS_Model
{

  protected $table_product;

  public function __construct()
  {
    parent::__construct();
    $this->table = "account_gift";
    $this->table_account = "account";
    $this->table_account_race = "account_race";
    $this->table_races = "races_translations";
    $this->column_order = array("$this->table.account_id", "$this->table_account_race.full_name","$this->table_account_race.birthday","$this->table_account_race.address", "$this->table.status", "$this->table.status", "$this->table.status","$this->table.race_id", "$this->table.status","$this->table.received_date"); //thiết lập cột sắp xếp
    $this->column_search = array("$this->table_account_race.full_name", "$this->table_account_race.email"); //thiết lập cột search
    $this->order_default = array("$this->table.received_date" => "DESC"); //cột sắp xếp mặc định
  }
  public function getByIds($id)
  {
    $this->db->select('id,username,full_name');
    $this->db->from($this->table_account);
    $this->db->where('id', $id);
    $query = $this->db->get()->row();
    return $query;
  }


  public function getDataAccount($type_id, $type = 'voucher')
  {
    $this->db->select("$this->table.account_id,$this->table.status,$this->table.type_id,$this->table.update_time,$this->table.race_id,$this->table.type,$this->table_account.username,$this->table_account.full_name,$this->table_account.address,$this->table_account.email");
    $this->db->from($this->table);
    $this->db->join("$this->table_account", "$this->table_account.id = $this->table.account_id");
    $this->db->where('type', $type);
    $this->db->where('type_id', $type_id);
    $this->db->where("$this->table.status", 1);
    $query = $this->db->get()->result();
    return $query;
  }

  public function getTotalVoucherUse($voucher_id)
  {
    $this->db->select('1');
    $this->db->from($this->table);
    $this->db->where('type_id',$voucher_id);
    $this->db->where('status',1);
    $query = $this->db->get();
    return $query->num_rows();
  }
}