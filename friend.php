<?php
session_start();
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','message');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
require dirname(__FILE__) . '/includes/check.func.php';
//判断是否登录
if (!isset($_COOKIE['username'])){
    alert_back_close('请登录后尝试');
}
if ($_GET['action']=='addfri'){
    //验证吗检验
    check_vcode($_POST['vcode'],$_SESSION['vcode']);
    if (!!$rows=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")) {
        //为了防止Cookie伪造，还要比对一下唯一标识符uniquid
        safe_uniquid($rows['u_uniquid'], $_COOKIE['uniquid']);
        $_clean = array();
        $_clean['touser'] = $_POST['touser'];
        $_clean['fromuser'] = $_COOKIE['username'];
        $_clean['content'] = check_content($_POST['contents'], 5, 200);
        //一次性转义数据
        $_clean = mysql_string($_clean);
        #添加好友前，从数据库里验证是否已经添加好友了
        $_rows=fetch_array("select f_id 
                                   from bbs_friends
                                   where f_touser='{$_clean['touser']}' and f_fromuser='{$_clean['fromuser']}'
                                   OR f_fromuser='{$_clean['touser']}' and f_touser='{$_clean['touser']}'
                                   limit 1
                           ");
        if (!!$_rows){
            alert_back_close('你们已经是好友或未验证的好友，不需重复添加');
        }else{
            //不能添加自己为好友
            if ($_clean['touser']==$_clean['fromuser']){
                alert_back('哈哈，自恋了吧！^_^您不能添加自己为好友哦');
            }
            ##开始添加好友信息
            query("insert into bbs_friends 
                                (
                                 f_touser,
                                 f_fromuser,
                                 f_content,
                                 f_date
                                ) 
                          VALUE (
                                 '{$_clean['touser']}',
                                 '{$_clean['fromuser']}',
                                 '{$_clean['content']}',
                                 NOW()        
                                 )
                  ");
            if (affetched_rows()==1){
                //清空session,腾出内存
                session_d();
                close();
                alert_back_close('好友添加成功');
            }
            else{
                session_d();
                close();
                alert_back('好友添加失败');
            }
        }

    }

}


//获取数据
if (isset($_GET['id'])){
    $sql="select u_username from bbs_user where u_id='{$_GET['id']}' limit 1";;
    $rows=fetch_array($sql);
    //如果有数据
    if (!!$rows){
        $_html=array();
        $_html['touser']=$rows['u_username'];
        $_html=html_spec($_html);
    }else{
        alert_back_close('不存在此用户');
    }
}else{
    alert_back_close('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <script type="text/javascript" src="js/vcode.js"></script>
    <script type="text/javascript" src="js/message.js"></script>
    <title>添加好友</title>
</head>
<body>
<div id="message">
    <h2>加好友</h2>
    <form method="post" action="?action=addfri">
        <input type="hidden" name="touser" value="<?php echo $_html['touser'] ?>"/>
        <dl>
            <dd><input type="text" readonly="readonly" class="text" value="<?php echo 'TO:'.$_html['touser'];?>"></dd><!--用来显示，上边的隐藏字段用来传递数据-->
            <dd><textarea name="contents">你好，我非常想和你交朋友！</textarea></dd>
            <dd>验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/> </dd>
            <dd><input type="submit" name="submit" class="submit" value="添加好友"> </dd>
        </dl>
    </form>
</div>
</body>
</html>