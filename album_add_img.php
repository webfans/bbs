<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','album_add_img');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//会员才能上传
if (!isset($_COOKIE['username'])){
    alert_back('请登录后使用上传');
}
//保存图片信息入库
if ($_GET['action']=='addimg'){
    block_fake_cookie();
    include ROOT_PATH.'includes/check.func.php';
    //授收图片信息
    $img=array();
    $img['name']=check_length($_POST['imgname'],'图片名',2,20);
    $img['url']=not_null($_POST['imgurl'],'图片地址');
    $img['content']=$_POST['imgcontent'];
    $img['sid']=$_POST['sid'];
    $img=mysql_string($img);
    //写入数据库
    query("insert into bbs.bbs_photo(
                                        photo_name, 
                                        photo_url, 
                                        photo_content, 
                                        photo_date, 
                                        photo_owner, 
                                        photo_sid
                                        )
                                  VALUES(
                                        '{$img['name']}',
                                        '{$img['url']}',
                                        '{$img['content']}',
                                         NOW(),
                                         '{$_COOKIE['username']}',
                                        '{$img['sid']}'       
                                        ) 
              ");
    after_query('图片添加成功','图片添加失败','photo_show.php?id='.$img['sid']);

}
//取得相册数据
if ($_GET['id']){
    if (!!$rows=fetch_array("select album_id,album_dir from bbs.bbs_album WHERE album_id='{$_GET['id']}'")){
        $get_album=array();
        $get_album['id']=$rows['album_id'];
        $get_album['dir']=$rows['album_dir'];
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
    <script type="text/javascript" src="js/album_add_img.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="album">
    <h2>上传照片</h2>
    <form method="post" action="?action=addimg" name="addimg">
        <input type="hidden"  name="sid" value="<?php echo $get_album['id']?>"><!--照片所从属的相册-->
    <dl>
        <dd>图片名称：<input type="text" name="imgname" class="text"> </dd>
        <dd>图片地址：<input type="text" id='imgurl' name="imgurl" class="text" readonly>
            <!--通过JS将title里面存放的上传目录的物理地址传给上传处理页upload.php-->
            <a href="javascript:;" title="<?php echo $get_album['dir']?>" id="upload">上传</a>
        </dd>
        <dd>图片描述：<textarea name='imgcontent'></textarea></dd>
        <dd><input type="submit" name="imgsubmit" class="submit" value="添加照片"></dd>
    </dl>
    </form>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>