<?php

function truncate($text, $chars = 25) {
    if (strlen($text) <= $chars) {
        return $text;
    }
    $text = $text." ";
    $text = substr($text,0, $chars);
//    $text = substr($text,0,strrpos($text,' '));
    $text = $text."...";
    return $text;
}

function getDataProperty($type, $limit = 5)
{
    $ci = &get_instance();
    $ci->load->model('property_model');
    return $ci->property_model->getData([
        'lang_code' => $ci->lang_code,
        'is_status' => 1,
        'where' => [
            'type' => $type
        ],
        'limit' => $limit,
        'order' => ['order' => 'DESC', 'created_time' => 'DESC']
    ]);
}

function getDataAddress()
{
    $ci = &get_instance();
    $ci->load->model('address_model');
    return $ci->address_model->getData([
        'is_status' => 1,
        'order' => ['order' => 'DESC', 'created_time' => 'DESC']
    ]);
}

function getDataChart($type)
{
    $data = record_sort(getDataProperty($type, null), "title");

    $category = [];
    $num1 = [];
    $num2 = [];
    $num3 = [];

    foreach ($data as $value) {
        $category[] = $value['title'];
        $num = (array)json_decode($value['data']);

        if ($type == 'chart_1') {
            $num1[] = (int)$num[1];
            $num2[] = (int)$num[2];
            $num3[] = (int)$num[3];
        } else {
            $num1[] = (int)$num[1];
        }
    }

    return [
        'category' => $category,
        'num1' => $num1,
        'num2' => $num2,
        'num3' => $num3
    ];
}

function getField($id, $limit = 5)
{
    $_this =& get_instance();
    $_this->load->model('field_model');
    $_model = new Field_model();
    $params = [
        'category' => $id,
        'limit' => $limit,
        'is_status' => 1,
        'lang_code' => $_this->lang_code,
    ];
    return $_model->getData($params);
}

function getNameUser($id)
{
    $_this =& get_instance();
    $_this->load->model('users_model');
    $_model = new Users_model();
    return $_model->getData([
        'in' => $id
    ], 'row')->full_name;
}

function getDataCategory($type, $limit = 10)
{
    $_this =& get_instance();
    $_this->load->model('category_model');
    $_model = new Category_model();
    $params = [
        'category_type' => $type,
        'is_status' => 1,
        'lang_code' => $_this->lang_code ?? $_this->session->admin_lang,
        'limit' => $limit,
        'order' => ['order' => 'DESC', 'created_time' => 'DESC']
    ];
    return $_model->getData($params);
}

function getNameCategoryById($id)
{
    $_this =& get_instance();
    $_this->load->model('category_model');
    $_model = new Category_model();
    return $_model->getById($id)[0]->title ?? '';
}

function getDataTag($limit = 10)
{
    $_this =& get_instance();
    $_this->load->model('tag_model');
    $_model = new Tag_model();
    $params = [
        'is_status' => 1,
        'lang_code' => $_this->lang_code ?? $_this->session->admin_lang,
        'limit' => $limit,
        'order' => ['order' => 'DESC', 'created_time' => 'DESC']
    ];
    return $_model->getData($params);
}

function getEcosystemByCategory($category_id, $limit = null)
{
    $_this =& get_instance();
    $_this->load->model('ecosystem_model');
    $_model = new Ecosystem_model();
    $params = [
        'category_id' => $category_id,
        'is_status' => 1,
        'lang_code' => $_this->lang_code ?? $_this->session->admin_lang,
        'limit' => $limit,
        'order' => ['order' => 'DESC', 'created_time' => 'DESC']
    ];
    return $_model->getData($params);
}

function getDataTagByBlogId($id)
{
    $_this =& get_instance();
    $_this->load->model('post_model');
    $_model = new Post_model();

    $lang_code = $_this->lang_code ?? $_this->session->admin_lang;

    return $_model->db
        ->select("$_model->table_tag.tag_id AS id, tag_translations.title, tag_translations.slug")
        ->from($_model->table_tag)
        ->join("tag_translations", "$_model->table_tag.tag_id = tag_translations.id")
        ->where('tag_translations.language_code', $lang_code)
        ->where($_model->table_tag . ".{$_model->table}_id", $id)
        ->get()
        ->result();
}

function getDataPostById($id)
{
    $_this =& get_instance();
    $_this->load->model('post_model');
    $_model = new Post_model();
    $params = [
        'is_status' => 1,
        'lang_code' => $_this->lang_code,
        'in' => $id
    ];
    return $_model->getData($params, 'row');
}
