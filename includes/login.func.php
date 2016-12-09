<?php
if (!defined('IN_TG')){
    exit('Access Denied');
}
if (!function_exists('alert_back')){
    echo 'alert_back函数不存在，请检查';
}
if (!function_exists('mysql_string')){
    echo 'mysql_string函数不存在，请检查';
}

function check_username($str_username,$min_num,$max_num){
    //去年两边的空格
    $str_username=trim($str_username);
    //长度限制
    if (mb_strlen($str_username,'utf-8')<$min_num||mb_strlen($str_username,'utf-8')>$max_num){
        alert_back('用户名长度不能小于'.$min_num.'位或大于'.$max_num.'位');
    }
    //限制敏感字符
    $char_pattern='/[<>\'\"\ \  ]/';
    if (preg_match($char_pattern,$str_username)){
        alert_back('用户名不能有特殊符号');
    }
    return mysql_string($str_username);
}
function check_pwd($first_pwd,$min_pwd){
    //判断密码倍数
    if (strlen($first_pwd)<$min_pwd){
        alert_back('密码倍数不能少于'.$min_pwd.'位');
    }
    return sha1($first_pwd);
}
function check_time($str){
    $time=array(0,1,2,3);
    if (!in_array($str,$time)){
        alert_back($str.'选择保留时间不合法');
    }
    return mysql_string($str);
}
//验证 验证码
function check_vcode($input_code,$output_code){
    if ($input_code!=$output_code){
        alert_back('验证码不正确');
    }
}

