<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('account');
        $this->load->library('ion_account');
        $this->load->model(array('account_model'));
        $this->_data = new Account_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        $data['heading_title'] = "Khách hàng";
        $data['heading_description'] = "Quản lý khách hàng";
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

        $where = [];
        if (!empty($post['is_status'])) {
            $where['active'] = intval($post['is_status']) - 1;
        }
        if (!empty($post['month'])) {
            $where['MONTH(birthday)'] = intval($post['month']) - 1;
        }

        $params = [
            'page' => $page,
            'limit' => $length,
            'where' => $where
        ];

        $list = $this->_data->getData($params);
        $data = array();
        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->full_name;
            $row[] = $item->email;
            $row[] = showCenter($item->phone);
            $row[] = showCenter(formatDate($item->birthday));
            $row[] = showStatusUser($item->active);
            $row[] = showCenter(formatDate($item->created_time, 'datetime'));
            $row[] = button_action($item->id, ['edit']);
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->_data->getTotalPro($params),
            "recordsFiltered" => $this->_data->getTotalPro($params),
            "data" => $data,
        );
        $this->returnJson($output);
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data[0] = $oneItem = (array)$this->_data->getById($id);
        $data[0]['birthday'] = formatDate($data[0]['birthday']);
        unset($data[0]['password']);

        die(json_encode($data));
    }

    public function ajax_add()
    {
        $this->_validate();
        $identity = strip_tags(trim($this->input->post('username')));
        $password = strip_tags(trim($this->input->post('password')));
        $email = strip_tags(trim($this->input->post('email')));
        $data_store['phone'] = strip_tags(trim($this->input->post('phone')));
        $data_store['type'] = strip_tags(trim($this->input->post('type')));
        $data_store['gender'] = strip_tags(trim($this->input->post('gender')));
        $data_store['active'] = !empty($this->input->post('active')) ? $this->input->post('active') : 0;
        $data_store['full_name'] = strip_tags(trim($this->input->post('full_name')));
        $data_store['thumbnail'] = strip_tags(trim($this->input->post('thumbnail')));
        $data_store['created_time'] = date('Y-m-d G:i:s');
        $data_store['updated_time'] = date('Y-m-d G:i:s');
        $birthday = strip_tags(trim($this->input->post('birthday')));
        if (!empty($birthday) && isDateTime($birthday))
            $data_store['birthday'] = convertDate($birthday);
        $group_id = 2;
        if ($this->input->post('group_id')) {
            $group_id = strip_tags(trim($this->input->post('group_id')));
        }
        $id = $this->ion_account->register($identity, $password, $email, $data_store, ['group_id' => $group_id]);
        if (!empty($id)) {
            $action = $this->router->fetch_class();
            $note = "Insert $action: " . $id;
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_update()
    {
        $this->_validate();
        $data_store = [];

        if ($this->input->post('password')) {
            $data_store['password'] = strip_tags(trim($this->input->post('password')));
        }

        $data_store['phone'] = strip_tags(trim($this->input->post('phone')));
        $data_store['email'] = strip_tags(trim($this->input->post('email')));
        $data_store['full_name'] = strip_tags(trim($this->input->post('full_name')));
        $data_store['type'] = strip_tags(trim($this->input->post('type')));
        $data_store['active'] = trim($this->input->post('active'));
        $data_store['gender'] = trim($this->input->post('gender'));
        $data_store['birthday'] = $birthday = strip_tags(trim($this->input->post('birthday')));
        $data_store['thumbnail'] = strip_tags(trim($this->input->post('thumbnail')));
        if (!empty($birthday) && isDateTime($birthday)) $data_store['birthday'] = convertDate($birthday);

        $response = $this->ion_account->update($this->input->post('id'), $data_store);
        if ($response != false) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: " . $this->input->post('id');
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_update_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }
        die(json_encode($message));
    }

    /*
     * Xóa một bản ghi
     * */
    public function ajax_delete($id)
    {
        $response = $this->_data->update(['id' => $id], ['active' => 0]);
        if ($response != false) {
            $action = $this->router->fetch_class();
            $note = "delete $action: $id";
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_delete_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_delete_unsuccess');
            $message['error'] = $response;
        }
        die(json_encode($message));
    }

    public function ajax_load()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $params = [
                'active' => 1,
                'search' => $term,
                'limit' => 1000,
                'order' => array('created_time' => 'desc')
            ];
            $list = $this->_data->getData($params);
            $json = [];
            if (!empty($list)) foreach ($list as $item) {

                $json[] = ['id' => $item->id, 'text' => $item->username . ' (' . $item->full_name . '- ' . $item->email . ')'];
            }
            echo json_encode($json);
        }
        exit;
    }

    private function _validate()
    {
        $this->checkRequestPostAjax();
        $unique = '';
        if (empty($this->input->post('id'))) {
            $unique = '|callback_check_email|is_unique[account.email]';
        }
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|strip_tags|callback_validate_html|xss_clean'.$unique);
        $this->form_validation->set_rules('full_name', 'Họ và tên', 'required|trim|min_length[3]|max_length[255]|trim|xss_clean|callback_validate_html');
        $this->form_validation->set_rules('phone', 'Số điện thoại', 'required|trim|regex_match[/^[0-9]{10,11}$/]|trim|strip_tags|callback_validate_html|xss_clean');
        $this->form_validation->set_rules('birthday', 'Ngày sinh', 'trim|strip_tags|xss_clean|callback_validate_html|callback_max_time_current');

        $required = '';
        if (!empty($this->input->post('id'))) {
            if (!empty($this->input->post('password')) || !empty($this->input->post('repassword'))) {
                $required = '|required';
            }
        }else{
            $required = '|required';
        }
        $this->form_validation->set_rules('repassword', 'Nhập lại mật khẩu', 'trim|matches[password]|min_length[6]|max_length[20]'.$required);
        $this->form_validation->set_rules('password', 'Mật khẩu', 'trim|min_length[6]|max_length[20]'.$required);
        if ($this->form_validation->run() === false) {
            $message['type'] = "warning";
            $message['message'] = $this->lang->line('mess_validation');
            $valid = [];
            if (empty($this->input->post('id')) || !empty($this->input->post('password'))) {
                $valid["password"] = form_error("password");
                $valid["repassword"] = form_error("repassword");
            }
            $valid["username"] = form_error("username");
            $valid["full_name"] = form_error("full_name");
            $valid["email"] = form_error("email");
            $valid["phone"] = form_error("phone");
            $valid["birthday"] = form_error("birthday");
            $message['validation'] = $valid;
            $this->returnJson($message);
        }
    }

    public function check_username()
    {
        $username = trim($this->input->post('username'));
        $check = $this->_data->check_oauth('username', $username);
        if (!empty($check)) {
            $this->form_validation->set_message('check_username', 'Username đã tồn tại');
            return FALSE;
        }
        if (empty($username)) {
            $this->form_validation->set_message('check_username', 'Trường Username là bắt buộc');
            return FALSE;
        }
        return TRUE;
    }

    public function check_email()
    {
        $email = trim($this->input->post('email'));
        $check = $this->_data->check_oauth('email', $email);
        if (!empty($email) && !empty($check)) {
            $this->form_validation->set_message('check_email', 'Email đã tồn tại');
            return FALSE;
        }

        return TRUE;
    }
}
