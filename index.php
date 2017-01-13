<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','index');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//echo ROOT_PATH;
//读取用户信息xml
$_html=html_spec(get_xml('userinfo.xml'));

//分布模块 分页容错处理
//WHERE art_reid=0 表示帖子是主题贴，而不是别人回复的帖子
global $_pagenum,$_pagesize;
$count_sql="select art_id from bbs_article  WHERE art_reid=0";
paging_fault_tolerant($count_sql,10);

//从数据库读取数据
$sql="select art_id,art_type,art_title,art_content,art_readcount,art_comment
            from bbs_article 
            WHERE art_reid=0
            ORDER BY art_date DESC 
            LIMIT $_pagenum,$_pagesize";
//取得 数据集
$result=query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/blog.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>多用户留言系统</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="list">
    <h2>帖子列表</h2>
    <a href="post.php" class="post">发表文章</a>
    <ul class="article">
        <?php
        $artlist=array();
        while ($rows=fetch_array_list($result)){
            $artlist['id']=$rows['art_id'];
            $artlist['type']=$rows['art_type'];
            $artlist['title']=$rows['art_title'];
            $artlist['readcount']=$rows['art_readcount'];
            $artlist['comment']=$rows['art_comment'];
            echo '<li class="icon'.$artlist['type'].'"><em>阅读数(<strong>'.$artlist['readcount'].'</strong>) 评论数(<strong>'.$artlist['comment'].'</strong>)</em><a href="article.php?id='.$artlist['id'].'">'.summary($artlist['title'],12).'</a></li>';
        }
        ?>
    </ul>

        <?php
        //销毁数据集
        mysql_free_result($result);
        //调用分页函数
        paging('both');
        ?>

</div>
<div id="user">
    <h2>新进会员</h2>
    <dl>
        <dd class="user"><?php echo $_html['username'];?>(<?php echo $_html['sex'];?>)</dd>
        <dt><img src="<?php echo $_html['face'];?>" alt="root"/></dt>
        <dd class="message"><a name="message" title="<?php echo $_html['id']?>">发消息</a></dd>
        <dd class="friend"><a name="friend" title="<?php echo $_html['id']?>">加好友</a></dd>
        <dd class="guest">写留言</dd>
        <dd class="flower"><a name="flower" title="<?php echo $_html['id']?>">"给他送花</a></dd>
        <dd class="email"><a href="mailto:<?php echo $_html['email'];?>">邮件:<?php echo $_html['email'];?></a></dd>
        <dd class="url"><a href="<?php echo $_html['url'];?>" target="_blank">网址:<?php echo $_html['url'];?></a></dd>
    </dl>
</div>
<div id="pics">
    <h2>最新图片</h2>
</div>
<?php
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>