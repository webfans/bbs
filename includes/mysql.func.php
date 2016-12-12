<?php
/*if (!defined('IN_TG')){
    exit('Access Denied');
}*/
//数据库连接
//define('DB_HOST','localhost');
//define('DB_USER','root');
//define('DB_PWD',398692315);
//define('DB_NAME','bbs');

//以上常量已经在common.inc.php 定义过了，如果重复定义将导致错误 Notice: Constant DB_HOST already defined in
//连接数据库函数

function connect(){
    //在函数内部将$conn资源句柄声明为全局变量，这样在函数外部也就可以识别$conn了
    global $conn;
    $conn=@mysql_connect(DB_HOST,DB_USER,DB_PWD);
    if (!$conn){
        exit('数据库连接失败');
    }
}
//选择一款数据库
//@return void
function selectdb(){
    if (!mysql_select_db(DB_NAME)){
        exit('找不到数据库');
    }
}

//设置字符集
function set_names(){
    if (!mysql_query('SET NAMES UTF8')){
        exit('字符集设置错误');
    }
}
//返回结果集
function query($sql){
    $result=@mysql_query($sql);
    if (!$result){
        exit('sql执行错误'.mysql_error());
    }
    return $result;
}
//返回一个查询结果集到数组
//只能获取指定数据集中的一条数据组
function fetch_array($sql){
    return mysql_fetch_array(query($sql),MYSQL_ASSOC);

}
//返回一个查询结果集到数组
//可以返回指定数据集中的所有数据组
function fetch_array_lsit($result){
    return mysql_fetch_array($result,MYSQL_ASSOC);
}
//判断数据库中是否存在重复数据
function is_repeat($sql,$msg){
    if (fetch_array($sql)){
        alert_back($msg);
    }
}
//返回前一次 MySQL 操作所影响的记录行数
function affetched_rows(){
    return mysql_affected_rows();
}

//关闭连接
function close(){
    mysql_close();
}
