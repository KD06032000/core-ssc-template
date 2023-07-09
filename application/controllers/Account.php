<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends Public_Controller
{
    protected $_data;
    protected $_data_booking;
    protected $_data_feedback;
    protected $_url;

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('account');
        $this->load->library(['ion_account', 'pagination']);
        $this->load->model(['account_model', 'calendar_model', 'feedback_model']);
        $this->_data = new Account_model();
        $this->_data_booking = new Calendar_model();
        $this->_data_feedback = new Feedback_model();
        $this->_url = BASE_URL_LANG . $this->_controller . '/ajax_booking/';
    }

    public function index()
    {
        if ($this->session->is_account_logged != true) redirect();
        $data['auth'] = $this->_user_login;
        $data['url'] = $this->_url;
        $data['main_content'] = $this->load->view($this->template_path . 'account/profile', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_booking($page = 1)
    {
        $limit = 6;
        $post = $this->input->post();

        // status : 0 - Upcoming; 2 - Completed, 3 - Cancelled
        switch ($post['status']) {
            case '0':
                $data['action'] = 'cancel';
                break;
            case '2':
                $data['action'] = 'review';
                break;
        }
        $params = array(
            'is_status' => $post['status'],
            'limit' => $limit,
            'order' => ['id' => 'DESC', 'order_date' => 'DESC'],
            'where' => ['user_id' => $this->_user_login->id],
            'page' => $page
        );

        /*Pagination*/
        $query_string = get_query_string();
        $data['data'] = $this->_data_booking->getData($params);
        $data['total'] = $this->_data_booking->getTotal($params);
        $data['pagination'] = $this->getPagination(
            $data['total'],
            $limit,
            $this->_url,
            $this->_url . $query_string
        );
        die($this->load->view($this->template_path . 'account/profile/list_booking', $data, TRUE));
    }

    public function ajax_detail_booking($id)
    {
        $data['data'] = $this->_data_booking->getById($id);
        die($this->load->view($this->template_path . 'account/_block_account/detail_booking_modal', $data, TRUE));
    }

    public function ajax_cancel_booking($id)
    {
        $data_store = $this->input->post();
        $data_store['is_status'] = 3;
        if ($this->_data_booking->update(['id' => $id], $data_store)) {
            $this->log_notify($id, 'booking_cancel');
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_review_booking($id)
    {
        $booking = $this->_data_booking->getById($id);

        if ($booking->is_review == '1') {
            $message['type'] = 'warning';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
            goto end;
        }

        $data_store = $this->input->post();
        $data_store['name'] = $this->_user_login->full_name;
        $data_store['store_id'] = $booking->store_id;
        $data_store['service_id'] = $booking->service_id;
        $data_store['employee_id'] = $booking->employee_id;
        $data_store['is_status'] = 1;
        $data_store['code'] = $booking->code;

        if ($this->_data_feedback->save($data_store)) {
            $this->_data_booking->update(['id' => $id], ['is_review' => 1]);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        end:
        die(json_encode($message));
    }

    public function lang_js()
    {
        $lang_curent = $this->session->public_lang_code == 'vi' ? 'vietnamese' : 'english';
        $lang_text = '';
        $lang_code = $this->lang->load('account', $lang_curent, true);
        foreach ($lang_code as $key => $lang) {
            $lang_text .= "language['" . $key . "'] = '" . $lang . "';";
        }
        print_r($lang_text);
        exit;
    }
    //
    //profile_
    public function change_password()
    {
        if ($this->session->is_account_logged != true) redirect();
        $data['heading_title'] = $this->lang->line('pagePersonal');

        $data['oneAccount'] = getUserAccountById($this->session->userdata('account')['account_id'], '', $this->session->public_lang_code);
        /*Breadcrumbs*/
        $this->breadcrumbs->push(" <i class='fa fa-home'></i>", base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/

        $data['main_content'] = $this->load->view($this->template_path . 'account/change_password', $data, TRUE);

        $this->load->view($this->template_main, $data);
    }

    public function update_password()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $pass_old = $this->input->post('pass_old');
            $pass = $this->input->post('password');

            $rules = array(
                array(
                    'field' => 'pass_old',
                    'label' => lang('old_password'),
                    'rules' => 'required|trim|min_length[6]'
                ),
                array(
                    'field' => 'password',
                    'label' => lang('new_password'),
                    'rules' => 'required|trim|min_length[6]|max_length[32]'
                ),
                array(
                    'field' => 'pass',
                    'label' => lang('re-password'),
                    'rules' => 'required|trim|matches[password]|min_length[6]|max_length[32]'
                )
            );

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() === TRUE) {
                if ($pass_old == $pass) {
                    $message['type'] = 'warning';
                    $message['message'] = lang('cannot_password');
                    die(json_encode($message));
                }
                $identity = $this->session->userdata['account']['account_identity'];
                $change = $this->ion_account->change_password($identity, $pass_old, $pass);
                if (!empty($change)) {
                    $message['type'] = "success";
                    $message['message'] = lang('change_password_s');
                } else {
                    $message['type'] = 'warning';
                    $message['message'] = lang('Please_check_password');
                }
            } else {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = array();
                if (!empty($rules)) foreach ($rules as $item) {
                    if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
                }
                $message['validation'] = $valid;
            }
            die(json_encode($message));
        }
    }

    public function update_profile()
    {
        $data_store = $this->_convert();

        if ($this->ion_account->update($this->_user_login->id, $data_store)) {
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_update_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }
        die(json_encode($message));
    }

    public function _convert()
    {
        $this->_validate();
        $data_store = [];

        if ($this->input->post('password')) {
            $data_store['password'] = strip_tags(trim($this->input->post('password')));
        }

        $data_store['phone'] = strip_tags(trim($this->input->post('phone')));
        $data_store['email'] = strip_tags(trim($this->input->post('email')));
        $data_store['full_name'] = strip_tags(trim($this->input->post('full_name')));
        $data_store['active'] = 1;
        $data_store['birthday'] = $birthday = strip_tags(trim($this->input->post('birthday')));
        if (!empty($birthday) && isDateTime($birthday)) $data_store['birthday'] = convertDate($birthday);

        unset($data_store['thumbnail']);
        if (!empty($_FILES['thumbnail']['name'])) {
            $ext = substr($_FILES['thumbnail']['name'], strrpos($_FILES['thumbnail']['name'], '.'));
            $image_name = 'avatar/' . 'avatar_' . time() . $ext;
            $target_dir = MEDIA_NAME . $image_name;
            move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_dir);
            $unlinkOld = $this->unlinkOld();
            if ($unlinkOld->thumbnail != '') unlink(MEDIA_NAME . $unlinkOld->thumbnail);
            $data_store['thumbnail'] = $image_name;
        }

        return $data_store;
    }

    private function _validate()
    {
        $this->checkRequestPostAjax();

        $required = '';
        if (!empty($this->input->post('change-password'))) {
            $required = '|required';
        }

        $rules = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|strip_tags|callback_validate_html|xss_clean'
            ),
            array(
                'field' => 'full_name',
                'label' => 'Họ và tên',
                'rules' => 'required|trim|min_length[3]|max_length[255]|trim|xss_clean|callback_validate_html'
            ),
            array(
                'field' => 'phone',
                'label' => 'Số điện thoại',
                'rules' => 'required|trim|regex_match[/^[0-9]{10,11}$/]|trim|strip_tags|callback_validate_html|xss_clean'
            ),
            array(
                'field' => 'birthday',
                'label' => 'Ngày sinh',
                'rules' => 'trim|strip_tags|xss_clean|callback_validate_html|callback_max_time_current'
            ),
            array(
                'field' => 'password',
                'label' => 'Mật khẩu',
                'rules' => 'trim|min_length[6]|max_length[20]' . $required
            ),
            array(
                'field' => 'repassword',
                'label' => 'Nhập lại mật khẩu',
                'rules' => 'trim|matches[password]|min_length[6]|max_length[20]' . $required
            ),

        );

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }

    private function unlinkOld()
    {
        return $this->_data->unlinkOld();
    }

    public function logout()
    {
        $logout = $this->ion_account->logout();
        redirect('/', 'refresh');
    }
}
