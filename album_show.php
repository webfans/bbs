<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','album_show');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能会员才能查看
if (!isset($_COOKIE['username'])){
    alert_back('请登录后使用上传');
}
//取值
if ($_GET['id']){
    if (!!$rows=fetch_array("select album_id from bbs.bbs_album WHERE album_id='{$_GET['id']}'")){
        $get_album=array();
        $get_album['id']=$rows['album_id'];
        $get_album=html_spec($get_album);
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
    <script type="text/javascript" src="js/album_add_dir.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="album">
    <h2>照片展示</h2>
    <p><a href="album_add_img.php?id=<?php echo $get_album['id']?>">上传图片</a></p>
</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>