<?php
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','active');
//
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//开始激活操作

//没有u_active id禁止访问active.php
/*if (!isset($_GET['active'])){
    alert_back('没有激活ID,非法操作');
}*/
if (isset($_GET['action']) && isset($_GET['active']) && $_GET['action']=='ok'){
    $active=mysql_string($_GET['active']);
    echo $active;
    $sql="select u_active from bbs_user WHERE u_active='$active' LIMIT 1";
    if (fetch_array($sql)) {
        //将u_active设置为空
        $update_sql = "update bbs_user set u_active=NULL where u_active='$active' LIMIT 1";
        query($update_sql);
        if (affetched_rows() == 1) {
            close();
            location('账号激活成功', 'login.php');
        } else {
            close();
            location('账号激活失败', 'register.php');
        }
    }
    else {
       alert_back('非法操作');
    }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--根据SCRIPT调用相应的CSS-->
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>PSY520多用户留言系统-激活页</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="active">
    <h2>激活帐号</h2>
    <p>本页面是为了模拟激活你的账户，请点击以下的连接以激活</p>
    <!--记住此处href双引号曾经犯过的错误 href="active.php?action=ok&amp;active="=<php echo $_GET['active']> -->
    <p><a href="active.php?action=ok&amp;active=<?php echo $_GET['active']?>"><?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']?>?action=ok&amp;active=<?php echo $_GET['active'];?></a></p>
</div>
<?php
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>