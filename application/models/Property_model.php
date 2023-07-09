<?php
defined("BASEPATH") OR exit("No direct script access allowed");
class Property_model extends APS_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table            = "property";
        $this->table_trans      = "property_translations";
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table_trans.title","$this->table_trans.is_featured","$this->table.order","$this->table.is_status","$this->table.created_time","$this->table.updated_time"); //thiết lập cột sắp xếp
        $this->column_search    = array("$this->table.id","$this->table_trans.title"); //thiết lập cột search
        $this->order_default    = array("$this->table.order" => "DESC"); //cột sắp xếp mặc định
    }

    public function _where_custom($args){
        extract($args);
        if(!empty($property_type)) $this->db->where("$this->table.type", $property_type);
        if(!empty($category_id)) $this->db->where("$this->table.category_id", $category_id);
    }

    public function getCategorySelect2($id, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table.category_id AS id, category_translations.title AS text");
        $this->db->from($this->table);
        $this->db->join("category_translations", "$this->table.category_id = category_translations.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where("$this->table.id", $id);
        return $this->db->get()->result();
    }
}