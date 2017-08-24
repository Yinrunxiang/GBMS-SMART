<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:POST');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
require_once '/UdpSocket.php';
require_once '/UdpProtocol.php';
//接收操作协议

// $params =  isset($_REQUEST["params"])? $_REQUEST["params"] : '';
// echo $params;
// $data =  isset($_REQUEST["data"])? $_REQUEST["data"] : '';
// echo $data;
$operatorCodefst = isset($_REQUEST["operatorCodefst"]) ? $_REQUEST["operatorCodefst"] : '';
$operatorCodesec = isset($_REQUEST["operatorCodesec"]) ? $_REQUEST["operatorCodesec"] : '';
$targetDeviceID = isset($_REQUEST["targetDeviceID"]) ? $_REQUEST["targetDeviceID"] : '';
$additionalContentData = isset($_REQUEST["additionalContentData"]) ? $_REQUEST["additionalContentData"] : [];

$macAddress = $_REQUEST["macAddress"]? $_REQUEST["macAddress"] : [];

// var_dump($macAddress);
// echo $operatorCodefst = "123";
//调用UDP协议类，生成UDP协议
$udpProtocol = new UdpProtocol();
$msg  = $udpProtocol->UdpProtocol($operatorCodefst,$operatorCodesec,$targetDeviceID,$additionalContentData,$macAddress);
// echo $msg;


//本机地址
$address = '127.0.0.1';
$port = 6000;
//广播地址
// $dest_address = '192.168.1.255';
// $dest_port = 6000;
$dest_address = $_REQUEST["dest_address"]? $_REQUEST["dest_address"] : '192.168.1.255';
$dest_port = $_REQUEST["dest_port"]? $_REQUEST["dest_port"] : 6000;
// echo $dest_address;
// echo $dest_port;
// $dest_address = '162.144.66.131';
// $dest_port = 8888;
$udpSocket = new UdpSocket();
$hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
echo $hear;

?>