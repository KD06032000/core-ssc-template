<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Library extends Public_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_data_page;
    protected $_data_pay;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['page_model', 'category_model', 'library_model', 'buy_docs_model']);
        $this->load->helper('payment');
        $this->_data = new Library_model();
        $this->_data_page = new Page_model();
        $this->_data_category = new Category_model();
        $this->_data_pay = new Buy_docs_model();
    }

    public function pay_document($id)
    {
        $this->load->library(array('Payment/ViettelPay', 'session'));

        $document = $this->_data->getById($id, "", "*", $this->lang_code);

        $content = 'Thanh toan mua tai lieu : ' . $document->title;

        $id_pay = $this->_data_pay->save([
            'content' => $content,
            'document_id' => $document->id,
            'price' => $document->price,
            'payment' => 'viettelpay',
            'is_status' => '2',
            'type' => 2
        ]);

        $_data_pay = [
            'trans_amount' => $document->price,
            'desc' => $content,
            'return_url' => base_url() . '/library/pay_callback_success',
            'cancel_url' => base_url() . '/library/pay_callback_reject',
            'order_id' => $id_pay,
            'billcode' => renderBillCode($id_pay)
        ];

        $viettel_pay = new ViettelPay();

        $this->returnJson([
            "url" => $viettel_pay->get_url($_data_pay)
        ]);
    }

    public function pay_callback_success()
    {
        $pages = $this->_data_page->getPageByLayout(['library_document'], '*')[0];
        if ($this->input->get('error_code') == '00') {

            $pay = $this->_data_pay->getById($this->input->get('order_id'));

            $this->_data_pay->update(['id' => $pay->id], [
                'is_status' => 3,
                'transaction_id' => $this->input->get('vt_transaction_id'),
                'phone' => $this->input->get('cust_msisdn'),
            ]);

            $document = $this->_data->getById($pay->document_id)[0];

            $this->session->set_flashdata('message', [
                'type' => 'success',
                'message' => 'Giao dịch thành công',
                'file_download' => MEDIA_URL . $document->file
            ]);
        } else {
            $this->_data_pay->delete(['id' => $this->input->get('order_id')]);
            $this->session->set_flashdata('message', [
                'type' => 'warning',
                'message' => 'Giao dịch không thành công',
            ]);
        }
        redirect(getUrlPage($pages));
    }

    public function pay_callback_reject()
    {
        $pages = $this->_data_page->getPageByLayout(['library_document'], '*')[0];
        $this->_data_pay->delete(['id' => $this->input->get('order_id')]);
        $this->session->set_flashdata('message', [
            'type' => 'warning',
            'message' => 'Giao dịch không thành công'
        ]);
        redirect(getUrlPage($pages));
    }

    public function get_list($type, $page, $limit)
    {
        $post = $this->input->post();
        $current = $this->_controller . '/' . $this->_method;
        $params = [
            'is_status' => 1,
            'lang_code' => $this->lang_code,
            'where' => [
                'type' => $type
            ],
            'page' => $page,
            'limit' => $limit,
            'order' => ['order' => 'DESC', 'displayed_time' => 'DESC', 'created_time' => 'DESC'],
        ];
        if (!empty($post['year'])) {
            $data['year'] = $params['where']['YEAR(displayed_time)'] = $post['year'];
        }
        if (!empty($post['keyword'])) {
            $data['keyword'] = $params['search'] = $post['keyword'];
        }
        if (!empty($post['type'])) {
            $data['type_price'] = $post['type'];
            if ((int)$post['type'] === 1) {
                $params['where']['price >'] = '0';
            } else {
                $params['where']['price'] = '0';
            }
        }
        $data['data'] = $this->_data->getData($params);
        $data['total'] = $this->_data->getTotal($params);
        $data['pagination'] = $this->getPagination($data['total'], $limit, $current, $current);
        return $data;
    }

    public function ajax_list_library_document($page = 1)
    {
        $data = $this->get_list(3, $page, 6);
        $data['path'] = 'item_document';
        $data['tab'] = 'document';
        die($this->load->view($this->template_path . 'page/_block/item-library_document', $data, TRUE));
    }

    public function ajax_list_library_image($page = 1)
    {
        $data = $this->get_list(1, $page, 9);
        $data['path'] = 'item_image';
        $data['tab'] = 'gallery';
        die($this->load->view($this->template_path . 'page/_block/item-library_image', $data, TRUE));
    }

    public function ajax_list_library_video($page = 1)
    {
        $data = $this->get_list(2, $page, 9);
        $data['path'] = 'item_video';
        $data['tab'] = 'video';
        die($this->load->view($this->template_path . 'page/_block/item-library_video', $data, TRUE));
    }
}
