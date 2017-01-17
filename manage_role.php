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
define('SCRIPT','manage_role');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//必须是管理员登录
admin_login();

//读取数据
global $_pagesize,$_pagenum;
paging_fault_tolerant("select u_id from bbs.bbs_user WHERE u_level=1",5);
//读取管理员数据集
$listadmin=query("select u_id,u_username,u_email,u_regtime,u_level 
                   from bbs.bbs_user 
                   WHERE u_level=1 OR u_level=2
                   ORDER BY u_regtime DESC   
                   limit $_pagenum,$_pagesize
                ");
//todo 删除管理员操作 管理员之间也可以相互删除，还没有加上判断是否是超级管理员
if (@$_GET['action']=='del' && isset($_GET['id'])){
    //防止Cookie伪造，校验唯一标识符
    block_fake_cookie();
    $del_sql="delete from bbs.bbs_user WHERE u_id='{$_GET['id']}'";
    query($del_sql);
    if (affetched_rows()==1){
        location('删除成功','manage_role.php');
        close();
    }else{
        alert_back('删除失败');
        close();
    }
}
//添加管理员 //todo 还没有加上只有超级管理员才能添加管理员的权限
if (@$_GET['action']=='addadmin'){
    //敏感操作必须验证唯一标识符
    block_fake_cookie();
    //接受表单数据
    $clean=array();
    $clean['username']=$_POST['adminname'];
    $clean=mysql_string($clean);
    //更改用户级别，转为管理员
    query("update bbs_user set u_level=1 WHERE u_username='{$clean['username']}'");
    if (affetched_rows()==1){
        location('添加管理员成功','manage_role.php');
        close();
    }else{
        alert_back('管理员添加失败，原因：不存在此用户或为空');
    }
}
//普通管理员自己辞职
if (@$_GET['action']=='resign' && isset($_GET['id'])){
    block_fake_cookie();
    query("update bbs_user set u_level=0 WHERE u_username='{$_COOKIE['username']}' AND u_id='{$_GET['id']}'");
    after_query('辞职成功','辞职失败','member.php',1);
    /*if (affetched_rows()==1){
        location('辞职成功','manage_role.php');
        close();
    }else{
        alert_back('辞职失败');
    }*/
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/manage_role.js"></script>
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
            while (!!$rows=fetch_array_list($listadmin)) {
                $html['username'] = $rows['u_username'];
                $html['userlevel'] = $rows['u_level'];
                $html['uid'] = $rows['u_id'];
                $html['email'] = $rows['u_email'];
                $html['regtime'] = $rows['u_regtime'];
                if ($_COOKIE['username']==$html['username']){
                    $html['role_html']='<a href="manage_role.php?action=resign&id='.$html['uid'].'">辞职</a>';

                }else{
                    $html['role_html']='无权操作';
                }

            ?>
                <tr>
                    <td><?php echo $html['uid']?></td>
                    <td><?php echo $html['username']?></td>
                    <td><?php echo $html['email']?></td>
                    <td><?php echo $html['regtime']?></td>
                    <td><?php echo $html['role_html']?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="5">
                   <form name="adminaddform" method="post" action="?action=addadmin">
                      用户名: <input type="text" name="adminname">
                       <input type="submit" name="adminok" value="添加管理员">
                   </form>
                </td>
            </tr>
          </table>
        </form>

        <?php
        //销毁数据集
        mysql_free_result($listadmin);
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