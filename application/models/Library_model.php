<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Library_model extends APS_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table            = "library";
        $this->table_trans      = "library_translations";
        $this->table_category   = "library_category";
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table_trans.title","$this->table.is_status","$this->table.created_time"); //thiết lập cột sắp xếp
        $this->column_search    = array("$this->table.id","$this->table_trans.title"); //thiết lập cột search
        $this->order_default    = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }   

    public function getTotalAllnew($type,$table = '')
    {
      if (empty($table)) $table = $this->table;
      $this->db->from($table);
      $this->db->where('type', $type);
      return $this->db->count_all_results();
    }
    
    public function getCategorySelect2($document, $lang_code = null){
        if(empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_category.category_id AS id, category_translations.title AS text");
        $this->db->from($this->table_category);
        $this->db->join("category_translations","$this->table_category.category_id = category_translations.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category.".library_id", $document);
        $data = $this->db->get()->result();
        
        return $data;
    }
    
}