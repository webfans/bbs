<?php
$fp=fopen('new.xml','w');
if (!$fp){
    exit('系统错误，文件不存在');
}
flock($fp,LOCK_EX);//锁定
    $string="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="<vip>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<id>5</id>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<username>face</username>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<sex>男</sex>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<face>images/face/m01.gif</face>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<email>cclovesky@gmail.com</email>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="\t<url>xlzx.online</url>\r\n";
    fwrite($fp,$string,strlen($string));
    $string="</vip>\r\n";
    fwrite($fp,$string,strlen($string));
flock($fp,LOCK_UN);//解锁
fclose($fp);
