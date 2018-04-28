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

mysqli_select_db($con,'user');
mysqli_set_charset($con, "utf8");
$action =  $_REQUEST["action"];

switch ($action)
{
case "login":
    $username = $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : '';
    $password = $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : '';

        $sql = "SELECT * FROM user where username ='".$username."' and password ='".$password."'";
        $result = mysqli_query($con,$sql);
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
         $data[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($data)); 
        echo $json_results;
    break;
    case "change":
    $username = $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : '';
    $password = $password = isset($_REQUEST["new_pwd"]) ? $_REQUEST["new_pwd"] : '';
        $sql = "update user set password = '".$password."' where username ='".$username."'";
        $result = mysqli_query($con,$sql);
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "Failed";
            echo(json_encode($message)); 
        }
        else{
            $message = [];
            $message[0] = true;
            $message[1] = "Successfully";
            echo(json_encode($message)); 
        }
    break;
    }
    mysqli_close($con);
    ?>