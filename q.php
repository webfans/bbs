<?php
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','q');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if (isset($_GET['num']) && isset($_GET['path'])){
    if (!is_dir(ROOT_PATH.$_GET['path'])){
        alert_back('非法操作');
    }
}else{
    alert_back('非法登录');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <script type="text/javascript" src="js/register.js"></script>
    <script type="text/javascript" src="js/qopener.js"></script>
    <!--<title>Q贴图选取页面</title>-->
</head>
<body>
<div id="q">
    <h3>选择Q贴图</h3>
    <dl>
        <?php foreach (range(1,$_GET['num']) as $_num){?>
        <dd><img src="<?php echo $_GET['path'].$_num?>.gif" alt="<?php echo $_GET['path'].$_num?>.gif" title="头像<?php echo $_num;?>"></dd>
        <?php }?>

    </dl>

</div>
</body>
</html>