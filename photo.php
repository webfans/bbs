<?php
session_start();
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','photo');
require dirname(__FILE__).'/includes/common.inc.php';
//读取相册数据
global $_pagenum,$_pagesize;
global $sys;
$sql="select photo_id from bbs.bbs_photodir";
//$sys['sys_blog']为后台系统设置指定的，每页的列表数
paging_fault_tolerant($sql,$sys['photo']);

//从数据库读取数据
$sql="select * from bbs.bbs_photodir ORDER BY photo_date DESC LIMIT $_pagenum,$_pagesize";
//取得 数据集
$result=query($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/blog.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
    <h2>相册</h2>
    <?php
    $_photo=array();
    while(!!$rows=fetch_array_list($result)){
    $_photo['id']=$rows['photo_id'];
    $_photo['name']=$rows['photo_name'];
    $_photo['type']=$rows['photo_type'];
    $_photo['pwd']=$rows['photo_pwd'];
    $_photo['corver']=$rows['photo_corver'];
    $_photo=html_spec($_photo);
    if ($_photo['type']==0){
        $_photo['type_html']='(公开)';
    }else{
        $_photo['type_html']='(私密)';
    }
    if (empty($_photo['corver'])){
        $_photo['corver_html']='';
    }else{
        $_photo['corver_html']='<img alt="'.$_photo['name'].'" src="'.$_photo['corver'].'">';
    }
    ?>
        <dl>
            <dt><?php echo  $_photo['corver_html']?></dt>
            <dd><a href="photo_show.php?id=<?php echo $_photo['id']?>"><?php echo $_photo['name']?> <?php echo $_photo['type_html']?></a></dd>
            <?php
            if (isset($_COOKIE['username'])&&isset($_SESSION['admin'])) {
                if ($_COOKIE['username']==$_SESSION['admin']){
                    echo ' <dd><a href="photo_modify_dir.php?id='.$_photo['id'].'">[修改]</a>[删除]</dd>';
                }
            }
            ?>
        </dl>
    <?php }?>
    
    <?php
    if (isset($_COOKIE['username'])&&isset($_SESSION['admin'])) {
        if ($_COOKIE['username']==$_SESSION['admin']){
            echo '<p><a href="photo_add_dir.php" class="admin">添加目录</a></p>';
        }
    }
    ?>
</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>