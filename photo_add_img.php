<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','photo_add_img');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/photo_add_img.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
    <h2>上传照片</h2>
    <form method="post" action="?action=adddir" name="addphotodir">
    <dl>
        <dd>图片名称：<input type="text" name="photoname" class="text"> </dd>
        <dd>图片地址：<input type="text" name="photourl" class="text" readonly><a href="javascript:;" id="upload">上传</a></dd>
        <dd>图片描述：<textarea name='photocontent'></textarea></dd>
        <dd><input type="submit" name="photosubmit" class="submit" value="添加照片"></dd>
    </dl>
    </form>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>