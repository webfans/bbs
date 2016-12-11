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
function alert_back($msg){
    //history.go(-1):后退+刷新 history.back():后退
    echo "<script type=text/javascript>alert('".$msg."');history.back();</script>";
    exit();
}
//页面跳转
function location($msg,$url){
    if (!$msg) {
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
        $conn=mysql_connect('localhost','root','398692315');
        if (!$conn)
        {
            die('Could not connect: ' . mysql_error());
        }
        return mysql_real_escape_string($string,$conn);
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
    session_destroy();
}
//销毁cookie
function cookie_d(){
    setcookie('username','',time()-1);
    setcookie('uniqid','',time()-1);
    session_d();
    location('null','index.php');
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