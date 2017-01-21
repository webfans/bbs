<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','photo_show');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能会员才能查看
if (!isset($_COOKIE['username'])){
    alert_back('请登录后使用上传');
}
//取得相册数据
if ($_GET['dirid']){
    if (!!$rows=fetch_array("select album_id,album_name from bbs.bbs_album WHERE album_id='{$_GET['dirid']}'")){
        $get_album=array();
        $get_album['id']=$rows['album_id'];
        $get_album['name']=$rows['album_name'];
        $get_album=html_spec($get_album);
    }else{
        alert_back('不存在此相册');
    }
}else{
    alert_back('非法操作');
}
//取得照片数据
//$filename='album/1484808483/touxiang.jpg';
$zoom=0.3;
global $_pagesize,$_pagenum,$sys;
global $_id;
$_id='dirid='.$get_album['id'].'&';
$conut_sql="select photo_id from bbs.bbs_photo WHERE photo_sid='{$_GET['dirid']}'";
paging_fault_tolerant($conut_sql,4);
$photo_result=query("select photo_id,photo_name,photo_url,photo_owner
                          from bbs.bbs_photo
                          WHERE photo_sid='{$_GET['dirid']}'
                          ORDER BY photo_date DESC 
                          limit $_pagenum,$_pagesize
                     ");


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
    <h2>照片展示-<?php echo $get_album['name']?></h2>
    <?php
    $photo=array();
    while(!!$rows=fetch_array_list($photo_result)){
        $photo['id']=$rows['photo_id'];
        $photo['name']=$rows['photo_name'];
        $photo['url']=$rows['photo_url'];
        $photo['owner']=$rows['photo_owner'];
        $photo=html_spec($photo);
        $filename=$photo['url'];
        ?>
    <dl>
        <dt>
            <a href="photo_detail.php?imgid=<?php echo $photo['id']?>">
                <img src="thumb.php?filename=<?php echo $photo['url'];?>&zoom=<?php echo $zoom;?>">
            </a>
        </dt>
        <dd>
            <a href="photo_detail.php?imgid=<?php echo $photo['id']?>">
                <?php echo $photo['name']?>
            </a>
        </dd>
        <dd>阅(<strong>0</strong>)评(<strong>0</strong>)上传者:<?php echo $photo['owner']?></dd>
    </dl>
    <?php }?>
    <p><a href="album_add_img.php?id=<?php echo $get_album['id']?>">上传图片</a></p>

    <?php
    //销毁数据集
    mysql_free_result($result);
    //调用分页函数
    paging(1);
    paging(2);
    ?>
</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>