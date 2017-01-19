<?php
session_start();

//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量用来指定本页
define('SCRIPT','thumb');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//缩略图生成
if (isset($_GET['filename'])&&isset($_GET['zoom'])){
    thumb($_GET['filename'],$_GET['zoom']);
}
