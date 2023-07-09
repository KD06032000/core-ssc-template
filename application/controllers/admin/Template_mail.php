<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_mail extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('directory');
        $this->load->helper('file');
        $this->lang->load('template_mail');
    }

    public function index(){
        $data['template_mails'] = [];

        //Load folder template-mail
        $directory_template_mail = directory_map("./template-mail", 0, TRUE);
        foreach ($directory_template_mail as $value){
            $data['template_mails'][] = ['name' => str_replace(".html", '', $value),'content'=> read_file('./template-mail/' . $value)];
        }

        $data['heading_title'] = "Mail mẫu";
        $data['heading_description'] = "Danh sách các mail mẫu";

        /*Start Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*End Breadcrumbs*/

        $data['main_content'] = $this->load->view($this->template_path . 'template_mail/index', $data, TRUE);//Load sub-view
        $this->load->view($this->template_main, $data);//Load main view
    }

    public function ajax_update($templateFileName, $addNew = null){
        $data = $this->input->POST();//Lấy dữ liệu ajax post
        $template_data = $data['content_template'];//Lấy nội dung tinycme
        unset($data['content_template']);//Bỏ nội dung tinycme khỏi biến data
        unset($data['fileName']);//Bỏ form-name khỏi biến data
        foreach ($data as $key => $value) {
            if(array_key_exists("content_" . $key, $data)){//Nếu tên key hiện tại bằng key tiếp
                if(!empty($data["content_" . $key])){//Kiểm tra giá trị key có rỗng
                    $str_replace = "{{{$data[$key]}}}" . " - " .$data["content_" . $key];//Nếu không rỗng đặt nội dung kèm key
                }else{
                    $str_replace = "{{{$data[$key]}}}";
                }
                $template_data = str_replace("{{{$data[$key]}}}", $str_replace, $template_data);//Thay thế key trong template
            }
        }

        $issucess = false;
        if(file_exists('./template-mail/' . $templateFileName)){//Kiểm tra xem có template chưa
            //Thực hiện ghi nội dung đã sửa đổi
            write_file('./template-mail/' . $templateFileName, $template_data);
            $issucess = true;
        }else{
            if($addNew == "addNew"){//Kiểm tra là tạo mới template
                if(!empty($templateFileName) && !file_exists('./template-mail/' . $templateFileName . '.html')){
                    write_file('./template-mail/' . $templateFileName . ".html", $template_data);
                    $issucess = true;
                }
            }
        }

        if ($issucess)
        {
            $message['type'] = 'success';
            $message['message'] = "Thay đổi thành công";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Thay đổi thất bại";
        }

        die(json_encode(['template' => $template_data, 'message' => $message]));
    }

    public function tree_template_mail(){
        $directory_template_mail = directory_map("./template-mail", 0, TRUE);
        die(json_encode($directory_template_mail));
    }

    public function url_ajax_load_template_mail($fileTemplate){
        die(json_encode(read_file('./template-mail/' . $fileTemplate)));
    }

    public function url_ajax_remove_template_mail($fileTemplate){
        if (file_exists('./template-mail/' . $fileTemplate)){
            unlink('./template-mail/' . $fileTemplate);
            $message['type'] = 'success';
            $message['message'] = "Thay đổi thành công";
        }else{
            $message['type'] = 'error';
            $message['message'] = "Thay đổi thất bại";
        }

        die(json_encode(['message' => $message]));
    }



}