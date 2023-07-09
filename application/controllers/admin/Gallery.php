<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gallery extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();

        //tai thu vien
        $this->lang->load('gallery');
        $this->load->model('Library_model');
        $this->_data = new Library_model();
        $this->_name_controller = $this->router->fetch_class();
        $_table = $this->_data->table;
        $_table_trans = $this->_data->table_trans;
        $this->_data->column_order = array("$_table.id", "$_table.id", "$_table_trans.title", "$_table.order", "$_table.is_status", "$_table.is_status", "$_table.created_time"); //thiết lập cột sắp xếp
    }

    public function index()
    {

        $data['heading_title'] = 'Thư viện ảnh';
        $data['heading_description'] = 'Danh sách thư viện ảnh';

        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();

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

        if (!empty($post['year'])) {
            $params['where']['YEAR(displayed_time)'] = $post['year'];
        }

        $params['page'] = $page;
        $params['limit'] = $length;
        $params['where']['type'] = 1;
        $list = $this->_data->getData($params);
        $data = array();

        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->title;
            $row[] = showOrder($item->id, $item->order);
            $row[] = showImagePreview($item->thumbnail);
            $row[] = showStatusnotdarf($item->is_status);

            $row[] = showCenter(formatDate($item->created_time, 'd/m/Y H:i'));
            $action = button_action($item->id);
            $row[] = $action;
            $data[] = $row;
        }
        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        );
        $this->returnJson($output);
    }

    public function ajax_add()
    {
        $data_store = $this->_convertData();
        if ($this->_data->save($data_store)) {
            $action = $this->router->fetch_class();
            $note = "Insert $action: " . $this->db->insert_id();
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        } else {
            $message['type'] = 'error';
            $message['messsage'] = $this->lang->line('message_add_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_edit($id)
    {
        $data = (array)$this->_data->getById($id);
        die(json_encode($data));
    }

    public function ajax_update()
    {
        $data_store = $this->_convertData();

        $response = $this->_data->update(array('id' => $this->input->post('id')), $data_store, $this->_data->table);
        if ($response != false) {

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

            $this->_data->delete(["id" => $id], $this->_data->table_trans);

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

    private function _validate()
    {
        $this->checkRequestPostAjax();
        $rules = [];
        if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
            $required = '';
            if ($lang_code == $this->config->item('default_language')) {
                $required = 'required|';
            }
            $rulesLang = [
                array(
                    'field' => 'title[' . $lang_code . ']',
                    'label' => 'Tên',
                    'rules' => $required . 'trim|min_length[3]|max_length[255]|trim|xss_clean|callback_validate_html'
                ),
                array(
                    'field' => 'description[' . $lang_code . ']',
                    'label' => 'Tóm tắt',
                    'rules' => 'trim|xss_clean|callback_validate_html'
                )
            ];
            $rules = array_merge($rules, $rulesLang);
        }
        $rules[] = array(
            'field' => 'thumbnail',
            'label' => 'Ảnh đại diện',
            'rules' => 'required|trim|xss_clean|callback_validate_html'
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }

    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        if (empty($data['displayed_time']) || !isDateTime($data['displayed_time'])) $data['displayed_time'] = date('Y-m-d');
        else $data['displayed_time'] = convertDate($data['displayed_time']);
        if (empty($data['type'])) {
            $data['type'] = 1;
        }
        $data['album']  = !empty($data['album']) ? json_encode($data['album']) : '';
        return $data;
    }
}
