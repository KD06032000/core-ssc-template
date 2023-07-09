<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('authorization')) {
    function authorization($controller, $method = "view")
    {
        $_method = $method . '_' . $controller;
        $_this =& get_instance();
        $_this->load->library('session');
        $_this->load->model('module_model', 'module');
        //$module=$_this->module->get_datatables();
        if ($_this->session->userdata('user_id') == 1) return true;
        $group = $_this->ion_auth->get_users_groups()->result();
        $listAuth = array();
        if (!empty($group)) foreach ($group as $key => $value) {
            if (isset($value->authorization)) {
                $auth = $value->authorization;
                $decode = JSON_decode($auth, true);
                $listAuth = array_merge_recursive($listAuth, $decode);
            }
        }
        $listAuth = array_filter($listAuth);
        if (!empty($listAuth)) foreach ($listAuth as $cont => $action) {
            if (key_exists($controller, $action) == true && key_exists($_method, $action[$controller]) == true)
                return true;
        }
    }
}
if (!function_exists('authLogin')) {
    function authLogin()
    {
        $_this =& get_instance();
        $_this->load->model('module_model', 'module');
        $_this->load->library('session');
        $module = $_this->module->get_datatables();
        $auth = array();
        if ($module) {
            foreach ($module as $list) {
                $row = array();
                if (authorization($list->controller) == true) {
                    $row = 1;
                } else {
                    $row = 0;
                }
                $auth[] = $row;
            }
        }
        if (in_array(1, $auth, true) == false) {
            $_this->session->set_flashdata('error_authorization', 'Bạn không có quyền truy cập vào trang này');
            redirect(site_url('/admin/auth/login'), 'refresh');
        }
    }
}
// Kiểm tra user có quyền không
if (!function_exists('checkPer')) {
    function checkPer($per)
    {
        $_this =& get_instance();
        $method = $_this->router->fetch_method();
        $return = true;
        if (in_array($method, ['ajax_update', 'ajax_add', 'ajax_delete', 'export_excel', 'import_excel'])) {

            switch ($method) {
                case 'ajax_update': // Sửa
                    if (empty($per['edit'])) $return = false;
                    break;
                case 'ajax_add':// Thêm
                    if (empty($per['add'])) $return = false;
                    break;
                case 'ajax_delete':// Xoá
                    if (empty($per['delete'])) $return = false;
                    break;
                case 'export_excel':// Export
                    if (empty($per['export'])) $return = false;
                    break;
                default:
                    $return = true;
            }
        }
        if ($return == false) {

            if (in_array($method, ['export_excel', 'import_excel'])) {
                redirect($_SERVER['HTTP_REFERER']);
            }

            $message = [
                'type' => 'warning',
                'message' => 'Bạn không có quyền thực hiện chức năng này !'];
            die(json_encode($message));
        } else {
            return true;
        }
    }
}
if (!function_exists('perUserLogin')) {
//  Kiểm tra phân quyền của user đang login
    function perUserLogin($controller, $cmsCusPer = '', $cmsPerMethod = '')
    {
        $_this =& get_instance();
        $return = false;
        if ($_this->session->userdata['user_id'] != 1) {
            $conExp = explode('/', $controller);
            if ($conExp[0] == 'dashboard') {
                $return = true;
            } else {
                if (!empty($conExp[1]) && in_array($conExp[0], $cmsCusPer) && in_array($conExp[1], $cmsPerMethod)) {
                    if (!empty($_this->session->userdata['admin_permission'][$conExp[0]][$conExp[1]])) $return = true;
                } else {
                    if (!empty($_this->session->userdata['admin_permission'][$conExp[0]])) $return = true;
                }
            }
        } else {
            $return = true;
        }

        return $return;
    }
}