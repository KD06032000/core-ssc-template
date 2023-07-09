<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getImageThumb')) {
    function getImageThumb($image = '', $width = '', $height = '', $crop = true, $quality = '100%')
    {
        if (is_array(json_decode($image))) {
            $imageArray = json_decode($image);
            $image = $imageArray[0];
        }
        if (empty($image)) {
            $width = !empty($width) ? $width : 400;
            $height = !empty($height) ? $height : 200;
            return "//via.placeholder.com/{$width}x{$height}";
        }
        $image = str_replace(MEDIA_NAME, '', $image);
        $sourceImage = MEDIA_PATH . $image;

        $sourceImage = str_replace('\\', '/', $sourceImage);

        if (!file_exists($sourceImage)) {
            $width = !empty($width) ? $width : 400;
            $height = !empty($height) ? $height : 200;
            return "//via.placeholder.com/{$width}x{$height}";
        }
        $CI =& get_instance();
        if ($width != 0 && $height != 0) {
            $size = sprintf('-%dx%d', $width, $height);
            $part = explode('.', $image);
            $ext = '.' . end($part);
            $newImage = str_replace($ext, $size . $ext, $image);
            $newPathImage = MEDIA_PATH . 'thumb' . DIRECTORY_SEPARATOR . $newImage;
            $newPathImage = str_replace('\\', '/', $newPathImage);
            if (!file_exists($newPathImage)) {
                if (!is_dir(dirname($newPathImage))) {
                    mkdir(dirname($newPathImage), 0755, TRUE);
                }
                // CONFIGURE IMAGE LIBRARY
                $CI->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $sourceImage;
                $config['new_image'] = $newPathImage;
                $config['maintain_ratio'] = TRUE;
                $config['create_thumb'] = FALSE;
                $config['height'] = $height;
                $config['width'] = $width;
                $imageSize = getimagesize($sourceImage);
                $imageWidth = intval($imageSize[0]);
                $imageHeight = intval($imageSize[1]);
                if ($imageHeight > 0) {
                    $dim = ($imageWidth / $imageHeight) - ($width / $height);
                    $config['master_dim'] = ($dim > 0) ? "height" : "width";
                    $CI->image_lib->initialize($config);
                    if (!$CI->image_lib->resize()) {
                        print ($CI->image_lib->display_errors());
                    } else {
                        if ($crop == true) {
                            $image_config['image_library'] = 'gd2';
                            $image_config['source_image'] = $newPathImage;
                            $image_config['new_image'] = $newPathImage;
                            $image_config['quality'] = $quality;
                            $image_config['maintain_ratio'] = FALSE;
                            $image_config['width'] = $width;
                            $image_config['height'] = $height;

                            $imageSize = getimagesize($newPathImage);
                            $imageWidth = intval($imageSize[0]);
                            $imageHeight = intval($imageSize[1]);
                            $cropStartX = ($imageWidth / 2) - ($width / 2);
                            $cropStartY = ($imageHeight / 2) - ($height / 2);
                            $image_config['x_axis'] = $cropStartX;
                            $image_config['y_axis'] = $cropStartY;

                            $CI->image_lib->clear();
                            $CI->image_lib->initialize($image_config);
                            if (!$CI->image_lib->crop()) {
                                print ($CI->image_lib->display_errors());
                            }
                        }

                    }
                } else {
                    return str_replace('\\', '/', MEDIA_URL . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $newImage);
                }

            }
            return str_replace('\\', '/', MEDIA_URL . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $newImage);
        } else {

            return str_replace('\\', '/', MEDIA_URL . DIRECTORY_SEPARATOR . $image);
        }
    }
}
if (!function_exists('thePostThumbnail')) {
    function thePostThumbnail($url, $name = '', $attr = '', $return = false)
    {
        $_this =& get_instance();
        $width = $height = 0;
        $crop = false;
        $qlty = '100%';
        if (!empty($name)) {
            $info = $_this->config->item($name);
            $width = !empty($info['width']) ? $info['width'] : 0;
            $height = !empty($info['height']) ? $info['height'] : 0;
            $crop = isset($info['crop']) ? $info['crop'] : false;
            $qlty = !empty($info['qlty']) ? $info['qlty'] : '100%';

        }
        $dAttr = '';

        if (!empty($attr) && is_array($attr)) {
            foreach ($attr as $key => $value) {
                $dAttr .= ' ' . $key . '="' . $value . '" ';
            }
        } else {
            $dAttr = ' alt="' . $attr . '" title="' . $attr . '"';
        }
        $data = '<img ' . $dAttr . ' src="' . getImageThumb($url, $width, $height, $crop, $qlty) . '" />';
        if ($return == true) return $data;
        echo $data;
    }
}

if (!function_exists('getImageSeting')) {
    function getImageMultiLang($url, $name = '', $attr = array(), $arrImg = array())
    {
        if (!empty($url)) {
            thePostThumbnail($url, $name, $attr);
        } else {
            $_this =& get_instance();
            $url = $arrImg[$_this->config->item('default_language')];
            thePostThumbnail($url['img'], $name, $attr);
        }
    }
}


function resizeImage($image = '', $size = '', $size_mobile = '', $folder = MEDIA_NAME)
{
    $CI =& get_instance();
    $image_path = $folder . $image;
    if (!file_exists($image_path) || empty($image)) {
        $image_path = 'public/no_image.jpg';
    }
    if (empty($size)) {
        return $image_path;
    }
    if (!empty($size_mobile) && _isMobile()) {
        $size = $size_mobile;
    }
    $size = explode('x', $size);
    $width = $size[0];
    $height = !empty($size[1]) ? $size[1] : 0;
    $info = pathinfo($image_path);
    $thumb_folder = $info['dirname'] . '/thumb/';
    checkFilePath($thumb_folder);
    $thumb_name = $info['filename'] . '-' . $width . 'x' . $height . '.' . $info['extension'];
    $thumb_path = $thumb_folder . $thumb_name;
    if (file_exists($thumb_path)) {
        return $thumb_path;
    } else {
        $CI->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_path;
        $config['new_image'] = $thumb_path;
        $config['maintain_ratio'] = TRUE;
        $config['create_thumb'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $imageSize = getimagesize($image_path);
        $imageWidth = intval($imageSize[0]);
        $imageHeight = intval($imageSize[1]);
        if ($height > 0) {
            $dim = ($imageWidth / $imageHeight) - ($width / $height);
            $config['master_dim'] = ($dim > 0) ? "height" : "width";
        }
        $CI->image_lib->initialize($config);
        if (!$CI->image_lib->resize()) {
            print ($CI->image_lib->display_errors());
        } else {
            if ($height > 0) {
                $image_config['image_library'] = 'gd2';
                $image_config['source_image'] = $thumb_path;
                $image_config['new_image'] = $thumb_path;
                $image_config['maintain_ratio'] = FALSE;
                $image_config['width'] = $width;
                $image_config['height'] = $height;
                $imageSize = getimagesize($thumb_path);
                $imageWidth = intval($imageSize[0]);
                $imageHeight = intval($imageSize[1]);
                $cropStartX = ($imageWidth / 2) - ($width / 2);
                $cropStartY = ($imageHeight / 2) - ($height / 2);
                $image_config['x_axis'] = $cropStartX;
                $image_config['y_axis'] = $cropStartY;
                $CI->image_lib->clear();
                $CI->image_lib->initialize($image_config);
                if (!$CI->image_lib->crop()) {
                    print ($CI->image_lib->display_errors());
                }
            }
        }
        return $thumb_path;
    }
}

function getThumbLazy($size = '', $size_mobile = '')
{
    $image_path = 'public/no_image.jpg';
    return resizeImage($image_path, $size, $size_mobile, '');
}

function checkFilePath($dirname)
{
    if (!file_exists(FCPATH . '/' . $dirname)) {
        $parse_folder = explode('/', $dirname);
        $path = '';
        foreach ($parse_folder as $key => $item) {
            if (!empty($item)) {
                $path = $key === 0 ? $item : ($path . '/' . $item);
                if (!file_exists($path)) {
                    mkdir($path, 0755);
                }
            }
        }
    }
}

function imageWaterMark($image, $icon, $size = '', $size_mobile = '')
{
    $CI =& get_instance();
    $CI->load->library('image_lib');
    $waterMark = true;

    $image_path = MEDIA_NAME . $image;
    if (!file_exists($image_path) || empty($image)) {
        $waterMark = false;
    }

    $source_image = resizeImage($image, $size, $size_mobile);
    if (!$waterMark) return $source_image;
    $width_icon = (int)(getimagesize($source_image)[0] / 15);

    $overlay_path = resizeImage($icon, $width_icon);

    $info = pathinfo($source_image);

    $thumb_folder = MEDIA_NAME . '/thumb/';
    checkFilePath($thumb_folder);
    $thumb_name = $info['filename'] . '-water-mark.' . $info['extension'];
    $thumb_path = $thumb_folder . $thumb_name;

    if (file_exists($thumb_path)) {
        return $thumb_path;
    } else {
        $config['source_image'] = $source_image;
        $config['new_image'] = $thumb_path;
        $config['wm_overlay_path'] = $overlay_path;
        $config['wm_type'] = 'overlay';
        $config['wm_opacity'] = '0.2';
        $config['wm_padding'] = '10';
        $config['wm_vrt_alignment'] = 'top';
        $config['wm_hor_alignment'] = 'left';
        $config['wm_vrt_offset'] = '0';
        $config['wm_hor_offset'] = '0';
        $config['wm_x_transp'] = 4;
        $config['wm_y_transp'] = 4;

        $CI->image_lib->initialize($config);
        if (!$CI->image_lib->watermark()) {
            print ($CI->image_lib->display_errors());
        }
        return $thumb_path;
    }
}

function convertWaterMark($album)
{
    $data = [];
    if (!empty($album)) foreach (json_decode($album, true) as $value) {
        $data[] = imageWaterMark($value, SiteSettings::item('water_mark'));
    }
    return json_encode($data);
}

function convertAblbum($album)
{
    $data = [];
    if (!empty($album)) foreach (json_decode($album, true) as $value) {
        $data[] = imageWaterMark($value, SiteSettings::item('water_mark'));
    }
    return json_encode($data);
}
