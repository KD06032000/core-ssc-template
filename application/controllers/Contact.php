<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends Public_Controller
{
    protected $_data_contact;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('contact_model'));
        $this->_data_contact = new Contact_model();
    }

    private function _validate($callback = '')
    {
        $this->checkRequestPostAjax();
        $rules = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|max_length[255]|valid_email|xss_clean'
            ),
            array(
                'field' => 'phone',
                'label' => lang('phone_number'),
                'rules' => 'required|trim|strip_tags|regex_match[/^[0-9]{10,11}$/]|xss_clean|callback_validate_html'
            ),
            array(
                'field' => 'fullname',
                'label' => lang('full_name'),
                'rules' => 'required|trim|max_length[255]|strip_tags|xss_clean|callback_validate_html'
            ),
            array(
                'field' => 'company',
                'label' => lang('company'),
                'rules' => 'trim|strip_tags|max_length[255]|xss_clean|callback_validate_html'
            ),
            array(
                'field' => 'content',
                'label' => lang('content_mess'),
                'rules' => 'trim|strip_tags|max_length[255]|xss_clean|callback_validate_html'
            ),
        );

        if (is_callable($callback)) {
            $rules = array_merge($rules, $callback());
        }

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }

    public function send_contact()
    {
        $this->_validate();

        $data = $this->input->post();
        $data['type'] = 'contact';

        $template_mail = 'contact';

        $listEmailBCC = array(
            $this->settings['email_admin']
        );

        $email_admin = $this->settings['email_admin'];

        $subject = lang('customer_contact');

        $response = sendMail($data['email'], $email_admin, $subject, $template_mail, $data, '', $listEmailBCC);

        if ($response && $this->_data_contact->insert($data)) {
            $message['type'] = "success";
            $message['message'] = lang('send_contact_success');
        } else {
            $message['type'] = "warning";
            $message['message'] = lang('send_contact_unsuccess');
        };

        die(json_encode($message));
    }

    public function send_register()
    {
        $this->_validate();

        $data = $this->input->post();
        $data['type'] = 'register';

        $template_mail = 'contact';

        $listEmailBCC = array(
            $this->settings['email_admin']
        );

        $email_admin = $this->settings['email_admin'];

        $subject = lang('contact_info');

        $response = sendMail($data['email'], $email_admin, $subject, $template_mail, $data, '', $listEmailBCC);

        if ($response && $this->_data_contact->insert($data)) {
            $message['type'] = "success";
            $message['message'] = lang('send_register_success');
        } else {
            $message['type'] = "warning";
            $message['message'] = lang('send_register_unsuccess');
        };

        die(json_encode($message));
    }

    public function check_recaptcha($str)
    {
        $captcha_key = trim($str);
        if (empty($captcha_key)) {
            $this->form_validation->set_message('check_recaptcha', lang('required_recaptcha'));
            return FALSE;
        } else {
            $userIp = $this->input->ip_address();
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . GG_CAPTCHA_SECRET_KEY . "&response=" . $captcha_key . "&remoteip=" . $userIp;
            $response = callCURL($url);

            $result = json_decode($response, true);
            if ($result['success']) {
                return TRUE;
            } else {
                $this->form_validation->set_message('check_recaptcha', lang('verify_recaptcha'));
                return FALSE;
            }
        }
    }

    public function register_party()
    {
        $this->_validate(function () {
            return [
                [
                    'field' => 'humans',
                    'label' => lang('number_of_attendees'),
                    'rules' => 'required|trim|strip_tags|is_natural|xss_clean|callback_validate_html'
                ],
                [
                    'field' => 'date_start',
                    'label' => lang('organization_date'),
                    'rules' => 'required|trim|strip_tags|xss_clean|callback_validate_html'
                ],
                [
                    'field' => 'time_range',
                    'label' => lang('hold_time'),
                    'rules' => 'required|trim|strip_tags|xss_clean|callback_validate_html'
                ]
            ];
        });
        $data = $this->input->post();
        $data['type'] = 'party';
        $data['time_start'] = trim(explode('-', $data['time_range'])[0]);
        $data['time_end'] = trim(explode('-', $data['time_range'])[1]);

        $template_mail = 'party';

        $listEmailBCC = array(
            $this->settings['email_admin']
        );

        $email_admin = $this->settings['email_admin'];

        $subject = lang('customers_order_a_party');

        $response = sendMail($data['email'], $email_admin, $subject, $template_mail, $data, '', $listEmailBCC);
        unset($data['time_range']);
        $data['date_start'] = convertDate($data['date_start']);

        if ($response && $this->_data_contact->insert($data)) {
            $message['type'] = "success";
            $message['message'] = lang('register_party_success');
        } else {
            $message['type'] = "warning";
            $message['message'] = lang('register_party_unsuccess');
        };

        die(json_encode($message));
    }

    public function register_conference()
    {
        $this->_validate(function () {
            return [
                [
                    'field' => 'humans',
                    'label' => lang('number_of_attendees'),
                    'rules' => 'required|trim|strip_tags|is_natural|xss_clean|callback_validate_html'
                ],
                [
                    'field' => 'date_start',
                    'label' => lang('organization_date'),
                    'rules' => 'required|trim|strip_tags|xss_clean|callback_validate_html'
                ],
                [
                    'field' => 'time_range',
                    'label' => lang('hold_time'),
                    'rules' => 'required|trim|strip_tags|xss_clean|callback_validate_html'
                ]
            ];
        });

        $data = $this->input->post();
        $data['type'] = 'conference';
        $data['time_start'] = trim(explode('-', $data['time_range'])[0]);
        $data['time_end'] = trim(explode('-', $data['time_range'])[1]);

        $template_mail = 'conference';

        $listEmailBCC = array(
            $this->settings['email_admin']
        );

        $email_admin = $this->settings['email_admin'];

        $subject = lang('customer_booked_a_conference');

        $response = sendMail($data['email'], $email_admin, $subject, $template_mail, $data, '', $listEmailBCC);
        unset($data['time_range']);
        $data['date_start'] = convertDate($data['date_start']);

        if ($response && $this->_data_contact->insert($data)) {
            $message['type'] = "success";
            $message['message'] = lang('register_success');
        } else {
            $message['type'] = "warning";
            $message['message'] = lang('register_unsuccess');
        }

        die(json_encode($message));
    }
}
