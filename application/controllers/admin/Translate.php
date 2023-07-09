<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Translate extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;
    protected $category_tree;
    public $base_language = 'english';
    protected $country;

    // const STATUS_CANCEL = 0;
    // const STATUS_ACTIVE = 1;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->load->model('country_model');
        $this->_data = new Country_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->session->category_type = $this->_name_controller;
    }

    public function index()
    {
        $data['heading_title'] = "Chuyển đổi ngôn ngữ";
        $data['heading_description'] = "Chuyển đổi ngôn ngữ";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    public function ajax_list_country()
    {
        $data = $this->config->item('cms_lang_cnf');
        $str = '';
        foreach ($data as $key => $value) {
            $str .= '<option value="' . $value . '" data-code=' . strtolower($key) . '>' . $value . '</option>';
        }
        //trả về data

        die(json_encode($str));
    }

    public function translation($code, $country)
    {
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $parameters['key'] = 'trnsl.1.1.20200313T101721Z.00373f667536c3c8.358324d7c8fe31340d4004c85687a797e78ea5e2';
        $parameters['lang'] = 'vi-' . $code;

        $data = $this->input->post('text');
        $data_translate = array();
        $host_api = 'https://translate.yandex.net/api/v1.5/tr.json/translate';
        foreach ($data as $key => $value) {
            $parameters['text'] = $value;
            $url = $host_api . '?' .  http_build_query($parameters);
            $remoteResult = file_get_contents($url);
            // curl_setopt($handler, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/translate');
            // curl_setopt($handler, CURLOPT_POST, true);
            // curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query($parameters));
            // $remoteResult = curl_exec($handler);
            // if ($remoteResult === false) continue;
            $result = json_decode($remoteResult, true);
            $data_translate[$key] = $result['text'][0];
        }

        $this->create_lang_file_for_country($country, $data_translate);
    }

    public function get_data_file($country)
    {
        $this->load->helper('file');
        $country = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $country)));

        $file = APPPATH . 'language/' . $country . '/api_lang.php';
        // dd($file);
        if (read_file($file)) {
            $lang_country = $this->load_frontend('api', $country);
        }

        $lang_default = $this->load_frontend('api');
        $data_default = '';
        foreach ($lang_default as $key => $value) :
            $data_default .= '<tr role="row" class="odd"><td><div class="text_translate" data-index="' . $key . '">';
            $data_default .= $value;
            $data_default .= '</div></td><td><div class="text-center nowrap"><input type="text" name="';
            $data_default .= $key;
            $data_default .= '" class="form-control"></div></td></tr> ';
        endforeach;

        $output = array(
            "lang_default" => $data_default,
            "lang_country" => empty($lang_country) ? null : $lang_country,
        );

        die(json_encode($output));
    }

    public function create_lang_file_for_country($country, $data = null)
    {
        if (empty($data)) $data = $this->input->post();

        $this->load->helper('file');
        $country = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $country)));

        $file = APPPATH . 'language/' . $country . '/api_lang.php';


        if (read_file($file)) unlink($file);
        else if (!is_dir(APPPATH . 'language/' . $country)) mkdir(APPPATH . 'language/' . $country);

        $openFile = fopen($file, 'w');
        if ($this->write_files($file, $data)) {
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_update_success');
            $message['data'] = $data;
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }

        fclose($openFile);

        die(json_encode($message));
    }

    public function write_files($file, $data = null)
    {
        $this->load->helper('file');
        $str = '';

        if (!empty($data)) {
            $str .= "<?php if (!defined('BASEPATH')) exit('No direct script access allowed');\n//form";
            foreach ($data as $key => $value) {
                $value = htmlentities($value, ENT_QUOTES | ENT_IGNORE | ENT_HTML5, "UTF-8");
                $str .= "\n\$lang['" . $key . "'] = '" . $value . "';";
            }
        }

        if (write_file($file, $str)) return true;

        return false;
    }

    public function load_frontend($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
    {
        if (is_array($langfile)) {
            foreach ($langfile as $value) {
                $this->load($value, $idiom, $return, $add_suffix, $alt_path);
            }

            return;
        }

        $langfile = str_replace('.php', '', $langfile);

        if ($add_suffix === TRUE) {
            $langfile = preg_replace('/_lang$/', '', $langfile) . '_lang';
        }

        $langfile .= '.php';

        if (empty($idiom) or !preg_match('/^[a-z_-]+$/i', $idiom)) {
            $config = &get_config();
            $idiom = empty($config['language']) ? $this->base_language : $config['language'];
        }

        if ($return === FALSE && isset($this->is_loaded[$langfile]) && $this->is_loaded[$langfile] === $idiom) {
            return;
        }

        // load the default language first, if necessary
        // only do this for the language files under system/
        $basepath = SYSDIR . 'language/' . $this->base_language . '/' . $langfile;
        if (($found = file_exists($basepath)) === TRUE) {
            include($basepath);
        }

        // Load the base file, so any others found can override it
        $basepath = BASEPATH . 'language/' . $idiom . '/' . $langfile;
        if (($found = file_exists($basepath)) === TRUE) {
            include($basepath);
        }

        // Do we have an alternative path to look in?
        if ($alt_path !== '') {
            $alt_path .= 'language/' . $idiom . '/' . $langfile;
            if (file_exists($alt_path)) {
                include($alt_path);
                $found = TRUE;
            }
        } else {
            foreach (get_instance()->load->get_package_paths(TRUE) as $package_path) {
                $package_path .= 'language/' . $idiom . '/' . $langfile;
                if ($basepath !== $package_path && file_exists($package_path)) {
                    include($package_path);
                    $found = TRUE;
                    break;
                }
            }
        }
        return $lang;
    }
}
