<?php
	if (!function_exists('resJSON')) {
		/*
		* hàm trả về header chuẩn json
		* res: mảng dữ liệu trả về
		*/
		function resJSON($res)
		{
			header('Content-Type: application/json');
			die(json_encode($res));
		}
	}

	function jsonError($message = 'Có lỗi xảy ra')
	{
		die(json_encode([
			'type' => 'error',
			'message' => $message
		]));
	}

	function jsonWarning($message = 'Vui lòng kiểm tra lại thông tin')
	{
		die(json_encode([
			'type' => 'warning',
			'message' => $message
		]));
	}

	function jsonSuccess($message = '')
	{
		die(json_encode([
			'type' => 'success',
			'message' => $message
		]));
	}

	function redirectShowMessage($message, $type, $link_redirect = '')
	{
		$_this = &get_instance();
		if (empty($link_redirect)) {
			$link_redirect = base_url() . 'admin/' . $_this->controller;
		}
		$_this->session->set_flashdata('message', [
			'type' => $type,
			'message' => $message
		]);
		redirect($link_redirect);
	}

	function redirectError($message = 'Có lỗi xảy ra', $link_redirect = '')
	{
		$type = 'error';
		redirectShowMessage($message, $type, $link_redirect);
	}

	function redirectWarning($message = 'Thiếu thông tin dữ liệu', $link_redirect = '')
	{
		$type = 'warning';
		redirectShowMessage($message, $type, $link_redirect);
	}

	function redirectSuccess($message = '', $link_redirect = '')
	{
		$type = 'success';
		redirectShowMessage($message, $type, $link_redirect);
	}



