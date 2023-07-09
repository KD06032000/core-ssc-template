<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Career_model extends APS_Model
{

    public $table_company;

    public function __construct()
    {
        parent::__construct();
        $this->table = "career"; // alias A
        $this->table_trans = "career_translations";//bảng bài viết // alias B
        $this->table_category = "career_category";//bảng bài viết // alias D
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.is_status", "$this->table.deadline", "$this->table.created_time"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }
}
