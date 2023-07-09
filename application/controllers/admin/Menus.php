<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menus extends Admin_Controller
{

    protected $_data;
    protected $_name_controller;
    protected $_pageModel;
    protected $_postModel;
    protected $_categoryModel;
    protected $_listMenu;

    public function __construct()
    {
        parent::__construct();
        //tải file ngôn ngữ
        //$this->lang->load('menu');
        $this->config->load('menus');
        $this->lang->load('category');
        $this->load->model(['Menus_model', 'post_model', 'category_model', 'page_model']);
        $this->_data = new Menus_model();
        $this->_postModel = new Post_model();
        $this->_pageModel = new Page_model();
        $this->_categoryModel = new Category_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        $data['heading_title'] = 'Menu';
        $data['heading_description'] = "Danh sách menu";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_load()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $BASE_URL = BASE_URL;
            $lang_code = $this->input->post('lang_code');
            $data['lang_code'] = !empty($lang_code) ? $lang_code : $this->session->admin_lang;
            if ($data['lang_code'] != $this->config->item('default_language')) {
                $BASE_URL = BASE_URL . $data['lang_code'] . '/';
            }
            define('BASE_URL_LANG', $BASE_URL, true);
            $groupCategory = $this->_categoryModel->getDataGroupBy();
            $allCategory = $this->_categoryModel->getAll($lang_code);
            $list_name_category = [
                'post' => 'Danh mục bài viết',
                'ecosystem' => 'Danh mục hệ sinh thái',
                'calendar' => 'Danh mục lịch hoạt động',
            ];
            $data_select_general = [
                [
                    'slug' => '#', 'title' => 'Link khác'
                ],
                [
                    'slug' => '/', 'title' => 'Trang chủ'
                ]
            ];
            $list_tabs = [
                [
                    'id' => 'tab_general',
                    'name' => 'Chung',
                    'convert_link' => '',
                    'data_select' => $data_select_general
                ],
                [
                    'id' => 'tab_page',
                    'name' => 'Trang tĩnh',
                    'convert_link' => 'getUrlPage',
                    'data_select' => $this->_pageModel->getAll($lang_code)
                ],
            ];
            $list_tabs_category = [];
            if (!empty($groupCategory)) foreach ($groupCategory as $item) {
                $data_category = $this->getCategoryByType($allCategory, $item['type']);
                $name_cat = !empty($list_name_category[$item['type']]) ? $list_name_category[$item['type']] : "Category {$item['type']}";
                switch ($item['type']) {
                    case 'ecosystem':
                        $urlCate = 'getUrlCateEcosystem';
                        break;
                    default:
                        $urlCate = 'getUrlCateNews';
                        break;
                }
                $list_tabs_category[] = [
                    'id' => 'tab_category_' . $item['type'],
                    'name' => $name_cat,
                    'convert_link' => $urlCate,
                    'data_select' => $data_category
                ];
            }
            $list_tabs = array_merge($list_tabs, $list_tabs_category);
            $list_tabs[] = [
                'id' => 'tab_post',
                'name' => 'Bài viết',
                'convert_link' => 'getUrlNews',
                'data_select' => $this->_postModel->getAll($lang_code),
            ];
            $list_tabs[] = [
                'id' => 'tab_ecosystem',
                'name' => 'Hệ sinh thái',
                'convert_link' => 'getUrlAvailable',
                // 'data_select' => $this->_ecosystemModel->getAll($lang_code),
            ];
            $data['list_tabs'] = $list_tabs;

            die($this->load->view($this->template_path . 'menus/_ajax_load_data', $data, TRUE));
        }
        exit;
    }

    public function ajax_load_menu()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $locationId = $this->input->get('location_id');
            $lang_code = $this->input->get('lang_code');
            $data = $this->_data->search(['location_id' => $locationId, 'language_code' => $lang_code]);
            $this->listMenu($data, 0, $locationId, $lang_code);
            echo json_encode($this->_listMenu);
        }
        exit;
    }

    public function ajax_save_menu()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $menuLocation = $this->input->post('loc');
            $response = $this->input->post('s'); // decoding received JSON to array
            //log_message('error', $response);
            $menuLanguage = $this->input->post('lang');

            log_message('error', json_encode($response));
            $this->_data->delete(['location_id' => $menuLocation, 'language_code' => $menuLanguage]);
            if (is_array($response)) {
                //start saving now
                $topmenusorder = 1;
                log_message('error', json_encode($response));

                $regex = "((https?|ftp)\:\/\/)?";
                $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";
                $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})";
                $regex .= "(\:[0-9]{2,5})?";
                $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
                $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?";
                $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?";

                if (!empty($response)) foreach ($response as $key => $block) {

                    $type = !empty($block['type']) ? trim($block['type']) : "";
                    $link = trim($block['link']);

                    if (preg_match("/^$regex$/i", $link)) {
                        $type = 'other';
                    }

                    $tmp['title'] = trim($block['label']);
                    $tmp['class'] = trim($block['cls']);
                    $tmp['type'] = $type;
                    $tmp['link'] = $link;
                    $tmp['icon'] = !empty($block['icon']) ? trim($block['icon']) : "";
                    $tmp['order'] = $topmenusorder;
                    $tmp['parent_id'] = 0;
                    $tmp['location_id'] = $menuLocation;
                    $tmp['language_code'] = $menuLanguage;
                    $menuid = $this->_data->saveMenu($tmp);
                    if (!empty($block['children'])) {
                        $this->childsubmenus($menuid, $block['children'], $menuLocation, $menuLanguage);
                    }
                    $topmenusorder++;
                }
            } //if is_array($response);
            echo 1;
        }
        exit;
    }

    //-----------------------------------
    private function childsubmenus($menuid, $e, $menuLocation, $menuLanguage)
    {
        $topmenusorder = 1;

        $regex = "((https?|ftp)\:\/\/)?";
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";
        $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})";
        $regex .= "(\:[0-9]{2,5})?";
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
        $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?";
        $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?";
        foreach ($e as $key => $block) {

            $type = !empty($block['type']) ? trim($block['type']) : "";
            $link = trim($block['link']);

            if (preg_match("/^$regex$/i", $link)) {
                $type = 'other';
            }

            $tmp['title'] = trim($block['label']);
            $tmp['class'] = trim($block['cls']);
            $tmp['type'] = $type;
            $tmp['link'] = $link;
            $tmp['order'] = $topmenusorder;
            $tmp['parent_id'] = $menuid;
            $tmp['location_id'] = $menuLocation;
            $tmp['language_code'] = $menuLanguage;
            $menu = $this->_data->saveMenu($tmp);
            if (!empty($block['children'])) {
                $this->childsubmenus($menu, $block['children'], $menuLocation, $menuLanguage);
            }
            $topmenusorder++;
        }
    }

    // hiển thị dữ liệu
    private function listMenu($menu, $parent = 0, $locationId, $lang_code)
    {
        if (!empty($menu)) foreach ($menu as $row) {
            $row = (array)$row;
            if ($row['parent_id'] == $parent) {
                $this->_listMenu[] = array(
                    'id' => $row['id'],
                    'name' => $row['title'],
                    'class' => $row['class'],
                    'icon' => $row['icon'],
                    'type' => $row['type'],
                    'link' => $row['link'],
                    'level' => $row['parent_id']);
                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                $this->listMenu($menu, $row['id'], $locationId, $lang_code);
            }
        }
    }

    private function getCategoryByType($all, $type)
    {
        $data = [];
        if (!empty($all)) foreach ($all as $item) {
            if ($item->type === $type) $data[] = $item;
        }
        return $data;
    }
}