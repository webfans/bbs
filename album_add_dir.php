<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','album_add_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能管理员才能查看
admin_login();
//#添加相册
if (isset($_GET['action']) && $_GET['action']=='adddir'){
    //引入校验文件
    include_once ROOT_PATH.'includes/check.func.php';
    //校验唯一标识符
    block_fake_cookie();
    //授受数据
    $album=array();
    $album['name']=check_length($_POST['albumname'],'相册名',2,20);
    //$album['name']=$_POST['albumname'];
    $album['type']=$_POST['albumtype'];
    $album['content']=$_POST['albumcontent'];
    if (!empty($album['type'])){
        $album['pwd']=check_length($_POST['albumpwd'],'密码',6,20,1);
    }
    $album['dir']=time();
    $album=mysql_string($album);
    //1先确认主目录是否存在
    //mkdir('album',0777);
    if (!is_dir('album')){
        mkdir('album',0777);
    }
    //2再在主目录下创建用户相册子目录
    if (!is_dir('album/'.$album['dir'])){
        mkdir('album/'.$album['dir'],0777);
    }
    //include ROOT_PATH.'includes/check.func.php';
    //把当前的目录信息写入数据库
    if (empty($album['type'])){
        query("insert into bbs.bbs_album( 
                                            album_name, 
                                            album_type,
                                            album_content, 
                                            album_dir,
                                            album_date
                                            ) 
                                            VALUES 
                                            (
                                            '{$album['name']}',
                                            '{$album['type']}',
                                            '{$album['content']}',
                                            'album/{$album['dir']}',
                                             NOW() 
                                             )
              ");
    }else{
        query("insert into bbs.bbs_album( 
                                            album_name, 
                                            album_type,
                                            album_pwd, 
                                            album_content, 
                                            album_dir,
                                            album_date
                                            ) 
                                            VALUES 
                                            (
                                            '{$album['name']}',
                                            '{$album['type']}',
                                            '{$album['pwd']}',
                                            '{$album['content']}',
                                            'album/{$album['dir']}',
                                            NOW()     
                                            )
              ");
    }
    after_query('相册创建成功','相册创建失败','album.php');
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
    <h2>创建相册</h2>
    <form method="post" action="?action=adddir" name="addalbumdir">
    <dl>
        <dd>相册名称：<input type="text" name="albumname" class="text"> </dd>
        <dd>
            相册类型：<input type="radio" name="albumtype" value="0">公开
                    <input type="radio" name="albumtype" value="1">私密
        </dd>
        <dd id="albumpwd">相册密码：<input type="password" name="albumpwd" class="text"></dd>
        <dd>相册描述：<textarea name='albumcontent'></textarea></dd>
        <dd><input type="submit" name="albumsubmit" class="submit" value="创建相册"></dd>
    </dl>
    </form>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>