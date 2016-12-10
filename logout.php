<?php
session_start();
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
//define('SCRIPT','logout');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//退出清空cookie
cookie_d();

