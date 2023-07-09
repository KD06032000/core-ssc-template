<?php
class Tag_model extends APS_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "tag";
        $this->table_trans = "tag_translations";
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.is_status", "$this->table.updated_time", "$this->table.created_time"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }
}