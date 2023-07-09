<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends Admin_Controller{

    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('tag');
        $this->load->model('tag_model');
        $this->_data = new Tag_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->session->category_type = $this->_name_controller;
    }

    public function index()
    {
        $data['heading_title'] = "Danh sách thẻ";
        $data['heading_description'] = "Danh sách thẻ";

        /*Start Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*End Breadcrumbs*/

        $data['main_content'] = $this->load->view($this->template_path . 'tag/index', $data, TRUE);//Load sub-view
        $this->load->view($this->template_main, $data);//Load main view
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
        $params['page'] = $page;
        $params['limit'] = $length;

        $list = $this->_data->getData($params);

        $data = array();
        $this->load->helper('text');
        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->title;
            $row[] = showStatus($item->is_status);
            $row[] = showCenter(formatDate($item->created_time, 'd/m/Y H:i'));
            //thêm action
            $action = button_action($item->id);
            $row[] = $action;
            $data[] = $row;
        }
        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->_data->getTotalAll(),
            "recordsFiltered" => $total,
            "data" => $data,
        );
        //trả về json
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
            //Xóa translate của tag
            $this->_data->delete(["id" => $id], $this->_data->table_trans);
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
        $list = $this->_data->getData($params, 'object','tag.id, tag_translations.title, tag_translations.slug');

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
        if (!empty($this->config->item('cms_language'))) {
            foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                $rulesLang = $this->default_rules_lang($lang_code);
                $rules = array_merge($rulesLang, $rules);
            }
        }

        $rules[] = [
            'field' => 'order',
            'label' => 'sắp xếp',
            'rules' => 'trim|xss_clean|is_natural'
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
        if (empty($data['displayed_time']) || !isDateTime($data['displayed_time'])) $data['displayed_time'] = date('Y-m-d');
        else $data['displayed_time'] = convertDate($data['displayed_time']);
        $data['updated_by'] = $this->session->userdata['user_id'];
        if (empty($data['id'])) {
            $data['created_by'] = $this->session->userdata['user_id'];
        }
        return $data;
    }
}