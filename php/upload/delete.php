<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:*');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
$image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
// if ($_SERVER['SERVER_NAME'] == "localhost") {
//     $host_name = exec("hostname");
//     $host_ip = gethostbyname($host_name);
// } else {
//     $host_ip = $_SERVER['SERVER_NAME'];
// }
// $host = "http://" . $host_ip . ":" . $_SERVER["SERVER_PORT"]."/upload";
// $host_len = strlen($host);
// $image = substr($image, $host_len);
echo $image;
if ($image) {
    unlink('../..'.$image);
}
?>