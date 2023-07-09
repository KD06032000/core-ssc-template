<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Log_action_model extends APS_Model
{
    public $table_notify;
    public $table_notify_account;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'log_action';
        $this->table_notify = 'log_notify';
        $this->table_notify_account = 'log_notify_account';
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table.action","$this->table.note","$this->table.uid","$this->table.created_time"); //thiết lập cột sắp xếp
        $this->column_search    = array('action','note'); //thiết lập cột search
        $this->order_default    = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }

    public function save_notify($data, $user, $is_read)
    {
        $id = $this->save($data, $this->table_notify);

        foreach ($user as $key => $value) {
            $data_notify['log_id'] = $id;
            $data_notify['account_id'] = $value->id;
            $data_notify['is_read'] = $is_read;
            $this->save($data_notify, $this->table_notify_account);
        }

        return true;
    }

    public function get_notify($account_id)
    {
        return $this->db->from($this->table_notify_account)
            ->join($this->table_notify, "$this->table_notify.id = $this->table_notify_account.log_id")
            ->where('account_id', $account_id)
            ->order_by('created_time', 'DESC')
            ->get()
            ->result();
    }

}
