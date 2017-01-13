<?php

//防止恶意调用
if (!defined('IN_TG')){
    exit('非法调用');
}
?>
<div id="header">
    <h1><a href="../../index.php">多用户留言系统</a></h1>
    <ul>
        <li><a href="index.php">首页</a></li>

        <?php
        if (isset($_COOKIE['username'])){
            echo '<li><a href="member.php">'.$_COOKIE['username'].'·个人中心</a> '.$msg.'</li>';
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

        <li><a href="blog.php">博友</a></li>
        <li>风格</li>
        <?php
            if (isset($_COOKIE['username'])&& isset($_SESSION['admin'])) {
                echo '<li><a href="manage.php" class="admin">管理</a> </li>';
            }
        ?>
        <?php
        if (isset($_COOKIE['username'])){
            echo '<li><a href="logout.php">退出</a> </li>';
        }
        ?>

</div>

