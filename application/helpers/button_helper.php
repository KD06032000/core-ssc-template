<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    if (!function_exists('button_admin')) {

//    dd($this->session->admin_permission);

        //       if($this->session->admin_permission[$controller]['add'])
        function button_admin($args = '')
        {
            $_this =& get_instance();
            if (empty($args)) $args = array('add', 'delete');

            if ($_this->session->userdata['user_id'] == 1) {
                if (empty($args)) {
                    showButtonAdd();
                    showButtonDelete();
                } else {
                    if (in_array('import', $args)) showButtonImport();
                    if (in_array('export', $args)) showButtonExport();
                    if (in_array('add', $args)) showButtonAdd();
                    if (in_array('delete', $args)) showButtonDelete();
                }
            } else {
                $per = getPerButton();
                if (in_array('export', $args)) {
                    if (isset($per['export'])) {
                        showButtonExport();
                    }
                }
                if (in_array('import', $args)) {
                    if (isset($per['import'])) {
                        showButtonImport();
                    }
                }
                if (in_array('add', $args)) {
                    //dd($args);
                    if (isset($per['add'])) {

                        showButtonAdd();
                    }
                }

                if (in_array('delete', $args)) {
                    if (isset($per['delete'])) {
                        showButtonDelete();
                    }
                }
                if (in_array('copy', $args)) {
                    if (isset($per['copy'])) {
                        showButtonCopy();
                    }
                }


            }

        }
    }
    function getPerButton($controller = '', $type = '')
    {
        $_this =& get_instance();
        $controller = !empty($controller) ? $controller : $_this->router->fetch_class();
        $perController = [];
        if ($_this->session->userdata['user_id'] != 1 && !empty($_this->session->admin_permission[$controller])) {
            $perController = $_this->session->admin_permission[$controller];

            $cmsCusPer = $_this->config->item('cms_custom_per');
            if (in_array($controller, $cmsCusPer)) {
                $type = !empty($type) ? $type : $_this->session->userdata[$controller . '_type'];
                $perController = $_this->session->admin_permission[$controller][$type];
            }
        }

        return $perController;
    }

// button view

    if (!function_exists('button_action')) {

        function button_action($id = '', $args = array('edit', 'delete'))
        {
            $_this =& get_instance();
            $controller = $_this->router->fetch_class();
            $per = getPerButton();
            $action = '';
            $action .= '<div class="text-center nowrap">';
            if ($controller == 'voucher') {
                $action .= '&nbsp;<a class="btn btn-sm  btn-info" title="Xem user sử dụng" onclick="user_used(' . "'" . $id . "'" . ')">Chi tiết&nbsp;</a>';
            }
            if (in_array('edit', $args)) {
                if (isset($per['edit']) || $_this->session->userdata['user_id'] == 1) {
                    $action .= '&nbsp;<a class="btn btn-sm btn-primary" id="edit_form" href="javascript:void(0)" title="' . $_this->lang->line('btn_edit') . '" data-id="' . $id . '">' . $_this->lang->line('btn_edit') . '</a>';
                }

            }
            if (in_array('delete', $args)) {
                if (isset($per['delete']) || $_this->session->userdata['user_id'] == 1) {

                    $action .= '&nbsp;<a class="btn btn-sm btn-danger" id="delete_form" href="javascript:void(0)" title="' . $_this->lang->line('btn_remove') . '" data-id="' . $id . '">' . $_this->lang->line('btn_remove') . '</a>';
                }

            }
            if (in_array('resend', $args)) {
                $action .= '&nbsp;<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="' . $_this->lang->line('btn_resend') . '" data-id="' . $id . '">' . $_this->lang->line('btn_resend') . '</a>';
            }
            if (in_array('send', $args)) {
                $action .= '&nbsp;<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="' . $_this->lang->line('btn_send') . '" data-id="' . $id . '">' . $_this->lang->line('btn_send') . '</a>';
            }
            $action .= '<div>';
            return $action;

        }
    }
    if (!function_exists('button_action_user_admin')) {

        function button_action_user_admin($id = '', $args = array('delete'))
        {
            $_this =& get_instance();

            $per = getPerButton();
            $action = '';
            $action .= '<div class="text-center nowrap" style="">';
            if (in_array('delete', $args)) {
                if (isset($per['delete']) || $_this->session->userdata['user_id'] == 1) {

                    if ($id == 1) {
                        $action .= '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="' . $_this->lang->line('btn_remove') . '" onclick="">' . $_this->lang->line('btn_remove') . '</a>';

                    } else {
                        $action .= '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="' . $_this->lang->line('btn_remove') . '" onclick="delete_item(' . "'" . $id . "'" . ')">' . $_this->lang->line('btn_remove') . '</a>';
                    }
                }

            }
            $action .= '<div>';
            return $action;
        }

    }
    if (!function_exists('showButtonAdd')) {
        function showButtonAdd()
        {
            echo '&nbsp;<button class="btn btn-success" id="add_form" type="button">
                <i class="glyphicon glyphicon-plus"></i> Thêm
                </button>';
        }
    }
    if (!function_exists('showButtonCopy')) {
        function showButtonCopy()
        {
            echo '<button class="btn btn-info" type="button" onclick="copy_multiple()">
                   <i class="fa fa-fw fa-copy"></i>  Copy
                 </button>';
        }
    }
    if (!function_exists('showButtonDelete')) {
        function showButtonDelete()
        {
            echo ' <button  class="btn btn-danger" id="delete_multi" type="button">
                   <i class="glyphicon glyphicon-trash"></i>&nbsp;Xoá tất cả
                   </button>';
        }
    }
    if (!function_exists('showButtonExport')) {
        function showButtonExport()
        {
            echo '<button class="btn btn-success" id="export_table" title="' . lang("tooltip_export_excel") . '">
                <i class="glyphicon glyphicon-floppy-save"></i> ' .
                lang("btn_export_excel") . '
            </button>';
        }
    }
    if (!function_exists('showButtonImport')) {
        function showButtonImport()
        {
            echo '&nbsp;<button class="btn btn-primary" title="' . lang("tooltip_import_excel") . '">
                <i class="fa fa-file-import"></i>&nbsp;' .
                lang("btn_import_excel") . '
            </button>';
        }
    }

    if (!function_exists('button_action_status')) {

        function button_action_status()
        {
            $_this =& get_instance();
            $controller = $_this->uri->segment(2);
            $per = getPerButton();
            if (!empty($per['edit']) || (!empty($per['delete'])) || $_this->session->userdata['user_id'] == 1) {
                echo '<th>Hành động</th>';
            } else {
                echo '';
            }
        }

    }
    if (!function_exists('show_select_status')) {

        function show_select_status($id = '')
        {
            $_this =& get_instance();
            $per = getPerButton();
            if (!empty($per['delete']) || !empty($per['edit']) || $_this->session->userdata['user_id'] == 1) {
                ?>
                <div class="form-group">
                    <?php showSelectStatus() ?>
                </div>
                <?php
            } else {
                echo '<input type="hidden" value="0" name="is_status" />';
            }
        }

    }
    if (!function_exists('showColumnAction')) {

        function showColumnAction()
        {
            $_this =& get_instance();
            $per = getPerButton();
            if (!empty($per['delete']) || !empty($per['edit']) || $_this->session->userdata['user_id'] == 1) {
                ?>
                <th class="text-center"><?php echo lang('text_action'); ?></th>
                <?php
            } else {
                echo '<th></th>';
            }
        }

    }
