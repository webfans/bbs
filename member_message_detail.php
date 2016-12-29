<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','member_message_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登陆
if (!isset($_COOKIE['username'])){
    alert_back('please login,then try again');
}
//短信删除模块
if (@$_GET['action']=='delete'&&isset($_GET['msgid'])){
    //msgid变量不仅要存在，而且还要在数据库里有值，才能执行删除操作
    $rows=fetch_array("select m_id from bbs_message where m_id='{$_GET['msgid']}'");
    if (!!$rows){
        #!当你进行危险操作时（比如删除）之前，最好还要对唯一标识符进行验证!
        if (!!$_rows=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")) {
            //为了防止Cookie伪造，还要比对一下唯一标识符uniqid
            safe_uniquid($_rows['u_uniquid'], $_COOKIE['uniquid']);
        }else{
            alert_back('非常登录');
        }
        ##开始删除操作
        query("delete from bbs_message where m_id={$_GET['msgid']}");
        if (affetched_rows()==1){
            close();
            session_d();
            location('删除成功','member_message.php');
        }else{
            close();
            session_d();
            alert_back('删除失败');
        }
    }else{
        alert_back('此短信不存在');
    }

}
if (isset($_GET['id'])){
    $rows=fetch_array("select m_id,m_fromuser,m_content,m_date,m_state from bbs_message where m_id='{$_GET['id']}'");
    if (!!$rows){
        //进入短信详情，即表示此短信已经读过，故此时将 m_state由0更新为1
        if ($rows['m_state']==0){
            query("update bbs_message set m_state=1 where m_id='{$_GET['id']}'");
        }
        if (!affetched_rows()){
            alert_back('异常');
        }
        $_html=array();
        $_html['id']=$rows['m_id'];
        $_html['fromuser']=$rows['m_fromuser'];
        $_html['content']=$rows['m_content'];
        $_html['date']=$rows['m_date'];
        $_html=html_spec($_html);
    }else{
        alert_back('此短信不存在');
    }
}else{
    alert_back('非法登录');
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/member_modify.js"></script>
    <script type="text/javascript" src="js/member_message_detail.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>短信详情</title>
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
        <h2>短信详情</h2>
            <dl>
                <dd>发件人:<?php echo $_html['fromuser']?></dd>
                <dd>短信内容:<?php echo $_html['content']?></dd>
                <dd>发送时间:<?php echo $_html['date']?></dd>
                <dd class="btn">
                    <input type="button" value="返回列表" id="gobacklist">
                    <input type="button" value="删除短信" id="delete" title="<?php echo $_html['id'];?>">
                </dd>
            </dl>

    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>