<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service_model extends APS_Model
{

    public $table;
    public $table_property;
    public $table_trans;
    public $table_category;

    public function __construct()
    {
        parent::__construct();
        $this->table = "service"; // alias A
        $this->table_property = "service_property"; // alias P
        $this->table_trans = "service_translations";//bảng bài viết // alias B
        $this->table_category = "service_category";//bảng bài viết // alias C
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.is_featured", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }

    public function getCategoryByPostId($postId, $lang_code = null, $return = 'result')
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select();
        $this->db->from($this->table_category);
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->join("category", "$this->table_category.category_id = category.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category . ".{$this->table}_id", $postId);
        if ($return == 'result') $data = $this->db->get()->result();
        else $data = $this->db->get()->row();
        return $data;
    }


    public function getCategorySelect2($postId, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_category.category_id AS id, category_translations.title AS text");
        $this->db->from($this->table_category);
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category . ".{$this->table}_id", $postId);
        $data = $this->db->get()->result();
        //ddQuery($this->db);
        return $data;
    }

    public function listIdByCategory($category_id)
    {
        $this->db->from($this->table_category);
        $this->db->where('category_id', $category_id);
        $result = $this->db->get()->result();
        $listPostId = [];
        if (!empty($result)) foreach ($result as $item) {
            $listPostId[] = $item->post_id;
        }
        return $listPostId;
    }

    public function getOneCateIdById($id, $lang = null)
    {
        $data = $this->getCategoryByPostId($id, $lang, 'row');
        return $data;
    }

    public function getCateIdById($id)
    {
        $this->db->select('category_id');
        $this->db->from($this->table_category);
        $this->db->where("{$this->table}_id", $id);
        $data = $this->db->get()->result();
        $listId = [];
        if (!empty($data)) foreach ($data as $item) {
            $listId[] = $item->category_id;
        }
        return $listId;
    }

    public function getPropertySelect2($room_id, $type, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_property.property_id AS id, property_translations.title AS text");
        $this->db->from($this->table_property);
        $this->db->join("property_translations", "$this->table_property.property_id = property_translations.id");
        $this->db->where('property_translations.language_code', $lang_code);
        $this->db->where($this->table_property . ".{$this->table}_id", $room_id);
        $this->db->where($this->table_property . ".type", $type);
        $data = $this->db->get()->result();
        return $data;
    }

    public function slugExit($slug, $suser_id)
    {
        $this->db->select('A.id');
        $this->db->from("$this->table as A");
        $this->db->join("$this->table_trans as B", "A.id=B.id");
        $this->db->where("B.slug", $slug);
        $this->db->where("A.user_id", $suser_id);
        return $this->db->get()->row();
    }

    public function titleExit($title, $suser_id)
    {
        $this->db->select('A.id');
        $this->db->from("$this->table as A");
        $this->db->join("$this->table_trans as B", "A.id=B.id");
        $this->db->where("B.title", $title);
        $this->db->where("A.user_id", $suser_id);
        return $this->db->get()->row();
    }
}
