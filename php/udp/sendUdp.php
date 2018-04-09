<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:*');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
require_once '/UdpSocket.php';
require_once '/UdpProtocol.php';
//接收操作协议
$operatorCodefst = isset($_REQUEST["operatorCodefst"]) ? $_REQUEST["operatorCodefst"] : '';
$operatorCodesec = isset($_REQUEST["operatorCodesec"]) ? $_REQUEST["operatorCodesec"] : '';
$targetSubnetID = isset($_REQUEST["targetSubnetID"]) ? $_REQUEST["targetSubnetID"] : '';
$targetDeviceID = isset($_REQUEST["targetDeviceID"]) ? $_REQUEST["targetDeviceID"] : '';
$additionalContentData = isset($_REQUEST["additionalContentData"]) ? $_REQUEST["additionalContentData"] : [];
//广播地址
if ($_REQUEST["udp_type"] == "1") {
    $dest_address = $_REQUEST["dest_address"] ? $_REQUEST["dest_address"] : '255.255.255.255';
    $dest_port = 8888;
    $macAddress = $_REQUEST["macAddress"] ?$_REQUEST["macAddress"] : [];
}else{
    $dest_address = '255.255.255.255';
    $dest_port = 6000;
    $macAddress = [];
}
// var_dump($macAddress);
//调用UDP协议类，生成UDP协议
$udpProtocol = new UdpProtocol();
$msg = $udpProtocol->UdpProtocol($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress);
// echo $msg;

if ($_SERVER['SERVER_NAME'] == "localhost") {
    $host_name = exec("hostname");
    $host_ip = gethostbyname($host_name);
} else {
    $host_ip = $_SERVER['SERVER_NAME'];
}
new UdpSocket("0.0.0.0", 0, $dest_address, $dest_port, $msg);
// usleep(300000);
// $hear = $udpSocket->UdpSocket("0.0.0.0", 0, $dest_address, $dest_port, $msg);
// $hear = $udpSocket->UdpSocket($host_ip, '6000', $dest_address, $dest_port, $msg);
// echo $hear;

?>