<?php
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','upload');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//会员才能上传
if (!isset($_COOKIE['username'])){
    alert_back('请登录后使用上传');
}
if ($_GET['action']=='upload'){
    //敏感操作验证唯一标识符
    block_fake_cookie();
    //开始上传照片
    //还有两个问题要处理
    define('MAX_SIZE',2000000);
    define('URL',dirname(__FILE__).'\photo');

    #1.设置上传图片的类型
    $filemimes=array('image/jpeg','image/pjpeg','image/png','image/png','image/x-png');
    //判断[文件类型]是否是数组中规定的类型的一种，跟下面的switch效果是一样的，只不过写法更简化
    if (is_array($filemimes)){
        if (!in_array($_FILES['userfile']['type'],$filemimes)){
            echo "<script>alert('本站只允许JPG格式的文件');history.back()</script>";
            exit();
        }
    }
    #2.判断文件错误类型
    $error_id=$_FILES['userfile']['error'];
    if ($error_id>0){
        switch ($error_id){
            case 1:echo "<script>alert('上传文件超过约定值')</script>";
                break;
            case 2:echo "<script>alert('上传文件超过表单约定值')</script>";
                break;
            case 3:echo "<script>alert('部分文件被上传')</script>";
                break;
            case 4:echo "<script>alert('没有文件被上传')</script>";
                break;
        }
        exit();
    }
    //#3.判断[配置文件大小]限制
    if ($_FILES['userfile']['size']>MAX_SIZE){
        echo "<script>alert('上传文件超过内定文件限制大小2兆');history.back();</script>";
    }
    //is_uploaded_file()
    //判断文件是否通过HTTP POST 上传的
    //通过HTTP POST 上传后，文件会存放在临时文件夹

    //#4.移动文件
    if (is_uploaded_file($_FILES['userfile']['tmp_name'])){
        echo '上传的临时文件存在，等待移动中...';
        //move_uploaded_file(临时文件地址，你要存放的地址)移动文件到指定的地方
        //注意写法：URL.'/'.$_FILES['userfile']['name'] .'/正斜杠'.将目录和文件名 相加and
        if (!move_uploaded_file($_FILES['userfile']['tmp_name'],URL.'/'.$_FILES['userfile']['name'])){
            //如果移动失败，就失败
            //echo '移动失败';
            echo "<script>alert('移动失败');</script>";
            exit();
        }

    }else{
        //echo '时时文件夹找不到要上传的文件';
        echo "<script>alert('临时文件夹找不到上传的文件');history.back();</script>";
        exit;
    }
    //全部成功了
    //url=".$_FILES['userfile']['name']." ".."
    //必须传一个值给upload_file_show.php

    #学PHP 李炎恢38-42
    echo "<script>alert('上传文件成功');location.href='upload_file_show.php?url=".$_FILES['userfile']['name']."'</script>";


}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <script type="text/javascript" src="js/register.js"></script>
    <script type="text/javascript" src="js/opener.js"></script>
    <!--<title>头像选取页面</title>-->
</head>
<body>
<div id="face">
    <h3>上传照片</h3>
    <form enctype="multipart/form-data" action="?action=upload" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" VALUE="1000000">
        上传文件：<input type="file" name="userfile">
        <input type="submit" value="上传"/>
    </form>
    </form>
</div>
</body>
</html>