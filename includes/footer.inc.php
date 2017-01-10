<?php
if (!defined('IN_TG')){
    echo 'Access denied';
}
$end_time=runtime();
//mysql_close();
?>
<div id="footer">
    <p>本程序执行耗时为:<?php echo $end_time-START_TIME;?></p>
    <p>本程序由<span>心理咨询论坛提供</span><strong>&nbsp;作者:CAOCONG</strong></p>
    <p>Copyright © 2016-2017 xlzx.online All Rights Reserved. 备案号：豫ICP备160399351-1</p>
</div>

