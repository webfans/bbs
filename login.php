<?php
session_start();
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','login');
//
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//登录状态下 防止注册
block_login_reg();
if (@$_GET['action']=='login'){
    //include 在需要时引入
    include ROOT_PATH.'includes/login.func.php';
    //验证码验证
    check_vcode($_POST['vcode'],$_SESSION['vcode']);
    //授受数据
    //创建一个空数据用来放提交过来的合法的数据
    $clean=array();
    $clean['username']=check_username($_POST['username'],2,20);
    $clean['password']=check_pwd($_POST['password'],6);
    $clean['time']=check_time($_POST['time']);
    //print_r($clean);
    //从数据库调取数据开始比对
    $sql="select u_username,u_uniqid,u_level from bbs_user WHERE u_username='{$clean['username']}'AND u_password='{$clean['password']}' AND u_active='' limit 1";
    if (!!$rows=fetch_array($sql)){
        //登录成功后写入cookie,并记录IP,登录时间和次数
        query("UPDATE bbs_user SET
                                u_lasttime=NOW(),
                                u_lastip='{$_SERVER['REMOTE_ADDR']}',
                                u_loginnum=u_loginnum+1
                              WHERE 
                                 u_username='{$rows['u_username']}'
                              ");

        setlogincookies($rows['u_username'],$rows['u_uniqid'],$clean['time']);
        if ($rows['u_level']==1){
            $_SESSION['admin']=$rows['u_username'];
        }
        location('登录成功','member.php');
        close();
        // //session_d();
    }
    else{
        session_destroy();
        location('用户名和密码错误或者该用户名未激活','login.php');
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--根据SCRIPT调用相应的CSS-->
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <script type="text/javascript" src="js/vcode.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
    <title>PSY520多用户留言系统-激活页</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="login">
    <h2>用户登录</h2>
    <form action="login.php?action=login" method="post" name="register">
        <dl>
            <dt></dt>
            <dd>用户名：&emsp;<input type="text" name="username" class="text"></dd>
            <dd>密&emsp;&emsp;码：<input type="password" name="password" class="text"></dd>
            <dd>保&emsp;&emsp;留：
                <input type="radio" name="time" value="0"  class="radio" checked>不保留
                <span style="margin-left: 10px;">保留</span>
                <input type="radio" name="time" value="1"  class="radio">一天
                <input type="radio" name="time" value="2"  class="radio">一周
                <input type="radio" name="time" value="3"  class="radio">一月
            </dd>
            <dd>验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/> </dd>
            <dd>
                <input type="submit" name="submit" class="submit_btn" value="登录">
                <input type="button" name="loacation" class="submit_btn location" value="注册" id="location">
            </dd>
        </dl>
    </form>



</div>
<?php
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>

