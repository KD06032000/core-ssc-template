<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Post extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->lang->load('post');
        $this->load->model('post_model');
        $this->_data = new Post_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->session->category_type = $this->_name_controller;
    }

    public function index()
    {
        $data['heading_title'] = 'Bài viết';
        $data['heading_description'] = "Danh sách bài viết";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        if (!empty($post['status'])) {
            $params['is_status'] = intval($post['status']) - 1;
        }

        $params['category_id'] = !empty($post['category_id']) ? $post['category_id'] : null;
        $params['page'] = $page;
        $params['limit'] = $length;

        $list = $this->_data->getData($params);
        $data = array();

        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->title;
            $row[] = showFeatured($item->is_featured);
            $row[] = showStatus($item->is_status);
            $row[] = showOrder($item->id, $item->order);
            $row[] = showCenter(formatDate($item->created_time, 'd/m/Y H:i'));
            //thêm action
            $action = button_action($item->id);
            $row[] = $action;
            $data[] = $row;
        }
        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $this->_data->getTotalAll(),
            "recordsFiltered" => $total,
            "data" => $data,
        );

        $this->returnJson($output);
    }

    public function ajax_add()
    {
        $data_store = $this->_convertData();
        unset($data_store['id']);
        if ($id_post = $this->_data->save($data_store)) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Insert $action: " . $id_post;
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_edit($id)
    {
        $data = (array)$this->_data->getById($id);
        $data['category_id'] = $this->_data->getCategorySelect2($id);
        $data['tag_id'] = $this->_data->getTagSelect2($id);
        die(json_encode($data));
    }

    public function ajax_update()
    {
        $data_store = $this->_convertData();
        $response = $this->_data->update(array('id' => $this->input->post('id')), $data_store, $this->_data->table);
        if ($response != false) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: " . $data_store['id'];
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_update_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id' => $id]);
        if ($response != false) {
            //Xóa translate của post
            $this->_data->delete(["id" => $id], $this->_data->table_trans);
            //Xóa category của post
            $this->_data->delete(["{$this->_name_controller}_id" => $id], $this->_data->table_category);
            //Xóa tag của post
            $this->_data->delete(["{$this->_name_controller}_id" => $id], $this->_data->table_tag);
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: $id";
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_delete_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_delete_unsuccess');
            $message['error'] = $response;
            log_message('error', $response);
        }
        die(json_encode($message));
    }

    public function ajax_load()
    {
        $this->checkRequestGetAjax();
        $term = $this->input->get("q");
        $id = $this->input->get('id') ? $this->input->get('id') : 0;
        $params = [
            'is_status' => 1,
            'not_in' => ['id' => $id],
            'search' => $term,
            'limit' => 50,
            'order' => array('created_time' => 'desc')
        ];
        $list = $this->_data->getData($params);
        if (!empty($list)) foreach ($list as $item) {
            $item = (object)$item;
            $json[] = ['id' => $item->id, 'text' => $item->title];
        }
        $this->returnJson($json);
    }
    /*
     * Kiêm tra thông tin post lên
     * */
    private function _validate()
    {
        $this->checkRequestPostAjax();
        $rules = [];
        if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
            $rulesLang = $this->default_rules_lang($lang_code);
            $rules = array_merge($rulesLang, $rules);
        }
        $rules[] = array(
            'field' => 'thumbnail',
            'label' => 'Ảnh đại diện',
            'rules' => 'trim|xss_clean|callback_validate_html'
        );
        $rules[] = array(
            'field' => 'link',
            'label' => 'Link liên kết',
            'rules' => 'trim|xss_clean|callback_validate_html'
        );
        $rules[] = array(
            'field' => 'category_id[]',
            'label' => $this->lang->line('from_category'),
            'rules' => 'required'
        );
        $rules[] = [
            'field' => 'order',
            'label' => 'sắp xếp',
            'rules' => 'trim|xss_clean|is_natural|callback_validate_html'
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }

    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        if (!in_array($data['is_status'], [0, 1])) {
            $data['is_status'] = 0;
        }
        if (empty($data['displayed_time']) || !isDateTime($data['displayed_time'])) $data['displayed_time'] = date('Y-m-d');
        else $data['displayed_time'] = convertDate($data['displayed_time']);
//        $data['updated_by'] = $this->session->userdata['user_id'];
//        if (empty($data['id'])) {
//            $data['created_by'] = $this->session->userdata['user_id'];
//        }
        return $data;
    }

}