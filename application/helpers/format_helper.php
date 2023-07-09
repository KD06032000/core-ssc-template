<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function getSettings($setting, $lang_code = '')
{
    $_this =& get_instance();
    if (!empty($lang_code)) {
        return (isset($_this->settings[$setting][$lang_code])) ? $_this->settings[$setting][$lang_code] : '';
    } else {
        return (isset($_this->settings[$setting])) ? $_this->settings[$setting] : '';
    }
}

if (!function_exists('show_checked')) {
    function show_checked($value1, $value2)
    {
        $checked = '';
        if ($value1 == $value2) $checked = 'checked';
        return $checked;
    }
}
if (!function_exists('show_selected')) {
    function show_selected($value1, $value2)
    {
        $selected = '';
        if (!empty($value1) && $value1 == $value2) $selected = 'selected';
        return $selected;
    }
}
if (!function_exists('getLastKeyArr')) {
    function getLastKeyArr($arr = array())
    {
        $b = array_keys($arr);
        $last = end($b);
        return $last;
    }
}

if (!function_exists('getNumberics')) {
    function getNumberics($arr)
    {
        $arrnews = array_keys($arr);
        $arrnews = end($arrnews);
        preg_match_all('/\d+/', $arrnews, $matches);
        return (int)end($matches[0]);
    }
}

if (!function_exists('showCenter')) {
    function showCenter($value, $nowrap = '')
    {
        return "<div class='text-center " . (empty($nowrap) ? 'nowrap' : '') . "'>" . $value . "</div>";
    }
}
if (!function_exists('showLeft')) {
    function showLeft($value)
    {
        return "<div class='text-left'>" . $value . "</div>";
    }
}
if (!function_exists('showRight')) {
    function showRight($value)
    {
        return "<div class='text-right nowrap'>" . $value . "</div>";
    }
}
if (!function_exists('get_query_string')) {
    function get_query_string()
    {
        return !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    }
}

if (!function_exists('fetch_from_array')) {

    function fetch_from_array($array, $index = NULL)
    {
        // If $index is NULL, it means that the whole $array is requested
        isset($index) or $index = array_keys($array);
        // allow fetching multiple keys at once
        if (is_array($index)) {
            $output = array();
            foreach ($index as $key) {
                $output[$key] = fetch_from_array($array, $key);
            }
            return $output;
        }
        if (isset($array[$index])) {
            $value = $array[$index];
        } elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1) // Does the index contain array notation
        {
            $value = $array;
            for ($i = 0; $i < $count; $i++) {
                $key = trim($matches[0][$i], '[]');
                if ($key === '') // Empty notation will return the value as array
                {
                    break;
                }

                if (isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    return NULL;
                }
            }
        } else {
            return NULL;
        }
        return $value;
    }
}

if (!function_exists('config_field_settings')) {
    function config_field_settings($name_primary, $list)
    {
        $_this =& get_instance();
        $html = '';
        if (!empty($list)) foreach ($list as $key => $item) {
            $field_name = $item['name'][0];
            $child_name = @$item['name'][1];
            $item['name'] = "{$name_primary}[{$field_name}][{$child_name}]";
            $item['value'] = !empty(getSettings($name_primary)[$field_name][$child_name]) ? getSettings($name_primary)[$field_name][$child_name] : '';
            $html .= $_this->load->view($_this->template_path . 'setting/items/' . $item['type'], $item, true);
        }
        return $html;
    }
}
