<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','manage');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能管理员访问
admin_login();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/member_modify.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>后台管理</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多 头部文件
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="member">
    <div id="member_sidebar">
        <?php require ROOT_PATH."includes/manage.inc.php";?>
    </div>
    <div id="member_main">
        <h2>管理导航</h2>
        <!--?action=modify提交给自己的简写-->
        <form action="?action=modify" method="post" name="modify">
            <dl>
                <dd>服务器主机名：<?php echo $_SERVER['SERVER_NAME']?></dd>
                <dd>服务器版本：<?php echo PHP_OS;?></dd>
                <dd>通信协议：<?php echo $_SERVER['SERVER_PROTOCOL']?></dd>
                <dd>服务器IP：<?php echo $_SERVER['SERVER_ADDR']?></dd>
                <dd>服务器端口：<?php echo $_SERVER['SERVER_PORT']?></dd>
                <dd>客户端IP：<?php echo $_SERVER['REMOTE_ADDR']?></dd>
                <dd>客户端端口：<?php echo $_SERVER['REMOTE_PORT']?></dd>
                <dd>管理员邮箱：<?php echo $_SERVER['SERVER_ADMIN']?></dd>
                <dd>HOSTS头部：<?php echo $_SERVER['HTTP_HOST']?></dd>
                <dd>服务器主目录：<?php echo $_SERVER['DOCUMENT_ROOT']?></dd>
                <dd>服务器系统盘：<?php echo $_SERVER["SystemRoot"]?></dd>
                <dd>脚本执行的绝对路径：<?php echo $_SERVER['SCRIPT_FILENAME']?></dd>
                <dd>Apache&PHP：<?php echo $_SERVER['SERVER_SOFTWARE']?></dd>
            </dl>
        </form>
    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>