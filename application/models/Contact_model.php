<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Contact_model extends APS_Model
{
    public function __construct(){
        parent::__construct();
        $this->table = 'contact';
        $this->column_order = array('id','id','fullname','phone','email','content','created_time'); //thiết lập cột sắp xếp
        $this->column_search = array('id','email','phone','fullname','content'); //thiết lập cột search
        $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định
    }

    public function getContact($params){
        $this->_get_datatables_query();
        if (!empty($params['limit']))
            $this->db->limit($params['limit'], $params['offset']);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function countContact($params = array()){
      $this->_get_datatables_query();
        return $this->db->count_all_results($this->table);
    }
}
