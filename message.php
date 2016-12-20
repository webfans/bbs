<?php
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','message');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

/*foreach (range(1,9) as $number){
    echo $number.'|';
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <script type="text/javascript" src="js/register.js"></script>
    <script type="text/javascript" src="js/opener.js"></script>
    <title>发短信</title>
</head>
<body>
<div id="message">
    <h2>写短信</h2>
    <form action="">
        <dl>
            <dd><input type="text" class="text"></dd>
            <dd><textarea type="content"></textarea></dd>
            <dd>验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/> </dd>
            <dd><input type="submit" name="submit" class="submit" value="发送短信"> </dd>
        </dl>
    </form>
</div>
</body>
</html>