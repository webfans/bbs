<?php
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','article');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//#读出数据
if (isset($_GET['id'])){
    //ID存在，且数据库里有合法数据
    $rows=fetch_array("select * from bbs.bbs_article where art_id='{$_GET['id']}'");
    //数据存在
    if (!!$rows){
      //创建一个数组存放数据
        $_html=array();
        $_html['username']=$rows['art_username'];
        $_html['type']=$rows['art_type'];
        $_html['title']=$rows['art_title'];
        $_html['content']=$rows['art_content'];
        $_html['date']=$rows['art_date'];
        $_html['readcount']=$rows['art_readcount'];
        $_html['comment']=$rows['art_comment'];
        //拿出用户名去查用户信息(email,url)
        $_rows=fetch_array("select u_id,u_sex,u_face,u_email,u_url from bbs.bbs_user WHERE u_username='{$_html['username']}'");
        if (!!$_rows){
           //提取用户信息
           $_html['userid']=$_rows['u_id'];
           $_html['sex']=$_rows['u_sex'];
           $_html['face']=$_rows['u_face'];
           $_html['email']=$_rows['u_email'];
           $_html['url']=$_rows['u_url'];
           $_html=html_spec($_html);
        }else{
            //这个用户已经被删除
        }
    }else{
        alert_back('文章ID不存在');
    }
}else{
    alert_back('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/blog.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>帖子页面</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="article">
    <h2>帖子</h2>
    <div id="subject">
        <dl>
            <dd class="user"><?php echo $_html['username'];?>(<?php echo $_html['sex'];?>)</dd>
            <dt><img src="<?php echo $_html['face'];?>" alt="root"/></dt>
            <dd class="message"><a name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
            <dd class="friend"><a name="friend" title="<?php echo $_html['userid']?>">加好友</a></dd>
            <dd class="guest">写留言</dd>
            <dd class="flower"><a name="flower" title="<?php echo $_html['userid']?>">"给他送花</a></dd>
            <dd class="email"><a href="mailto:<?php echo $_html['email'];?>">邮件:<?php echo $_html['email'];?></a></dd>
            <dd class="url"><a href="<?php echo $_html['url'];?>" target="_blank">网址:<?php echo $_html['url'];?></a></dd>
        </dl>
        <div class="content">
            <div class="user">
                <span>1#</span><?php echo $_html['username']?>| 发表于：<?php echo $_html['date']?>
            </div>
            <h3>主题：<?php echo $_html['title']?> <img src="images/icon<?php echo $_html['type']?>.gif" alt="icon"> </h3>
            <div class="detail">
                <?php echo $_html['content']?>
            </div>
            <div class="read">
                阅读量：(<?php echo $_html['readcount']?>) 评论量：(<?php echo $_html['comment']?>)
            </div>    　
        </div>

    </div>
    <p class="line"></p>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>