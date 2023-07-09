<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banner extends Admin_Controller
{
    var $action = '';
    var $note = '';
    protected $_dataCategory;
    protected $_data;
    protected $_name_controller;
    protected $category_tree;

    public function __construct()
    {
        parent::__construct();
        //tải file ngôn ngữ
        $this->lang->load('banner');
        $this->load->model(['category_model', 'banner_model']);
        $this->_data = new Banner_model();
        $this->_dataCategory = new Category_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function _queue_select($categories, $parent_id = 0, $char = '')
    {
        foreach ($categories as $key => $item) {
            if ($item->parent_id == $parent_id) {
                $tmp['name'] = $parent_id ? $char . '&nbsp;|--&nbsp;' . $item->title : $char . $item->title;
                $tmp['id'] = $item->id;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue_select($categories, $item->id, $char . '&nbsp;&nbsp;');
            }
        }
    }


    public function index()
    {
        $data['heading_title'] = 'Banner';
        $data['heading_description'] = "Danh sách banner";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        if (!empty($post['filter'])) {
            $params['where'] = $post['filter'];
        }

        $params['page'] = $page;
        $params['limit'] = $length;
        $list = $this->_data->getData($params);
        $data = array();
        $list_position_banner = get_list_position_banner();
        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = !empty($list_position_banner[$item->position]) ? $list_position_banner[$item->position] : '';
            $row[] = $item->title;
            $row[] = showOrder($item->id, $item->order);
            $row[] = showImagePreview($item->thumbnail);
            $row[] = showStatusnotdarf($item->is_status);
            $row[] = showCenter(formatDate($item->created_time, 'datetime'));
            //thêm action
            $row[] = button_action($item->id);
            $data[] = $row;
        }

        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data,
        );
        //trả về json
        $this->returnJson($output);
    }


    /*
      * Ajax xử lý thêm mới
      * */
    public function ajax_add()
    {
        $data_store = $this->_convertData();
        unset($data_store['id']);
        if ($id_banner = $this->_data->save($data_store)) {
            // log action
            $action = 'post';
            $note = 'Thêm Banner có id là ' . $id_banner;
            $this->addLogaction($action, $note);

            $message['type'] = 'success';
            $message['message'] = 'Thêm mới thành công !';
        } else {
            $message['type'] = 'error';
            $message['message'] = 'Thêm mới thất bại';
        }
        die(json_encode($message));
    }

    /*
     * Ajax copy
     * */
    function ajax_copy($id)
    {
        $data = $this->_data->getById($id);
        $data_store = [];
        if (!empty($data)) foreach ($data as $value) {
            $data_store['title'][$value->language_code] = $value->title;
            $data_store['description'][$value->language_code] = $value->description;
            $data_store['is_status'] = $value->is_status;
            $data_store['thumbnail'] = $value->thumbnail;
            $data_store['property_id'] = $value->property_id;
            $data_store['url'] = $value->url;
        }
        $response = $this->_data->save($data_store);
        if ($response !== false) {
            $message['type'] = 'success';
            $message['message'] = "Nhân bản thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Nhân bản thất bại !";
            $message['error'] = $response;
            log_message('error', $response);
        }
        die(json_encode($message));
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $this->load->model('property_model');
        $propertyModel = new Property_model();
        $data = (array)$this->_data->getById($id);
        if (!empty($data[0]->property_id)) $data['property_id'] = $propertyModel->getSelect2($data[0]->property_id);
        die(json_encode($data));
    }

    /*
     * Xóa một bản ghi
     * */
    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id' => $id]);
        if ($response != true) {
            $message['type'] = 'error';
            $message['message'] = "Xóa bản ghi thất bại !";
        } else {
            $message['type'] = 'success';
            $message['message'] = "Xóa bản ghi thành công !";
        }
        die(json_encode($message));
    }

    /*
     * Cập nhật thông tin
     * */
    public function ajax_update()
    {
        $data_store = $this->_convertData();
        $response = $this->_data->update(array('id' => $this->input->post('id')), $data_store);
        if ($response == false) {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
            $message['error'] = $response;
            log_message('error', $response);
        } else {
            // log action
            $action = 'banner';
            $note = 'Sửa Banner có id là ' . $this->input->post('id');
            $this->addLogaction($action, $note);

            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        }
        die(json_encode($message));
    }

    public function ajax_update_field()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $id = $this->input->post('id');
            $field = $this->input->post('field');
            $value = $this->input->post('value');
            $response = $this->_data->update(['id' => $id], [$field => $value]);
            if ($response != false) {
                $message['type'] = 'success';
                $message['message'] = $this->lang->line('mess_update_success');
            } else {
                $message['type'] = 'error';
                $message['message'] = $this->lang->line('mess_update_unsuccess');
            }
            print json_encode($message);
        }
        exit;
    }

    /*
     * Kiêm tra thông tin post lên
     * */
    private function _validate()
    {
        $this->checkRequestPostAjax();
        $data = $this->input->post();
        $rules = [];
        if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
            $required = '';
            if ($lang_code == $this->config->item('default_language')) {
                $required = 'required|';
            }
            $rulesLang = array(
                array(
                    'field' => 'title[' . $lang_code . ']',
                    'label' => 'Tên',
                    'rules' => $required . 'trim|min_length[3]|max_length[255]|trim|xss_clean|callback_validate_html',
                ), array(
                    'field' => 'description[' . $lang_code . ']',
                    'label' => 'Tóm tắt ',
                    'rules' => 'trim|xss_clean'
                )
            );

            $rules = array_merge($rulesLang, $rules);
        }

        // if (!empty($data['detail'])) foreach ($data['detail'] as $key => $value) {
        //     $rules[] = array(
        //         'field' => 'detail['. $key .'][name]',
        //         'label' => 'Tiêu đề',
        //         'rules' => 'required|trim|xss_clean|callback_validate_html'
        //     );
        // }

        $rules[] = array(
            'field' => 'thumbnail',
            'label' => 'Ảnh đại diện',
            'rules' => 'trim|xss_clean|callback_validate_html'
        );
        $rules[] = array(
            'field' => 'url',
            'label' => 'Đường dẫn',
            'rules' => 'trim|xss_clean|callback_validate_html'
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
        if (!in_array($data['is_status'], [0, 1])) {
            $data['is_status'] = 0;
        }

        // if (!empty($data['detail'])) {
        //     $data['detail'] = json_encode($data['detail']);
        // } else {
        //     $data['detail'] = '';
        // }

        $data['position'] = 1;

        if (empty($data['displayed_time']) || !isDateTime($data['displayed_time'])) $data['displayed_time'] = date('Y-m-d');
        else $data['displayed_time'] = convertDate($data['displayed_time']);
        return $data;
    }
}
