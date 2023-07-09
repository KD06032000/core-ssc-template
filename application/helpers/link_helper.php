<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getUrlCateNews')) {
    function getUrlCateNews($optional)
    {

        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        if (!empty($optional['slug'])) {
            $slug = $optional['slug'];

        } else {
            $_this =& get_instance();
            $_this->load->model('category_model');
            $categoryModel = new Category_model();
            $dataSlug = $categoryModel->getUrl($id);
            if (!empty($dataSlug)) {
                $slug = $dataSlug->slug;
            } else {
                $slug = '';
            }
        }
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-c$id";
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlNews')) {
    function getUrlNews($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-d$id";
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlTag')) {
    function getUrlTag($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-t$id";
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlAvailable')) {
    function getUrlAvailable($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        return !empty($optional['url']) ? $optional['url'] : 'javascript:;';
    }
}

if (!function_exists('getUrlCateEcosystem')) {
    function getUrlCateEcosystem($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        return !empty($optional['thumbnail']) ? $optional['thumbnail'] : '#';
    }
}

if (!function_exists('getUrlEvent')) {
    function getUrlEvent($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-e$id";
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}
if (!function_exists('getUrlStory')) {
    function getUrlStory($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-s$id";
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlRoom')) {
    function getUrlRoom($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-r$id";
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlCareer')) {
    function getUrlCareer($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-cr$id";
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlCareerDetails')) {
    function getUrlCareerDetails($optional)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        $linkReturn .= "$slug-ccd$id";
        return $linkReturn;
    }
}

if (!function_exists('getUrlPage')) {

    function getUrlPage($optional = [])
    {

        $_this =& get_instance();
        if (is_object($optional)) $optional = (array)$optional;
        $linkReturn = BASE_URL_LANG;
        if (empty($optional)) goto end;
        if (!empty($optional['slug'])) {
            $slug = $optional['slug'];
            $linkReturn .= "$slug";
        } else {
            $_this->load->model('page_model');
            $pageModel = new Page_model();
            $data = $pageModel->getById($optional['id'], '*', $_this->session->public_lang_code);
            $linkReturn .= "$data->slug";
        }
        if (isset($optional['page'])) $linkReturn .= '/page/';
        end:
        return $linkReturn;
    }
}

if (!function_exists('getUrlProfile')) {
    function getUrlProfile($slug = '')
    {
        $linkReturn = BASE_URL_LANG . 'profile/';
        $linkReturn .= $slug;
        return $linkReturn;
    }
}
if (!function_exists('getUrlAccount')) {
    function getUrlAccount($optional, $action)
    {
        if (is_object($optional)) $optional = (array)$optional;
        $slug = $optional['slug'];
        $linkReturn = BASE_URL_LANG;
        if (!empty($slug)) $linkReturn .= $slug;
        if (!empty($action)) $linkReturn .= '/' . $action;
        if (isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}
if (!function_exists('getUrlAjax')) {

    function getUrlAjax($optional = [])
    {
        if (is_object($optional)) $optional = (array)$optional;
        $linkReturn = BASE_URL_LANG;
        $slug = $optional['slug'];
        $linkReturn .= "$slug";
        if (isset($optional['page'])) $linkReturn .= '/';
        return $linkReturn;
    }
}
if (!function_exists('getUrlGeneral')) {

    function getUrlGeneral($optional = [])
    {
        if (is_object($optional)) $optional = (array)$optional;
        $linkReturn = BASE_URL_LANG;
        $slug = $optional['slug'];
        $linkReturn .= "$slug";
        if (isset($optional['page'])) $linkReturn .= '/';
        return $linkReturn;
    }
}
