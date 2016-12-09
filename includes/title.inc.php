<?php
//防止非法调用
if (!defined('IN_TG')){
    exit('Access Denied');
}
//防止非HTML文件调用
if (!defined('SCRIPT')){
    exit('script Error');
}
?>
<link rel="stylesheet" type="text/css" href="styles/1/basic.css"/>
<link rel="stylesheet" type="text/css" href="styles/1/<?php echo SCRIPT?>.css"/>
<link rel="shortcut icon" href="images/heart2.ico">