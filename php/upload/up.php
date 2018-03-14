<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:POST');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
$uploaddir = 'image/';
$name = $_FILES['file']['name'];
$uploadfile = $uploaddir . $name;
$type = strtolower(substr(strrchr($name, '.'), 1));
//获取文件类型
$typeArr = array("jpg", "png", "gif");
if (!in_array($type, $typeArr)) {
    echo "请上传jpg,png或gif类型的图片！";
    exit;
}
if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $_FILES['file']['name'])) {
    if ($_SERVER['SERVER_NAME'] == "localhost") {
        $host_name = exec("hostname");
        $host_ip = gethostbyname($host_name);
    } else {
        $host_ip = $_SERVER['SERVER_NAME'];
    }
    $img_url = "http://" . $host_ip .":". $_SERVER["SERVER_PORT"] . "/upload/" . $uploaddir . $name;
    $url = str_replace("\\", "/", $img_url);
    print_r($url);
} else {
    print "Possible file upload attack!  Here's some debugging info:\n";
    print_r($_FILES);
}
?>