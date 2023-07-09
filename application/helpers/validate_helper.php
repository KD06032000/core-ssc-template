<?php

if (!function_exists('employeeForStoreAndService')) {
    function employeeForStoreAndService($service_id, $store_id, $employee_id)
    {
        $_this =& get_instance();
        $_this->load->model('employee_model');
        $model = new Employee_model();

        $params = [
            'service_id' => $service_id,
            'where' => [
                'store_id' => $store_id,
                'id' => $employee_id,
            ]
        ];
        $result = $model->getData($params);
        if (empty($result)) {
            $_this->form_validation->set_message('check_employee', '{field} không nằm trong tiệm hoặc dịch vụ đã chọn.');
            return false;
        }

        return true;
    }
}

if (!function_exists('dateOld')) {
    function dateOld($data)
    {
        if (!empty($data['id'])) {
            return true;
        }

        if (strtotime(date('Y-m-d')) > strtotime(convertDate($data['order_date']))) {
            $_this->form_validation->set_message('check_date_old', 'Không được chọn ngày cũ.');
            return false;
        }
        return true;
    }
}

if (!function_exists('employeeTimeOff')) {
    function employeeTimeOff($employee_id, $service_id, $time_start, $order_date)
    {
        $_this =& get_instance();
        $_this->load->model('employee_model');
        $model = new Employee_model();

        $time = $model->getTimeService($employee_id, $service_id);
        $num_day = intval(date('N', strtotime($order_date))) + 1;

        $employee = $model->getById($employee_id);
        $timesheet = !empty($employee->timesheet)
            ? json_decode($employee->timesheet, true)[$num_day]
            : getConfigTimesheetDefault()[$num_day];

        if (!empty($timesheet['start']) && !empty($timesheet['end'])) {
            $timesheet_start = strtotime($timesheet['start'] . ":00");
            $timesheet_end = strtotime($timesheet['end'] . ":00");
        } else {
            $_this->form_validation->set_message('check_time_off', 'nhân viên hôm nay không có giờ làm.');
            return false;
        }

        if ($time_start) {
            $time_start = strtotime($time_start);
            $time_end = strtotime("+" . $time . " minutes", $time_start);

            if ($time_start >= $timesheet_end) {
                $_this->form_validation->set_message('check_time_off', '{field} trùng giờ nghỉ của thợ.');
                return false;
            }

            if ($time_end <= $timesheet_start) {
                $_this->form_validation->set_message('check_time_off', '{field} trùng giờ nghỉ của thợ.');
                return false;
            }

            if ($time_end > $timesheet_start && $time_start < $timesheet_start) {
                $_this->form_validation->set_message('check_time_off', '{field} trùng giờ nghỉ của thợ.');
                return false;
            }

            if ($time_start < $timesheet_end && $time_end > $timesheet_end) {
                $_this->form_validation->set_message('check_time_off', '{field} trùng giờ nghỉ của thợ.');
                return false;
            }
        }
    }
}

if (!function_exists('employeeTimeWork')) {
    function employeeTimeWork($id, $employee_id, $service_id, $time_start, $order_date, $item)
    {
        $_this =& get_instance();
        $_this->load->model(['employee_model', 'calendar_model']);
        $model_employee = new Employee_model();
        $model_calendar = new Calendar_model();

        // Check update time
        if (!empty($id)) {
            $booking = $_this->_data->getById($id);
            if ($booking->employee_id == $employee_id && $booking->time_start == $item) {
                return true;
            }
        }

        $time = $model_employee->getTimeService($employee_id, $service_id);

        $list = $model_calendar->getBooking($employee_id, $order_date);

        if ($time_start && !empty($list)) {
            $time_start = strtotime($time_start);
            $time_end = strtotime("+" . $time . " minutes", $time_start);

            foreach ($list as $key => $value) {
                $compare_time_start = strtotime($value->time_start);
                $compare_time_end = strtotime("+" . $value->time . " minutes", $compare_time_start);

                if ($time_start == $compare_time_start) {
                    $_this->form_validation->set_message('check_time_work', '{field} đã được đặt.');
                    return false;
                }

                if ($time_end > $compare_time_start && $time_start < $compare_time_start) {
                    $_this->form_validation->set_message('check_time_work', '{field} đã được đặt.');
                    return false;
                }

                if ($time_start < $compare_time_end && $time_end > $compare_time_end) {
                    $_this->form_validation->set_message('check_time_work', '{field} đã được đặt.');
                    return false;
                }
            }
        }
        return true;
    }
}
