<?php
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','index');
//
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//echo ROOT_PATH;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>PSY520多用户留言系统</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="list">
    <h2>帖子列表</h2>
</div>
<div id="user">
    <h2>新进会员</h2>
</div>
<div id="pics">
    <h2>最新图片</h2>
</div>
<?php
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>