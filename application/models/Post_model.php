<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post_model extends APS_Model
{

    public $table;
    public $table_trans;
    public $table_category;
    public $table_tag;

    public function __construct()
    {
        parent::__construct();
        $this->table = "post";
        $this->table_trans = "post_translations";
        $this->table_category = "post_category";
        $this->table_tag = "post_tag";
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table_trans.is_featured", "$this->table.is_status", "$this->table.order", "$this->table.created_time", "$this->table.updated_time"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }

    public function _where_custom($args)
    {
        if (!empty($args['category_info'])) {
            $this->db->join($this->table_category, "$this->table.id = $this->table_category.post_id");
            $this->db->join('category_translations', "$this->table_category.category_id = category_translations.id");
        }

        if (!empty($args['tag_id'])) {
            if (!empty($this->table_tag)) {
                $nameModel = str_replace('_model', '', $this->table);
                $this->db->join($this->table_tag, "$this->table.id = $this->table_tag.{$nameModel}_id");
                $this->db->where_in("$this->table_tag.tag_id", $args['tag_id']);
            }
        }
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

    public function getTagSelect2($postId, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_tag.tag_id AS id, tag_translations.title AS text");
        $this->db->from($this->table_tag);
        $this->db->join("tag_translations", "$this->table_tag.tag_id = tag_translations.id");
        $this->db->where('tag_translations.language_code', $lang_code);
        $this->db->where($this->table_tag . ".{$this->table}_id", $postId);
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

    // Lấy tags dự án
    public function getTags($project_id)
    {
        if (empty($project_id)) return false;
        $this->db->select("C.id,C.title,C.slug");
        $this->db->from("$this->table_property as A");
        $this->db->join("property as B", "A.property_id = B.id");
        $this->db->join("property_translations as C", "C.id = B.id");
        $this->db->where("B.is_status", 1);
        $this->db->where("A.type", 'tags');
        $this->db->where("C.language_code", $this->session->public_lang_code);
        $this->db->where("A.post_id", $project_id);
        return $this->db->get()->result();
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
