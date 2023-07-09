<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Redirect extends Public_Controller
{
    protected $_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['redirect_model']);
        $this->_data = new Redirect_model();
    }

    public function index($id) {
        $data = $this->_data->getById($id);
        if (empty($data)) $this->show_404();
        redirect($data->redirect_link);
    }
}
