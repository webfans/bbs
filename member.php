<?php
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','member');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (isset($_COOKIE['username'])){
    //获取数据集
    $sql="select * from bbs_user where u_username='{$_COOKIE['username']}'";;
    $rows=fetch_array($sql);
    //如果有数据，,或者数据库没有此用户，伪造用户cookie
    if (!!$rows){
       $html=array();
       $html['username']=$rows['u_username'];
       $html['sex']=$rows['u_sex'];
       $html['face']=$rows['u_face'];
       //$html['email']=$rows['u_email']."<html>";//转义测试
       $html['email']=$rows['u_email'];
       $html['url']=$rows['u_url'];
       $html['qq']=$rows['u_qq'];
       $html['autograph']=$rows['u_autograph'];
       $html['regtime']=$rows['u_regtime'];
       //一次性转义数组，也可以分别每一行
       $html=html_spec($html);
       switch ($rows['u_level']){
           case 0:
               $html['level']='普通会员';
               break;
           case 1:
               $html['level']='管理员';
               break;
           default:
               $html['level']='Guest';
       }
    }else{
        alert_back('此用户不存在');
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
    <title>个人中心</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多 头部文件
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="member">
    <div id="member_sidebar">
        <?php require ROOT_PATH."includes/member.inc.php";?>
    </div>
    <div id="member_main">
        <h2>会员管理中心</h2>
        <dl>
            <dd>用 户 名:<?php echo $html['username'];?></dd>
            <dd>性&emsp;&emsp;别：<?php echo $html['sex'];?></dd>
            <dd>头&emsp;&emsp;像：<?php echo $html['face'];?></dd>
            <dd>电子邮件：<?php echo $html['email'];?></dd>
            <dd>主&emsp;&emsp;页：<?php echo $html['url'];?></dd>
            <dd>Q&emsp;&emsp;&emsp;Q:<?php echo $html['qq'];?></dd>
            <dd>个性签名:<?php echo $html['autograph'];?></dd>
            <dd>注册时间：<?php echo $html['regtime'];?></dd>
            <dd>身&emsp;&emsp;份：<?php echo $html['level'];?></dd>
        </dl>
    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>