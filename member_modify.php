<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','member_modify');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//#修改用户信息#
if (@$_GET['action']=='modify'){
    include ROOT_PATH.'includes/check.func.php';
    //验证码
    check_vcode($_POST['vcode'],$_SESSION['vcode']);

    // 如果cookie存在，才允许提交修改数据验证
    if (!!$rows=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")){
        //为了防止Cookie伪造，还要比对一下唯一标识符uniqid
        safe_uniqid($rows['u_uniqid'],$_COOKIE['uniqid']);

        $clean=array();
        $clean['password']=check_modify_pwd($_POST['password'],6);
        $clean['sex']=check_sex($_POST['sex']);
        $clean['face']=check_face($_POST['face']);
        $clean['email']=check_emial($_POST['email'],6,30);
        $clean['qq']=check_qq($_POST['qq']);
        $clean['url']=check_url($_POST['url'],40);
        $clean['switch']=$_POST['switch'];
        $clean['autograph']=check_autograph($_POST['autograph'],200);
        //print_r($clean);
        //开始修改资料
        if (empty($clean['password'])){
            //如果密码为空
            query("UPDATE bbs_user SET 
                                     u_sex='{$clean['sex']}',
                                     u_face='{$clean['face']}',
                                     u_email='{$clean['email']}',
                                     u_qq='{$clean['qq']}',
                                     u_url='{$clean['url']}',
                                     u_switch='{$clean['switch']}',
                                     u_autograph='{$clean['autograph']}'
                              WHERE u_username='{$_COOKIE['username']}'  
              ");
        }else{
            query("UPDATE bbs_user SET 
                                     u_password='{$clean['password']}',
                                     u_sex='{$clean['sex']}',
                                     u_face='{$clean['face']}',
                                     u_email='{$clean['email']}',
                                     u_qq='{$clean['qq']}',
                                     u_url='{$clean['url']}',
                                     u_switch='{$clean['switch']}',
                                     u_autograp='{$clean['autograph']}',
                              WHERE u_username='{$_COOKIE['username']}'  
              ");
        }
        if (affetched_rows()==1){
            //跳转到激活页
            //location('恭喜你注册成功','active.php?action=ok&active='.$clean['active']);//加上OK不用点击，直接激动不知道为什么？
            location('恭喜你修改成功','member.php');
            //清空session,腾出内存
             //session_d();
            close();
        }
        else{
            location('没有任何被修改','member_modify.php');
             //session_d();
            close();
        }
    }


}
//#显示用户信息#
//是否正常登录
if (isset($_COOKIE['username'])){
    //获取数据集
    $sql="select * from bbs_user where u_username='{$_COOKIE['username']}'";;
    $rows=fetch_array($sql);
    //如果有数据，,或者数据库没有此用户，伪造用户cookie
    if ($rows){
       $html=array();
       $html['username']=$rows['u_username'];
       $html['sex']=$rows['u_sex'];
       $html['face']=$rows['u_face'];
       //$html['email']=$rows['u_email']."<html>";//转义测试
       $html['email']=$rows['u_email'];
       $html['url']=$rows['u_url'];
       $html['qq']=$rows['u_qq'];
       $html['regtime']=$rows['u_regtime'];
       $html['switch']=$rows['u_switch'];
       $html['autograph']=$rows['u_autograph'];

       //一次性转义数组，也可以分别每一行
       $html=html_spec($html);
        //性别选择
        if ($html['sex']=='男'){
            $html['sex_html']='<input type="radio" name="sex" value="男" checked/>男<input type="radio" name="sex" value="女"/>女';
        }else{
            $html['sex_html']='<input type="radio" name="sex" value="男" />男<input type="radio" name="sex" value="女" checked/>女';
        }
        //头像选择
        //！注意：这里必须是【.=】,即追加模式
        $html['face_html']='<select name="face">';
            foreach (range(1,9) as $num){
                $html['face_html'].='<option value="images/face/m0'.$num.'.gif">m0'.$num.'.gif</option>';
            }
            foreach (range(10,64) as $num){
                $html['face_html'].='<option value="images/face/m'.$num.'.gif">m'.$num.'.gif</option>';
            }
        $html['face_html'].='</select>';
        //会员级别
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
       //签名开关
        if ($html['switch']==0){
           $html['switch_html']='<input type="radio" name="switch" value="1">启用  <input type="radio" name="switch" value="0" checked>关闭';
        }else{
            $html['switch_html']='<input type="radio" name="switch" value="1" checked>启用  <input type="radio" name="switch" value="0">关闭';
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
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/member_modify.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>会员管理中心</title>-->
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
        <!--?action=modify提交给自己的简写-->
        <form action="?action=modify" method="post" name="modify">
            <dl>
                <dd>用 户 名:<?php echo $html['username'];?></dd>
                <dd>密&emsp;&emsp;码：<input type="password" name="password">(*缺省则不修改)</dd>
                <dd>性&emsp;&emsp;别：<?php echo $html['sex_html'];?></dd>
                <dd>头&emsp;&emsp;像：<?php echo $html['face_html'];?></dd>
                <dd>电子邮件：<input type="text" name="email" value="<?php echo $html['email'];?>" /></dd>
                <dd>主&emsp;&emsp;页：<input type="text" name="url" value="<?php echo $html['url'];?>" /></dd>
                <dd>Q&emsp;&emsp;&emsp;Q:<input type="text" name="qq" value="<?php echo $html['qq'];?>" /></dd>
                <dd>身&emsp;&emsp;份：<?php echo $html['level'];?></dd>
                <dd>
                    个性签名：<?php echo $html['switch_html'];?>&nbsp;(支持UBB)
                    <p><textarea name="autograph" rows="3" cols="25"><?php echo $html['autograph']?></textarea></p>
                </dd>
                <dd>验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/> </dd>
                <dd><input type="submit" name="submit" class="submit" value="修改资料"> </dd>
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