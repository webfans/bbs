<?php
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','face');
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
    <title>头像选取页面</title>
</head>
<body>
<div id="face">
    <h3>选择头像</h3>
    <dl>
        <?php foreach (range(1,9) as $num){?>
        <dd><img src="images/face/m0<?php echo $num;?>.gif" alt="images/face/m0<?php echo $num;?>.gif" title="头像<?php echo $num;?>"></dd>
        <?php }?>

    </dl>
    <dl>
        <?php foreach (range(10,64) as $num){?>
        <dd><img src="images/face/m<?php echo $num;?>.gif" alt="images/face/m<?php echo $num;?>.gif" title="头像<?php echo $num;?>"></dd>
        <?php }?>

    </dl>
</div>
</body>
</html>