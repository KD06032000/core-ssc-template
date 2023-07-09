<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Category_model extends APS_Model
{

    public $_list_category_child;
    public $_list_category_parent;
    public $_list_category_child_id;

    public function __construct()
    {
        parent::__construct();
        $this->table = "category";
        $this->table_trans = "category_translations";//bảng bài viết
        $this->table_product = "product";
        $this->table_product_cate = "product_category";
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.order", "$this->table.is_status", "$this->table.created_time"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.order" => "DESC", "$this->table.id" => "DESC"); //cột sắp xếp mặc định
    }

    public function getUrl($id)
    {
        return $this->getById($id, '', '*', $this->session->admin_lang);
    }

    public function _where_custom($args)
    {
        extract($args);
        if (!empty($this->table_category) && !empty($category_id)) {
            $nameModel = str_replace('_model', '', $this->table);
            $this->db->join($this->table_category, "$this->table.id = $this->table_category.{$nameModel}_id");
            $this->db->where_in("$this->table_category.category_id", $category_id);
        }
        if (!empty($category_type)) {
            $this->db->where("$this->table.type", $category_type);
            if ($category_type = 'post' && !empty($excludeFakePost) && $excludeFakePost) {
                $this->db->where("$this->table.id <>", FAQ_POST);
            }
        }
        if (isset($parent_id)) $this->db->where("$this->table.parent_id", $parent_id);
    }

    public function getCategoryByStyle($style)
    {
        return $this->db->from("$this->table")
            ->where("$this->table.style", $style)
            ->get()->row();
    }

    public function getOneCate($where = [])
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);
        $data = $this->db->get()->row();
        return $data;
    }

    public function checkDataUseCat($id, $table)
    {
        $this->db->select('A.id');
        $this->db->from("$table as A");
        $this->db->join("{$table}_category as B", "A.id = B.{$table}_id");
        $this->db->where("B.category_id", $id);
        $data = $this->db->get()->row();
        return $data;
    }

    // get data group by
    public function getDataGroupBy()
    {
        $this->db->select('type');
        $this->db->from($this->table);
        $this->db->group_by('type');
        $query = $this->db->get();
        return $query->result_array();
    }
}
