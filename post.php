<?php
session_start();
//指定一个常量 用来授权能不能调用文件
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','post');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//发贴前必须登录
if (!isset($_COOKIE['username'])){
    location('发贴前必须登录哦','login.php');
}
if (@$_GET['action']=='post'){
    //include 在需要时引入
    include ROOT_PATH.'includes/check.func.php';
    #安全验证#
    //1.验证码验证
    //check_vcode($_POST['vcode'],$_SESSION['vcode']);
    //2.对比唯一标识符
    if (!!$row=fetch_array("select u_uniqid,u_posttime from bbs_user where u_username='{$_COOKIE['username']}'")){
      safe_uniqid($row['u_niqid'],$_SESSION['uniqid']);
    }
    //验证一下是否是在规定的时间内发帖，禁止在$sys['postlimit']60s内发帖
    //1#// post_checktime(time(),$_COOKIE['first_posttime']);
    global $sys;
    post_checktime(time(),$row['u_posttime'],$sys['postlimit']);
    //接收帖子数据
    $clean=array();
    $clean['username']=$_COOKIE['username'];
    $clean['type']=$_POST['article_type'];
    $clean['title']=check_article_title($_POST['title'],5,40);
    $clean['content']=check_article_content($_POST['content'],15,10000);
    $clean=mysql_string($clean);
    //写入数据库
    query("insert into bbs.bbs_article(
                                       art_username,
                                       art_type,
                                       art_title,
                                       art_content,
                                       art_date
                                       ) 
                                 VALUES (
                                        '{$clean['username']}',
                                        '{$clean['type']}',
                                        '{$clean['title']}',
                                        '{$clean['content']}',
                                        NOW()
                                        )
          ");
    if (affetched_rows()==1){
        //获取刚刚新增的ID
        $clean['id']=mysql_insert_id();
        //记录最近发贴时间 有两种方法，1#为cookie 2#二为写入数据库
        //1#setcookie('first_posttime',time());
        //2#将发主题贴的时间写入数据库
        $clean['posttime']=time();
        query("update bbs_user set u_posttime='{$clean['posttime']}' WHERE u_username='{$_COOKIE['username']}'");
        location('发贴成功','article.php?id='.$clean['id']);
        //清空session,腾出内存
        // // //session_d();
        close();
    }
    else{
        alert_back('发贴失败');
         // //session_d();
        close();
    }

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/post.js"></script>

    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>发表帖子</title>-->
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
<div id="post">
    <h2>发贴</h2>
    <form action="?action=post" method="post" name="postarticle">
        <!--uniqid这个隐藏域用来生成唯一标识符-->
        <input type="hidden" name="uniqid" value="<?php echo $uniqid;?>"/>
        <dl>
            <dt>请认真填写一下内容</dt>
            <dd>类型
                <?php
                foreach (range(1,6) as $num){
                    if ($num==1){
                        echo '<label for="type'.$num.'"><input id="type'.$num.'"type="radio" name="article_type" value="'.$num.'" checked>';
                    }else{
                        echo '<label for="type'.$num.'"><input type="radio" name="article_type" value="'.$num.'">';
                    }
                    echo '<img src="images/icon'.$num.'.gif" alt="帖子类型"></label>';
                }
                ?>
            </dd>
            <dd>标题：&emsp;<input type="text" name="title" class="text">(*必填，2-40位)</dd>
            <dd id="q">贴图：<a href="javascript:;">Q贴图1</a><a href="javascript:;">Q贴图2</a><a href="javascript:;">Q贴图3</a> </dd>
            <dd>
                <?php require ROOT_PATH.'includes/ubb.inc.php'; ?>
                <textarea  name="content" rows="10"></textarea></dd>
            <dd>
                <?php
                  if ($sys['vcode']==1){
                    echo  '验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/>';
                }
                ?>
                <input type="submit" name="submit" class="submit" value="发送文章">
            </dd>

        </dl>
    </form>
</div>
<?php require ROOT_PATH.'includes/footer.inc.php'; ?>
</body>
</html