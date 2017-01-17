<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','photo_modify_dir');
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
    $photo=array();
    $photo['id']=$_POST['photoid'];
    $photo['name']=check_length($_POST['photoname'],'相册名',2,20);
    $photo['type']=$_POST['phototype'];
    $photo['corver']=$_POST['photocorver'];
    $photo['content']=$_POST['photocontent'];
    if (!empty($photo['type'])){
        $photo['pwd']=check_modify_pwd($_POST['photopwd'],6);
    }
    $photo=mysql_string($photo);
    //#相册公开
    if ($photo['type']==0){
        query("update bbs_photodir set
                                photo_name='{$photo['name']}',
                                photo_type='{$photo['type']}',
                                photo_corver='{$photo['corver']}',
                                photo_content='{$photo['content']}',
                                photo_pwd=NULL 
                                WHERE photo_id='{$photo['id']}' 
                                limit 1
              ");
    }//##相册私密
    elseif ($photo['type']==1){
        //1密码留为空
        query("update bbs_photodir set
                                photo_name='{$photo['name']}',
                                photo_type='{$photo['type']}',
                                photo_corver='{$photo['corver']}',
                                photo_content='{$photo['content']}',
                                photo_pwd='{$photo['pwd']}' 
                                WHERE photo_id='{$photo['id']}' 
                                limit 1
              ");
    }else{
          //2密码不为空
            query("update bbs_photodir set
                                photo_name='{$photo['name']}',
                                photo_type='{$photo['type']}',
                                photo_corver='{$photo['corver']}',
                                photo_content='{$photo['content']}',
                                photo_pwd='{$photo['pwd']}'
                                WHERE photo_id='{$photo['id']}' 
                                limit 1
              ");
    }
    after_query('相册修改成功','相册修改失败','photo.php');
}
//1读出数据
if (isset($_GET['id'])){
    if (!!$rows=fetch_array("select * from bbs.bbs_photodir WHERE photo_id='{$_GET['id']}' limit 1")){
        //拿数据
        $read_photo=array();
        $read_photo['id']=$rows['photo_id'];
        $read_photo['name']=$rows['photo_name'];
        $read_photo['type']=$rows['photo_type'];
        $read_photo['content']=$rows['photo_content'];
        $read_photo['corver']=$rows['photo_corver'];
        $read_photo['pwd']=$rows['photo_pwd'];
        $read_photo=html_spec($read_photo);
        if ($read_photo['type']==0){
            $read_photo['type_html']='<input type="radio" name="phototype" value="0" checked>公开
                                      <input type="radio" name="phototype" value="1">私密';
        }else{
            $read_photo['type_html']='<input type="radio" name="phototype" value="0">公开
                                      <input type="radio" name="phototype" value="1" checked>私密';
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
    <script type="text/javascript" src="js/photo_modify_dir.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
    <h2>修改相册</h2>
    <form method="post" action="?action=modify" name="modifyphotodir">
    <dl>
        <dd>相册名称：<input type="text" name="photoname" class="text" value="<?php echo $read_photo['name']?>"> </dd>
        <dd>相册类型：<?php echo $read_photo['type_html']?></dd>
        <dd id="photopwd" <?php if ($read_photo['type']==0){echo 'style="display:none"';}?>>相册密码：<input type="password" name="photopwd" class="text"><span>(*留空则不修改)</span></dd>
        <dd>相册封面：<input type="text" name="photocorver" class="text" value="<?php echo $read_photo['corver']?>"> </dd>
        <dd>相册描述：<textarea name='photocontent'><?php echo $read_photo['content']?></textarea></dd>
        <dd><input type="submit" name="photosubmit" class="submit" value="修改相册"></dd>
    </dl>
        <!--这个隐藏表单不能放在上面，否则影响[相册类型]的JS事件，因为你一旦加在上面，就改变了[相册类型]的fm['id']-->
        <input type="hidden" name="photoid" value="<?php echo $read_photo['id']?>">
    </form>
</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>