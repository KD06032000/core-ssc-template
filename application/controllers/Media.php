<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Media extends Public_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_data_page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['page_model', 'category_model', 'library_model']);
        $this->_data = new Library_model();
        $this->_data_page = new Page_model();
        $this->_data_category = new Category_model();
    }

    public function ajax_call($page = 1)
    {
        $limit = $default = 10;
        $post = $this->input->get();

        if (!empty($post['type'])) {
            switch ($post['type']) {
                case 'home':
                    $limit = 4;
                    $pagination = false;
                    break;
            }
        }

        if (!empty($post['page'])) {
            $page = intval($post['page']);
            $limit = $page * $default;
        }

        $params = array(
            'is_status' => 1, //0: Huỷ, 1: Hiển thị
            'lang_code' => $this->lang_code,
            'limit' => $limit,
            'order' => ['order' => 'DESC', 'displayed_time' => 'DESC', 'created_time' => 'DESC'],
            'where' => ['displayed_time <=' => time_now()],
//            'page' => $page
        );

        $layout = '';
        // Type Library = $post['category_id']
        if (!empty($post['category_id'])) {
            $params['where']['type'] = $post['category_id'];
            switch ($post['category_id']) {
                case 1:
                    $layout = '_item_images';
                    break;
                case 2:
                    $layout = '_item_videos';
                    break;
            }
        }

        $data['page_next'] = $page + 1;
        $data['data'] = $this->_data->getData($params);

        $total = $this->_data->getTotal($params);

        $data['isShowMore'] = $limit < $total;

        $this->returnJson([
            'error' => false,
            'data' => [
                'html' => $this->load->view($this->template_path . 'media/_block/' . $layout, $data, TRUE)
            ]
        ]);
    }
}
