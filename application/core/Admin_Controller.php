<?php
	class Admin_Controller extends APS_Controller
	{
		public function __construct()
		{
			parent::__construct();
			//set đường dẫn template
			$this->template_path = 'admin/';
			$this->template_main = $this->template_path . '_layout';
			$this->templates_assets = base_url() . 'public/admin/';
			//fix language admin tiếng việt
			$this->session->admin_lang = $this->_lang_default;
			$this->load->model('system_menu_model');
			$list_menus = $this->system_menu_model->getMenu();
			$this->load->vars(array('list_menu' => $list_menus));
			//tải thư viện
			$this->load->library(array('ion_auth'));
			//load helper
			$this->load->helper(array('authorization', 'image', 'format', 'button', 'status'));
			//Load config
			$this->config->load('seo');
			$this->config->load('permission');
			$this->check_auth();
//			$this->connect_redis();

			//đọc file setting
			$dataSetting = file_get_contents(FCPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.cfg');
			$dataSetting = $dataSetting ? json_decode($dataSetting, true) : array();
			if (!empty($dataSetting)) foreach ($dataSetting as $key => $item) {
				if ($key === 'meta') {
					$oneMeta = $item[$this->config->item('default_language')];
					if (!empty($oneMeta)) foreach ($oneMeta as $keyMeta => $value) {
						$this->settings[$keyMeta] = str_replace('"', '\'', $value);
					}
				} else
					$this->settings[$key] = $item;
			}
		}

		// add log action
		public function addLogaction($action, $note)
		{
			$this->load->model("Log_action_model", "logaction");
			$data['action'] = $action;
			$data['note'] = $note;
			$data['uid'] = $this->session->user_id;
			$dates = "%Y-%m-%d %h:%i:%s";
			$time = time();
			$data['created_time'] = mdate($dates, $time);
			$this->logaction->save($data);
		}

		public function check_auth()
		{
			if (!$this->ion_auth->logged_in()) {
				//chưa đăng nhập thì chuyển về page login
				redirect(BASE_ADMIN_URL . 'auth/login?url=' . urlencode(current_url()), 'refresh');
			} else {
				if ($this->session->userdata['user_id'] != 1) {
					$this->session->admin_permission = null;
					if (!$this->session->admin_permission) {
						$this->load->model('groups_model');
						$groupModel = new Groups_model();
						$group = $groupModel->get_group_by_userid((int)$this->session->userdata('user_id'));
						$data = $groupModel->getById($group->group_id);
						if (!empty($data)) {
							$this->session->admin_permission = json_decode($data->permission, true);
						}
					}
					$cmsControllerPermission = $this->config->item('cms_controller_permission'); // list controller cần phân quyền
					$cmsCustomPer = $this->config->item('cms_custom_per'); // Những controller custom phân quyền
					$cmsPerListMethod = $this->config->item('cms_per_list_method'); // những method cần phân quyền theo cms_custom_per
					$cmsNotPerMethod = $this->config->item('cms_not_per_method'); // những method không cần phân quyền
					if (in_array($this->_controller, array_merge($cmsControllerPermission, $cmsCustomPer))) {
						if (!empty($this->session->admin_permission[$this->_controller])) { //check quyen view
							if (in_array($this->_controller, $cmsCustomPer)) {
								$perMethod = (in_array($this->_method, $cmsPerListMethod)) ? $this->_method : $this->session->userdata[$this->_controller . '_type'];
								if (!in_array($this->_method, $cmsNotPerMethod) && (empty($perMethod) || empty($this->session->admin_permission[$this->_controller][$perMethod]['view']))) { //check quyen view
									$this->load->view($this->template_path . 'not_permission');
								} else {
									checkPer($this->session->admin_permission[$this->_controller][$perMethod]);
								}
							} else {
								if (empty($this->session->admin_permission[$this->_controller]['view'])) { //check quyen view
									$this->load->view($this->template_path . 'not_permission');
								} else {
									checkPer($this->session->admin_permission[$this->_controller]);
								}
							}
						} else {
							$this->load->view($this->template_path . 'not_permission');
						}
					}
				}
			}
		}

        public function ajax_update_field()
        {
            $this->checkRequestPostAjax();
            $post = $this->input->post();

            $id = $post['id'];
            $field = $post['field'];
            $value = $post['value'];

            if ($field == 'is_featured') {
                $language_code = !empty($this->input->post('language_code')) ? $this->input->post('language_code') : $this->session->admin_lang;
                $response = $this->_data->update(['id' => $id, 'language_code' => $language_code], [$field => $value], $this->_data->table_trans);
            } else {
                $response = $this->_data->update(['id' => $id], [$field => $value]);
            }

            if ($response != false) {
                $message['type'] = 'success';
                $message['message'] = $this->lang->line('mess_update_success');
            } else {
                $message['type'] = 'error';
                $message['message'] = $this->lang->line('mess_update_unsuccess');
            }
            $this->returnJson($message);
        }
	}
