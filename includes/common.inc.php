<?php
if (!defined('IN_TG')){
    exit('Access Denied');
}
//转换硬路径常量
define('ROOT_PATH',substr(dirname(__FILE__),0,-8));
//echo ROOT_PATH;
//创建一个自动转义状态的常量
define('GPC',get_magic_quotes_gpc());
//拒绝PHP低版本
if (PHP_VERSION<'4.1.0'){
    exit('Version is too low');
}
//引入核心函数库
//在winodws下 最初这样写includes\global.func.php 在linuxq情况下就会引起 require 出错
require_once ROOT_PATH.'includes/global.func.php';
require_once ROOT_PATH.'includes/mysql.func.php';

define('START_TIME',runtime());

//数据库连接
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PWD',398692315);
define('DB_NAME','bbs');

//初始化数据库
connect(); //数据库连接
selectdb(); //选择用户数据库
set_names(); //设置字符集

//短信提醒
//统计未读取的短信数
@$sql="select count(m_id) as counts from bbs_message where m_state=0 and m_touser='{$_COOKIE['username']}'";
$result=query($sql);
$message=fetch_array_list($result);//或者$message=fetch_array($sql);
//print_r($message);
if (empty($message['counts'])){
    $msg='<strong class="read"><a href="member_message.php"><span>(0)</span></a> </strong>';
}else{
    $msg='<strong  class="noread"><a href="member_message.php"><span>('.$message['counts'].')</span></a></strong>';
}
