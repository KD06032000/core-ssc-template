<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('contact_model');
        $this->_data = new Contact_model();
    }

    public function get_list($data, $layout = 'index')
    {
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . 'contact/' . $layout, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function contact()
    {
        $this->session->contact_type = $data['contact_type'] = $this->router->fetch_method();
        $data['heading_title'] = 'Liên hệ';
        $data['heading_description'] = "Danh sách liên hệ";
        $this->get_list($data, 'contact');
    }

    public function party()
    {
        $this->session->contact_type = $data['contact_type'] = $this->router->fetch_method();
        $data['heading_title'] = 'Đăng ký tiệc';
        $data['heading_description'] = "Danh sách liên hệ đăng ký tiệc";
        $this->get_list($data, 'party');
    }

    public function conference()
    {
        $this->session->contact_type = $data['contact_type'] = $this->router->fetch_method();
        $data['heading_title'] = 'Đăng ký hội nghị - hội thảo';
        $data['heading_description'] = "Danh sách liên hệ đăng ký hội nghị - hội thảo";
        $this->get_list($data, 'conference');
    }

    public function export_excel($type)
    {
        $params = [
            'limit' => null,
            'where' => ['type' => $type]
        ];

        $data = $this->_data->getData($params);

        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $tmp_path = in_array($this->session->contact_type, ['conference', 'party']) ? FCPATH . 'public/Order.xls' : FCPATH . 'public/Contact.xls';
        $objFile = PHPExcel_IOFactory::identify($tmp_path);
        $objReader = PHPExcel_IOFactory::createReader($objFile);
        $objPHPExcel = $objReader->load($tmp_path);
        if (!empty($data)) {
            $objWorkSheet = $objPHPExcel->setActiveSheetIndex(0);
            $rowNumberH = 3;
            if (!empty($data)) foreach ($data as $key_item => $item) {
                $objWorkSheet->setCellValue('A' . $rowNumberH, $key_item + 1);
                $objWorkSheet->setCellValue('B' . $rowNumberH, $item->id);
                $objWorkSheet->setCellValue('C' . $rowNumberH, $item->fullname);
                $objWorkSheet->setCellValue('D' . $rowNumberH, $item->phone);
                $objWorkSheet->setCellValue('E' . $rowNumberH, $item->email);

                if (in_array($this->session->contact_type, ['conference', 'party'])) {
                    $objWorkSheet->setCellValue('F' . $rowNumberH, $item->humans);
                    $objWorkSheet->setCellValue('G' . $rowNumberH, formatDate($item->date_start, 'd/m/Y'));
                    $objWorkSheet->setCellValue('H' . $rowNumberH, formatDate($item->time_start, 'H:m:i') . ' - ' . formatDate($item->time_end, 'H:m:i'));
                    $objWorkSheet->setCellValue('I' . $rowNumberH, $item->content);
                    $objWorkSheet->setCellValue('J' . $rowNumberH, $item->created_time);
                } else {
                    $objWorkSheet->setCellValue('F' . $rowNumberH, $item->content);
                    $objWorkSheet->setCellValue('G' . $rowNumberH, $item->created_time);
                }

                $rowNumberH++;
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        header('Content-Type: application/force-download');
        header('Content-disposition: attachment; filename=ds_' . $type . '_' . date('Ymd_H\hi') . '.xls');
        // Fix for crappy IE bug in download.
        header("Pragma: ");
        header("Cache-Control: ");

        ob_start();
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        $params['page'] = $page;
        $params['limit'] = $length;
        $params['where'] = [
            'type' => $this->session->contact_type
        ];

        if (!empty($post['page_id'])) {
            $params['where'] = [
                'page_id' => $post['page_id']
            ];
        }

        $list = $this->_data->getData($params);
        $data = array();
        foreach ($list as $item) {
            $row = array();
            $row[] = $item->id;
            $row[] = showCenter($item->id);
            $row[] = $item->fullname;
            $row[] = showCenter($item->phone);
            $row[] = $item->email;

            if (in_array($this->session->contact_type, ['conference', 'party'])) {
                $row[] = showCenter($item->humans);
                $row[] = showCenter(formatDate($item->date_start, 'd/m/Y'));
                $row[] = showCenter(formatDate($item->time_start, 'H:m:i') . ' - ' . formatDate($item->time_end, 'H:m:i'));
            }

            $row[] = $item->content;

            $row[] = showCenter(formatDateTime($item->created_time));
            $action = button_action($item->id, ['delete']);
            $row[] = $action;
            $data[] = $row;
        }

        $total = $this->_data->getTotal($params);
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data,
        );
        $this->returnJson($output);
    }

    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id' => $id]);
        if ($response != false) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: $id";
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_delete_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_delete_unsuccess');
            $message['error'] = $response;
            log_message('error', $response);
        }
        die(json_encode($message));
    }
}
