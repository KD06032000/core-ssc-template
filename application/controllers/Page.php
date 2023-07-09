<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends Public_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_data_document;
    protected $_data_post;
    protected $_data_property;
    protected $_data_banner;

    public function __construct()
    {
        parent::__construct();
        //tải model
        $this->load->model(['page_model', 'property_model', 'banner_model', 'document_model', 'category_model', 'post_model']);
        $this->_data = new Page_model();
        $this->_data_post = new Post_model();
        $this->_data_category = new Category_model;
        $this->_data_document = new Document_model();
        $this->_data_property = new Property_model();
        $this->_data_banner = new Banner_model();
    }


    public function index($slug = null, $page = 1)
    {
        $id = $this->_data->slugToId($slug);
        $data['main'] = $main = $this->_data->getById($id, '', '*', $this->lang_code);

        if (empty($main)) {
            $this->show_404();
        }

        if ($this->input->get('lang')) redirect(getUrlPage($main));

        //view layout
        if (!empty($main->style)) $layoutView = '-' . $main->style;
        else $layoutView = '';

        switch ($main->style) {
            case 'calendar':
                $data['categories'] = getDataCategory('calendar', null);
                break;
            case 'news':
                $data['tags'] = getDataTag(null);
                break;
            default :

                break;
        }
        //SEO
        $data['SEO'] = $this->blockSEO($main, getUrlPage($main));
        $this->breadcrumbs->push(lang('home'), base_url());
        $this->breadcrumbs->push($main->title, getUrlPage($main));
        $data['main_content'] = $this->load->view($this->template_path . 'page/page' . $layoutView, $data, TRUE);

        $this->load->view($this->template_main, $data);
    }

    public function _404()
    {
        $data['flagHeader'] = true;
        $data['main_content'] = $this->load->view($this->template_path . 'page/_404', NULL, TRUE);
        $this->load->view($this->template_main, $data);
    }

}
