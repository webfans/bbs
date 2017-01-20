<?php
//error输出
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
session_start();
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','article');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//处理跟贴
if ($_GET['action']=='RE'){
    //include 在需要时引入
    include ROOT_PATH.'includes/check.func.php';
    #安全验证#
    //1.验证码验证
    //check_vcode($_POST['vcode'],$_SESSION['vcode']);
    //2.对比唯一标识符
    if (!!$row=fetch_array("select u_uniqid,u_replytime from bbs_user where u_username='{$_COOKIE['username']}'")){
        safe_uniqid($row['u_niqid'],$_SESSION['uniqid']);
        //限制频繁跟帖
        global $sys;
        //post_checktime(time(),$_COOKIE['first_replytime'],30);
        post_checktime(time(),$row['u_replytime'],$sys['relplylimit']);
        //接受跟贴表单数据
        $clean=array();
        $clean['reid']=$_POST['reid'];
        $clean['retype']=$_POST['retype'];
        $clean['retitle']=$_POST['retitle'];
        $clean['recontent']=$_POST['content'];
        $clean['reusername']=$_COOKIE['username'];
        $clean=mysql_string($clean);
        //写入数据库
        query("insert into bbs.bbs_article(
                                      art_reid,
                                      art_username,
                                      art_title,
                                      art_type,
                                      art_content,
                                      art_date
                                      )
                               VALUES (
                                      '{$clean['reid']}',
                                      '{$clean['reusername']}',
                                      '{$clean['retitle']}',
                                      '{$clean['retype']}',
                                      '{$clean['recontent']}',
                                       NOW()
                                      )"
             );
        if (affetched_rows()==1){
            //第一次回帖成功后，#1写入cookie #2或者写入数据库
            //setcookie('firt_replytime',time());
            $clean['replytime']=time();
            query("update bbs_user set u_replytime='{$clean['replytime']}' WHERE u_username='{$_COOKIE['username']}'");
            //每成功增加一条回复帖子，评论数自增1
            query("UPDATE bbs_article SET art_comment=art_comment+1 where art_reid=0 and art_id='{$clean['reid']}'");
            location('回贴成功','article.php?id='.$clean['reid']);
            //清空session,腾出内存
             //session_d();
            close();
        }
        else{
            alert_back('回贴失败');
             //session_d();
            close();
        }
    }else{
        alert_back('唯一标识符异常');
    }
}
//#1.读出主题帖子数据#
if (isset($_GET['id'])){
    //ID存在，且数据库里有合法数据
    $rows=fetch_array("select * from bbs.bbs_article where art_reid=0 and art_id='{$_GET['id']}'");
    //数据存在
    if (!!$rows){
      //创建一个数组存放数据
        $_html=array();
        $_html['subject_id']=$rows['art_id'];
        $_html['username_subject']=$rows['art_username'];
        $_html['type']=$rows['art_type'];
        $_html['title']=$rows['art_title'];
        $_html['content']=$rows['art_content'];
        $_html['date']=$rows['art_date'];
        $_html['modifydate']=$rows['art_lastmodify'];
        $_html['readcount']=$rows['art_readcount'];
        $_html['comment']=$rows['art_comment'];
        //累积阅读量
        query("update bbs_article set art_readcount=art_readcount+1 where art_id='{$_GET['id']}'");
        
        //拿出用户名去查用户信息(email,url)
        $_rows=fetch_array("select u_id,u_sex,u_face,u_email,u_url,u_switch,u_autograph 
                            from bbs.bbs_user WHERE u_username='{$_html['username_subject']}'
                          ");
        if (!!$_rows){
           //读取用户信息
           $_html['userid']=$_rows['u_id'];
           $_html['sex']=$_rows['u_sex'];
           $_html['face']=$_rows['u_face'];
           $_html['email']=$_rows['u_email'];
           $_html['url']=$_rows['u_url'];
           $_html['switch']=$_rows['u_switch'];
           $_html['autograph']=$_rows['u_autograph'];
           $_html=html_spec($_html);
           //点击【编辑】 修改主题贴模块
           if ($_html['username_subject']==$_COOKIE['username']) {
            	$_html['subject_modify']='[<a href="article_modify.php?id='.$_html['subject_id'].'">编辑</a>]';
           }
           //读取最后的修改时间
            if ($_html['modifydate']!='0000-00-00 00:00:'){
               $_html['modifydate_new']='本贴已由['.$_html['username_subject'].']于'.$_html['modifydate'].'修改过';
            }
            //回复楼主
            if(isset($_COOKIE['username'])){
                $_html['subjectreply_html']='<span><a href="#replyform" name="reply" title="回复1楼的'.$_html['username_subject'].'">[回复]</a></span>';
            }
            //个性签名
            if ($_html['switch']==1){
                $_html['autograph_html']=' <p class="autograph"><span></span>'.ubb($_html['autograph']).'</p>';
            }

           //#2.读取跟帖信息#
                     //创建一个全局变量，做一个带参数的分页
        			  global $_id;
        			  $_id='id='.$_html['subject_id'].'&';
                //分页模块 分页容错处理
                global $_pagenum,$_pagesize,$_page;
                $count_sql="select art_id from bbs.bbs_article WHERE art_reid='{$_html['subject_id']}'";
                paging_fault_tolerant($count_sql,6);
                //从数据库读取数据
                $reply_sql="select * from bbs.bbs_article 
                                  WHERE art_reid='{$_html['subject_id']}' 
                                                       ORDER BY art_date ASC 
                                                       LIMIT $_pagenum,$_pagesize";
                //取得跟贴数据集
                $reply_result=query($reply_sql);

        }else{
            //这个用户已经被删除
        }
    }else{
        alert_back('文章ID不存在');
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
    <!--<title>帖子页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="article">
    <h2>帖子</h2>
    <!--第1页才显示楼主的主题贴;第2页后，不再显示了，只显示回复贴-->
    <?php if ($_page==1){?>
        <div id="subject">
            <dl>
                <dd class="user"><?php echo $_html['username_subject'];?>(<?php echo $_html['sex'];?>)[楼主]</dd>
                <dt><img src="<?php echo $_html['face'];?>" alt="<?php echo $_html['username_subject'];?>"/></dt>
                <dd class="message"><a name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
                <dd class="friend"><a name="friend" title="<?php echo $_html['userid']?>">加好友</a></dd>
                <dd class="guest">写留言</dd>
                <dd class="flower"><a name="flower" title="<?php echo $_html['userid']?>">给他送花</a></dd>
                <dd class="email"><a href="mailto:<?php echo $_html['email'];?>">邮件:<?php echo $_html['email'];?></a></dd>
                <dd class="url"><a href="<?php echo $_html['url'];?>" target="_blank">网址:<?php echo $_html['url'];?></a></dd>
            </dl>
            <div class="content">
                <div class="user">
                    <span><?php echo $_html['subject_modify']?> 1楼</span><?php echo $_html['username_subject']?>| 发表于：<?php echo $_html['date']?>
                </div>
                <h3>
                    主题：<?php echo $_html['title']?> <img src="images/icon<?php echo $_html['type']?>.gif" alt="icon">
                    <?php echo $_html['subjectreply_html']?>
                </h3>
                <div class="detail">
                    <?php echo ubb($_html['content'])?>
                    <?php echo $_html['autograph_html']?>
                </div>
                <div class="read">
                    阅读量：(<?php echo $_html['readcount']?>) 评论量：(<?php echo $_html['comment']?>)&nbsp;<?php echo $_html['modifydate_new']?>
                </div>    　
            </div>
        </div>
    <?php }?>
    <p class="line"></p><!--分割线-->
    <?php
#START跟帖楼层开始START#
        ####楼层算法解释START#################################################
        /*
        $floor=2;楼主算1楼，第一个回贴的算2楼
         假设$_pagesize=2，即每页分2条数据，那么
         第1页 楼层号 2，3
         第2页 楼层号 4，5
         第3页 楼层号 6，7
        $floor=2
           $page=1 第1页循环
            第1页循第1个 $floor=$floor+(($page-1)*$_pagesize)= 2+(0*2)=2;
            第1页循第2个 $floor=$floor+(($page-1)*$_pagesize)= 3+(0*2)=3;
        $floor++

        $floor=2
           $page=2 第2页循环
            第2页循环第1个 $floor=$floor+(($page-1)*$_pagesize)= 2+(1*2)=4;
            第2页循环第2个 $floor=$floor+(($page-1)*$_pagesize)= 3+(1*2)=5;
        $floor++

        $floor=2
           $page=3 第3页循环
            第3页循环第1个 $floor=$floor+(($page-1)*$_pagesize)= 2+(2*2)=6;
            第3页循环第2个 $floor=$floor+(($page-1)*$_pagesize)= 3+(2*2)=7;
        $floor++
        */
       ####楼层算法END##########################################################

     $floor=2;
     while(!!$rows=fetch_array_list($reply_result))
     {      $_html['reply_id']=$rows['art_id'];
            $_html['username_reply']=$rows['art_username'];
            $_html['retitle']=$rows['art_title'];
            $_html['type']=$rows['art_type'];
            $_html['content']=$rows['art_content'];
            $_html['reply_modifydate']=$rows['art_lastmodify'];
            $_html['date']=$rows['art_date'];
        //拿出用户名去查跟帖用户信息(email,url)
        $_rows=fetch_array("select u_id,u_sex,u_face,u_email,u_url,u_switch,u_autograph 
                             from bbs.bbs_user WHERE u_username='{$_html['username_reply']}'");
        if (!!$_rows){
            //读取跟帖用户信息
            $_html['userid']=$_rows['u_id'];
            $_html['sex']=$_rows['u_sex'];
            $_html['face']=$_rows['u_face'];
            $_html['email']=$_rows['u_email'];
            $_html['url']=$_rows['u_url'];
            $_html['switch']=$_rows['u_switch'];
            $_html['reply_autograph']=$_rows['u_autograph'];
            $_html=html_spec($_html);

            //点击【编辑】 修改回复贴模块
            //如果不写下面的else 导致下面的非当前登录用户楼层，都会溢出$_html['reply_modify']源码
            if ($_html['username_reply']==$_COOKIE['username']) {
                $_html['reply_modify']='[<a href="article_modify.php?id='.$_html['reply_id'].'">编辑</a>]';
            }else{
                $_html['reply_modify']=null;
            }
            //读取最后的修改时间
            if ($_html['reply_modifydate']!='0000-00-00 00:00:'){
                $_html['reply_modifydate_new']='本贴已由['.$_html['username_reply'].']于'.$_html['reply_modifydate'].'修改过';
            }
            //楼层沙发设置
            //bug表现状况，只要是楼主抢了沙发，后边回复的所有贴，左侧用户名都会变成楼主的信息，并且下面的楼层都成沙发了
            /*if ($floor==2){
                if ($_html['username']==$_html['username_subject']){
                    $_html['username_html']=$_html['username'].'(楼主)';
                }else{
                    $_html['username_html']=$_html['username'].'(沙发)';
                }
            }*/
            //必须加$_page==1否则到第2页，第1条数据又变成沙发了
            if($_page==1&&$floor==2){
                if ($_html['username_reply']==$_html['username_subject']){
                    $_html['username_html']=$_html['username_reply'].'(楼主)';
                }else{
                    $_html['username_html']=$_html['username_reply'].'(沙发)';
                }
            }
            //此处还有问题，跟帖强到了沙发，还能再做板凳吗？
            elseif ($_page==1&&$floor==3&&$_html['username_reply']!==$_html['username_subject']){
                $_html['username_html']=$_html['username_reply'].'(板凳)';
            } else{
                $_html['username_html']=$_html['username_reply'];
            }
            //跟帖回复 只有在登录情况下才 显示[回复]
            if (isset($_COOKIE['username'])){
                $_html['reply_floor']='回复'.($floor+(($_page-1)*$_pagesize)).'楼的'.$_html['username_reply'].'';
                $_html['reply_html']='<span><a href="#replyform" name="reply" title="'.$_html['reply_floor'].'">[回复]</a></span>';
            }
            //个性签名 //不能写在这里，因为有循环会累积，溢出，直接写在下面的跟贴界面处
           /* if ($_html['switch']==1){
                $_html['reply_autograph']=' <p class="autograph"><span>个性签名:</span>'.$_html['autograph'].'</p>';
            }*/

        }else{
            //这个用户可能已经被删除，先不做这块儿
            echo '这个用户可能已经被删除';
        }
    ?>
        <!--#以下是跟贴楼界面-->
        <div class="re">
            <dl>
                <dd class="user"><?php echo $_html['username_html'];?>(<?php echo $_html['sex'];?>)</dd>
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
                    <span><?php echo $_html['reply_modify']?>&nbsp;<?php echo $floor+(($_page-1)*$_pagesize);//楼层算法?>楼</span><?php echo $_html['username_reply']?>| 发表于：<?php echo $_html['date']?>
                </div>
                <h3>
                    主题：<?php echo $_html['retitle']?> <img src="images/icon<?php echo $_html['type']?>.gif" alt="icon">
                    <?php echo $_html['reply_html']?>
                </h3>
                <div class="detail">
                    <?php echo ubb($_html['content'])?>
                    <?php
                    if ($_html['switch']==1){
                        echo ' <p class="autograph"><span></span>'.ubb($_html['reply_autograph']).'</p>';
                    }
                    ?>
                </div>
                <div class="read"><?php echo $_html['reply_modifydate_new']?></div>
            </div>
        </div>
        <p class="line"></p>
    <?php
     $floor++;//每循环一次楼层+1
     }
    mysql_free_result($reply_result);
    paging(1);
#END回贴楼层结束END#
    ?>
    <!--##以下是[跟贴表单],仅登录可见-->
    <?php if (isset($_COOKIE['username'])){?>
        <a name="replyform"></a><!--回复锚点-->
        <form action="?action=RE" method="post">
            <input type="hidden" name="reid" value="<?php echo $_html['subject_id']?>">
            <input type="hidden" name="retype" value="<?php echo $_html['type']?>">
            <dl>
                <dd>标题：&emsp;<input type="text" name="retitle" class="text" value="RE:<?php echo $_html['title']?>">(*必填，2-40位)</dd>
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
                    <input type="submit" name="submit" class="submit" value="发送文章">
                </dd>
            </dl>
        </form>
    <?php }?>


</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>