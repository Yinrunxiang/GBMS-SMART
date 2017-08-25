<?php
header("Content-type: text/html; charset=utf-8");
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Lib\Timer;
use PHPSocketIO\SocketIO;

include __DIR__ . '/vendor/autoload.php';
require_once './udp/UdpSocket.php';
require_once './udp/UdpProtocol.php';
// 全局数组保存uid在线数据
$uidConnectionMap = array();
// 记录最后一次广播的在线用户数
$last_online_count = 0;
// 记录最后一次广播的在线页面数
$last_online_page_count = 0;
function toHex($num) {
    $num = dechex($num);
    if(strlen($num) < 2){
        $num = '0'.$num;
    }
    return $num ;
}

$con = mysqli_connect('localhost','root','root');
        if (!$con)
        {
            die('Could not connect: ' . mysqli_error($con));
        }
        mysqli_select_db($con,"udp");
        mysqli_set_charset($con, "utf8");

// PHPSocketIO服务
$sender_io = new SocketIO(2120);
$udpProtocol = new UdpProtocol();
$udpSocket = new UdpSocket();
$devices = array();
// 客户端发起连接事件时，设置连接socket的各种事件回调
$sender_io->on('connection', function($socket){
    $socket->emit('update_online_count', "连接成功");
    // 当客户端发来登录事件时触发
    // $socket->on('login', function ($uid)use($socket){
    //     global $uidConnectionMap, $last_online_count, $last_online_page_count;
    //     // 已经登录过了
    //     if(isset($socket->uid)){
    //         return;
    //     }
    //     // 更新对应uid的在线数据
    //     $uid = (string)$uid;
    //     if(!isset($uidConnectionMap[$uid]))
    //     {
    //         $uidConnectionMap[$uid] = 0;
    //     }
    //     // 这个uid有++$uidConnectionMap[$uid]个socket连接
    //     ++$uidConnectionMap[$uid];
    //     // 将这个连接加入到uid分组，方便针对uid推送数据
    //     $socket->join($uid);
    //     $socket->uid = $uid;
    //     // 更新这个socket对应页面的在线数据
    //     $socket->emit('update_online_count', "当前<b>{$last_online_count}</b>人在线，共打开<b>{$last_online_page_count}</b>个页面");
    // });
    
    // // 当客户端断开连接是触发（一般是关闭网页或者跳转刷新导致）
    // $socket->on('disconnect', function () use($socket) {
    //     if(!isset($socket->uid))
    //     {
    //          return;
    //     }
    //     global $uidConnectionMap, $sender_io;
    //     // 将uid的在线socket数减一
    //     if(--$uidConnectionMap[$socket->uid] <= 0)
    //     {
    //         unset($uidConnectionMap[$socket->uid]);
    //     }
    // });
});

// 当$sender_io启动后监听一个http端口，通过这个端口可以给任意uid或者所有uid推送数据
$sender_io->on('workerStart', function(){
    // 监听一个UDP端口
    $inner_udp_worker = new Worker('udp://0.0.0.0:6000');
    // $inner_udp_worker = new Worker('udp://0.0.0.0:8888');
    // $inner_udp_worker = new Worker('udp://0.0.0.0:59263');
    // 当UDP客户端发来数据时触发
    $inner_udp_worker->onMessage = function($udp_connection, $data){
        global $sender_io;
        global $con;
        global $devices;
        $sender_io->emit('new_msg', bin2hex($data));

        $msg = bin2hex($data);
        $operatorCode = substr($msg,42, 4);
        // echo $operatorCode.'                      ';
        $subnetid = substr($msg,34, 2);
        $deviceid = substr($msg,36, 2);
        // echo $deviceid.'                      ';
        switch($operatorCode){
            case "0034":
                $channel = substr($msg,52, 2);
                $brightness = substr($msg,54, 2);
                // $devicetype = $channel != '00'? 'light' : 'led';
                $devicetype = 'light';
                $on_off = $brightness != '00'? $on_off = '1' :$on_off = '0';
                $channelnum  =  hexdec(substr($msg,50, 2));
                $mac = "";
                $remote = substr($msg,52+($channelnum*2), 2);
                if($remote == "02"){
                    for($i = 0;$i <8 ;$i++){
                        $start  = 54+($channelnum*2)+($i*2);
                        $mac  = $mac.'.'.substr($msg,$start, 2);
                    }
                }
                for($i = 1 ; $i <= $channelnum; $i++){
                    $start = ($i *2)+50;
                    if(substr($msg,$start, 2) != "00"){
                        $on_off = "on";
                    }else{
                        $on_off = "off";
                    }
                    $channel = toHex($i);
                    // echo  $channel;
                    $sql = "update device set on_off = '".$on_off."' where subnetid = '".$subnetid."' and  deviceid = '".$deviceid."' and  channel = '".$channel."' and  mac = '".$mac."'";
                    mysqli_query($con,$sql);
                    // echo $sql;
                }
                // $sql="insert into record (deviceid,channel,devicetype,on_off,record_date) values ('".$deviceid."','".$channel."','".$devicetype."','".$on_off."',now())";
                
                // echo $sql;
                
            break;
            case "e0ed":
                $on_off = substr($msg,50, 2);
                $on_off = $on_off != '00'? $on_off = 'on' :$on_off = 'off';
                $devicetype = 'ac';
                $mode = substr($msg,55, 1);
                $mode = hexdec($mode);
                switch ($mode) {
                    case 0:
                        $mode = "cool";
                        break;
                    case 1:
                        $mode = "heat";
                        break;
                    case 2:
                        $mode = "fan";
                        break;
                    case 3:
                        $mode = "auto";
                        break;
                }
                $grade = substr($msg,56, 2);
                $grade = hexdec($grade);
                switch ($grade) {
                    case 0:
                        $grade = "auto";
                        break;
                    case 1:
                        $grade = "high";
                        break;
                    case 2:
                        $grade = "medial";
                        break;
                    case 3:
                        $grade = "low";
                        break;
                }
                $mac = "";
                $remote = substr($msg,66, 2);
                if($remote == "02"){
                    for($i = 0;$i <8 ;$i++){
                        $start  = 68+($i*2);
                        $mac  = $mac.'.'.substr($msg,$start, 2);
                    }
                }
                $sql = "update device set on_off = '".$on_off."',mode = '".$mode."',grade = '".$grade."' where subnetid = '".$subnetid."' and  deviceid = '".$deviceid."' and  mac = '".$mac."'";
                // echo $sql;
                // $sql="insert into record (deviceid,devicetype,on_off,record_date) values ('".$deviceid."','".$devicetype."','".$on_off."',now())";
                // echo $sql;
                mysqli_query($con,$sql);
            break;
        }
        
    };
    // 执行监听
    $inner_udp_worker->listen();
    // 一个定时器，定时发送读取指令 并记录设备运行状态
    // $min = date("i");
    // $min = substr($min,1,1);
    Timer::add(600, function(){
        global $con;
        global $udpProtocol;
        global $udpSocket;
        global $devices;
        $address = '127.0.0.1';
        $port = 6000;
        $sql="SELECT * FROM device";
        $result = mysqli_query($con,$sql);
        //遍历设备表，发送读取指令
        while ($row = mysqli_fetch_assoc($result)) {
            $devices[] = $row;
            // $row = json_decode($row);
            switch($row["devicetype"]){
                case "light":
                    $operatorCodefst = '00';
                    $operatorCodesec = '33';
                    $targetDeviceID = $row["deviceid"]? $row["deviceid"] : '';
                    $additionalContentData = $row["channel"] ? [$row["channel"],'00','00','00']:[];
                    $macAddress = $row["mac"] ? $row["mac"]: [];
                    $msg  = $udpProtocol->UdpProtocol($operatorCodefst,$operatorCodesec,$targetDeviceID,$additionalContentData,$macAddress);
                    // echo bin2hex($msg);
                    $dest_address = $row["ip"]? $row["ip"] : '192.168.1.255';
                    $dest_port = $row["port"]? $row["port"] : 6000;
                    $hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
                break;
                case "ac":
                    $operatorCodefst = 'E0';
                    $operatorCodesec = 'EC';
                    $targetDeviceID = $row["deviceid"]? $row["deviceid"] : '';
                    $additionalContentData = ['00'];
                    $macAddress = $row["mac"] ? $row["mac"]: [];
                    $msg  = $udpProtocol->UdpProtocol($operatorCodefst,$operatorCodesec,$targetDeviceID,$additionalContentData,$macAddress);
                    // echo bin2hex($msg);
                    $dest_address = $row["ip"]? $row["ip"] : '192.168.1.255';
                    $dest_port = $row["port"]? $row["port"] : 6000;
                    $hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
                break;
                case "led":
                    $operatorCodefst = '00';
                    $operatorCodesec = '33';
                    $targetDeviceID = $row["deviceid"]? $row["deviceid"] : '';
                    $additionalContentData = [];
                    $macAddress = $row["mac"] ? $row["mac"]: [];
                    $msg  = $udpProtocol->UdpProtocol($operatorCodefst,$operatorCodesec,$targetDeviceID,$additionalContentData,$macAddress);
                    // echo bin2hex($msg);
                    $dest_address = $row["ip"]? $row["ip"] : '192.168.1.255';
                    $dest_port = $row["port"]? $row["port"] : 6000;
                    $hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
                break;
            }
        }
        //延迟3秒后，将设备运行状态记录到record表
        sleep(3);
        $sql="insert into record (device,subnetid,deviceid,channel,mac,ip,port,devicetype,on_off,mode,grade,breed,country,address,record_date) select device,subnetid,deviceid,channel,mac,ip,port,devicetype,on_off,mode,grade,breed,country,address,now() from device";
        $result = mysqli_query($con,$sql);
        // global $uidConnectionMap, $sender_io, $last_online_count, $last_online_page_count;
        // $online_count_now = count($uidConnectionMap);
        // $online_page_count_now = array_sum($uidConnectionMap);
        // // 只有在客户端在线数变化了才广播，减少不必要的客户端通讯
        // if($last_online_count != $online_count_now || $last_online_page_count != $online_page_count_now)
        // {
        //     $sender_io->emit('update_online_count', "当前<b>{$online_count_now}</b>人在线，共打开<b>{$online_page_count_now}</b>个页面");
        //     $last_online_count = $online_count_now;
        //     $last_online_page_count = $online_page_count_now;
        // }
    });
    // Timer::add(605, function(){
    //     global $con;
    //     global $devices;
    //     $sql="SELECT * FROM device";
    //     $result = mysqli_query($con,$sql);
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         // $devices[] = $row;
    //         $insert_sql =  "insert into record device,subnetid,deviceid,channel,mac,ip,port,devicetype,on_off,mode,grade,breed,country,address,datetime values ('".$row["device"]."','".$row["subnetid"]."','".$row["deviceid"]."','".$row["channel"]."','".$row["mac"]."','".$row["ip"]."','".$row["port"]."','".$row["devicetype"]."','".$row["on_off"]."','".$row["mode"]."','".$row["grade"]."','".$row["breed"]."','".$row["country"]."','".$row["address"]."',now())";
    //     }
    //     // global $uidConnectionMap, $sender_io, $last_online_count, $last_online_page_count;
    //     // $online_count_now = count($uidConnectionMap);
    //     // $online_page_count_now = array_sum($uidConnectionMap);
    //     // // 只有在客户端在线数变化了才广播，减少不必要的客户端通讯
    //     // if($last_online_count != $online_count_now || $last_online_page_count != $online_page_count_now)
    //     // {
    //     //     $sender_io->emit('update_online_count', "当前<b>{$online_count_now}</b>人在线，共打开<b>{$online_page_count_now}</b>个页面");
    //     //     $last_online_count = $online_count_now;
    //     //     $last_online_page_count = $online_page_count_now;
    //     // }
    // });
});

if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}
