<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','album_modify_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能管理员才能查看
admin_login();
//2修改数据
if ($_GET['action']=='modify'){
    //引入校验文件
    include_once ROOT_PATH.'includes/check.func.php';
    //校验唯一标识符
    block_fake_cookie();
    //授受数据
    $album=array();
    $album['id']=$_POST['albumid'];
    $album['name']=check_length($_POST['albumname'],'相册名',2,20);
    $album['type']=$_POST['albumtype'];
    $album['corver']=$_POST['albumcorver'];
    $album['content']=$_POST['albumcontent'];
    if (!empty($album['type'])){
        $album['pwd']=check_modify_pwd($_POST['albumpwd'],6);
    }
    $album=mysql_string($album);
    //#相册公开
    if ($album['type']==0){
        query("update bbs_album set
                                album_name='{$album['name']}',
                                album_type='{$album['type']}',
                                album_corver='{$album['corver']}',
                                album_content='{$album['content']}',
                                album_pwd=NULL 
                                WHERE album_id='{$album['id']}' 
                                limit 1
              ");
    }//##相册私密
    elseif ($album['type']==1){
        //1密码留为空
        query("update bbs_album set
                                album_name='{$album['name']}',
                                album_type='{$album['type']}',
                                album_corver='{$album['corver']}',
                                album_content='{$album['content']}',
                                album_pwd='{$album['pwd']}' 
                                WHERE album_id='{$album['id']}' 
                                limit 1
              ");
    }else{
          //2密码不为空
            query("update bbs_album set
                                album_name='{$album['name']}',
                                album_type='{$album['type']}',
                                album_corver='{$album['corver']}',
                                album_content='{$album['content']}',
                                album_pwd='{$album['pwd']}'
                                WHERE album_id='{$album['id']}' 
                                limit 1
              ");
    }
    after_query('相册修改成功','相册修改失败','album.php');
}
//1读出数据
if (isset($_GET['id'])){
    if (!!$rows=fetch_array("select * from bbs.bbs_album WHERE album_id='{$_GET['id']}' limit 1")){
        //拿数据
        $read_album=array();
        $read_album['id']=$rows['album_id'];
        $read_album['name']=$rows['album_name'];
        $read_album['type']=$rows['album_type'];
        $read_album['content']=$rows['album_content'];
        $read_album['corver']=$rows['album_corver'];
        $read_album['pwd']=$rows['album_pwd'];
        $read_album=html_spec($read_album);
        if ($read_album['type']==0){
            $read_album['type_html']='<input type="radio" name="albumtype" value="0" checked>公开
                                      <input type="radio" name="albumtype" value="1">私密';
        }else{
            $read_album['type_html']='<input type="radio" name="albumtype" value="0">公开
                                      <input type="radio" name="albumtype" value="1" checked>私密';
        }
    }else{
        alert_back('不存在此相册目录');
    }
}else{
    alert_back('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/album_modify_dir.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="album">
    <h2>修改相册</h2>
    <form method="post" action="?action=modify" name="modifyalbumdir">
    <dl>
        <dd>相册名称：<input type="text" name="albumname" class="text" value="<?php echo $read_album['name']?>"> </dd>
        <dd>相册类型：<?php echo $read_album['type_html']?></dd>
        <dd id="albumpwd" <?php if ($read_album['type']==0){echo 'style="display:none"';}?>>相册密码：<input type="password" name="albumpwd" class="text"><span>(*留空则不修改)</span></dd>
        <dd>相册封面：<input type="text" name="albumcorver" class="text" value="<?php echo $read_album['corver']?>"> </dd>
        <dd>相册描述：<textarea name='albumcontent'><?php echo $read_album['content']?></textarea></dd>
        <dd><input type="submit" name="albumsubmit" class="submit" value="修改相册"></dd>
    </dl>
        <!--这个隐藏表单不能放在上面，否则影响[相册类型]的JS事件，因为你一旦加在上面，就改变了[相册类型]的fm['id']-->
        <input type="hidden" name="albumid" value="<?php echo $read_album['id']?>">
    </form>
</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>