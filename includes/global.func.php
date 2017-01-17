<?php
//ession_start();//调用页有自己的session_start() 移除重复
//获取运行耗时
/*
 @runtime()
 @public 函数公开
 @return float 返回值为浮点型
 * */
function runtime(){
    $mtime=explode(' ',microtime());
    return $mtime[0]+$mtime[1];
}
//管理员登录
function admin_login(){
    if (!isset($_COOKIE['username'])||!isset($_SESSION['admin'])||($_COOKIE['username'])!=$_SESSION['admin']){
        alert_back('非法登录');
    }
}


//限制短时间内频繁发帖
function post_checktime($now_time,$past_time,$sec=60){
    if ($now_time-$past_time<$sec){
        alert_back('请阁下休息一会儿再发贴');
    }
}
function alert_back($msg){
    //history.go(-1):后退+刷新 history.back():后退
    echo "<script type=text/javascript>alert('".$msg."');history.back();</script>";
    exit();
}
function alert_back_close($msg){
    //history.go(-1):后退+刷新 history.back():后退
    echo "<script type=text/javascript>alert('".$msg."');window.close();</script>";
    exit();
}

//页面跳转
function location($msg,$url){
    if (!!$msg) {
        echo "<script type=text/javascript>alert('$msg');location.href='$url';</script>";
    }
    else{
        header('location:'.$url);
    }
}
//处理form表单自动转义
function mysql_string($string){
    //get_magic_quotes_gpc()如果开启状态，那么就不需要转义
    if (!GPC){
        $conn=@mysql_connect('localhost','root','398692315');
        if (!$conn)
        {
            die('Could not connect: ' . mysql_error());
        }
        if (is_array($string)){
            foreach ($string as $key=>$value){
                //$str[$key]=htmlspecialchars($value);//方法一
                $string[$key]=mysql_string($value);//此处用了递归 //方法二
            }
        }else{
            $string=mysql_real_escape_string($string,$conn);
        }
    }
    return $string;
}
function sha1_uniqid(){
    return mysql_string(sha1(uniqid(rand(),true)));
}
//登录状态 在登录状态下防止通过直接在浏览器里通过输入register.php来注册
function block_login_reg(){
    if (isset($_COOKIE['username'])){
        alert_back('登录状态下无法进行本操作');
    }
}
//销毁session
function session_d(){
   /* if (session_start()){
        session_destroy();
    }*/
   session_destroy();
}
//销毁cookie
function cookie_d(){
    setcookie('username','',time()-1);
    setcookie('uniqid','',time()-1);
     //session_d();
    location('','index.php');
}
//生成验证码
/*
 @vode()
 @public 函数公开
 @参数1 验证框的宽度；参数2 高度；参数3，验证码的个数；参数4，是否要边框
 @默认大小是75*25 长度4位;6位，框宽度建议125；8位，框宽度建议175;
 * */
function vcode($width=75,$height=25,$vcode_num=4,$border_flag=false){
    //随机码的个数
     //$vcode_num=4;

    //创建随机码
    $nmsg='';
    for ($i=0;$i<$vcode_num;$i++){
        $nmsg.=dechex(mt_rand(0,15));
    }
//把随机码保存在session 以实现 跨页面
    $_SESSION['vcode']=$nmsg;
//echo $_SESSION['vcode'];

//创建验证码图像
    //$width=75;
    //$height=25;

//创建图像资源
    $img=imagecreatetruecolor($width,$height);

//创建一个画笔，分配颜色
    $white=imagecolorallocate($img,255,255,255);
    $black=imagecolorallocate($img,0,0,0);
//填充颜色
    imagefill($img,0,0,$white);

    //$flag=false;
    if ($border_flag){
        //创建边框
        imagerectangle($img,0,0,$width-1,$height-1,$black);
    }
//随机画6条线条
    for ($i=0;$i<6;$i++){
        $ling_color=imagecolorallocate($img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
        imageline($img,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$ling_color);
    }
//随机雪花
    for ($i=0;$i<6;$i++){
        $flower_color=imagecolorallocate($img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
        imagestring($img,1,mt_rand(1,$width),mt_rand(1,$height),'*',$flower_color);
    }
//输出验证码

    for ($i=0;$i<strlen($_SESSION['vcode']);$i++){
        $font_size=mt_rand(3,5);
        //下面的这一句有变量$i，必须把这一句放入for内，否则$I的值就不不会变，根本显示不出来验证码，我就犯了这个错误
        //$i*$width/4 在x轴的方向，将4个字符分开，分别1/4 2/4 3/4 4/4附近
        $pos_x=$i*$width/$vcode_num+mt_rand(1,10);

        $pos_y=mt_rand(1,$height/2);
        $string=$_SESSION['vcode'][$i];
        $string_color=mt_rand(0,150);

        imagestring($img,$font_size,$pos_x,$pos_y,$string,$string_color);
        //imagestring($img,5,$i,$i,$_SESSION['vcode'][$i],$black);
    }
//输出图像
    header('Content-Type:image/png');
    imagepng($img);
//销毁图像
    imagedestroy($img);
}
//分页容错函数
function paging_fault_tolerant($sql,$size){
    //这样外部调用页的sql 才能认识这两个函数内部变量的值
    global  $_pagesize,$_pagenum;
    //这样的话下边的分页pageing()函数才能认识这几个变量取到值
    global $_page,$_page_absolute,$total_num;
    
    if (isset($_GET['page'])){
        $_page=@$_GET['page'];
        if (empty($_page)||$_page<=0||!is_numeric($_page)){
            //防止page存在，但是空值（0）或是负值或不是数字
            $_page=1;
        }else{
            //防止$page=2.5这种小数情况，把它取为整数
            $_page=intval($_page);
        }
    } else{
        //如果直接访问blog.php显然page不存在，则默认page是0，则导致$_pagenum=负值，进而引起sql执行出错
        //所以默认给它赋值1，是为容错处理
        $_page=1;
    }
    //每页多少条数据
    $_pagesize=$size;
    //从第几条数据开始读起
    $_pagenum=($_page-1)*$_pagesize;
    
    //统计数据库中数据总条数
    $total_num=mysql_num_rows(query($sql));
    //!防止数据库清零后(数据库没数据)，总数据条数为0的情况
    if ($total_num==0){
        $_page_absolute=1;
    }else{
        //数据库里有数据
        //ceil()进一取整法,例如$_page_absolute=3.1也算为4页
        //根据总数据条数，算出总页码数
        $_page_absolute=ceil($total_num/$_pagesize);
        //echo $_page_absolute;
    }
    //处理 如果page>总页码数的情况
    if ($_page>$_page_absolute){
        $_page=$_page_absolute;
    }
}
//分页函数
//$pageing_type=1 数字分布 2：文本分页 3：同时两效果
function paging($paging_type){
    //这里把这几个变量声明为全局变量，这样才能用blog.php页面在该函数声明的变量的值
    //另外一种访问函数外变量的值，就是直接参数传递
    //$_id 专门用于 回贴分页
    global $_page,$_page_absolute,$total_num,$_id;
    #1数字分页模式 
    if ($paging_type=='num'||$paging_type==1){
         echo '<div id="page_num">';
            echo '<ul>';
                for ($i=0;$i<$_page_absolute;$i++){
                    if ($_page==($i+1)){
                    	 //加上当前页选中状态的样式
                        echo '<li><a class="selected" href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a> </li>';
                    }
                    else{
                        echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a> </li>';
                    }
                }
            echo '</ul>';
        echo '</div>';

    }
    ##2文本分页模式
    elseif ($paging_type=='text'||$paging_type==2){
             echo '<div id="page_text">';
                echo '<ul>';
                        echo '<li>'.$_page.'/'.$_page_absolute.'页 |</li>';
                        echo '<li>共有<strong>'.$total_num.'</strong>条数据 |</li>';
                    if($_page==1){
                        //如果是首页，那么首页和上一页无效，不能点击
                        echo '<li>首页</li>';
                        echo '<li>上一页</li>';
                    }else{
                        //echo '<li><a href="'.$_SERVER[SCRIPT_NAME].'.php">首页</a> </li>';
                        echo '<li><a href="'.SCRIPT.'.php">首页</a> </li>';
                        echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'">上一页</a> </li>';
                    }
                    if ($_page==$_page_absolute){
                        //如果是尾页，则尾页和下一页无效，不能点击
                        echo '<li>下一页</li>';
                        echo '<li>尾页</li>';
                    }else{
                        echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'">下一页</a> </li>';
                        echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.$_page_absolute.'">尾页</a> </li>';
                    }
                echo '</ul>';
            echo '</div>';
        }else{
        //同时支持两种分布模式
           paging(1);
           paging(2);
    }
}

//转义HTML特殊字符
//如果是数组按数组的方式过滤，如果是字符串则按字符串方式过渡
function html_spec($str){
    if (is_array($str)){
        foreach ($str as $key=>$value){
            //$str[$key]=htmlspecialchars($value);//方法一
            $str[$key]=html_spec($value);//此处用了递归 //方法二
        }
    }else{
        $str=htmlspecialchars($str);
    }
    return $str;
}
//为了防止Cookie伪造，还要比对一下唯一标识符uniqid
function safe_uniqid($mysql_uniqid,$cookie_uniqid){
    if ($mysql_uniqid!=$cookie_uniqid){
        alert_back('唯一标识符异常');
    }
}
//再次封装，防止Cookie伪造
function block_fake_cookie(){
    if (!!$_rows=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")) {
        //为了防止Cookie伪造，还要比对一下唯一标识符uniqid
        safe_uniqid($_rows['u_uniqid'], $_COOKIE['uniqid']);
    }else{
        alert_back('不要法登录');;
    }
}
//affecch_rows

//长文本以摘要的形式显示
function summary($str,$length=14){
    if (mb_strlen($str,'utf-8')>$length){
        $str=mb_substr($str,0,$length,'utf-8').'...';
    }
    return $str;
}
//读取XML
function get_xml($xmlfile){
    if (file_exists($xmlfile)){
        $xml_content=file_get_contents($xmlfile);
        //第一次过滤筛选
        preg_match_all('/<vip>(.*)<\/vip>/s',$xml_content,$_dom);
        //print_r($_dom);
        //再次筛选
        foreach ($_dom[1] as $value){
            preg_match_all('/<id>(.*)<\/id>/s',$value,$_id);
            //print_r($_id);//二维数组
            preg_match_all('/<username>(.*)<\/username>/s',$value,$_username);
            preg_match_all('/<sex>(.*)<\/sex>/s',$value,$_sex);
            preg_match_all('/<face>(.*)<\/face>/s',$value,$_face);
            preg_match_all('/<email>(.*)<\/email>/s',$value,$_email);
            preg_match_all('/<url>(.*)<\/url>/s',$value,$_url);
            $_html['id']=$_id[1][0];
            $_html['username']=$_username[1][0];
            $_html['sex']=$_sex[1][0];
            $_html['face']=$_face[1][0];
            $_html['email']=$_email[1][0];
            $_html['url']=$_url[1][0];

        }
    }else{
        echo 'file is not Exist';
    }
    return $_html;
}
//生成 XML
function set_xml($xmlfile,$clean){
    $fp=fopen($xmlfile,'w');
    if (!$fp){
        exit('系统错误，文件不存在');
    }
    flock($fp,LOCK_EX);//锁定
    $string="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="<vip>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<id>{$clean['id']}</id>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<username>{$clean['username']}</username>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<sex>{$clean['sex']}</sex>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<face>{$clean['face']}</face>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<email>{$clean['email']}</email>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<url>{$clean['url']}</url>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="</vip>\r\n";
    fwrite($fp,$string,strlen($string));
    flock($fp,LOCK_UN);//解锁
    fclose($fp);

}
//ubb
function ubb($content){
    //nl2br 将回车形式的换行/n,转换成网页代码形式的换行</br>
    $content=nl2br($content);
    $content=preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>',$content);
    $content=preg_replace('/\[size=(.*)\](.*)\[\/size\]/U','<span style="font-size:\1px">\2</span>',$content);
    $content=preg_replace('/\[i\](.*)\[\/i\]/U','<em>\1</em>',$content);
    $content=preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline">\1</span>',$content);
    $content=preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through">\1</span>',$content);
    $content=preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color:\1">\2</span>',$content);
    $content=preg_replace('/\[url\](.*)\[\/url\]/U','<a href="\1" target="_blank">\1</a>',$content);
    $content=preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:">\1</a>',$content);
    $content=preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" alt="Pic">',$content);
    $content=preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed src="\1" style="width:480px;height:400px" alt="flash">',$content);

    return $content;
}


