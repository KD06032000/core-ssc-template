<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('timeAgo')) {
	/*
	* hàm xử lý time ago
	* truyên vào datetime
	*/
    function timeAgo($datetime, $format = false) {
        if(strtotime($datetime) < 0) return $datetime = date('d/m/Y',strtotime("+3 month"));
        if($format !== false) return date($format,strtotime($datetime));
        $today = time();
        if(!is_numeric($datetime)) $createdday= strtotime($datetime);
        else $createdday = $datetime;
        $datediff = abs($today - $createdday);
        $difftext="";
        $years = floor($datediff / (365*60*60*24));
        $months = floor(($datediff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($datediff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $hours= floor($datediff/3600);
        $minutes= floor($datediff/60);
        $seconds= floor($datediff);
        //năm checker
        if($difftext=="")
        {
            if($years>1)
                $difftext=$years." năm trước đây";
            elseif($years==1)
                $difftext=$years." năm trước đây";
        }
        //month checker
        if($difftext=="")
        {
            if($months>1)
                $difftext=$months." tháng trước đây";
            elseif($months==1)
                $difftext=$months." tháng trước đây";
        }
        //month checker
        if($difftext=="")
        {
            if($days>1)
                $difftext=$days." ngày trước đây";
            elseif($days==1)
                $difftext=$days." ngày trước đây";
        }
        //hour checker
        if($difftext=="")
        {
            if($hours>1)
                $difftext=$hours." giờ trước đây";
            elseif($hours==1)
                $difftext=$hours." giờ trước đây";
        }
        //minutes checker
        if($difftext=="")
        {
            if($minutes>1)
                $difftext=$minutes." phút trước đây";
            elseif($minutes==1)
                $difftext=$minutes." phút trước đây";
        }
        //seconds checker
        if($difftext=="")
        {
            if($seconds>1)
                $difftext=$seconds." giây trước đây";
            elseif($seconds==1)
                $difftext=$seconds." giây trước đây";
        }
        return $difftext;
    }
}

if (!function_exists('isDateTime')) {
	/*
	* hàm kiểm tra định dạng datetime
	* truyên vào datetime
	*/
    function isDateTime($datetime)
    {
        if ((strpos($datetime, '0000-00-00') !== false) || (strpos($datetime, '1970-01-01') !== false)) {
            return false;
        } else {
            return (strtotime($datetime)) || (DateTime::createFromFormat('d/m/Y', $datetime));
        }
    }
}

if (!function_exists('formatDate')) {
	/*
	* hàm xử lý format date mặc đinh DD/MM/YYYY
	* datetime: định dạng YYYY-MM-DD
	* format: kiểu định dạng datetime
	* default: tham số trả về khi sai định dạng
	*/
    function formatDate($datetime, $format = 'date', $default = '-'){
		if(!isDateTime($datetime)){
			return $default;
		}
		
		if($format == 'date'){
			return date('d/m/Y',strtotime($datetime));
		}elseif($format == 'datetime'){
			return date('d/m/Y H:i',strtotime($datetime));
		}else{
			return date($format,strtotime($datetime));
		}
	}
}
if (!function_exists('dateDiffInDays')) {
    function dateDiffInDays($date1, $date2)  
    { 
        // Hàm sử lý khoảng cách thời gian :3
        $diff = strtotime($date2) - strtotime($date1); 
        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        return abs(round($diff / 86400)); 
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($dateStr) {
        if (trim($dateStr) == '' || substr($dateStr,0,10) == '0000-00-00') {
            return '';
        }
        $ts = date('d/m/Y H:i',strtotime($dateStr));
        if ($ts === false) {
            return '';
        }
        return date($ts);
    }
}
if (!function_exists('convertDate')) {

    /*
    * hàm xử lý convert date để lưu db
    */
    function convertDate($date, $default = '/', $type = 'date')
    {
        if (!isDateTime($date)) {
            return date('Y-m-d');
        }
        if ($type != 'date') {
            $dateTime = explode(' ', $date);

            $date = $dateTime[0];
        }
        $dateEx = explode($default, $date);
        $dateNew = $dateEx[2] . '-' . $dateEx[1] . '-' . $dateEx[0];
        if ($type != 'date') $dateNew = $dateNew . ' ' . $dateTime[1];
        return $dateNew;
    }

}
if (!function_exists('time_now')) {
    function time_now(){
        return date('Y-m-d H:i:s');
    }
}
