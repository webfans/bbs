<?php
//防止恶意调用
if (!defined('IN_TG')){
    exit('非法调用');
}
?>
<div id="header">
    <h1><a href="../../index.php">psy520多用户留言系统</a></h1>
    <ul>
        <li><a href="index.php">首页</a></li>

        <?php
        if (isset($_COOKIE['username'])){
            echo '<li><a href="member.php">'.$_COOKIE['username'].'·个人中心</a> </li>';
            echo "\n";
        }
        else{
            echo '<li><a href="register.php">注册</a> </li>';
            echo "\n";//为了查看源代码时，格式好看
            echo "\t\t";//为了查看源代码时，格式好看
            echo '<li><a href="login.php">登录</a></li>';
            echo "\n";//为了查看源代码时，格式好看
        }

        ?>

        <li>风格</li>
        <li>管理</li>
        <?php
        if (isset($_COOKIE['username'])){
            echo '<li><a href="logout.php">退出</a> </li>';
        }
        ?>

</div>

