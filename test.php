<?php
$conn=mysql_connect('localhost','root','398692315');
if (!$conn) {
 	die('Cannot connect the database'.mysql_error());
}else {
	 	$selectdb=mysql_select_db('info',$conn);
	if (!$selectdb) {
	 	echo 'Cannot use the Database'.mysql_error();
	}
	$sql="select * from grade";
	$result=mysql_query($sql);
	$rows=mysql_fetch_array($result);
	$rows2=mysql_fetch_array($result,MYSQL_ASSOC);
	print_r($result);
	echo '<br/>';
	print_r($rows);
	echo '<br/>';
	print_r($rows2);
	echo '<br/>';
	print_r($rows['name']);

}
