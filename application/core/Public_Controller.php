<?php

class Public_Controller extends APS_Controller
{
    public $settings = array();
    public $_message = array();
    public $lang_code;
    public $_all_category = [];
    public $_user_login = [];
    public $layout;
    public $show_header = false;
    public $token;
    public $template_landingpage ;

    public function __construct()
    {
        parent::__construct();
        //set đường dẫn template
        $this->template_path = 'public/default/';
        $this->template_main = $this->template_path . '_layout';
        $this->template_landingpage = $this->template_path . '_landingpage';
        $this->templates_assets = base_url() . 'public/';
        //load cache driver
        $this->load->driver('cache', array('adapter' => 'file'));
        //tải thư viện
        $this->load->library(('cart'));
        //load helper
        $this->load->helper(array(
            'cookie',
            'navmenus',
            'notify',
            'title',
            'number',
            'format',
            'image',
            'status_order',
            'status'));
        //Detect mobile
        //$this->detectMobile = new Mobile_Detect();
        $this->config->load('email');
        //Language
        $layout = $this->input->get_request_header('layout');
        if (!empty($layout)) {
            $this->layout = $this->session->userdata['layout'] = $layout;
        } else {
            $this->layout = $this->session->layout;
        }
        $lang_code = $this->input->get('lang');
        $lang_cnf = $this->config->item('cms_lang_cnf');
        $lang_uri = $this->uri->segment(1);
//			$this->connect_redis();

        if (!empty($lang_uri) && array_key_exists($lang_uri, $lang_cnf)) {
            $this->session->public_lang_code = $lang_uri;
            $this->session->public_lang_full = $lang_cnf[$lang_uri];
        }
        if (!empty($lang_code) && array_key_exists($lang_code, $lang_cnf)) {
            $this->session->public_lang_code = $lang_code;
            $this->session->public_lang_full = $lang_cnf[$lang_code];
        }

        if (empty($this->session->public_lang_code)) {
            //không có lang code thì mặc định hiển thị tiếng việt
            $this->session->public_lang_code = $this->config->item('default_language');
            // Hoặc hiển thị ngôn ngữ theo IP
            $this->session->public_lang_full = $this->config->item('cms_lang_cnf')[$this->config->item('default_language')];
        }

        $this->lang_code = $this->session->public_lang_code;
        if (!empty($this->session->userdata['account']['account_id'])) {
            $this->load->model('account_model');
            $accountModel = new Account_model();
            $this->_user_login = $accountModel->getAccount($this->session->userdata['account']['account_id'], '*');
            if (empty($this->_user_login) || $this->_user_login->active != 1) {
                unset($this->session->userdata['account']);
                redirect(current_url());
            }
        }

        $this->config->set_item('language', $this->session->public_lang_full);

        $BASE_URL = BASE_URL;
        if ($this->lang_code != $this->config->item('default_language')) {
            $BASE_URL = BASE_URL . $this->lang_code . '/';
        }
        $this->loadSettings();
        define('BASE_URL_LANG', $BASE_URL);
        $this->lang->load(array('frontend', 'general'), $this->session->public_lang_full);

        SiteSettings::$all = $this->settings;

//        $this->check_redirect_link();

        //Set flash message
        $this->_message = $this->session->flashdata('message');
        if (MAINTAIN_MODE == TRUE) $this->load->view('public/coming_soon');
        if (!$this->cache->get('_all_category_' . $this->session->public_lang_code)) {
            $this->load->model('category_model');
            $categoryModel = new Category_model();
            $this->cache->save('_all_category_' . $this->session->public_lang_code, $categoryModel->getAll($this->session->public_lang_code, 1), 60 * 60 * 30);
        }
        $this->_all_category = $this->cache->get('_all_category_' . $this->session->public_lang_code);
    }

    public function loadSettings()
    {
        $dataSetting = file_get_contents(FCPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.cfg');
        $dataSetting = $dataSetting ? json_decode($dataSetting, true) : array();
        if (!empty($dataSetting)) foreach ($dataSetting as $key => $item) {
            if ($key === 'meta') {
                $oneMeta = $item[$this->session->public_lang_code];
                if (!empty($oneMeta)) foreach ($oneMeta as $keyMeta => $value) {
                    $this->settings[$keyMeta] = str_replace('"', '\'', $value);
                }
            } else
                $this->settings[$key] = $item;
        }
    }

    public function getPagination($total, $limit, $base_url, $first_url, $query_strings = false)
    {
        if (!empty($this->input->get('page'))) {
            $first_url = remove_query_arg('page');
        }
        $this->load->library('pagination');
        $paging['base_url'] = $base_url;
        $paging['first_url'] = $first_url;
        $paging['total_rows'] = $total;
        $paging['per_page'] = $limit;
        $paging['attributes'] = array('class' => 'page-link');
        $paging['page_query_string'] = $query_strings;
        $this->pagination->initialize($paging);
        return $this->pagination->create_links();
    }

    public function blockSEO($oneItem, $url)
    {
        $data = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => $url,
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail) : getImageThumb($this->settings['logo'])
        ];
        return $data;
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);
        return array($key => $value);
    }

    public function log_notify($id, $action, $is_read = 0)
    {
        $this->load->model('log_action_model');
        $log_model = new Log_action_model();

        $data['notify_id'] = $id;
        $data['action'] = $action;

        $user = array(
            0 => (object)[
                'id' => $this->_user_login->id,
            ]
        );
        return $log_model->save_notify($data, $user, $is_read);
    }
}
