<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page_model extends APS_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "page";
        $this->table_tore = "store";
        $this->table_trans = "page_translations";//bảng bài viết
        $this->table_category = "category";//bảng bài viết

        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time",); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }

    public function _where_custom($args)
    {
        extract($args);
        if (!empty($parent_id)) $this->db->where("$this->table.parent_id", $parent_id);
    }

    public function getBySlug($slug, $select = '*', $lang_code = null)
    {

        $this->db->select($select);
        $this->db->from($this->table);
        if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
        $this->db->where("$this->table_trans.slug", $slug);
        if (empty($this->table_trans)) {
            $query = $this->db->get();
            return $query->row();
        }

        if (!empty($lang_code)) {
            $this->db->where("$this->table_trans.language_code", $lang_code);
            $query = $this->db->get();
            return $query->row();
        } else {
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function slugToId($slug)
    {
        $this->db->select('tb1.id');
        $this->db->from($this->table . ' AS tb1');
        $this->db->join($this->table_trans . ' AS tb2', 'tb1.id = tb2.id');
        $this->db->where('tb2.slug', $slug);
        $data = $this->db->get()->row();
        //ddQuery($this->db);
        return !empty($data) ? $data->id : null;
    }

    public function getPageByLayout($layout, $select = '')
    {
        if (empty($select)) $select = array('A.id', 'B.slug', 'B.title');
        $this->db->select($select);
        $this->db->from($this->table . ' AS A');
        $this->db->join($this->table_trans . ' AS B', 'A.id = B.id');
        $this->db->where('A.is_status', 1);
        $this->db->where('B.language_code', $this->session->public_lang_code);
        if (is_array($layout)) {
            $this->db->where_in('A.style', $layout);
            $this->db->order_by('A.order', 'DESC');
            $data = $this->db->get()->result();
        } else {
            $this->db->where('A.style', $layout);
            $data = $this->db->get()->row();
        }

        return $data;
    }

    public function getPageByType($type, $select = '')
    {
        if (empty($select)) $select = array('A.id', 'B.slug', 'B.title');
        $this->db->select($select);
        $this->db->from($this->table . ' AS A');
        $this->db->join($this->table_trans . ' AS B', 'A.id = B.id');
        $this->db->where('A.is_status', 1);
        $this->db->where('B.language_code', $this->session->public_lang_code);
        $this->db->where_in('A.type', $type);
        $this->db->order_by('A.order', 'DESC');
        $data = $this->db->get()->result();


        return $data;
    }

    public function getPageChild($parent_id)
    {
        $this->db->select('*');
        $this->db->from($this->table . ' AS A');
        $this->db->join($this->table_trans . ' AS B', 'A.id = B.id');
        $this->db->where('A.is_status', 1);
        $this->db->where('B.language_code', $this->session->public_lang_code);
        $this->db->where_in('A.parent_id', $parent_id);
        $this->db->order_by('A.order', 'DESC');
        return $this->db->get()->result();
    }

    public function getPageSelect2($id, $type)
    {
        if (empty($select)) $select = array('A.id', 'B.slug', 'B.title as text');
        $this->db->select($select);
        $this->db->from($this->table . ' AS A');
        $this->db->join($this->table_trans . ' AS B', 'A.id = B.id');
        $this->db->where('A.is_status', 1);
        $this->db->where('A.id', $id);
        $this->db->where('B.language_code', $this->session->public_lang_code);
        $this->db->where_in('A.type', $type);
        return $this->db->get()->result();
    }


}