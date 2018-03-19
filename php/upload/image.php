<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:*');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('content-type: image/jpeg'); 
$image = $_REQUEST["image"];
$image_file = $image;
$image_info = getimagesize($image_file);
$base64_image_content = "data:{$image_info['mime']};base64," . 
chunk_split(base64_encode(file_get_contents($image_file)));
echo $base64_image_content;
?>