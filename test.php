<?php
session_start();
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','register');
//引入公共文件

require dirname(__FILE__).'/includes/common.inc.php';

//echo sha1_uniqid();
$d['active']=sha1_uniqid();
connect();
selectdb();
######错误无法插入完整插入u_acitve,u_uniqid#####原因是 数据库 数据类型错误的设为INT 应该为CHAR
//$sql="insert into bbs_user (u_active) values ('{$d['active']}')";
echo '<br/>';

print_r($d['active']);
echo '<br/>';
query(
    "insert into bbs_user(
                                     u_uniqid,
                                     u_active,
                                     u_username,
                                     u_password,
                                     u_question,
                                     u_sex,
                                     u_face,
                                     u_answer,
                                     u_email,
                                     u_qq,
                                     u_url,
                                     u_regtime,
                                     u_lasttime,
                                     u_lastip
                                     )                               
                               VALUES(
                                      '12345fdgg',
                                      '{$d['active']}',
                                      '123456',
                                      '12345678',
                                      '1234567',
                                      '男',
                                      '123jddd',
                                      '123456',
                                      'cclove@123.com',
                                      '123456789',
                                      'www.baidu.com',
                                       NOW(),
                                       NOW(),
                                      '{$_SERVER['REMOTE_ADDR']}'
                                     )"
) or die('sql执行错误'.mysql_error());
//query($sql);
if (affetched_rows()==1){
    location('数据测试插入成功','login.php');
}else {
    location('数据测试插入失败', 'reisger.php');
}
#######################################################################################
//$update_sql="update bbs_user set u_active=null where u_acitve='$active'";


