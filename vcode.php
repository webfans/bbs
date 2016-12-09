<?php
session_start();

//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);

//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

vcode();