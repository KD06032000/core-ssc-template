<?php

if (!function_exists('methodPayment')) {
    function methodPayment($type)
    {
        switch ($type) {
            case 0:
                $title = 'Quẹt thẻ';
                break;
            case 1:
                $title = 'Tiền mặt';
                break;
            case 2:
                $title = 'Chuyển khoản';
                break;
            default:
                $title = 'Thanh toán online';
                break;
        }
        return $title;
    }
}

if (!function_exists('statusBooking')) {
    function statusBooking($type)
    {
        switch ($type) {
            case 0:
                $title = 'Đã đặt lịch';
                break;
            case 1:
                $title = 'Khách đã đến';
                break;
            case 2:
                $title = 'Đã hoàn thành';
                break;
            default:
                $title = 'Hủy';
                break;
        }
        return $title;
    }
}

if (!function_exists('showStar')) {
    function showStar($star)
    {
        $html = '<div class="style-star">';
        $i = 1;
        while ($i <= 5) {
            $class = $i <= $star ? 'orange' : '';
            $html .= '<span class="'.$class.' fa fa-star"></span>';
            $i++;
        }
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('select_update_field')) {
    function select_update_field($item, $dataselect)
    {
        $_this = &get_instance();
        $html = '<select data-id="' . $item['id'] . '" name="' . $item['name'] . '" class="update_single_field">';
        foreach ($dataselect as $key => $value) {
            $selected = ($key == $item['value']) ? 'selected' : '';
            $html .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
        $html .= ' </select>';
        return $html;
    }
}
if (!function_exists('showImagePreview')) {
    function showImagePreview($image, $width = 200, $height = 50)
    {
        return '<a class="fancybox text-center" style="display:block" href="' . getImageThumb($image) . '"><img src="' . getImageThumb($image, $width, $height) . '" style="background-color:#0c7ede"></a>';
    }
}

if (!function_exists('showFeatured')) {
    function showFeatured($status)
    {
        $_this = &get_instance();
        $per = getPerButton();
        if (isset($per['edit']) || $_this->session->userdata['user_id'] == 1)
            $cls = 'btnUpdateFeatured';
        else $cls = '';
        return ($status == true) ? '<div class="text-center"><i data-value="1" class="text-primary fa fa-lg fa-star ' . $cls . '"></i></div>' : '<div class="text-center"><i data-value="0" class="text-primary fa fa-lg fa-star-o ' . $cls . '"></i></div>';
    }
}
if (!function_exists('showStatus')) {
    function showStatus($status)
    {
        $_this = &get_instance();
        $per = getPerButton();
        if (isset($per['edit']) || $_this->session->userdata['user_id'] == 1)
            $cls = 'btnUpdateStatus';
        else $cls = '';

        switch ($status) {
            case 1:
                $row = '<span class="label label-success ' . $cls . '" data-value="1">' . $_this->lang->line('text_status_1') . '</span>';
                break;
            case 2:
                $row = '<span class="label label-default ' . $cls . '" data-value="2">' . $_this->lang->line('text_status_2') . '</span>';
                break;
            case 3:
                $row = '<span class="label label-info" data-value="3">' . $_this->lang->line('text_status_3') . '</span>';
                break;
            default:
                $row = '<span class="label label-danger ' . $cls . '" data-value="0">' . $_this->lang->line('text_status_0') . '</span>';
                break;
        }
        return '<div class="text-center">' . $row . '</div>';
    }
}
if (!function_exists('showStatusNoUpdate')) {
    function showStatusNoUpdate($status)
    {
        $_this = &get_instance();
        switch ($status) {
            case 1:
                $row = '<span class="label label-success " data-value="1">' . $_this->lang->line('text_status_1') . '</span>';
                break;
            case 2:
                $row = '<span class="label label-default" data-value="2">' . $_this->lang->line('text_status_2') . '</span>';
                break;
            case 3:
                $row = '<span class="label label-info" data-value="3">' . $_this->lang->line('text_status_3') . '</span>';
                break;
            default:
                $row = '<span class="label label-danger" data-value="0">' . $_this->lang->line('text_status_0') . '</span>';
                break;
        }
        return '<div class="text-center">' . $row . '</div>';
    }
}
if (!function_exists('showStatusnotdarf')) {
    function showStatusnotdarf($status)
    {
        $_this = &get_instance();
        $per = getPerButton();
        if (isset($per['edit']) || $_this->session->userdata['user_id'] == 1)
            $cls = 'btnUpdateStatusnotdarf';
        else $cls = '';

        switch ($status) {
            case 1:
                $row = '<span class="label label-success ' . $cls . '" data-value="1">' . $_this->lang->line('text_status_1') . '</span>';
                break;
            case 2:
                $row = '<span class="label label-default ' . $cls . '" data-value="2">' . $_this->lang->line('text_status_2') . '</span>';
                break;
            case 3:
                $row = '<span class="label label-info ' . $cls . '" data-value="3">' . $_this->lang->line('text_status_3') . '</span>';
                break;
            default:
                $row = '<span class="label label-danger ' . $cls . '" data-value="0">' . $_this->lang->line('text_status_0') . '</span>';
                break;
        }
        return '<div class="text-center">' . $row . '</div>';
    }
}
if (!function_exists('showStatusUser')) {
    function showStatusUser($status)
    {
        switch ($status) {
            case 1:
                $row = '<span class="label label-success">Đang hoạt động</span>';
                break;
            default:
                $row = '<span class="label label-danger">Ngừng kích hoạt</span>';
                break;
        }
        return $row;
    }
}


if (!function_exists('showSelectStatus')) {
    function showSelectStatus($selected = 1, $status = [0, 1])
    {
        $html = '<label>Trạng thái</label><select class="form-control" name="is_status">';
        foreach ($status as $item) {
            $html .= '<option value="' . $item . '" ' . ($selected == $item ? 'selected' : '') . '>' . lang('text_status_' . $item) . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }
}

if (!function_exists('showOrder')) {
    function showOrder($id, $order)
    {
        $_this = &get_instance();
        $per = getPerButton();
        if (isset($per['edit']) || $_this->session->userdata['user_id'] == 1) {
            return '<input type="number" class="change_order" data-id="' . $id . '" value="' . $order . '" />';
        } else {
            return $order;
        }
    }
}
if (!function_exists('Status_Order')) {
    function Status_Order($value)
    {
        $_this = &get_instance();

        switch ($value->is_status) {
            case '0':
                //Cancel
                echo $_this->lang->line('status_or_0');
                break;
            case '1':
                //processing
                echo $_this->lang->line('status_or_1');
                break;
            case '2':
                //shipping
                echo $_this->lang->line('status_or_2');
                break;
            case '3':
                //Delivered
                echo $_this->lang->line('status_or_3');
                break;
            default:
                echo $_this->lang->line('status_or_1');
                break;
        }
    }
}

if (!function_exists('Status_Payment')) {
    function Status_Payment($value)
    {
        $_this = &get_instance();
        switch ($value) {
            case '0':
                echo $_this->lang->line('status_pay_0');
                break;
            case '1':
                echo $_this->lang->line('status_pay_1');
                break;
            default:
                echo $_this->lang->line('status_pay_0');
                break;
        }
    }
}
if (!function_exists('statusVoucher')) {
    function statusVoucher($status, $end_time)
    {
        $label = '';
        $list = [
            1 => '<span class="label label-success">Chưa sử dụng</span>',
            2 => '<span class="label label-danger">Đã hủy</span>',
            3 => '<span class="label label-warning">Hết hạn</span>',
            4 => '<span class="label label-primary">Đã sử dụng</span>',
        ];
        $label = $list[$status];
        if ($status == 1 && strtotime($end_time) < time()) $label = $list[3];
        return $label;
    }
}
if (!function_exists('statusPayment')) {
    function statusPayment($status = '')
    {
        $list = [
            1 => lang('status_success'),
            2 => lang('status_unsuccess'),
            3 => 'Chờ respon từ thanh toán online',
            '' => lang('status_payment'),
        ];
        return $list[$status];
    }
}
if (!function_exists('statusOrder')) {
    function statusOrder($status)
    {
        switch ($status) {
            case 1:
                $label = lang('status_dangxuly');
                break;
            case 2:
                $label = lang('status_dangvanchuyen');
                break;
            case 3:
                $label = lang('status_dagiaohang');
                break;
            default:
                $label = lang('status_cancel_order');
                break;
        }
        return $label;
    }
}
if (!function_exists('showRequiredField')) {
    function showRequiredField($lang = '')
    {
        $_this = &get_instance();
        $lagDf = $_this->config->item('default_language');
        if (!empty($lang)) {
            if ($lang == $lagDf) {
                echo '<span class="text-red"> *</span>';
            }
        } else {
            echo '<span class="text-red"> *</span>';
        }
    }
}
