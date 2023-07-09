<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Property extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->lang->load('property');
        $this->load->model('property_model');
        $this->_data = new Property_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function get_list($data, $layout = 'index')
    {
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/' . $layout, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_load($type = '')
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $id = $this->input->get('id') ? $this->input->get('id') : 0;
            $params = [
                'property_type' => !(empty($type)) ? $type : $this->session->property_type,
                'is_status' => 1,
                'not_in' => ['id' => $id],
                'search' => $term,
                'limit' => 1000
            ];
            $list = $this->_data->getData($params);
            $json = [];
            if (!empty($list)) foreach ($list as $item) {
                $item = (object)$item;
                $json[] = ['id' => $item->id, 'text' => $item->title];
            }
            print json_encode($json);
        }
        exit;
    }

    public function process()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Giới thiệu";
        $data['heading_description'] = "Quá trình phát triển";
        $this->get_list($data, 'process');
    }

    public function action()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Giới thiệu";
        $data['heading_description'] = "Hoạt động chính";
        $this->get_list($data, 'action');
    }

    public function service_society()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Dịch vụ xã hội";
        $data['heading_description'] = "Giới thiệu";
        $this->get_list($data, 'service_society');
    }

    public function service_society_support()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Dịch vụ xã hội";
        $data['heading_description'] = "Hỗ trợ";
        $this->get_list($data, 'service_society');
    }

    public function feature()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Tính năng";
        $data['heading_description'] = "Danh sách tính năng";
        $this->get_list($data);
    }

    public function partner()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Đối tác";
        $data['heading_description'] = "Danh sách đối tác";
        $this->get_list($data);
    }

    // Kết quả hỗ trợ tham vấn
    public function chart_1()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Dữ liệu biểu đồ";
        $data['heading_description'] = "Kết quả hỗ trợ tham vấn";
        $this->get_list($data, 'chart_1');
    }

    // Kết quả đón tiếp và cung cấp dịch vụ cho phụ nữ và trẻ em bị bạo lực Gia đình
    public function chart_2()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Dữ liệu biểu đồ";
        $data['heading_description'] = "Kết quả đón tiếp và cung cấp dịch vụ cho phụ nữ và trẻ em bị bạo lực Gia đình";
        $this->get_list($data, 'chart_2');
    }

    // Kết quả đón tiếp và cung cấp dịch vụ cho phụ nữ và trẻ em bị mua bán trở về
    public function chart_3()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Dữ liệu biểu đồ";
        $data['heading_description'] = "Kết quả đón tiếp và cung cấp dịch vụ cho phụ nữ và trẻ em bị mua bán trở về";
        $this->get_list($data, 'chart_2');
    }

    public function tab_society()
    {
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['heading_title'] = "Tab con Dịch vụ xã hội";
        $data['heading_description'] = "List tab con";
        $this->get_list($data, 'tab_society');
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list($type)
    {
        $this->checkRequestPostAjax();
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        $params['property_type'] = $type;
        $params['page'] = $page;
        $params['limit'] = $length;

        if (!empty($post['status'])) {
            $params['is_status'] = intval($post['status']) - 1;
        }

        $data = array();
        $list = $this->_data->getData($params);
        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->title;
            if (!in_array($type, ['chart_3', 'chart_2', 'chart_1'])) {
                $row[] = showFeatured($item->is_featured);
                $row[] = showOrder($item->id, $item->order);
            }
            $row[] = showStatus($item->is_status);
            $row[] = showCenter(formatDateTime($item->created_time));
            //thêm action
            $action = button_action($item->id);
            $row[] = $action;
            $data[] = $row;
        }

        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data,
        );

        $this->returnJson($output);
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_add()
    {
        $data_store = $this->_convertData();
        if ($this->_data->save($data_store)) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Insert $action: " . $this->db->insert_id();
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data = (array)$this->_data->getById($id);
        if (in_array($this->session->property_type, ['service_society', 'service_society_support'])) {
            $data['category_id'] = $this->_data->getCategorySelect2($id);
        }

        if (in_array($this->session->property_type, ['chart_3', 'chart_2', 'chart_1'])) {
            $data_num = [];
            foreach(json_decode($data[0]->data) as $key => $value) {
                $data_num[$key] = $value;
            }
            $data[0]->data = $data_num;
        }
        die(json_encode($data));
    }

    /*
     * Ajax xử lý thêm mới
     * */
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

    public
    function createdTag($type = 'tags')
    {
        $this->checkRequestPostAjax();
        $value = $this->input->post('value');
        $params['title'][$this->config->item('default_language')] = trim(xss_clean($value));
        $params['type'] = $type;
        if ($id = $this->_data->save($params)) {
            $this->_message = [
                'type' => 'success',
                'message' => 'Tạo tags thành công',
                'id' => $id
            ];
        } else {
            $this->_message = [
                'type' => 'warning',
                'message' => 'Tạo tags thành không công',
            ];
        }
        $this->returnJson();
    }

    private
    function _validate()
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
                ),
                array(
                    'field' => 'content[' . $lang_code . ']',
                    'label' => 'Nội dung',
                    'rules' => 'trim'
                )
            ];
            $rules = array_merge($rules, $rulesLang);
        }
        $rules[] = [
            'field' => 'order',
            'label' => 'Thứ tự',
            'rules' => 'trim|strip_tags|xss_clean|is_natural'
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }

    private
    function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        if (!in_array($data['is_status'], [0, 1])) {
            $data['is_status'] = 0;
        }
        $data['data'] = !empty($data['data']) ? json_encode($data['data']) : '';
        return $data;
    }
// load features
}