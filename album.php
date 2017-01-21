<?php
session_start();
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','album');
require dirname(__FILE__).'/includes/common.inc.php';
//读取相册数据
global $_pagenum,$_pagesize;
global $sys;
$sql="select album_id from bbs.bbs_album";
//$sys['sys_blog']为后台系统设置指定的，每页的列表数
paging_fault_tolerant($sql,$sys['photo']);

//从数据库读取相册数据
$sql="select * from bbs.bbs_album ORDER BY album_date DESC LIMIT $_pagenum,$_pagesize";
//取得相册数据集
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

<div id="album">
    <h2>相册</h2>
    <?php
    $_album=array();
    while(!!$rows=fetch_array_list($result)){
    $_album['id']=$rows['album_id'];
    $_album['name']=$rows['album_name'];
    $_album['type']=$rows['album_type'];
    $_album['pwd']=$rows['album_pwd'];
    $_album['corver']=$rows['album_corver'];
    $_album=html_spec($_album);
        if ($_album['type']==0){
            $_album['type_html']='(公开)';
        }else{
            $_album['type_html']='(私密)';
        }
        if (empty($_album['corver'])){
            $_album['corver_html']='';
        }else{
            $_album['corver_html']='<img alt="'.$_album['name'].'" src="'.$_album['corver'].'">';
        }
    ?>
        <dl>
            <dt><?php echo  $_album['corver_html']?></dt>
            <dd><a href="photo_show.php?dirid=<?php echo $_album['id']?>"><?php echo $_album['name']?><?php echo $_album['type_html']?></a></dd>
            <?php
            if (isset($_COOKIE['username']) && isset($_SESSION['admin'])) {
                if ($_COOKIE['username']==$_SESSION['admin']){
                    echo '<dd><a href="album_modify_dir.php?id='.$_album['id'].'">[修改]</a>[删除]</dd>';
                }
            }
            ?>
        </dl>
    <?php }?>
    <p>
        <?php
        if (isset($_COOKIE['username']) && isset($_SESSION['admin'])) {
            if ($_COOKIE['username']==$_SESSION['admin']){
            echo '<a href="album_add_dir.php" class="admin">添加目录</a>';
            }
        }
        ?>
    </p>
   <!--上边是替代写法。下边这种写法，如果是普通会员，因为不显示 （添加目录），导致列出的相册，溢出到父#album容器外，不知道为什么？ -->
    <?php
/*    if (isset($_COOKIE['username']) && isset($_SESSION['admin'])) {
        //if ($_COOKIE['username']==$_SESSION['admin']){
            echo '<p><a href="album_add_dir.php" class="admin">添加目录</a></p>';
        //}
    }
    */?>
</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>