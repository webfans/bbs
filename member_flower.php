<?php
if(!isset($_SESSION))
{
    session_start();
}
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','member_message');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登陆
if (!isset($_COOKIE['username'])){
    alert_back('please login,then try again');
}
#批量删除#
if (@$_GET['action']=='delete'&&isset($_POST['id_chkbox'])){
//print_r($_POST['id_chkbox']);
    $_clean=array();
    $_clear['id_chkbox']=mysql_string(implode(',',$_POST['id_chkbox']));
    #!当你进行危险操作时（比如删除）之前，最好还要对唯一标识符进行验证!
    if (!!$_rows=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")) {
        //为了防止Cookie伪造，还要比对一下唯一标识符uniqid
        safe_uniquid($_rows['u_uniqid'], $_COOKIE['uniqid']);
        //开始批量删除
        query("delete from bbs_flower where fl_id in({$_clear['id_chkbox']})");
        if (affetched_rows()){
            close();
             //session_d();
            location('花朵删除成功','member_flower.php');
        }else{
            close();
             //session_d();
            alert_back('花朵删除失败');
        }
    }else{
        alert_back('非法登录');
    }
}
//分页模块
global $_pagenum,$_pagesize;
$sql="select fl_id from bbs_flower where fl_touser='{$_COOKIE['username']}'";
paging_fault_tolerant($sql,4);

//从数据库读取数据
$sql="select fl_id,fl_fromuser,fl_num,fl_content,fl_date from bbs_flower where fl_touser='{$_COOKIE['username']}' ORDER BY fl_date DESC LIMIT $_pagenum,$_pagesize";
//取得 数据集
$result=query($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/member_message.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>会员管理中心-花朵</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多 头部文件
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="member">
    <div id="member_sidebar">
        <?php require ROOT_PATH."includes/member.inc.php";?>
    </div>
    <div id="member_main">
        <h2>会员花朵管理中心</h2>
        <form method="post" action="?action=delete">
            <table cellspacing="1">
            <tr>
                <th>送花人</th>
                <th>送花数目</th>
                <th>送花感言</th>
                <th>送花时间</th>
                <th>操作</th>
            </tr>
            <?php
            $_html = array();
            while(!!$rows=fetch_array_list($result)) {
                $_html['id'] = $rows['fl_id'];
                $_html['fromuser'] = $rows['fl_fromuser'];
                $_html['flowernum'] = $rows['fl_num'];
                $_html['content'] = $rows['fl_content'];
                $_html['date'] = $rows['fl_date'];
                $_html['flower_total']+=$_html['flowernum'];//统计总花朵数
                $_html = html_spec($_html);
                ?>
                <tr>
                    <td><?php echo $_html['fromuser']?></td>
                    <td><img src="images/x4.gif">x <?php echo $_html['flowernum']?>朵</td>
                    <td><?php echo summary($_html['content'])?></td>
                    <td><?php echo $_html['date']?></td>
                    <!--注意下面的复选框的命名必须加上[]这样以数组形式，才能批删除-->
                    <td><input type="checkbox" name="id_chkbox[]" value="<?php echo $_html['id'];?>"/> </td>
                </tr>
            <?php
            }
            ?>
                <tr>
                    <td colspan="5">共收到<?php echo $_html['flower_total']?>朵花</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <lable for="all">
                            全选<input type="checkbox" name="checkall" id="checkall">
                            <input type="submit" name="delall" value="批量删除">
                        </lable>
                    </td>
                </tr>

          </table>
        </form>

        <?php
        //销毁数据集
        mysql_free_result($result);
        //调用分页函数
        paging(1);
        paging('num');
        ?>

    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>