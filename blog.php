<?php
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','blog');
//
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
###分页模块#############################################################
/*limit n,m //n是从第几条数据开始读起，默认是0；m是读取的条数
 * 假设$page_size=10 每页显示10条数据; $pagenum=($GET['page']-1)*$pagesize
 * page=1 说明是第1页数据 表示1-10条数据 LIMIT 0,10; $page_num=0;
 * page=2 说明是第2页数据 表示11-20条数据 LIMIT 10,10; $page_num=10;
 * page=3 说明是第3页数据 表示21-30条数据 LIMIT 20,10; $page_num=20;
 *  */
//分页容错处理
if (isset($_GET['page'])){
    $_page=@$_GET['page'];
    if (empty($_page)||$_page<0){
        //防止page存在，但是空值（0）或是负值
        $_page=1;
    }
}else{
    //如果直接访问blog.php显然page不存在，则默认page是0，则导致$_pagenum=负值，进而引起sql执行出错
    //所以默认给它赋值1，是为容错处理
    $_page=1;
}

//每页多少条数据
$_pagesize=3;
//从第几条数据开始读起
$_pagenum=($_page-1)*$_pagesize;
//首页得到所有数据总条数
$total_num=mysql_num_rows(query("select u_id from bbs_user"));
//ceil()进一取整法,例如$_page_absolute=3.1也算为4页
//根据总数据条总算出页码数
$_page_absolute=ceil($total_num/$_pagesize);
//echo $_page_absolute;

//从数据库读取数据
$sql="select u_username,u_sex,u_face from bbs_user ORDER BY u_regtime DESC LIMIT $_pagenum,$_pagesize";
//取得 数据集
$result=query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>博友页面</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="blog">
    <h2>博友</h2>
    <!--while(!!rows=fetch_array($sql))将导致死循环，一直取出数据库第一条数据-->
    <!--while(!!rows=mysql_fetch_array($result)),是没问题的-->
    <!--我们必须是每次重新读取结果集，而不是每次重新执行一次SQL,而上边的fetch_array($sql)就是重复执行SQl导致死循环-->
    <?php while(!!$rows=fetch_array_list($result)){?>
    <dl>
        <dd class="user"><?php echo $rows['u_username']?></dd>
        <dt><img src="<?php echo $rows['u_face']?>" alt="root"/></dt>
        <dd class="message">发消息</dd>
        <dd class="friend">加好友</dd>
        <dd class="guest">写留言</dd>
        <dd class="flower">给他送花</dd>
    </dl>
    <?php } ?>

    <div id="page_num">
        <ul>
            <?php
            for ($i=0;$i<$_page_absolute;$i++){
                if ($_page==($i+1)){
                    echo '<li><a class="selected" href="blog.php?page='.($i+1).'">'.($i+1).'</a> </li>';
                }
                else{
                    echo '<li><a href="blog.php?page='.($i+1).'">'.($i+1).'</a> </li>';
                }

            }
            ?>
        </ul>
    </div>

</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>