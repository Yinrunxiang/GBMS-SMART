<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:POST');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
$con = mysqli_connect('localhost','root','root');
if (!$con)
{
    die('Could not connect: ' . mysqli_error($con));
}
$database_name = $database_name = isset($_REQUEST["database_name"]) ? $_REQUEST["database_name"] : '';
mysqli_select_db($con,$database_name);
mysqli_set_charset($con, "utf8");
?>