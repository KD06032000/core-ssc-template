<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Report extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('report');
        $this->load->model('calendar_model');
        $this->_data = new Calendar_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        $data['heading_title'] = 'Báo cáo đơn hàng';
        $data['heading_description'] = 'Quản lý báo cáo đơn hàng';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'where' => [
                'is_status =' => 2
            ]
        ];
        if ($store_id = getPermissionStore()) {
            $params['where']['store_id'] = $store_id;
        }
        $data['total_price'] = $this->_data->totalRevenue($params);

        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        $params = [
            'limit' => $length,
            'page' => $page,
            'where' => $this->_where_convert()
        ];

        $list = $this->_data->getData($params);
        $data = array();
        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->name;
            $row[] = $item->email;
            $row[] = $item->phone;
            $row[] = $item->code;
            $row[] = showCenter(getNameStore($item->store_id));
            $row[] = showCenter(getNameEmployee($item->employee_id));
            $row[] = showCenter(number_format($item->price));
            $row[] = showCenter(statusBooking($item->is_status));
            $row[] = showCenter(methodPayment($item->payments));
            $row[] = showCenter(formatDate($item->order_date));
            $row[] = $item->note;
            $data[] = $row;
        }
        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data,
        );
        //trả về json
        $this->returnJson($output);
    }

    public function total_revenue(){
        $where = $this->_where_convert();
        $where['is_status'] = 2;
        if ($store_id = getPermissionStore()) {
            $where['store_id'] = $store_id;
        }
        $total_revenue = $this->_data->totalRevenue(['where' => $where]);
        $this->returnJson(['total' => number_format($total_revenue)]);
    }

    public function _where_convert()
    {
        $post = $this->input->post();
        $where = [];

        if (!empty($post['date'])) {
            $start_date = convertDate(explode(" - ", $post['date'])[0]);
            $end_date = convertDate(explode(" - ", $post['date'])[1]);
            $where['order_date >='] = $start_date;
            $where['order_date <='] = $end_date;
        }
        if (!empty($post['store_id'])) {
            $where['store_id'] = $post['store_id'];
        }else if ($store_id = getPermissionStore()){
            $where['store_id'] = $store_id;
        }
        if (!empty($post['employee_id'])) {
            $where['employee_id'] = $post['employee_id'];
        }
        if (!empty($post['is_status'])) {
            $where['is_status'] = intval($post['is_status']) - 1;
        }
        if (!empty($post['payments'])) {
            $where['payments'] = intval($post['payments']) - 1;
        }
        return $where;
    }

    public function export_excel()
    {
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $tmp_path       = FCPATH . 'public/Report_Booking.xls';
        $objPHPExcel    = $objReader->load($tmp_path);

        $get = $this->input->get();
        $where = [];

        if (!empty($get['date'])) {
            $start_date = convertDate(explode(" - ", $get['date'])[0]);
            $end_date = convertDate(explode(" - ", $get['date'])[1]);
            $where['order_date >='] = $start_date;
            $where['order_date <='] = $end_date;
        }
        if (!empty($get['store_id'])) {
            $where['store_id'] = $get['store_id'];
        }else if ($store_id = getPermissionStore()){
            $where['store_id'] = $store_id;
        }
        if (!empty($get['employee_id'])) {
            $where['employee_id'] = $get['employee_id'];
        }
        if (!empty($get['is_status'])) {
            $where['is_status'] = intval($get['is_status']) - 1;
        }
        if (!empty($get['payments'])) {
            $where['payments'] = intval($get['payments']) - 1;
        }
        $params['where'] = $where;
        $list = $this->_data->getData($params);
        $row = 5;
        $objWorkSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($list as $key => $item) {
            $objWorkSheet->SetCellValue('A' . $row, $item->id);
            $objWorkSheet->SetCellValue('B' . $row, $item->name);
            $objWorkSheet->SetCellValue('C' . $row, $item->email);
            $objWorkSheet->SetCellValue('D' . $row, $item->phone);
            $objWorkSheet->SetCellValue('E' . $row, $item->code);
            $objWorkSheet->SetCellValue('F' . $row, getNameStore($item->store_id));
            $objWorkSheet->SetCellValue('G' . $row, getNameEmployee($item->employee_id));
            $objWorkSheet->SetCellValue('H' . $row, $item->price);
            $objWorkSheet->SetCellValue('I' . $row, statusBooking($item->is_status));
            $objWorkSheet->SetCellValue('J' . $row, methodPayment($item->payments));
            $objWorkSheet->SetCellValue('K' . $row, formatDateTime($item->order_date));
            $objWorkSheet->SetCellValue('L' . $row, $item->note);

            $row++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment;filename="Bao_cao_doanh_thu-'.date('d-m-Y').'.xls"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        ob_start();
        $objWriter->save('php://output');
    }
}