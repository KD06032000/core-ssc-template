<?php

	trait APS_Trait
	{
		public function connect_redis()
		{
			if (ACTIVE_REDIS == TRUE) {
				try {
					$this->_redis = new Redis();
					$this->_redis->pconnect(REDIS_HOST, REDIS_PORT);
					if (REDIS_PASS) {
						$this->_redis->auth(REDIS_PASS);
					}
				} catch (Exception $e) {
					$this->_redis->close();
					$this->_redis = new Redis();
					$this->_redis->pconnect(REDIS_HOST, REDIS_PORT);
					if (REDIS_PASS) {
						$this->_redis->auth(REDIS_PASS);
					}
				}
			}
		}

		public function load_settings()
		{
			//đọc file setting
			$file = FCPATH . 'config' . DIRECTORY_SEPARATOR . '/settings.cfg';
			if (!file_exists($file)) {
				file_put_contents($file, '');
			}
			$dataSetting = file_get_contents($file);
			$this->settings = $dataSetting ? json_decode($dataSetting, true) : array();
		}

		public function setRules($field, $label, $rules)
		{
			return [
				'field' => $field,
				'label' => $label,
				'rules' => $rules
			];
		}

		public function update_single_file($file_name, $allow_type = 'jpg|jpeg|png|gif|GIF|JPG|JPEG|PNG', $uploadDir = '')
		{
			$uploadDir = !empty($uploadDir) ? MEDIA_PATH.$uploadDir : MEDIA_PATH ;
			if (!file_exists($uploadDir)) {
				mkdir($uploadDir, 0755);
			}
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = $allow_type;
			$config['max_size'] = "25600‬";
			$config['file_name'] = date('d_m_Y') . '-' . $_FILES[$file_name]['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload($file_name)) {
				$uploadData = $this->upload->data();
				return $uploadData['file_name'];
			} else {
				return null;
			}
		}
	}
