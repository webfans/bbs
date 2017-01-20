<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','photo_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能会员才能查看
if (!isset($_COOKIE['username'])){
    alert_back('请登录后使用上传');
}
//取得照片数据
if ($_GET['imgid']){
    if (!!$rows=fetch_array("select * from bbs.bbs_photo WHERE photo_id='{$_GET['imgid']}'")){
        //累积阅读量
        query("update bbs_photo set photo_readcount=photo_readcount+1 where photo_id='{$_GET['imgid']}'");

        $get_photo=array();
        $get_photo['id']=$rows['photo_id'];
        $get_photo['name']=$rows['photo_name'];
        $get_photo['url']=$rows['photo_url'];
        $get_photo['owner']=$rows['photo_owner'];
        $get_photo['readcount']=$rows['photo_readcount'];
        $get_photo['commentcount']=$rows['photo_commentcount'];
        $get_photo['content']=$rows['photo_content'];
        $get_photo['date']=$rows['photo_date'];
        $get_photo=html_spec($get_photo);
    }else{
        alert_back('不存在此相册');
    }
}else{
    alert_back('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/vcode.js"></script>
    <script type="text/javascript" src="js/article.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
    <h2>图片详情</h2>
    <dl class="detail">
        <dd class="name"><?php echo $get_photo['name']?></dd>
        <dt><img src="<?php echo $get_photo['url']?>"></dt>
        <dd>
            浏览量(<strong><?echo $get_photo['readcount']?></strong>)&nbsp;
            评论量(<strong><?php $get_photo['commentcount']?></strong>)&nbsp;
            上传者:<?php echo $get_photo['owner']?>&nbsp;上传于:<?php echo $get_photo['date']?>
        </dd>
        <dd>简介:<?php echo $get_photo['content']?></dd>
    </dl>

    <!--##以下是[评论表单],仅登录可见-->
    <?php if (isset($_COOKIE['username'])){?>
        <p class="line"></p><!--分割线-->
        <form action="?action=rephoto" method="post">
            <input type="hidden" name="reid" value="<?php echo $_html['subject_id']?>">
            <input type="hidden" name="retype" value="<?php echo $_html['type']?>">
            <dl class="re_photo">
                <dd>标题：&emsp;<input type="text" name="retitle" class="text" value="RE:<?php echo $_html['title']?>">(*必填，2-40位)</dd>
                <dd id="q">贴图：<a href="javascript:;">Q贴图1</a><a href="javascript:;">Q贴图2</a><a href="javascript:;">Q贴图3</a> </dd>
                <dd>
                    <?php require ROOT_PATH.'includes/ubb.inc.php'; ?>
                    <textarea  name="content" rows="10"></textarea></dd>
                <dd>
                    验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/>
                    <input type="submit" name="submit" class="submit" value="发送文章">
                </dd>
            </dl>
        </form>
    <?php }?>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>