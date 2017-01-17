<?php
if(!isset($_SESSION))
{
    session_start();
}
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','manage_member');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//必须是管理员登录
admin_login();
//读取数据
global $_pagesize,$_pagenum;
paging_fault_tolerant("select u_id from bbs.bbs_user WHERE u_level=0",15);
//读用户数据集
$listuser_result=query("select u_id,u_username,u_email,u_regtime 
                   from bbs.bbs_user
                   WHERE u_level=0
                    ORDER BY u_regtime DESC 
                   limit $_pagenum,$_pagesize
                ");
//删除会员操作
if ($_GET['action']='del' && isset($_GET['id'])){
    //防止Cookie伪造，校验唯一标识符
    block_fake_cookie();
    $del_sql="delete from bbs.bbs_user WHERE u_id='{$_GET['id']}'";
    query($del_sql);
    if (affetched_rows()){
        location('删除成功','manage_member.php');
        close();
    }else{
        alert_back('删除失败');
        close();
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/manage_membe.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>会员管理中心-收件箱</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多 头部文件
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="member">
    <div id="member_sidebar">
        <?php require ROOT_PATH."includes/manage.inc.php";?>
    </div>
    <div id="member_main">
        <h2>会员列表中心</h2>
        <form method="post" action="?action=delete">
            <table cellspacing="1">
            <tr>
                <th>会员ID</th>
                <th>会员名</th>
                <th>用户邮件</th>
                <th>注册时间</th>
                <th>操作</th>
            </tr>
            <?php
            $html=array();
            while (!!$rows=fetch_array_list($listuser_result)) {
                $html['username'] = $rows['u_username'];
                $html['uid'] = $rows['u_id'];
                $html['email'] = $rows['u_email'];
                $html['regtime'] = $rows['u_regtime'];

            ?>
                <tr>
                    <td><?php echo $html['uid']?></td>
                    <td><?php echo $html['username']?></td>
                    <td><?php echo $html['email']?></td>
                    <td><?php echo $html['regtime']?></td>
                    <td>[<a name="deluser" href="?action=del&id=<?php echo $html['uid']?>">删除</a>][修改]</td>
                </tr>
            <?php
            }
            ?>
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
        mysql_free_result($listuser_result);
        //调用分页函数
        paging(1);
        paging(2);
        ?>

    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>