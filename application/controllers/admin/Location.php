<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Location extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;
    protected $_location;
    protected $_region;
    protected $_quee_localtion;
    const STATUS_CANCEL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        //$this->load->library(array('ion_auth'));
//    $this->lang->load('location');
        $this->load->model('location_model');
        $this->load->helper('download');
        $this->_data = new Location_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->_location = $this->session->location_type;
        $this->_region = $this->config->item('region');
    }

    public function index($data, $type = 'city')
    {
        /*Breadcrumbs*/
        $this->session->location_type = $type;
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], current_url());
        $this->breadcrumbs->push($data['heading_description'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/' . $type, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }


    public function city()
    {
        $data['heading_title'] = "Tỉnh / Thành phố";
        $data['heading_description'] = "Danh sách Tỉnh / Thành phố";
        $this->index($data, 'city');
    }

    public function district()
    {
        $data['heading_title'] = "Quận / Huyện";
        $data['heading_description'] = "Danh sách Quận / Huyện";
        $this->index($data, 'district');
    }

    public function ward()
    {
        $data['heading_title'] = "Phường / Xã";
        $data['heading_description'] = "Danh sách phường xã";
        $this->index($data, 'ward');
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        $type = $this->_location;
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no / $length + 1;
            $params['page'] = $page;
            $params['limit'] = $length;
            if (!empty($this->input->post('filter_city_id'))) $params['city_id'] = $this->input->post('filter_city_id');
            if (!empty($this->input->post('filter_district_id'))) $params['district_id'] = $this->input->post('filter_district_id');
            if ($type === 'city') {
                $list = $this->_data->getDataCity($params);
            } elseif ($type === 'district') $list = $this->_data->getDataDistrict($params);

            else $list = $this->_data->getDataWard($params);
            $data = array();
            if ($type === 'ward') {
                if (!empty($list)) foreach ($list as $item) {
                    $no++;
                    $city = $this->_data->getCityById($item->city_id);
                    $district = $this->_data->getDistrictById($item->district_id);
                    $row = array();
                    $row[] = $item->id;
                    $row[] = $item->id;
                    $row[] = $item->title;
                    $row[] = $district->title;
                    $row[] = $city->title;
                    $row[] = $item->type;
                    $row[] = $item->name_with_type;
                    $row[] = $item->latitude;
                    $row[] = $item->longitude;
                    //thêm action
                    $action = button_action($item->id);
                    $row[] = $action;
                    $data[] = $row;
                }
            } else {
                if (!empty($list)) foreach ($list as $item) {
                    $no++;
                    if ($type == 'district') $city = $this->_data->getCityById($item->city_id);
                    $row = array();
                    $row[] = $item->id;
                    $row[] = $item->id;
                    $row[] = $item->title;
                    if ($type == 'district') $row[] = $city->title;
                    $row[] = $item->type;
                    $row[] = $item->name_with_type;
                    $row[] = $item->latitude;
                    $row[] = $item->longitude;
                    //thêm action
                    $action = button_action($item->id);
                    $row[] = $action;
                    $data[] = $row;
                }
            }

            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAll(),
                "recordsFiltered" => $this->_data->getTotalLocation($params),
                "data" => $data,
            );
            //trả về json
            echo json_encode($output);
        }
        exit;
    }

    public function ajax_load_city()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $params = [
                'search' => $term,
                'limit' => 100,
                'order' => ['order' => 'DESC']
            ];
            $list = $this->_data->getDataCity($params);
            $json = [];
            if (!empty($list)) foreach ($list as $item) {
                $item = (object)$item;
                $json[] = ['id' => $item->id, 'text' => $item->title];
            }
            print json_encode($json);
        }
    }

    public function ajax_load_district($city_id)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->post("q");
            $params = [
                'city_id' => $city_id,
                'search' => $term,
                'limit' => 100,
                'order' => ['order' => 'DESC']
            ];
            $list = $this->_data->getDataDistrict($params);
            $json = [];
            if (!empty($list)) foreach ($list as $item) {
                $item = (object)$item;
                $json[] = ['id' => $item->id, 'text' => $item->title];
            }
            print json_encode($json);
        }
    }

    public function ajax_update_field()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $id = $this->input->post('id');
            $field = $this->input->post('field');
            $value = $this->input->post('value');
            $response = $this->_data->update(['id' => $id], [$field => $value], 'ap_location_' . $this->_location);
            if ($response != false) {
                /*Call API update trạng thái bài viết crawler*/
                $message['type'] = 'success';
                $message['message'] = $this->lang->line('mess_update_success');
            } else {
                $message['type'] = 'error';
                $message['message'] = $this->lang->line('mess_update_unsuccess');
            }
            print json_encode($message);
        }
        exit;
    }

    public function ajax_import_excel_city()
    {
        //Freeze pane
        $message = array();
        $this->load->library('PHPExcel');
        $fileName = time() . $_FILES['file']['name'];
        // Sesuai dengan nama Tag Input/Upload
        $config['upload_path'] = 'public/media/';

        // Buat folder dengan nama "fileExcel" di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        // $config['max_size'] = 10000;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file'))
            $this->upload->display_errors();
        $media = $this->upload->data();
        //dd($media);
        $filename = FCPATH . 'public/media/' . $media['file_name'];
        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);

        $objPHPExcel = $objReader->load("$filename");

        $total_sheets = $objPHPExcel->getSheetCount();

        $allSheetName = $objPHPExcel->getSheetNames();
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $arraydata = array();
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $arraydata[$row - 1][$col] = $value;
            }
        }
        $total = count($arraydata);
        for ($i = 1; $i < $total; $i++) {
            $data['title'] = $arraydata[$i][1];
            $data['code'] = $arraydata[$i][2];
            $data['type'] = $arraydata[$i][3];
            $data['name_with_type'] = $data['type'] . ' ' . $data['title'];
            $result = $this->_data->saveCity($data);
        }
        $message['type'] = 'success';
        $message['message'] = 'Import thành công';
        die(json_encode($message));
        exit();
    }

    public function ajax_import_excel_district()
    {
        //Freeze pane
        $message = array();
        $this->load->library('PHPExcel');
        $fileName = time() . $_FILES['file']['name'];
        // Sesuai dengan nama Tag Input/Upload
        $config['upload_path'] = 'public/media/';

        // Buat folder dengan nama "fileExcel" di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        // $config['max_size'] = 10000;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file'))
            $this->upload->display_errors();
        $media = $this->upload->data();
        //dd($media);
        $filename = FCPATH . 'public/media/' . $media['file_name'];
        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);

        $objPHPExcel = $objReader->load("$filename");

        $total_sheets = $objPHPExcel->getSheetCount();

        $allSheetName = $objPHPExcel->getSheetNames();
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $arraydata = array();
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $arraydata[$row - 1][$col] = $value;
            }
        }
        $total = count($arraydata);
        for ($i = 0; $i < $total; $i++) {
            $data['id'] = $arraydata[$i][0];
            $data['title'] = $arraydata[$i][3];
            $data['city_id'] = $arraydata[$i][2];
            $data['code'] = $arraydata[$i][4];
            $data['type'] = $arraydata[$i][8];
            $data['slug'] = $arraydata[$i][6];
            $data['name_with_type'] = $arraydata[$i][11];
            $data['latitude'] = $arraydata[$i][9];
            $data['longitude'] = $arraydata[$i][10];
            $result = $this->_data->saveDistrict($data);
        }
        $message['type'] = 'success';
        $message['message'] = 'Import thành công';
        die(json_encode($message));
        exit();
    }

    public function ajax_import_excel_ward()
    {
        $message = array();
        $this->load->library('PHPExcel');
        $fileName = time() . $_FILES['file']['name'];

        // Sesuai dengan nama Tag Input/Upload
        $config['upload_path'] = 'public/media/';

        // Buat folder dengan nama "fileExcel" di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        // $config['max_size'] = 10000;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file'))
            $this->upload->display_errors();
        $media = $this->upload->data();
        $filename = FCPATH . 'public/media/' . $media['file_name'];

        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);

        $objPHPExcel = $objReader->load("$filename");

        $total_sheets = $objPHPExcel->getSheetCount();

        $allSheetName = $objPHPExcel->getSheetNames();
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $arraydata = array();
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $arraydata[$row - 1][$col] = $value;
            }
            $data['id'] = $arraydata[$row - 1][0];
            $data['title'] = $arraydata[$row - 1][3];
            $data['district'] = $arraydata[$row - 1][11];
            $data['district_id'] = $arraydata[$row - 1][2];
            $data['city'] = $arraydata[$row - 1][13];
            $data['city_id'] = $arraydata[$row - 1][12];
            $data['slug'] = $arraydata[$row - 1][8];
            $data['type'] = $arraydata[$row - 1][9];
            $data['name_with_type'] = $arraydata[$row - 1][16];
            $data['latitude'] = $arraydata[$row - 1][14];
            $data['longitude'] = $arraydata[$row - 1][15];
            $this->_data->saveWard($data);
        }
        $message['type'] = 'success';
        $message['message'] = 'Import thành công';
        die(json_encode($message));

    }

    public function ajax_import_excel_country()
    {
        $message = array();
        $this->load->library('PHPExcel');
        $fileName = time() . $_FILES['file']['name'];

        // Sesuai dengan nama Tag Input/Upload
        $config['upload_path'] = 'public/media/';

        // Buat folder dengan nama "fileExcel" di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        // $config['max_size'] = 10000;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file'))
            $this->upload->display_errors();
        $media = $this->upload->data();
        $filename = FCPATH . 'public/media/' . $media['file_name'];

        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load("$filename");
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $arraydata = array();
        for ($row = 2; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $arraydata[$row - 1][$col] = $value;
            }
            $data['title'] = $arraydata[$row - 1][1];
            $data['currency_name'] = $arraydata[$row - 1][2];
            $data['currency_code'] = $arraydata[$row - 1][3];
            $data['currency_symbol'] = $arraydata[$row - 1][4];
            $data['format'] = $arraydata[$row - 1][5];
            $data['status'] = $arraydata[$row - 1][6];
            if (toSlug(toNormal(trim($data['status']))) == toSlug(toNormal(trim('Hiển thị')))) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $country = $this->_data->getCountryByCurrencyCode($data['currency_code']);
            if (!empty($country)) {
                $this->_data->update(array("id" => $country->id), $data, $this->_data->_table_country);
            } else {
                $this->_data->save($data, $this->_data->_table_country);
            }
        }
        $message['type'] = 'success';
        $message['message'] = 'Import thành công';
        die(json_encode($message));
    }

    public function export_file($location)
    {
        switch ($location) {
            case 'country' :
            {
                $file_path = '././public/download/example_data/import_country.xlsx';
                force_download($file_path, NULL);
            }
            case 'city' :
            {
                $file_path = '././public/download/example_data/import_city.xlsx';
                force_download($file_path, NULL);
            }
        }
    }

    public function ajax_load_wards($district_id)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->load->model('location_model');
            $locationModel = new Location_model();
            $term = $this->input->post("q");
            $params = array(
                'district_id' => $district_id,
                'search' => $term,
                'limit' => 20,
                'order_by_name' => 'title'
            );
            $list = $this->_data->getDataWard($params);
            $json = [];
            if (!empty($list)) foreach ($list as $item) {
                $item = (object)$item;
                $json[] = ['id' => $item->id, 'text' => $item->title];
            }
            echo json_encode($json);
        }
        exit;
    }

    public function ajax_add()
    {
        $data_store = $this->_convertData();
        $city_id = $this->_data->save($data_store, 'location_' . $this->_location);
        if ($city_id) {
            $message['type'] = 'success';
            $message['message'] = 'Thêm mới thành công !';
        } else {
            $message['type'] = 'error';
            $message['message'] = 'Thêm mới thất bại';
        }
        die(json_encode($message));
    }

    public function ajax_edit($id)
    {
        if ($this->_location == 'city') {
            $data['data'] = $this->_data->getByIdlocation(['id' => $id], $this->_data->_table_city);
            if (!empty($data['data']->country_id)) {
                $country = $this->_data->getCountryById($data['data']->country_id);
                $data['country'][] = array('id' => $country->id, 'text' => $country->title);
            }

        } else {
            $data['data'] = $this->_data->getByIdlocation(['id' => $id], 'location_' . $this->_location);
            $data['city'] = array();
            if (isset($data['data']->city_id)) {
                $city = $this->_data->getCityById($data['data']->city_id);
            } else {
                $city = $this->_data->getCityById($data['data']->id);
            }
            $data['city'][0] = ['id' => $city->id, 'text' => $city->title];
            if (isset($data['data']->district_id)) {
                $district = $this->_data->getDistrictById($data['data']->district_id);
                $data['district'][0] = ['id' => $district->id, 'text' => $district->title];
            }
        }
        die(json_encode($data));
    }

    public function ajax_delete($id)
    {
        if ($id == 6) {
            $message['type'] = 'error';
            $message['message'] = "Xóa bản ghi thất bại !";
            die(json_encode($message));
        }

        $response = $this->_data->delete(['id' => $id], 'location_' . $this->_location);
        if ($response != true) {
            $message['type'] = 'error';
            $message['message'] = "Xóa bản ghi thất bại !";
        } else {
            $message['type'] = 'success';
            $message['message'] = "Xóa bản ghi thành công !";
        }
        die(json_encode($message));
    }

    public function ajax_update()
    {
        $data_store = $this->_convertData();
        $response = $this->_data->updateLocation(array('id' => $this->input->post('id')), $data_store, 'location_' . $this->_location);
        if ($response == false) {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        } else {
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        }
        die(json_encode($message));
    }

    private function _validate()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $rules = [
                [
                    'field' => 'name_with_type',
                    'label' => 'Địa chỉ đầy đủ',
                    'rules' => 'trim|xss_clean|callback_validate_html'
                ], [
                    'field' => 'title',
                    'label' => 'Tên',
                    'rules' => 'required|trim|xss_clean|callback_validate_html'
                ], [
                    'field' => 'order',
                    'label' => 'Sắp xếp',
                    'rules' => 'trim|xss_clean|callback_validate_html|is_natural'
                ],
                [
                    'field' => 'latitude',
                    'label' => 'Vĩ độ',
                    'rules' => 'required|trim|xss_clean|callback_validate_html|numeric'
                ], [
                    'field' => 'longitude',
                    'label' => 'Kinh độ',
                    'rules' => 'required|trim|xss_clean|callback_validate_html|numeric'
                ]
            ];
            if ($this->_location == 'district') {
                $rules[] = [
                    'field' => 'city_id',
                    'label' => 'Tỉnh / Thành phố',
                    'rules' => 'required|trim|xss_clean|callback_validate_html'
                ];
            }
            if ($this->_location == 'ward') {
                $rules[] = [
                    'field' => 'city_id',
                    'label' => 'Tỉnh / Thành phố',
                    'rules' => 'required|trim|xss_clean|callback_validate_html'
                ];
                $rules[] = [
                    'field' => 'district_id',
                    'label' => 'Quận/huyện',
                    'rules' => 'required|trim|xss_clean|callback_validate_html'
                ];
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                if (!empty($rules)) foreach ($rules as $item) {
                    if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
                }
                $this->_message = array(
                    'validation' => $valid,
                    'type' => 'warning',
                    'message' => $this->lang->line('mess_validation')
                );
                $this->returnJson();
            }
        }
    }

    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        unset($data['id']);
        return $data;
    }

    public function ajax_load_country()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $params = [
                'search' => $term,
                'limit' => 100
            ];
            $list = $this->_data->getDataCountry($params);
            $json = [];
            if (!empty($list)) foreach ($list as $item) {
                $item = (object)$item;
                $json[] = ['id' => $item->id, 'text' => $item->title];
            }
            print json_encode($json);
        }
    }

}