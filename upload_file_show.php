<?php
header("Content-Type:text/html;charset='utf-8'");
$url=$_GET['url'];
//echo '<img src="'.$url.'"/>';
//先写好 echo '<img src=""/>'
//再双引号里写'..'
//再写进$url
echo '<img src="uploads/'.$url.'"/>';