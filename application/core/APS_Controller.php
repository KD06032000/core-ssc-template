<?php

	/**
	 * User: linhth
	 * Created Date: 23/03/2019
	 * Updated Date: 25/03/2019
	 */
	defined('BASEPATH') or exit('No direct script access allowed');

	include 'APS_Trait.php';
	class APS_Controller extends CI_Controller
	{
		use APS_Trait;
		public $template_path = '';
		public $template_main = '';
		public $templates_assets = '';
		public $_message = array();
		public $_lang_default;
		public $_redis;
		public $_controller;
		public $_method;

		public function __construct()
		{
			parent::__construct();
			//Load library
			$this->load->library(array('session', 'form_validation', 'user_agent', 'breadcrumbs'));
			$this->load->helper(array('data', 'email', 'security', 'youtube', 'cms', 'general', 'url', 'number', 'link', 'directory', 'file', 'form', 'datetime', 'language', 'debug', 'curl', 'string', 'response', 'builder_query'));
			$this->config->load('cms');
			$this->config->load('languages');
			$this->config->load('images');
			$this->lang->load('general');
			//Load database
			$this->load->database();
			$this->_controller = $this->router->fetch_class();
			$this->_method = $this->router->fetch_method();
			if (DEBUG_MODE == TRUE && !is_numeric(strpos(current_url(), 'api'))) {
				//Load third party
				$this->load->add_package_path(APPPATH . 'third_party', 'codeigniter-debugbar');
				$this->output->enable_profiler(TRUE);
			}
			$this->_lang_default = $this->config->item('default_language');
		}

		public function get_setting($key = '')
		{
			if (isset($this->settings[$key])) {
				return $this->settings[$key];
			}
			return null;
		}


		public function validate_html($str)
		{
			if ($str == strip_tags($str) && $str == html_entity_decode($str)) {
				return true;
			} else {
				$this->form_validation->set_message('validate_html', lang('form_validation_html_title'));
				return false;
			}
		}

		public function echoJson($type, $mess, $code = '')
		{
			$respon = new stdClass;
			$respon->type = $type;
			$respon->message = $mess;
			if (!empty($code)) {
				$respon->code = $code;
			}
			die(json_encode($respon));
		}

		public function default_rules_lang($lang_code, $label = [], $seo = true)
		{
			$title = 'Tiêu đề ';
			$meta_title = 'Meta title ';
			$description = 'Tóm tắt ';
			$slug = 'Url ';
			$meta_description = 'Meta description ';
			$meta_keyword = 'Meta keyword';
			extract($label);
			$required = '';
			if ($lang_code == $this->config->item('default_language')) {
				$required = 'required|';
			}

			$rules_seo = [];
			if ($seo) {
                $rules_seo = [
                    array(
                        'field' => 'meta_title[' . $lang_code . ']',
                        'label' => $meta_title,
                        'rules' => $required . 'trim|xss_clean|callback_validate_html'
                    ), array(
                        'field' => 'slug[' . $lang_code . ']',
                        'label' => $slug,
                        'rules' => $required . 'trim|xss_clean|callback_validate_html'
                    ), array(
                        'field' => 'meta_description[' . $lang_code . ']',
                        'label' => $meta_description,
                        'rules' => 'trim|xss_clean|callback_validate_html'
                    ), array(
                        'field' => 'meta_keyword[' . $lang_code . ']',
                        'label' => $meta_keyword,
                        'rules' => 'trim|xss_clean|callback_validate_html'
                    )
                ];
            }

			return array_merge($rules_seo, [
                array(
                    'field' => 'title[' . $lang_code . ']',
                    'label' => $title,
                    'rules' => $required . 'trim|min_length[3]|max_length[255]|trim|xss_clean|callback_validate_html'
                ), array(
                    'field' => 'description[' . $lang_code . ']',
                    'label' => $description,
                    'rules' => 'trim|xss_clean|callback_validate_html'
                ),
            ]);
		}

		public function return_notify_validate($rules)
		{
			$valid = [];
			if (!empty($rules)) foreach ($rules as $item) {
				if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
			}
			$this->_message = array(
				'validation' => $valid,
				'type' => 'warning',
				'message' => $this->lang->line('mess_validation')
			);
			$this->returnJson();
		}

		public function check_redirect_link()
		{
			$check_link = $this->db->where(['link' => current_url()])->get('redirect')->row(0);
			if (!empty($check_link)) {
//				http_response_code(301);
//				$redirect = $check_link->redirect_link;
//
//                $data['url'] = $redirect;
//                $data['main_content'] = $this->load->view($this->template_path . 'page/page-redirect', $data, TRUE);
//                $this->load->view($this->template_main, $data);

//				redirect($redirect, 'location', 301);
			}
		}

		public function show_404()
		{
			redirect(site_url('404_override'), 'location', 404);
		}

		public function checkRequestGetAjax($type = 0)
		{
			if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'))
				if ($type == 1) {
					return false;
				} else {
					die('Not Allow');
				} else {
				return true;
			}
		}

		public function checkRequestPostAjax($type = 0)
		{
			$check = ($this->input->server('REQUEST_METHOD') !== 'POST'
				|| empty($_SERVER['HTTP_X_REQUESTED_WITH'])
				|| (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'));
			if ($check) {
				if ($type == 1) {
					return false;
				}
				die('Not Allow');
			}
			return true;
		}

		public function returnJson($data = null)
		{
			if (empty($data)) $data = $this->_message;
			die(json_encode($data));
		}

		public function max_time_current($date)
		{
			if (!empty($date)) {
				$date = str_replace('/', '-', $date);
				if (date('Y-m-d', strtotime($date)) >= date('Y-m-d')) {
					$this->form_validation->set_message('max_time_current', '%s ' . lang('mess_greater_current_date'));
					return false;
				}
			}
			return true;
		}

		public function min_time_current($date)
		{
			if (!empty($date)) {
				$date = str_replace('/', '-', $date);
				if (strtotime(date('Y-m-d', strtotime($date))) < strtotime(date('Y-m-d'))) {
					$this->form_validation->set_message('min_time_current', '%s ' . 'phải lớn hơn ngày hiện tại');
					return false;
				}
			}
			return true;
		}

		public function load_view($view, $data)
		{
			$data['main_content'] = $this->load->view($view, $data, true);
			$this->load->view($this->template_main, $data);
		}


	}

	include 'Admin_Controller.php';
	include 'Public_Controller.php';
	include 'API_Controller.php';
