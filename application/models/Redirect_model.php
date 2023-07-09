<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Redirect_model extends APS_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'redirect';
        $this->table_category = 'redirect_category';
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.link", "$this->table.redirect_link", "$this->table.category_id", "$this->table.description"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table.id", "$this->table.link", "$this->table.redirect_link", "$this->table.description"); //thiết lập cột search
        $this->order_default = array($this->table . '.id' => 'desc'); //cột sắp xếp mặc định
    }

    public function getCategorySelect2($id, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_category.category_id AS id, category_translations.title AS text");
        $this->db->from($this->table_category);
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category . ".{$this->table}_id", $id);
        //ddQuery($this->db);
        return $this->db->get()->result();
    }

}
