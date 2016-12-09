<?php
/*if (!defined('IN_TG')){
    exit('Access Denied');
}*/
//数据库连接
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PWD',398692315);
define('DB_NAME','bbs');
//连接数据库函数
function connect(){
    //在函数内部将$conn资源句柄声明为全局变量，这样在函数外部也就可以识别$conn了
    global $conn;
    if (!$conn=mysql_connect(DB_HOST,DB_USER,DB_PWD)){
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
    $result=mysql_query($sql);
    if (!$result){
        exit('sql执行错误'.mysql_error());
    }
    return $result;
}
//返回一个查询结果集到数组
function fetch_array($sql){
    return mysql_fetch_array(query($sql),MYSQL_ASSOC);

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
