<?php
session_start();
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','register');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
#测试数据时候能插入成功
#mysql_query("insert into bbs_user(u_question) VALUES('howoldareu')") or  die('sql执行错误'.mysql_error());
//判断是否提交了数据 数据提交到本页
if ($_GET['action']=='register'){
    //include 在需要时引入
    include ROOT_PATH.'includes/register.func.php';
    //为了防止恶意注册和跨
    //验证码正确才进行数据授受
    check_vcode($_POST['vcode'],$_SESSION['vcode']);
    //授受数据
    //创建一个空数据用来放提交过来的合法的数据
    $clean=array();
    //可以通过一个唯一标识符来防止恶意注册，伪装表单跨站攻击
    //这个唯一标识符第二个用途，就是登录Cookie验证
    $clean['uniqid']=check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
    //active 也是一个唯一标识符，用来激活用户，方可登录
    $clean['active']=sha1_uniqid();

    $clean['username']=check_username($_POST['username'],2,20);
    $clean['password']=check_pwd($_POST['password'],$_POST['notpassword'],6);
    $clean['question']=check_question($_POST['question'],2,20);
    $clean['sex']=check_sex($_POST['sex']);
    $clean['face']=check_face($_POST['textface']);
    $clean['answer']=check_answer($_POST['question'],$_POST['answer'],2,20);
    $clean['email']=check_emial($_POST['email'],6,20);
    $clean['qq']=check_qq($_POST['qq']);
    $clean['url']=check_url($_POST['url'],40);
    #print_r($clean);
    //在插入数据前，先从数据库里查询实是否有相同记录，有则不允许插入
    $sql="select u_username from bbs_user where u_username='{$clean['username']}'";
    is_repeat($sql,'已经存在此用户名');

    mysql_query(
               "insert into bbs_user(
                                     u_uniqid,
                                     u_active,
                                     u_username,
                                     u_password,
                                     u_question,
                                     u_sex,
                                     u_face,
                                     u_answer,
                                     u_email,
                                     u_qq,
                                     u_url,
                                     u_regtime,
                                     u_lasttime,
                                     u_lastip
                                     )                               
                               VALUES(
                                      '{$clean['uniqid']}',
                                      '{$clean['active']}',
                                      '{$clean['username']}',
                                      '{$clean['password']}',
                                      '{$clean['question']}',
                                      '{$clean['sex']}',
                                      '{$clean['face']}',
                                      '{$clean['answer']}',
                                      '{$clean['email']}',
                                      '{$clean['qq']}',
                                      '{$clean['url']}',
                                       NOW(),
                                       NOW(),
                                      '{$_SERVER['REMOTE_ADDR']}'
                                     )"
               ) or die('sql执行错误'.mysql_error());
   //mysql_query("insert into bbs_user(u_question,u_face) values('{$clean['question']}','{$clean['face']}')") or
   //die('sql执行错误'.mysql_error());
    if (affetched_rows()==1){
        //跳转到激活页
        //location('恭喜你注册成功','active.php?action=ok&active='.$clean['active']);//加上OK不用点击，直接激动不知道为什么？
        location('恭喜你注册成功','active.php?active='.$clean['active']);
        //清空session,腾出内存
        session_d();
        close();
    }
    else{
        location('注册失败','register.php');
        session_d();
        close();
    }

    mysql_close();

}else{
    //sha1_uniqid(){return mysql_string(sha1(uniqid(rand(),true)));}
    //sha1(uniqid(rand(),true)生成唯一标识符，每个电脑都一样，
    //样可以判断这个标识符提交前后是否变化，来确保数据是本页面提交的；如果变化了，就不是本页提交的
    $_SESSION['uniqid']=$uniqid=sha1_uniqid();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/register.js"></script>

    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>注册页</title>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
<div id="register">
    <h2>用户注册</h2>
    <form action="register.php?action=register" method="post" name="register">
        <!--uniqid这个隐藏域用来生成唯一标识符-->
        <input type="hidden" name="uniqid" value="<?php echo $uniqid;?>"/>
        <dl>
            <dt>请认真填写一下内容</dt>
            <dd>用户名：&emsp;<input type="text" name="username" class="text">(*必填，至少2位)</dd>
            <dd>密&emsp;&emsp;码：<input type="password" name="password" class="text">(*必填，至少6位)</dd>
            <dd>确认密码：<input type="password" name="notpassword" class="text">(*必填，至少2位)</dd>
            <dd>密码提示：<input type="text" name="question" class="text">(*必填，至少2位)</dd>
            <dd>密码回答：<input type="text" name="answer" class="text">(*必填，至少2位)</dd>
            <dd>
                性 别：<input type="radio" name="sex" value="男" checked="checked">男
                <input type="radio" name="sex" value="女" checked="checked">女
            </dd>
            <dd class="face"><input type="hidden" name="textface" id="faceimg_value" value="images/face/m01.gif"/> <img src="images/face/m01.gif" alt="头像选择" id="faceimg"> </dd>
            <dd>电子邮件：<input type="text" name="email" class="text">(*必填，用以激活)</dd>
            <dd>扣&emsp;&emsp;扣：<input type="text" name="qq" class="text"> </dd>
            <dd>个人网站：<input type="text" name="url" class="text" value="http://"> </dd>
            <dd>验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/> </dd>
            <dd><input type="submit" name="submit" class="submit" value="注册"> </dd>

        </dl>
    </form>
</div>
<?php require ROOT_PATH.'includes/footer.inc.php'; ?>
</body>
</html