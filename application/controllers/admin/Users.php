<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends Admin_Controller
{
    protected $_data;
    protected $_data_group;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->load->library(array('ion_auth'));
        $this->lang->load('users');
        $this->load->model(['users_model', 'groups_model']);
        $this->_data = new Users_model();
        $this->_data_group = new Groups_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        $data['heading_title'] = "Tài khoản quản trị";
        $data['heading_description'] = "Thông tin tài khoản";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['group_user'] = $this->_data_group->get_all_group();
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function logged()
    {
        $data = [];
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/logged', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function profile()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $post = $this->input->post();

            $rules = [
                [
                    'field' => 'full_name',
                    'label' => 'Tên',
                    'rules' => 'trim|xss_clean|max_length[50]|callback_validate_html'
                ],
                [
                    'field' => 'company',
                    'label' => 'Công ty',
                    'rules' => 'trim|xss_clean|callback_validate_html'
                ],
                [
                    'field' => 'phone',
                    'label' => 'Số điện thoại',
                    'rules' => 'trim|required|regex_match[/^[0-9]{10,11}$/]|trim|xss_clean|callback_validate_html'
                ],

            ];
            $have_password = !empty($post['password']);
            if ($have_password) {
                $rules_password = 'required|trim|callback_validate_html|callback_validate_strong_password';
                $rules_repassword = 'required|trim|callback_validate_html|matches[password]';
                $rules[] = [
                    'field' => 'password',
                    'label' => 'Mật khẩu',
                    'rules' => $rules_password
                ];
                $rules[] = [
                    'field' => 'repassword',
                    'label' => 'nhắc lại mật khẩu',
                    'rules' => $rules_repassword
                ];

            } else {
                unset($post['password']);
            }
            if (empty($post['repassword'])) unset($post['repassword']);
            $data['error'] = [];
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                if (!empty($rules)) foreach ($rules as $item) {
                    if (!empty(form_error($item['field']))) $data['error'][$item['field']] = form_error($item['field']);
                }
            }
            if (empty($data['error'])) {
                if (isset($post['username'])) unset($post['username']);
                if (isset($post['email'])) unset($post['email']);
                $response = $this->ion_auth->update($this->session->user_id, $post);
                if ($response != false) {
                    // log action
                    $action = $this->router->fetch_class();
                    $note = "Update $action: " . $this->session->user_id;
                    $this->addLogaction($action, $note);
                    $message['type'] = "success";
                    $message['message'] = $this->lang->line('mess_update_success');
                    $this->session->set_flashdata('message', $message);
                } else {
                    $message['type'] = 'error';
                    $message['message'] = $this->lang->line('mess_update_unsuccess');
                    $this->session->set_flashdata('message', $message);
                }
                redirect('admin/users/profile', 'refresh');
            }

        }
        $data['data'] = (array)$this->_data->getById($this->session->user_id);
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/profile', $data, TRUE);

        $this->load->view($this->template_main, $data);
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        $params['page'] = $page;
        $params['limit'] = $length;

        $list = $this->_data->getData($params);
        $data = array();
        $no = $this->input->post('start');
        if (!empty($list)) foreach ($list as $item) {
            $user_group = $this->ion_auth->get_users_groups($item->id)->row();
            $name_user_group = !empty($user_group->name) ? $user_group->name : '';
            if (!empty($item->store_id)) $name_user_group = 'Chủ tiệm';
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->username;
            $row[] = $item->email;
            $row[] = showCenter($item->phone);
            $row[] = $name_user_group;
            $row[] = $item->full_name;
            $row[] = $item->active ? '<div class="text-center"><span class="label label-success">Đang hoạt động</span></div>' : '<div class="text-center"><span class="label label-danger">Ngừng hoạt động</span></div>';
            //thêm action
            $action = ($item->id == 1) ? ['edit'] : ['edit', 'delete'];
            $row[] = button_action($item->id, $action);
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->_data->getTotalAll(),
            "recordsFiltered" => $this->_data->getTotal($params),
            "data" => $data,
        );
        //trả về json
        die(json_encode($output));
    }

    //Load user logged devide recently
    public function ajax_list_logged()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no / $length + 1;
            $params['page'] = $page;
            $params['Product'] = ['updated_time' => 'DESC'];
            $params['limit'] = $length;
            $list = $this->_data->getDataLoggedDevice($params);
            $data = array();
            foreach ($list as $item) {
                $oneUser = getUser($item->user_id);
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = isset($oneUser->username) ? $oneUser->username : '';
                $row[] = $item->ip_address;
                $row[] = $item->user_agent;
                $row[] = date('d/m/Y H:i', strtotime($item->created_time));
                $row[] = date('d/m/Y H:i', strtotime($item->updated_time));
                $data[] = $row;
            }

            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAllLoggedDevice(),
                "recordsFiltered" => $this->_data->getTotalLoggedDevice($params),
                "data" => $data,
            );
            //trả về json
            die(json_encode($output));
        }
        exit;
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data = (array)$this->_data->getById($id);
        unset($data['password']);
        $data['group_id'] = $this->ion_auth->get_users_groups($id)->row()->id;
        die(json_encode($data));
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_add()
    {
        $this->_validate();
        $identity = strip_tags(trim($this->input->post('username')));
        $password = strip_tags(trim($this->input->post('password')));
        $email = strip_tags(trim($this->input->post('email')));
        $data_store['full_name'] = strip_tags(trim($this->input->post('full_name')));
        $data_store['phone'] = strip_tags(trim($this->input->post('phone')));
        $data_store['company'] = strip_tags(trim($this->input->post('company')));
        $data_store['active'] = $this->input->post('active');
        $group_id = 2;
        if($this->input->post('group_id')){
            $group_id = strip_tags(trim($this->input->post('group_id')));
        }
        if ($this->ion_auth->register($identity, $password, $email, $data_store, array('group_id' => $group_id)) !== false) {
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
     * Cập nhật thông tin
     * */
    public function ajax_update()
    {
        $this->_validate();
        $data_store = $this->input->post();
        foreach ($data_store as $key => $val) {
            $data_store[$key] = strip_tags(trim($val));
        }
        $data_store['active'] = $this->input->post('active');
        $response = $this->ion_auth->update($this->input->post('id'), $data_store);
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
        if ($id == 1) {
            $message['type'] = 'error';
            $message['message'] = 'Không được phép xóa';
            print json_encode($message);
            die;
        }
        $response = $this->_data->delete(['id' => $id]);
        if ($response != false) {
            // log action
            $action = $this->router->fetch_class();
            $note = "delete $action: $id";
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

    public function validate_strong_password($password)
    {
        if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[0-9])(?=.*\W).*$#", $password)) {
            return true;
        } else {
            $this->form_validation->set_message('validate_strong_password', lang('form_validation_password'));
            return false;
        }
    }

    private function _validate($ajax = true)
    {
        if (empty($this->input->post('id')) || !empty($this->input->post('username'))) {
            $rules_username = 'required|trim|min_length[3]|max_length[50]|trim|xss_clean|callback_validate_html|is_unique[users.username]';

        } else {
            $rules_username = 'trim|min_length[3]|max_length[50]|trim|xss_clean|callback_validate_html';
        }

        if ((empty($this->input->post('id')) || !empty($this->input->post('password')))) {
            $rules_password = 'required|trim|callback_validate_html|callback_validate_strong_password';
            $rules_repassword = 'required|trim|callback_validate_html|matches[password]';

        } else {
            $rules_password = 'trim';
            $rules_repassword = 'trim';
        }

        $rules = [
            [
                'field' => 'username',
                'label' => 'Tài khoản',
                'rules' => $rules_username
            ],
            [
                'field' => 'password',
                'label' => 'Mật khẩu',
                'rules' => $rules_password
            ],
            [
                'field' => 'repassword',
                'label' => 'nhắc lại mật khẩu',
                'rules' => $rules_repassword
            ],
            [
                'field' => 'full_name',
                'label' => 'Họ và tên',
                'rules' => 'trim|xss_clean|max_length[50]|callback_validate_html'
            ],
            [
                'field' => 'phone',
                'label' => 'Số điện thoại',
                'rules' => 'required|regex_match[/^[0-9]{10,11}$/]|trim|xss_clean|callback_validate_html'
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => (empty($this->input->post('id')) || !empty($this->input->post('email'))) ? 'required|trim|valid_email|is_unique[users.email]' : 'trim|valid_email'
            ],
        ];

        if ($this->input->post('group_id')) {
            $group_id = strip_tags(trim($this->input->post('group_id')));
            if ($group_id == 'CT') {
                $rules[] = [
                    'field' => 'store_id',
                    'label' => 'Tiệm',
                    'rules' => 'required'
                ];
                $group_id = "1";
            }
            $this->ion_auth->remove_from_group(NULL, $this->input->post('id'));
            if (!empty($this->input->post('id'))) {
                $this->ion_auth->add_to_group($group_id, $this->input->post('id'));
            }
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === false) {
            $this->return_notify_validate($rules);
        }
    }
}