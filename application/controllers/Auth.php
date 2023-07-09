<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Public_Controller
{
    protected $_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('hybridauth', 'ion_account'));
        $this->load->model('account_model');
        $this->_data = new Account_model();
    }

    public function profile()
    {
        $data = [];
        $data['main_content'] = $this->load->view($this->template_path . 'account/profile', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_login()
    {
        $this->checkRequestPostAjax();
        $this->form_validation->set_rules('identity', lang('email_or_phone'), 'trim|required');
        $this->form_validation->set_rules('password', lang('txt_password'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            if ($account = $this->ion_account->login($this->input->post('identity'), $this->input->post('password'), false)) {
                //if the login is successful
                //redirect them back to the home page
                $account = $this->_data->getById($account->id, '', '*', $this->session->public_lang_code);
                if ($account->active == 1) {
                    $this->session->is_account_logged = true;
                    $this->session->userdata['account']['account_id'] = $account->id;
                    $this->session->userdata['account']['account_identity'] = !empty($account->username) ? $account->username : $account->email;
                    $this->_message = [
                        'type' => 'success',
                        'message' => lang('login_success'),
                    ];
                } else {
                    $this->_message = [
                        'type' => 'warning',
                        'message' => lang('account_deactive'),
                    ];
                }
            } else {
                $this->_message = [
                    'type' => 'warning',
                    'message' => strip_tags(strip_tags($this->ion_account->errors())),
                ];
            }
        } else {
            $validation['identity'] = form_error('identity');
            $validation['password'] = form_error('password');
            $this->_message = [
                'type' => 'warning',
                'message' => lang('mess_validation'),
                'validation' => $validation
            ];
        }
        $this->returnJson($this->_message);
    }

    public function ajax_register()
    {
        $this->checkRequestPostAjax();
        $dataPost = $this->input->post();
        $rules = array(
            array(
                'field' => 'full_name',
                'label' => lang('txt_full_name'),
                'rules' => 'trim|required|strip_tags|xss_clean'
            ), array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email|is_unique[account.email]strip_tags|xss_clean'
            ), array(
                'field' => 'phone',
                'label' => lang('txt_number_phone'),
                'rules' => 'trim|required|regex_match[/^[0-9.-]{10,11}+$/]|is_unique[account.username]'
            ), array(
                'field' => 'password',
                'label' => lang('create_user_validation_password_label'),
                'rules' => 'required|min_length[6]|max_length[32]|strip_tags|xss_clean'
            ), array(
                'field' => 'birthday',
                'label' => lang('txt_birthday'),
                'rules' => 'required|callback_max_time_current'
            )
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            if (!empty($rules)) foreach ($rules as $item) {
                if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $this->_message = array(
                'validation' => $valid,
                'type' => 'warning',
                'message' => $this->lang->line('mess_validation')
            );
        } else {
            $identity = $email = strip_tags(trim($dataPost['email']));
            $password = strip_tags(trim($dataPost['password']));
            $data_store['phone'] = strip_tags(trim($dataPost['phone']));
            $data_store['active'] = 1;
            $data_store['birthday'] = date('Y-m-d', strtotime(convertDate($dataPost['birthday'], '/')));
            $data_store['full_name'] = strip_tags(trim($dataPost['full_name']));
            $id_user = $this->ion_account->register($identity, $password, $email, $data_store, ['group_id' => 2]);
            if ($id_user !== false) {
                $this->_message = [
                    'type' => 'success',
                    'message' => lang('register_success'),
                ];
            } else {
                $this->_message = [
                    'type' => 'warning',
                    'message' => lang('register_unsuccess'),
                ];
            }
        }
        $this->returnJson($this->_message);
    }

    public function forgot_password()
    {
        $message = array();
        // setting validation rules by checking whether identity is username or email
        if ($this->config->item('identity', 'ion_account') != 'email') {
            $this->form_validation->set_rules('identity_fp', $this->lang->line('forgot_password_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('identity_fp', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }
        if ($this->form_validation->run() === FALSE) {
            $data['type'] = $this->config->item('identity', 'ion_account');
            // setup the input
            $data['identity'] = array('name' => 'identity',
                'id' => 'identity',
            );

            if ($this->config->item('identity', 'ion_account') != 'email') {
                $data['identity_label'] = $this->lang->line('forgot_password_identity_label');
            } else {
                $data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            $this->_message = [
                'message' => lang('mess_validation'),
                'type' => 'warning',
                'validation' => ['identity_fp' => form_error('identity_fp')]
            ];
        } else {
            $identity_column = $this->config->item('identity', 'ion_account');
            $value_identity =  $this->input->post('identity_fp');
            $user_data = $this->_data->getUserByField($identity_column, $value_identity);
            if (empty($user_data)) {
                $this->_message = [
                    'message' => $identity_column . ' của bạn không tồn tại',
                    'type' => 'warning',
                ];
            } else {
                $identity = $this->ion_account->where($identity_column, $value_identity)->user($user_data->id)->row();
                if (empty($identity)) {
                    if ($this->config->item('identity', 'ion_account') != 'email') {
                        $this->ion_account->set_error('forgot_password_identity_not_found');
                    } else {
                        $this->ion_account->set_error('forgot_password_email_not_found');
                    }
                    $this->_message = [
                        'message' => strip_tags($this->ion_account->errors()),
                        'type' => 'warning',
                    ];
                }

                // create new pass
                $md5_hash = md5(rand(0, 999));
                $security_code = substr($md5_hash, 15, 10);
                $password = $this->ion_account->reset_password($identity->{$this->config->item('identity', 'ion_account')}, $security_code);

                // run the forgotten password method to email an activation code to the user
                $forgotten = $this->ion_account->forgotten_password($identity->{$this->config->item('identity', 'ion_account')}, $security_code);

                if ($forgotten && $password) {
                    // if there were no errors
                    $this->_message = [
                        'message' => strip_tags($this->ion_account->messages()),
                        'type' => 'success',
                    ];
                } else {
                    $this->_message = [
                        'message' => strip_tags($this->ion_account->errors()),
                        'type' => 'warning',
                    ];
                }
            }
        }
        $this->returnJson($this->_message);
    }

    public function ajax_reset_password()
    {
        $data = array();
        $code = $this->input->post('key_forgotten');
        if (empty($code)) {
            $data['type'] = 'error';
            $data['message'] = lang('mess_validation');
        } else {
            $user = $this->ion_account->forgotten_password_check($code);

            if ($user) {
                // if the code is valid then display the password reset form
                $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_account') . ']|max_length[' . $this->config->item('max_password_length', 'ion_account') . ']');
                $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required|matches[new]');

                if ($this->form_validation->run() === TRUE) {
                    // display the form
                    // set the flash data error message if there is one
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_account')};

                    $change = $this->ion_account->reset_password($identity, $this->input->post('new'));
//        dd($change);
                    if ($change) {
                        $data['type'] = 'success';
                        $data['message'] = strip_tags($this->ion_account->messages());
                        // Gửi mail khi đổi mk thành công

                    } else {

                        $data['type'] = 'error';
                        $data['message'] = strip_tags($this->ion_account->errors());

                    }
                } else {
                    $data['type'] = 'warning';
                    $data['validation'] = [
                        'new' => form_error('new'),
                        'new_confirm' => form_error('new_confirm'),
                    ];
                    $data['message'] = lang('mess_validation');
                }

            } else {
                $data['type'] = 'error';
                $data['message'] = strip_tags($this->ion_account->errors());
            }
        }

        $this->returnJson($data);
    }

    public function logout()
    {
        $logout = $this->ion_account->logout();
        $calback = empty($this->input->get('url')) ? base_url() : urldecode($this->input->get('url'));
//        $this->session->set_flashdata('message', $this->ion_account->messages());
//        $this->session->set_flashdata('type', 'success');
        redirect($calback, 'refresh');
    }


    /**
     * Try to authenticate the user with a given provider
     *
     * @param string $provider_id Define provider to login
     */
    public function window($provider_id)
    {
        $data_store = array();
        $params = array(
            'hauth_return_to' => site_url("auth/window/{$provider_id}"),
        );
        if (isset($_REQUEST['openid_identifier'])) {
            $params['openid_identifier'] = $_REQUEST['openid_identifier'];
        }
        $this->input->set_cookie('redirect_login', $_SERVER['HTTP_REFERER'], time() + 60);
        try {
            $adapter = $this->hybridauth->HA->authenticate($provider_id, $params);
            $profile = $adapter->getUserProfile();
            $user_name = str_replace(' ', '', toNormal(strtolower($profile->displayName)));
            $check_auth = $this->_data->check_oauth('oauth_uid', $profile->identifier);
            $check_email = $this->_data->check_oauth('email', $profile->email);
            $check_username = $this->_data->check_oauth('username', $user_name);
//      $check_phone = !(empty($profile->phone)) ? $this->_data->check_oauth('phone', $profile->phone) : '';
            $data_store['oauth_provider'] = $provider_id;
            $data_store['oauth_uid'] = $profile->identifier;
            $data_store['full_name'] = $profile->displayName;
            $data_store['phone'] = $profile->phone;
            $data_store['email'] = $profile->email;
            if (!empty($profile->birthYear)) $data_store['birthday'] = $profile->birthYear . '-' . $profile->birthMonth . '-' . $profile->birthDay;
            $email = $profile->email;
            $identity = (empty($check_username)) ? $user_name : time();
            if (isset($this->session->userdata)) unset($this->session->userdata['account']);
            $urlRedirect = !empty($this->input->cookie('redirect_login')) ? $this->input->cookie('redirect_login') : site_url();
            $file = $profile->photoURL;
            $dir = MEDIA_PATH . '/avatar';
            if (empty($check_auth)) {
                $group_id = 2;
                $data_store['active'] = 1;
                // copy avatar
                if (!is_dir($dir)) {
                    mkdir($dir, '0755');
                }
                chmod($dir, 755);
                $newfile = $dir . '/' . $profile->identifier . '.png';
                copy($file, $newfile);
                $avatar = 'avatar/' . $profile->identifier . '.png';
                $data_store['thumbnail'] = $avatar;
                // End avatar
                $id_user = $this->ion_account->register($identity, $profile->identifier, $email, $data_store, ['group_id' => $group_id]);

                $this->session->is_account_logged = true;

                $this->session->userdata['account']['account_identity'] = $identity;
                $this->session->userdata['account']['account_id'] = $id_user;
                $this->session->set_flashdata('message', lang('login_success'));
                $this->session->set_flashdata('type', 'success');
                $this->session->set_flashdata('return_email', $email);
                redirect($urlRedirect, 'refresh');
            } else {
                $account = $this->_data->getUserByField('oauth_uid', $profile->identifier);
                //if the login is successful
                //redirect them back to the home page
                $this->session->is_account_logged = true;
                $this->session->userdata['account']['account_id'] = $account->id;
                $this->session->userdata['account']['account_identity'] = $account->username;
                $message['type'] = 'success';
                $message['message'] = lang('login_success');
                $this->session->set_flashdata('message', $message);
                redirect($urlRedirect, 'refresh');
            }
        } catch (Exception $e) {
            show_error($e->getMessage());
        }
        redirect($urlRedirect);
    }

    /**
     * Handle the OpenID and OAuth endpoint
     */
    public function endpoint()
    {
        $data = $this->input->get();
        if ($data['hauth_done'] == 'Facebook' && $data['error'] == 'access_denied') {
            $urlRedirect = !empty($this->input->cookie('redirect_login')) ? $this->input->cookie('redirect_login') : site_url();
            redirect($urlRedirect, 'refresh');
        }
        $this->hybridauth->process();
    }

}
