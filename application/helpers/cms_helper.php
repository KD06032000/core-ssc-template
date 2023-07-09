<?php

class SiteSettings
{
    public static $all = [];

    public static function item($key, $element = [], $default = '')
    {
        if (empty(self::$all[$key])) return '';

        $data = self::$all[$key];
        if (!empty($element)) foreach ($element as $item) {

            if ($value = Arr::element($item, $data, false)) {
                $data = $value;
            } else {
                return $default;
            }
        }
        return $data;
    }

    public static function item_lang($key, $lang_code = '', $element = [])
    {
        $lang_code = !empty($lang_code) ? $lang_code : get_instance()->lang_code;

        if (empty(self::$all[$key][$lang_code])) return '';

        $data = self::$all[$key][$lang_code];
        if (!empty($element)) foreach ($element as $item) {

            if ($value = Arr::element($item, $data, false)) {
                $data = $value;
            } else {
                return '';
            }
        }
        return $data;
    }

    public function get($name)
    {
        return fetch_from_array(self::$all, $name);
    }

}

class Arr
{
    public static function field_to_key($data, $key = 'id')
    {
        $result = [];
        if (!empty($data)) foreach ($data as $k => $item) {
            $field = is_object($item) ? $item->{$key} : $item[$key];
            $result[$field] = $item;
        }
        return $result;
    }

    public static function cal_repeat_key($data)
    {
        $result = [];
        if (!empty($data)) {
            $result = array_reduce($data, function ($carry, $item) {
                if (!empty($carry[$item->id])) {
                    $carry[$item->id]++;
                } else {
                    $carry[$item->id] = 1;
                }
                return $carry;
            }, []);
        }
        return $result;
    }

    public static function group_field($groups = '', $data = [], $is_unique = false, $prefix = 'list_')
    {
        $result = [];
        if (!empty($data)) foreach ($data as $k => $item) {
            if (is_array($groups)) {
                foreach ($groups as $field) {
                    $value = is_array($item) ? $item[$field] : $item->{$field};
                    $list_field = $prefix . $field;
                    if (!$is_unique || ($is_unique && isset($result[$list_field]) && !in_array($value, $result[$list_field]))) {
                        $result[$list_field][] = $value;
                    }
                }
            } else {
                $value = is_array($item) ? $item[$groups] : $item->{$groups};
                if (!$is_unique || ($is_unique && !in_array($value, $result))) {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

    public static function element($item, $array = [], $default = NULL)
    {
        if (!is_array($array)) {
            return $default;
        }
        return array_key_exists($item, $array) ? $array[$item] : $default;
    }

}