<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function menuPrimaryLeft($classname = '', $id = '', $submenuclass = '')
{
    return menus_custom(1, $classname, $id, $submenuclass);
}

function menuPrimaryMobile($classname = '', $id = '', $submenuclass = '')
{
    return menus(1, $classname, $id, $submenuclass);
}

function menuFooter1($classname = '', $id = '', $submenuclass = '')
{
    return menus(2, $classname, $id, $submenuclass);
}

function menuFooter2($classname = '', $id = '', $submenuclass = '')
{
    return menus(3, $classname, $id, $submenuclass);
}

function menuFooter3($classname = '', $id = '', $submenuclass = '')
{
    return menus(4, $classname, $id, $submenuclass);
}

function prepare_items_menu(array $data, $parent = null)
{
    $items = array();

    foreach ($data as $item) {
        if ($item['parent'] == $parent) {
            $items[$item['id']] = $item;
            $items[$item['id']]['children'] = prepare_items_menu($data, $item['id']);
        }
    }

    // after items constructed
    // sort array by order
    usort($items, function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    return $items;
}

function menus_custom($location, $classname, $id, $submenuclass)
{
    $ci =& get_instance();

    if (!$ci->cache->get('listmenu_main_' . $location . '_' . $ci->session->public_lang_code)) {

        $ci->load->model('menus_model');
        $menuModel = new Menus_model();
        $q = $menuModel->getMenu($location, $ci->session->public_lang_code, 'ASC');
        $menuModel->listmenu($q);

        $listMenu = $menuModel->listmenu;

        $data = prepare_items_menu($listMenu);


        $html = '<ul class="main-nav flex-center-end">';
        foreach ($data as $item) {


            switch ($item['class']) {
                case 'home':
                    $view = $ci->load->view($ci->template_path . '_menu/item_home', compact('item', 'ci'), TRUE);
                    break;
                case 'item-megamenu':
                    $view = $ci->load->view($ci->template_path . '_menu/item_mega', compact('item', 'ci'), TRUE);
                    break;
                default:
                    $view = $ci->load->view($ci->template_path . '_menu/item', compact('item', 'ci'), TRUE);
                    break;
            }
            $html .= $view;
        }

        $html .= '</ul>';

        $ci->cache->save('listmenu_main_' . $location . '_' . $ci->session->public_lang_code, $html, 60 * 60 * 30);
    }
    $html = $ci->cache->get('listmenu_main_' . $location . '_' . $ci->session->public_lang_code);
    return $html;
}

function menus($location, $classname, $id, $submenuclass)
{
    $ci =& get_instance();

    if (!$ci->cache->get('listmenu_' . $location . '_' . $ci->session->public_lang_code)) {
        $ci->load->model('menus_model');
        $ci->load->library('NavsMenu');
        $ci->load->helper('link');
        $menuModel = new Menus_model();
        $q = $menuModel->getMenu($location, $ci->session->public_lang_code);
        $menuModel->listmenu($q);

        $listMenu = $menuModel->listmenu;
        $navsMenu = new NavsMenu();
        $navsMenu->set_items($listMenu);
        $config["nav_tag_open"] = "<ul id='$id' class='$classname'>";
        $config["parent_tag_open"] = "<li class='%s'>";
//    $config["item_anchor"]          = "<a href='%s' class='smooth psy-btn' title='%s'>%s</a>";
//    $config["parent_anchor"]          = "<a href='%s' class='smooth' title='%s'>%s</a>";
        $config["item_active_class"] = "";
        $config["children_tag_open"] = "<ul class='$submenuclass'>";
        $navsMenu->initialize($config);
        $menuHtml = $navsMenu->render();
        $ci->cache->save('listmenu_' . $location . '_' . $ci->session->public_lang_code, $menuHtml, 60 * 60 * 30);
    }
    $menuHtml = $ci->cache->get('listmenu_' . $location . '_' . $ci->session->public_lang_code);
    return $menuHtml;

}

?>
