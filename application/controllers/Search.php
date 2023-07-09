<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Search extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [];
        $get = $this->input->get();
        $data['key'] = $keyword = $get['q'];
        if (empty($keyword)) show_404();

        $data['SEO'] = array(
            'meta_title' =>  lang('search_result') . ": $keyword",
            'meta_description' => lang('search_result') . ": $keyword",
            'meta_keyword' => "Keyword $keyword",
            'url' => current_url() . '?q=' . $keyword,
            'image' => resizeImage(SiteSettings::item('logo'))
        );

        $data['main_content'] = $this->load->view($this->template_path . 'search/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

}
