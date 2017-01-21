<?php
session_start();
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','photo_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//只能会员才能查看
if (!isset($_COOKIE['username'])){
    alert_back('请登录后使用上传');
}
//写入照片评论
if ($_GET['action']=='rephoto'){
    global $sys;
    //include 在需要时引入
    include ROOT_PATH.'includes/check.func.php';
    #安全验证#
    //#1.验证码验证
    if ($sys['vcode']==1){
        check_vcode($_POST['vcode'],$_SESSION['vcode']);
    }
    //#2.对比唯一标识符
    block_fake_cookie();
    //接受评论照片表单是数据，入库
    $rephoto=array();
    $rephoto['title']=$_POST['retitle'];
    $rephoto['content']=$_POST['content'];
    $rephoto['sid']=$_POST['rephoto_sid'];
    $rephoto['username']=$_COOKIE['username'];
    $rephoto=mysql_string($rephoto);
    //写入数据库
    query("insert into bbs.bbs_rephoto(
                                      rephoto_title,
                                      rephoto_content, 
                                      rephoto_sid, 
                                      rephoto_username, 
                                      rephoto_date
                                      )
                               VALUES (
                                      '{$rephoto['title']}',
                                      '{$rephoto['content']}',
                                      '{$rephoto['sid']}',
                                      '{$rephoto['username']}',
                                       NOW()
                                      )"
    );
   // after_query('照片评论成功','照片评论失败','photo_detail.php?imgid='.$rephoto['sid']);
    if (affetched_rows()==1){
        //照片每成功增加一条评论，照片评论量自增1
        query("UPDATE bbs_photo SET photo_commentcount=photo_commentcount+1 where photo_id='{$rephoto['sid']}'");
        location('照片评论成功','photo_detail.php?imgid='.$rephoto['sid']);
        close();
    }
    else{
        alert_back('照片评论失败');
        close();
    }

}
//读取照片数据bbs_photo
if (isset($_GET['imgid'])){
    if (!!$rows=fetch_array("select * from bbs.bbs_photo WHERE photo_id='{$_GET['imgid']}'")){
        //累积阅读量
        query("update bbs_photo set photo_readcount=photo_readcount+1 where photo_id='{$_GET['imgid']}'");
        $get_photo=array();
        $get_photo['id']=$rows['photo_id'];
        $get_photo['name']=$rows['photo_name'];
        $get_photo['url']=$rows['photo_url'];
        $get_photo['owner']=$rows['photo_owner'];
        $get_photo['readcount']=$rows['photo_readcount'];
        $get_photo['commentcount']=$rows['photo_commentcount'];
        $get_photo['content']=$rows['photo_content'];
        $get_photo['date']=$rows['photo_date'];
        $get_photo=html_spec($get_photo);
        //由照片ID去照片评论表里取评论信息 photo_id=rephoto_sid
        //#读取照片评论信息bbs_rephoto
        //创建一个全局变量，做一个带参数的分页
        global $_id;
        $_id='imgid='.$get_photo['id'].'&';
        //分页模块 分页容错处理
        global $_pagenum,$_pagesize,$_page;
        $count_sql="select rephoto_id from bbs.bbs_rephoto WHERE rephoto_sid='{$get_photo['id']}'";
        paging_fault_tolerant($count_sql,2);
        //从数据库读取数据
        $reply_sql="select * from bbs.bbs_rephoto
                                  WHERE rephoto_sid='{$get_photo['id']}' 
                                                       ORDER BY rephoto_date ASC 
                                                       LIMIT $_pagenum,$_pagesize";
        //取得照片评论数据集
        $rephoto_result=query($reply_sql);
    }else{
        alert_back('不存在此相册');
    }
}else{
    alert_back('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/vcode.js"></script>
    <script type="text/javascript" src="js/article.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
    <h2>图片详情</h2>
    <dl class="detail">
        <dd class="name"><?php echo $get_photo['name']?></dd>
        <dt><img src="<?php echo $get_photo['url']?>"></dt>
        <dd>
            浏览量(<strong><?echo $get_photo['readcount']?></strong>)&nbsp;
            评论量(<strong><?php echo $get_photo['commentcount']?></strong>)&nbsp;
            上传者:<?php echo $get_photo['owner']?>&nbsp;上传于:<?php echo $get_photo['date']?>
        </dd>
        <dd>简介:<?php echo $get_photo['content']?></dd>
    </dl>
    <!--#以下是照片评论显示界面,复制自article 可以做成一个包含文件-->
    <?php
    $floor=1;
    while(!!$rows=fetch_array_list($rephoto_result))
   {
        $get_rephoto['id']=$rows['rephoto_id'];
        $get_rephoto['username']=$rows['rephoto_username'];
        $get_rephoto['title']=$rows['rephoto_title'];
        $get_rephoto['content']=$rows['rephoto_content'];
        $get_rephoto['date']=$rows['rephoto_date'];
    //拿出用户名去查评论者信息(email,url)
    $_rows=fetch_array("select u_id,u_sex,u_face,u_email,u_url,u_switch,u_autograph,u_username 
                             from bbs.bbs_user WHERE u_username='{$get_rephoto['username']}'");
    if (!!$_rows) {
        //读取跟帖用户信息
        $_html['userid'] = $_rows['u_id'];
        $_html['username'] = $_rows['u_username'];
        $_html['sex'] = $_rows['u_sex'];
        $_html['face'] = $_rows['u_face'];
        $_html['email'] = $_rows['u_email'];
        $_html['url'] = $_rows['u_url'];
        $_html['switch'] = $_rows['u_switch'];
        $_html['reply_autograph'] = $_rows['u_autograph'];
        $_html = html_spec($_html);
    }

    ?>
    <p class="line"></p><!--分割线-->
    <div class="re">
        <dl>
            <dd class="user"><?php echo $get_rephoto['username'];?>(<?php echo $_html['sex'];?>)</dd>
            <dt><img src="<?php echo $_html['face'];?>" alt="facepic"/></dt>
            <dd class="message"><a name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
            <dd class="friend"><a name="friend" title="<?php echo $_html['userid']?>">加好友</a></dd>
            <dd class="guest">写留言</dd>
            <dd class="flower"><a name="flower" title="<?php echo $_html['userid']?>">给他送花</a></dd>
            <dd class="email"><a href="mailto:<?php echo $_html['email'];?>">邮件:<?php echo $_html['email'];?></a></dd>
            <dd class="url"><a href="<?php echo $_html['url'];?>" target="_blank">网址:<?php echo $_html['url'];?></a></dd>
        </dl>
        <div class="content">
            <div class="user">
                <span><?php echo $floor+(($_page-1)*$_pagesize);//楼层算法?>楼</span><?php echo $get_rephoto['username']?>| 发表于：<?php echo $get_rephoto['date']?>
            </div>
            <h3>
                主题：<?php echo $get_rephoto['title']?>
            </h3>
            <div class="detail">
                <?php echo ubb($get_rephoto['content'])?>
                <?php
                if ($_html['switch']==1){
                    echo ' <p class="autograph"><span></span>'.ubb($_html['reply_autograph']).'</p>';
                }
                ?>
            </div>
            <div class="read"><?php echo $_html['reply_modifydate_new']?></div>
        </div>
    </div>
    <?php
    $floor++;
   }
    //分页
    paging(1);
    paging(2);
    ?>
    <!--##以下是[评论照片表单],仅登录可见，可以做成一个包含文件-->
    <?php if (isset($_COOKIE['username'])){?>
        <p class="line"></p><!--分割线-->
        <form action="?action=rephoto" method="post">
            <input type="hidden" name="rephoto_sid" value="<?php echo $get_photo['id']?>">
            <dl class="re_photo">
                <dd>标题：&emsp;<input type="text" name="retitle" class="text" value="RE:<?php echo $get_photo['name']?>"></dd>
                <dd id="q">贴图：<a href="javascript:;">Q贴图1</a><a href="javascript:;">Q贴图2</a><a href="javascript:;">Q贴图3</a> </dd>
                <dd>
                    <?php require ROOT_PATH.'includes/ubb.inc.php'; ?>
                    <textarea  name="content" rows="10"></textarea></dd>
                <dd>
                    <?php
                    if ($sys['vcode']==1){
                        echo  '验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/>';
                    }
                    ?>
                    <input type="submit" name="submit" class="submit" value="提交评论">
                </dd>
            </dl>
        </form>
    <?php
    }
    ?>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>