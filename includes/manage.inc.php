<?php
//防止恶意调用
if (!defined('IN_TG')){
    exit('非法调用');
}
?>
<h2>管理导航</h2>
<dl>
    <dt>系统管理</dt>
    <dd><a href="manage.php">后台首页</a> </dd>
    <dd><a href="manage_set.php">系统设置</a> </dd>
</dl>
<dl>
    <dt>会员管理</dt>
    <dd><a href="member_message.php">普通会员</a> </dd>
    <dd><a href="member_friend.php">特殊会员</a> </dd>
    <dd><a href="member_flower.php">职业任命</a> </dd>
</dl>
<dl>
    <dt>帖子管理</dt>
    <dd><a href="member_message.php">主题管理</a> </dd>
    <dd><a href="member_friend.php">跟帖管理</a> </dd>
    <dd><a href="member_flower.php">批量删除</a> </dd>
</dl>
