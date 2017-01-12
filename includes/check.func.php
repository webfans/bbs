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
function check_uniqid($first_uniqid,$second_uniqid){
    if ((strlen($first_uniqid)!=40)||($first_uniqid!=$second_uniqid)){
        //alert_back('唯一标识符异常，非法数据提交');
        alert_back($first_uniqid.'\n'.$second_uniqid);
    }
    return mysql_string($first_uniqid);
}
/*
 * check_username() 检测并过滤用户名
 * @Access public 公共函数
 * @param string $strings 受污染的用户名
 * @param int $min_num 最小位数
 * @param int $max_num 最大位数
 * return string 返回值是字符串

 * */
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
    //限制敏感用户名
    $mg[0]='毛泽东';
    $mg[1]='邓小平';
    $mg[3]='江泽民';
    //告诉用户哪些敏感信息不能注册
    foreach ($mg as $value){
        $mg_string='';
        $mg_string.=$value.'\n';
    }
    //这里采用绝对匹配
    if (in_array($str_username,$mg)){
        alert_back($mg_string.'敏感用户名不能注册');
    }
    //将用户名转义输入，防SQL注入
    //return mysql_real_escape_string($strings);
    return mysql_string($str_username);
}

function check_pwd($first_pwd,$second_pwd,$min_pwd){
    //判断密码倍数
    if (strlen($first_pwd)<$min_pwd){
        alert_back('密码倍数不能少于'.$min_pwd.'位');
    }
    //密码和确认密码
    if ($first_pwd!=$second_pwd){
        alert_back('两次密码不一致');
    }
    return sha1($first_pwd);
}
function check_modify_pwd($first_pwd,$min_pwd){
    //判断密码位数
    if (!empty($first_pwd)){
        if (strlen($first_pwd)<$min_pwd){
            alert_back('密码倍数不能少于'.$min_pwd.'位');
        }
    }else{
        return null;
    }
    return sha1($first_pwd);
}

function check_question($strings,$min_num,$max_num){
    $strings=trim($strings);
    if (mb_strlen($strings,'utf-8')<$min_num||mb_strlen($strings,'utf-8')>$max_num){
        alert_back('密码提示不能小于'.$min_num.'位或大于'.$max_num.'位');
    }
    //返回密码提示
    //return mysql_real_escape_string($strings);
    //!!!即使mysql_string()这函数有return ，这个check_question调用了它，最后也必须再return 否则数据就无法插入数据库（为空），这是我的错误！！！
    return mysql_string($strings);
    //return $strings;
}
function check_answer($question,$answer,$min_num,$max_num){
    if (mb_strlen($answer,'utf-8')<$min_num||mb_strlen($answer,'utf-8')>$max_num){
        alert_back('密码提示不能小于'.$min_num.'位或大于'.$max_num.'位');
    }
    //密码提示和回答不能一样
    if ($question==$answer){
        alert_back('密码提示和回答不能一样');
    }
    return sha1($answer);
}
function check_emial($str_email,$min_num,$max_num){
    //参考bnbbs@163.com
    //[a-zA-Z0-9_]=>\w
    //[\w\-\.]16.3.
    //\.[\w+].com.cn
    //正则表达式看起来很难，理解了就不难了
        if (!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/',$str_email)){
            alert_back('邮件格式不正确');
        }
        if (strlen($str_email)<$min_num||strlen($str_email)>$max_num){
            alert_back('Email长度不合法');
        }
    return mysql_string($str_email);
}
function check_qq($str_qq){
    if (empty($str_qq)){
        return null;
    }else{
        //参考QQ：390322157
        if (!preg_match('/^[1-9]{1}[0-9]{4,9}$/',$str_qq)){
            alert_back('QQ填写不合格');
        }
        return $str_qq;
    }
}
function check_url($str_url,$max_num){
    if (empty($str_url)||($str_url=='http://')){
        return null;
    }else{
        //http://www.psy520.cn
        if (!preg_match('/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/',$str_url)){
            alert_back('网址格式不正确');
        }
        if (strlen($str_url)>$max_num){
            alert_back('网址地址长度太长');
        }
        return $str_url;
    }
}
function check_vcode($input_code,$output_code){
    if ($input_code!=$output_code){
        alert_back('验证码不正确');
    }
}
function check_sex($str_sex){
    return mysql_string($str_sex);
}
function check_face($str_face){
    return mysql_string($str_face);
}
function check_content($content,$min_num,$max_num){
   if( mb_strlen($content,'utf-8')<$min_num||mb_strlen($content,'utf-8')>$max_num){
       alert_back('短信内容不能小于'.$min_num.'位或大于'.$max_num.'位');
   }
   return $content;
}
function check_article_title($title,$min_num,$max_num){
    if( mb_strlen($title,'utf-8')<$min_num||mb_strlen($title,'utf-8')>$max_num){
        alert_back('帖子标题不能小于'.$min_num.'位或大于'.$max_num.'位');
    }
    return $title;
}
function check_article_content($content,$min_num,$max_num){
    if( mb_strlen($content,'utf-8')<$min_num||mb_strlen($content,'utf-8')>$max_num){
        alert_back('帖子内容不能小于'.$min_num.'位或大于'.$max_num.'位');
    }
    return $content;
}
function check_autograph($content,$max_num){
    if(mb_strlen($content,'utf-8')>$max_num){
        alert_back('个性签名不能大于'.$max_num.'位');
    }
    return $content;
}