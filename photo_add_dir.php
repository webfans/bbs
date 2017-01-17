<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','photo_add_dir');
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
    $photo=array();
    $photo['name']=check_length($_POST['photoname'],'相册名',2,20);
    //$photo['name']=$_POST['photoname'];
    $photo['type']=$_POST['phototype'];
    $photo['content']=$_POST['photocontent'];
    if (!empty($photo['type'])){
        $photo['pwd']=check_length($_POST['photopwd'],'密码',6,20,1);
    }
    $photo['dir']=time();
    $photo=mysql_string($photo);
    //1先确认主目录是否存在
    //mkdir('photo',0777);
    if (!is_dir('photo')){
        mkdir('photo',0777);
    }
    //2再在主目录下创建用户相册子目录
    if (!is_dir('photo/'.$photo['dir'])){
        mkdir('photo/'.$photo['dir'],0777);
    }
    //include ROOT_PATH.'includes/check.func.php';
    //把当前的目录信息写入数据库
    if (empty($photo['type'])){
        query("insert into bbs.bbs_photodir( 
                                            photo_name, 
                                            photo_type,
                                            photo_content, 
                                            photo_dir,
                                            photo_date
                                            ) 
                                            VALUES 
                                            (
                                            '{$photo['name']}',
                                            '{$photo['type']}',
                                            '{$photo['content']}',
                                            'photo/{$photo['dir']}',
                                             NOW() 
                                             )
              ");
    }else{
        query("insert into bbs.bbs_photodir( 
                                            photo_name, 
                                            photo_type,
                                            photo_pwd, 
                                            photo_content, 
                                            photo_dir,
                                            photo_date
                                            ) 
                                            VALUES 
                                            (
                                            '{$photo['name']}',
                                            '{$photo['type']}',
                                            '{$photo['pwd']}',
                                            '{$photo['content']}',
                                            'photo/{$photo['dir']}',
                                            NOW()     
                                            )
              ");
    }
    after_query('相册创建成功','相册创建失败','photo.php');
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/photo_add_dir.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
    <h2>创建相册</h2>
    <form method="post" action="?action=adddir" name="addphotodir">
    <dl>
        <dd>相册名称：<input type="text" name="photoname" class="text"> </dd>
        <dd>
            相册类型：<input type="radio" name="phototype" value="0">公开
                    <input type="radio" name="phototype" value="1">私密
        </dd>
        <dd id="photopwd">相册密码：<input type="password" name="photopwd" class="text"></dd>
        <dd>相册描述：<textarea name='photocontent'></textarea></dd>
        <dd><input type="submit" name="photosubmit" class="submit" value="创建相册"></dd>
    </dl>
    </form>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>