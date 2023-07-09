<?php

	class API_Controller extends CI_Controller
	{
		use APS_Trait;
		public $res_body = [];
		public $token = '';
		public $_redis;
		public $user_login = '';
		public $message = [];
		public $controller;
		public $method;
		public $lang_code;
		public $settings;
		const RESPONSE_SUCCESS = 200;
		const RESPONSE_EXIST = 201;
		const RESPONSE_REQUEST_ERROR = 400;
		const RESPONSE_LOGIN_ERROR = 401;
		const RESPONSE_LOGIN_DENIED = 403;
		const RESPONSE_NOT_EXIST = 404;
		const RESPONSE_LIMITED = 406;
		const RESPONSE_SERVER_ERROR = 500;

		public function __construct()
		{
			parent::__construct();
			$this->load->library(array("JWT"));
			$this->load->helper(['general',
				'debug',
				'security',
				'redis',
				'notify',
				'language',
				'datetime',
				'response']);
			$this->controller = $this->router->fetch_class();
			$this->config->load('languages');

			$this->method = $this->router->fetch_method();
			$this->lang_code = $this->input->get_request_header('lang-code') ? $this->input->get_request_header('lang-code') : $this->config->item('default_language');
			$lang_cnf = $this->config->item('cms_lang_cnf');
			$lang_name = !empty($lang_cnf[$this->lang_code]) ? $lang_cnf[$this->lang_code] : $this->config->item('default_language_name');
			$this->lang->load(['api', 'ion_auth'], $lang_name);
			$this->config->set_item('language', $lang_name);
			if ($this->input->get_request_header('x-token')) {
				$this->token = trim($this->input->get_request_header('x-token'));
			} else {
				if ($this->input->get_request_header('Authorization')) {
					$this->token = trim(str_replace('Bearer', '', $this->input->get_request_header('Authorization')));
				}
			}
			$this->set_request_body();
			$this->load_settings();
			$this->connect_redis();
			if (DEBUG_MODE === TRUE) log_message('error', json_encode($this->res_body));
			$this->load->database();
			if ($this->controller !== 'auth') {
				$this->check_token();
			}
		}

		public function checkRequestServer($method = "POST")
		{
			if ($this->input->server('REQUEST_METHOD') !== $method) {
				$this->message = [
					'code' => 400,
					'message' => "Request không hợp lệ"
				];
				$this->returnJson();
			}
			return true;
		}

		public function returnJson($data = null)
		{
			if (empty($data)) $data = $this->message;
			$this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($data, JSON_UNESCAPED_UNICODE))->_display();
			exit();
		}

		function resAPI($message = '', $code = 200, $data = [])
		{
			$res = [
				'message' => $message,
				'code' => $code
			];
			$result = array_merge($res, $data);
			$this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($result, JSON_UNESCAPED_UNICODE))->_display();
			exit();
		}
		public function parseToken(){
			return $this->jwt->decode($this->token, JWT_CONSUMER_SECRET);
		}

		//kiếm tra token
		public function check_token()
		{
			if ($_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR'] && $_SERVER['REQUEST_METHOD'] === 'GET') return true;
			if (!empty($this->token)) {
				try {
					$jwt_dec = $this->parseToken();
					if (empty($jwt_dec) || ((int)$jwt_dec->created_time + (int)$jwt_dec->ttl) < time()) {
						$this->message = [
							'code' => 403,
							'message' => 'Token hết hạn'
						];
						$this->returnJson();
					} else {
						$this->user_login = $jwt_dec;
					}
				} catch
				(Exception $e) {
					$this->message = [
						'code' => 403,
						'message' => 'Phiên đăng nhập hết hạn, vui lòng đăng nhập để sử dụng dịch vụ'
					];
					$this->returnJson();
				}
			} else {
				$this->message = [
					'code' => 403,
					'message' => 'Token không tồn tại'
				];
				$this->returnJson($this->message);
			}
		}

		public function _run_validate($rules = [], $data = [])
		{
			$this->load->library('form_validation');
			$data = !empty($data) ? $data : (array)$this->res_body;
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$this->message = array(
					'validation' => $this->form_validation->error_array(),
					'code' => 400,
					'message' => lang('please_check_info')
				);
				$this->returnJson();
			}
			return true;
		}

		public function getDataRedis($key = null)
		{
			if (ACTIVE_REDIS == TRUE) {
				$key_redis = REDIS_PREFIX . $key;
				return json_decode($this->_redis->get($key_redis));
			}
			return false;
		}

		public function setDataRedis($key = null, $data, $time = 7 * 24 * 60 * 60)
		{
			if (ACTIVE_REDIS == TRUE) {
				$key_redis = REDIS_PREFIX . $key;
				return $this->_redis->set($key_redis, json_encode($data), $time);
			}
			return false;
		}

		public function deleteDataRedis($key = null)
		{
			if (ACTIVE_REDIS == TRUE) {
				$key_redis = REDIS_PREFIX . $key;
				$this->_redis->del($key_redis);
			}
		}

		public function set_request_body()
		{
			$res_body = file_get_contents('php://input');
			$this->res_body = $res_body ? json_decode($res_body) : [];
		}

		public function get_request_body()
		{
			return XSSProtection((array)$this->res_body);
		}
	}
