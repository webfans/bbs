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
//#1 修改帖子数据
if ($_GET['action']=='modify'){
    //include 在需要时引入
    include ROOT_PATH.'includes/check.func.php';
    #安全验证#
    //1.验证码验证
    //check_vcode($_POST['vcode'],$_SESSION['vcode']);
    //2.对比唯一标识符
    if (!!$row=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")){
        safe_uniqid($row['u_niqid'],$_SESSION['uniqid']);
    }else{alert_back('您非法伪造了Cookie');}
    $clean=array();
    $clean['id']=$_POST['id'];//就是贴子id
    $clean['type']=$_POST['article_type'];
    $clean['title']=check_article_title($_POST['title'],10,40);
    $clean['content']=check_article_content($_POST['content'],15,10000);
    $clean=mysql_string($clean);
    //数据库更新操作
    $update_sql="update bbs_article set 
                                    art_type='{$clean['type']}',
                                    art_title='{$clean['title']}',
                                    art_content='{$clean['content']}',
                                    art_lastmodify=NOW()
                                    WHERE art_id='{$clean['id']}'
                 ";
    query($update_sql);
    if (mysql_affected_rows()==1){
        //帖子修改成功后，拿着$clear['id'],再去数据库查询，以验证该帖子是主题贴还是回复贴
        $verify_sql="select art_reid from bbs.bbs_article WHERE art_id='{$clean['id']}'";
        $role=fetch_array($verify_sql);
        //如果是回复贴，则将它的art_reid(也即它回复的主题贴的id)赋给$clearn['id']
        //article.php?id的值必须是主题贴的id,没有这段帖子身份判断，如果是 回复贴修改，修改成功后，就会因为不是主题贴id,而alert_back('文章ID不存在');
        if ($role['art_reid']!==0){
            $clean['id']=$role['art_reid'];
        }
        location('帖子修改成功','article.php?id='.$clean['id']);
         //session_d();
        close();
    }else{
        alert_back('帖子修改失败');
         //session_d();
        close();
    }
}
#调换#1和#2的顺序，会出现：$_GET['id']不存在，请不要非法操作# #想不明白这是为什么#
//#2 读取数据
if (isset($_GET['id'])){
    //这个get过来的id 分两种 subject_id（主题贴） 和replay_id(回复贴)，分别读出主题贴和回复贴数据
    $rows=fetch_array("select art_id,art_username,art_type,art_title,art_content from bbs.bbs_article where art_id='{$_GET['id']}'");
    //数据存在
    if (!!$rows){
        //创建一个数组存放数据
        $_html=array();
        $_html['id']=$_GET['id'];
        $_html['type']=$rows['art_type'];
        $_html['username']=$rows['art_username'];
        $_html['title']=$rows['art_title'];
        $_html['content']=$rows['art_content'];
        $_html=html_spec($_html);
        //判断权限 只能修改自己的帖子，不能修改别人的
        if ($_COOKIE['username']!=$_html['username']){
            alert_back('您无权修改别人的帖子');
        }
    }else{
        alert_back('不存在此帖子');
    }
}
else{
    alert_back('请不要非法操作');
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
    <!--<title>修改帖子</title>-->
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
<div id="post">
    <h2>修改帖子</h2>
    <form action="?action=modify" method="post" name="modify_article">
        <input type="hidden" name='id' value="<?php echo $_html['id'];?>"/>
        <dl>
            <dt>请认真修改帖子内容</dt>
            <dd>类型
                <?php
                foreach (range(1,6) as $num){
                    if ($num==$_html['type']){
                        echo '<label for="type'.$num.'"><input id="type'.$num.'"type="radio" name="article_type" value="'.$num.'" checked>';
                    }else{
                        echo '<label for="type'.$num.'"><input type="radio" name="article_type" value="'.$num.'">';
                    }
                    echo '<img src="images/icon'.$num.'.gif" alt="帖子类型"></label>';
                }
                ?>
            </dd>
            <dd>标题：&emsp;<input type="text" name="title" class="text" value="<?php echo $_html['title']?>">(*必填，10-40位)</dd>
            <dd id="q">贴图：<a href="javascript:;">Q贴图1</a><a href="javascript:;">Q贴图2</a><a href="javascript:;">Q贴图3</a> </dd>
            <dd>
                <?php require ROOT_PATH.'includes/ubb.inc.php'; ?>
                <textarea  name="content" rows="10"><?php echo $_html['content']?></textarea></dd>
            <dd>
                验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/>
                <input type="submit" name="submit" class="submit" value="修改提交">
            </dd>

        </dl>
    </form>
</div>
<?php require ROOT_PATH.'includes/footer.inc.php'; ?>
</body>
</html