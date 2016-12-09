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
require_once ROOT_PATH.'includes\global.func.php';
require_once ROOT_PATH.'includes\mysql.func.php';

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
