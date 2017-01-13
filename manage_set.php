<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','manage_set');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能管理员访问
admin_login();
//读取系统表
if (!!$rows=fetch_array("select * from bbs.bbs_system WHERE sys_id=1")){
    $sysinfo=array();
    $sysinfo['webname']=$rows['sys_webname'];
    $sysinfo['article']=$rows['sys_article'];
    $sysinfo['blog']=$rows['sys_blog'];
    $sysinfo['photo']=$rows['sys_photo'];
    $sysinfo['skin']=$rows['sys_skin'];
    $sysinfo['fiter_str']=$rows['sys_filter_str'];
    $sysinfo['post']=$rows['sys_post'];
    $sysinfo['reply']=$rows['sys_reply'];
    $sysinfo['vcode']=$rows['sys_vcode'];
    $sysinfo['register']=$rows['sys_register'];
    $sysinfo=html_spec($sysinfo);
    //文章数
    if ($sysinfo['article']==10){
        $sysinfo['article_html']='<select name="article">
                                  <option value="10" selected="selected">每页10篇</option>
                                  <option value="15">每页15篇</option>
                                  </select>';
    }elseif ($sysinfo['article']==15){
        $sysinfo['article_html']='<select name="article">
                                  <option value="10">每页10篇</option>
                                  <option value="15" selected="selected">每页15篇</option>
                                  </select>';
    }
    //博友数
    if ($sysinfo['blog']==15){
        $sysinfo['blog_html']='<select name="blog">
                                  <option value="15" selected="selected">每页15人</option>
                                  <option value="20">每页20人</option>
                                  </select>';
    }elseif ($sysinfo['blog']==20){
        $sysinfo['blog_html']='<select name="blog">
                                  <option value="15">每页15人</option>
                                  <option value="20" selected="selected">每页20人</option>
                                  </select>';
    }
    //相册
    if ($sysinfo['photo']==8){
        $sysinfo['photo_html']='<select name="photo">
                                  <option value="8" selected="selected">每页8张</option>
                                  <option value="12">每页12张</option>
                                  </select>';
    }elseif ($sysinfo['photo']==12){
        $sysinfo['photo_html']='<select name="photo">
                                  <option value="8">每页8张</option>
                                  <option value="12" selected="selected">每页12张</option>
                                  </select>';
    }
    //皮肤
    if ($sysinfo['skin']==1){
        $sysinfo['skin_html']='<select name="skin">
                                  <option value="1" selected="selected">皮肤1</option>
                                  <option value="2">皮肤2</option>
                                  <option value="3">皮肤2</option>
                                  </select>';
    }elseif ($sysinfo['skin']==2){
        $sysinfo['skin_html']='<select name="skin">
                                  <option value="1">皮肤1</option>
                                  <option value="2" selected="selected">皮肤1</option>
                                  <option value="3">皮肤3</option>
                                  </select>';
    }elseif ($sysinfo['skin']==3){
        $sysinfo['skin_html']='<select name="skin">
                                  <option value="1">皮肤1</option>
                                  <option value="2">皮肤1</option>
                                  <option value="3" selected="selected">皮肤3</option>
                                  </select>';
    }
    //发贴冻结时间
    if ($sysinfo['post']==30){
        $sysinfo['post_html']='<input type="radio" name="post" value="30" checked>30秒<input type="radio" name="post" value="60">60秒';
    }elseif ($sysinfo['post']==60){
        $sysinfo['post_html']='<input type="radio" name="post" value="30">30秒<input type="radio" name="post" value="60" checked>60秒';
    }
    //跟帖冻结时间
    if ($sysinfo['reply']==15){
        $sysinfo['reply_html']='<input type="radio" name="reply" value="15" checked>15秒
                                <input type="radio" name="reply" value="30">30秒;
                                <input type="radio" name="reply" value="45">45秒';
    }elseif ($sysinfo['reply']==30){
        $sysinfo['reply_html']='<input type="radio" name="reply" value="15">15秒
                                <input type="radio" name="reply" value="30" checked>30秒;
                                <input type="radio" name="reply" value="45">45秒';
    }elseif ($sysinfo['reply']==45){
        $sysinfo['reply_html']='<input type="radio" name="reply" value="15">15秒
                                <input type="radio" name="reply" value="30">30秒;
                                <input type="radio" name="reply" value="45" checked>45秒';
    }
    //验证码
    if ($sysinfo['vcode']==1){
        $sysinfo['vcode_html']='<input type="radio" name="vcode" value="1" checked>启用
                                <input type="radio" name="vcode" value="0">禁用;
                               ';
    }else{
        $sysinfo['vcode_html']='<input type="radio" name="vcode" value="1">启用
                                <input type="radio" name="vcode" value="0" checked>禁用;
                               ';
    }
    //开放注册 
    if ($sysinfo['register']==1){
        $sysinfo['register_html']='<input type="radio" name="register" value="1" checked>启用
                                <input type="radio" name="register" value="0">禁用;
                               ';
    }else{
        $sysinfo['register_html']='<input type="radio" name="register" value="1">启用
                                <input type="radio" name="register" value="0" checked>禁用;
                               ';
    }

}else{
    alert_back('系统表读取错误请联系管理员');
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
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>后台管理-设置</title>
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
        <h2>系统设置</h2>
        <!--?action=modify提交给自己的简写-->
        <form action="?action=set" method="post" name="modify">
            <dl>
                <dd>网站名称：<input type="text" name="webname" class="text" value="<?php echo $sysinfo['webname']?>"> </dd>
                <dd>文章每页列表数:<?php echo $sysinfo['article_html']?></dd>
                <dd>博友每页列表数：<?php echo $sysinfo['blog_html']?></dd>
                <dd>相册每页列表数：<?php echo $sysinfo['photo_html']?></dd>
                <dd>站点默认皮肤：<?php echo $sysinfo['skin_html']?></dd>
                <dd>发帖冻结时间：<?php echo $sysinfo['post_html']?></dd>
                <dd>回帖冻结时间：<?php echo $sysinfo['reply_html']?></dd>
                <dd>是否启用验证码：<?php echo $sysinfo['vcode_html']?></dd>
                <dd>是否开放注册：<?php echo $sysinfo['register_html']?></dd>
                <dd>敏感字符过滤：
                    <input type="text" name="filter_str" class="text" value="<?php echo $sysinfo['fiter_str']?>">
                    （*请用&nbsp;|&nbsp;隔开）
                </dd>
                <dd><input type="submit" name="sbm" value="修改系统" class="submit"> </dd>
            </dl>
        </form>
    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>