<?php
session_start();
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
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
//分页模块
global $_pagenum,$_pagesize;
$sql="select m_id from bbs_message";
paging_fault_tolerant($sql,4);

//从数据库读取数据
$sql="select m_id,m_fromuser,m_content,m_date from bbs_message ORDER BY m_date DESC LIMIT $_pagenum,$_pagesize";
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
    <script type="text/javascript" src="js/member_modify.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>会员管理中心-收件箱</title>
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
        <h2>会员短信管理中心</h2>
        <table cellspacing="1">
            <tr>
                <th>发件人</th>
                <th>短信内容</th>
                <th>时间</th>
                <th>操作</th>
            </tr>
            <?php
            while(!!$rows=fetch_array_list($result)) {
                $_html = array();
                $_html['id'] = $rows['m_id'];
                $_html['fromuser'] = $rows['m_fromuser'];
                $_html['content'] = $rows['m_content'];
                $_html['date'] = $rows['m_date'];
                $_html = html_spec($_html);
                ?>
                <tr>
                    <td><?php echo $_html['fromuser']?></td>
                    <td><a href="member_message_detail.php?id=<?php echo $_html['id'];?>" title="<?php echo $_html['content']?>"><?php echo summary($_html['content'])?></a></td>
                    <td><?php echo $_html['date']?></td>
                    <td><input type="checkbox" checked/> </td>
                </tr>
            <?php
            }
            ?>
          </table>

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