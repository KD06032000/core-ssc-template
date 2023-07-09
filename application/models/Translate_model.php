<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Translate_model extends APS_Model{

    protected $table_product;
    public function __construct()
    {
        parent::__construct();
        $this->table            = "country";
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table.name","$this->table.code","$this->table.updated_time"); //thiết lập cột sắp xếp
        $this->column_search    = array("$this->table.id","$this->table.name"); //thiết lập cột search
        $this->order_default    = array("$this->table.name" => "ASC"); //cột sắp xếp mặc định
    }
}
