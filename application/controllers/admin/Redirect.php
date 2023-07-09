<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Redirect extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;
    protected $_setting;

    public function __construct()
    {
        parent::__construct();
        //tải file ngôn ngữ
        $this->load->model('redirect_model');
        $this->lang->load('redirect');
        $this->_data = new Redirect_model();
        $this->_name_controller = $this->router->fetch_class();

        $dataContent = file_get_contents(FCPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.cfg');
        $this->_setting = $dataContent ? json_decode($dataContent, true) : array();
    }

    public function index()
    {
        $data['heading_title'] = 'Chuyển hướng liên kết';
        $data['heading_description'] = "Danh sách chuyển hướng";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $post = $this->input->post();

        $length = $post['length'];
        $no = $post['start'];
        $page = $no / $length + 1;

        if (!empty($post['status'])) {
            $params['is_status'] = intval($post['status']) - 1;
        }

        $params['category_id'] = !empty($post['tag_id']) ? $post['tag_id'] : null;
        $params['page'] = $page;
        $params['limit'] = $length;

        $list = $this->_data->getData($params);
        $data = array();
        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = $item->id;
            $row[] = '<a href="'.$item->link.'" target="_blank"> '. $item->link .' </a>';
            $row[] = '<a href="'.$item->redirect_link.'" target="_blank"> '. $item->redirect_link .' </a>';

            $category = $this->_data->getCategorySelect2($item->id);

            $title_category = '';

            if (!empty($category)) foreach ($category as $kc => $c) {
                if ($kc) $title_category .= ', ';
                $title_category .= $c->text;
            }

            $row[] = $title_category;
            $row[] = $item->description;

            $row[] = !empty($item->thumbnail)
                ? showImagePreview($item->thumbnail, 100, 100)
                : '';

            //thêm action
            $action = button_action($item->id);
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->_data->getTotalAll(),
            "recordsFiltered" => $this->_data->getTotal($params),
            "data" => $data,
        );

        $this->returnJson($output);
    }

    /*
      * Ajax xử lý thêm mới
      * */
    public function ajax_add()
    {
        $data_store = $this->_convertData();

        if ($id = $this->_data->save($data_store)) {

//            $this->_data->update(array('id' => $id), [
//                'link' => $this->generateRandomString(5) . '-r' . $id
//            ]);

            // log action
            $action = 'video';
            $note = 'Insert ' . $this->db->insert_id();
            $this->addLogaction($action, $note);

            $message['type'] = 'success';
            $message['message'] = 'Thêm mới thành công !';
        } else {
            $message['type'] = 'error';
            $message['message'] = 'Thêm mới thất bại';
        }
        die(json_encode($message));
    }

    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data = (array)$this->_data->getById($id);
        $data['category_id'] = $this->_data->getCategorySelect2($id);
        die(json_encode($data));
    }

    /*
     * Xóa một bản ghi
     * */
    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id' => $id]);
        if ($response != true) {

            $action = $this->router->fetch_class();
            $note = "Delete $action: $id";
            $this->addLogaction($action, $note);

            $message['type'] = 'error';
            $message['message'] = "Xóa bản ghi thất bại !";
        } else {
            $message['type'] = 'success';
            $message['message'] = "Xóa bản ghi thành công !";
            $message['error'] = $response;
            log_message('error', $response);
        }
        die(json_encode($message));
    }

    /*
     * Cập nhật thông tin
     * */
    public function ajax_update()
    {
        $data_store = $this->_convertData();
        $response = $this->_data->update(array('id' => $this->input->post('id')), $data_store);
        if ($response == false) {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
            $message['error'] = $response;
            log_message('error', $response);
        } else {
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: " . $data_store['id'];
            $this->addLogaction($action, $note);

            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        }
        die(json_encode($message));
    }

    private function _validate()
    {
        $this->checkRequestPostAjax();
        $rules = [
            [
                'field' => 'redirect_link',
                'label' => 'Link chuyển hướng',
                'rules' => 'required|trim|xss_clean'
            ],
            [
                'field' => 'link_prefix',
                'label' => 'Link rút gọn',
                'rules' => 'required|trim|xss_clean'
            ]
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->return_notify_validate($rules);
        }
    }

    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        $data['link'] = trim( BASE_URL . $data['link_prefix'], ' /');
        return $data;
    }

    public function export_excel()
    {
        $post = $this->input->post();

        $params = [
            'limit' => null,
        ];

        $params['category_id'] = !empty($post['tag_id']) ? $post['tag_id'] : null;

        $data = $this->_data->getData($params);

        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();

        $tmp_path = FCPATH . 'public/Redirect.xls';

        $objFile = PHPExcel_IOFactory::identify($tmp_path);
        $objReader = PHPExcel_IOFactory::createReader($objFile);
        $objPHPExcel = $objReader->load($tmp_path);
        if (!empty($data)) {
            $objWorkSheet = $objPHPExcel->setActiveSheetIndex(0);
            $rowNumberH = 3;
            if (!empty($data)) foreach ($data as $key_item => $item) {
                $objWorkSheet->setCellValue('A' . $rowNumberH, $key_item + 1);
                $objWorkSheet->setCellValue('B' . $rowNumberH, $item->link);
                $objWorkSheet->setCellValue('C' . $rowNumberH, $item->redirect_link);
//                $objWorkSheet->getCell('C' . $rowNumberH)->getHyperlink()->setUrl($item->redirect_link)->setTooltip($item->redirect_link);

                $title_category = '';

                if (!empty($category)) foreach ($category as $kc => $c) {
                    if ($kc) $title_category .= ', ';
                    $title_category .= $c->text;
                }

                $objWorkSheet->setCellValue('D' . $rowNumberH, $title_category);
                $objWorkSheet->setCellValue('E' . $rowNumberH, $item->description);

                $rowNumberH++;
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        header('Content-Type: application/force-download');
        header('Content-disposition: attachment; filename=ds_link_chuyen_huong_' . date('Ymd_H\hi') . '.xls');
        // Fix for crappy IE bug in download.
        header("Pragma: ");
        header("Cache-Control: ");

        ob_start();
        ob_end_clean();
        $objWriter->save('php://output');
    }
}
