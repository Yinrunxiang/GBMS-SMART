<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Lib\Timer;
use PHPSocketIO\SocketIO;

include __DIR__ . '/vendor/autoload.php';
require_once './udp/UdpSocket.php';
require_once './udp/UdpProtocol.php';
require_once './udp/udp.php';
// 全局数组保存uid在线数据
$uidConnectionMap = array();
// 记录最后一次广播的在线用户数
$last_online_count = 0;
// 记录最后一次广播的在线页面数
$last_online_page_count = 0;

$con = mysqli_connect('localhost', 'root', 'root');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, "admin");
mysqli_set_charset($con, "utf8");
function toHex($num)
{
    $num = dechex($num);
    if (strlen($num) < 2) {
        $num = '0' . $num;
    }
    return $num;
}

function tocolor($str)
{
    $color = toHex(round(hexdec("0x" + $str) / 100 * 255));
    return $color;
};
function sendCommand($schedule)
{
    global $con;
    global $UDP;
    $command = "select subnetid,deviceid,channel,channel_spare,devicetype,ip,port,mac,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,c.operation as udp_type from schedule_command as a left join device as b on a.device = b.id left join address as c on b.address = c.address where schedule = '" . $schedule . "'";
    $command = mysqli_query($con, $command);
    while ($command_row = mysqli_fetch_assoc($command)) {
        $UDP->sendStatusUdp($command_row);
    }
};
function updataLed($channel,$color,$subnetid,$deviceid,$con){
    if ($color != "00") {
        $sql = "update device as a left join address as b on a.address = b.address set on_off = 'on',mode = '" . $color . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
    } else {
        $sql = "update device as a left join address as b on a.address = b.address set on_off = 'off',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
    }
    mysqli_query($con, $sql);
};
// PHPSocketIO服务
$sender_io = new SocketIO(2120);
$udpProtocol = new UdpProtocol;
$udpSocket = new UdpSocket;
$UDP = new UDP;
$devices = array();
// 客户端发起连接事件时，设置连接socket的各种事件回调
$sender_io->on('connection', function ($socket) {
    $socket->emit('update_online_count', "连接成功");
});

// 当$sender_io启动后监听一个http端口，通过这个端口可以给任意uid或者所有uid推送数据
$sender_io->on('workerStart', function () {
    // 监听一个UDP端口
    $inner_udp_worker = new Worker('udp://0.0.0.0:6000');
    // $inner_udp_worker = new Worker('udp://0.0.0.0:8888');
    // $inner_udp_worker = new Worker('udp://0.0.0.0:59263');
    // 当UDP客户端发来数据时触发
    $inner_udp_worker->onMessage = function ($udp_connection, $data) {
        global $sender_io;
        global $con;
        global $devices;
        $sender_io->emit('new_msg', bin2hex($data));

        $msg = bin2hex($data);
        // echo $msg;
        $deviceTypeId = substr($msg, 38, 4);
        $operatorCode = substr($msg, 42, 4);
        // echo $operatorCode.'                      ';
        $subnetid = substr($msg, 34, 2);
        $deviceid = substr($msg, 36, 2);
        // echo $deviceid.'                      ';
        switch ($operatorCode) {
            case "0032":
                $channel = substr($msg, 50, 2);
                $brightness = substr($msg, 54, 2);
                $on_off = $brightness != '00' ? $on_off = 'on' : $on_off = 'off';
                if ($on_off == 'on') {
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
                } else {
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
                }

                mysqli_query($con, $sql);
                break;
            case 'f081':
                $red = substr($msg, 52, 2);
                $green = substr($msg, 54, 2);
                $blue = substr($msg, 56, 2);
                $mix = substr($msg, 58, 2);
                
                updataLed('31',$red,$subnetid,$deviceid,$con);
                updataLed('32',$green,$subnetid,$deviceid,$con);
                updataLed('33',$blue,$subnetid,$deviceid,$con);
                updataLed('34',$mix,$subnetid,$deviceid,$con);
                $red = tocolor($red);
                $green = tocolor($green);
                $blue = tocolor($blue);
                $mix = tocolor($mix);
                $color = "#" . $red . $green . $blue;
                if ($color != "#000000") {
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = 'on',mode = '" . $color . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                } else {
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = 'off',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                }
                mysqli_query($con, $sql);
                break;
            case "0034":
                if ($deviceTypeId == '0382') {
                    $red = substr($msg, 52, 2);
                    $green = substr($msg, 54, 2);
                    $blue = substr($msg, 56, 2);
                    $mix = substr($msg, 58, 2);
                    //用light模式控led
                    $on_off = $red != '00' ? 'on' : 'off';
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $red . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'  and devicetype = 'light' and channel = '31'";
                    mysqli_query($con, $sql);
                    $on_off = $green != '00' ? 'on' : 'off';
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $green . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'  and devicetype = 'light' and channel = '32'";
                    mysqli_query($con, $sql);
                    $on_off = $blue != '00' ? 'on' : 'off';
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $blue . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'  and devicetype = 'light' and channel = '33'";
                    mysqli_query($con, $sql);
                    $on_off = $mix != '00' ? 'on' : 'off';
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $mix . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'  and devicetype = 'light' and channel = '34'";
                    mysqli_query($con, $sql);
                    //普通LED模式
                    $red = tocolor($red);
                    $blue = tocolor($blue);
                    $green = tocolor($green);
                    $mix = tocolor($mix);
                    $color = "#" . $red . $green . $blue;
                    if ($color != "#000000") {
                        $on_off = true;
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = 'on',mode = '" . $color . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and devicetype = 'led'";
                        mysqli_query($con, $sql);
                    } else {
                        $on_off = false;
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = 'off' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and devicetype = 'led'";
                        mysqli_query($con, $sql);
                    }




                } else {
                    $devicetype = 'light';
                    $channelnum = hexdec(substr($msg, 50, 2));
                    for ($i = 1; $i <= $channelnum; $i++) {
                        $start = ($i * 2) + 50;
                        $brightness = substr($msg, $start, 2);
                        if ($brightness != "00") {
                            $on_off = "on";
                        } else {
                            $on_off = "off";
                        }

                        $channel = dechex($i);
                        if (strlen($channel) < 2) {
                            $channel = '0' . $channel;
                        }
                        // echo  $channel;
                        if ($on_off == 'on') {
                            $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "' and mode = '" . $brightness . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
                        } else {
                            $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
                        }

                        mysqli_query($con, $sql);
                        // echo $sql;
                    }
                }
                // $sql="insert into record (deviceid,channel,devicetype,on_off,record_date) values ('".$deviceid."','".$channel."','".$devicetype."','".$on_off."',now())";

                // echo $sql;

                break;
            case "e3d9":
                $type = substr($msg, 50, 2);
                $value = substr($msg, 52, 2);

                switch ($type) {
                    case "03":
                        $type = 'on_off';
                        if ($value == "01") {
                            $value = 'on';

                        } else {
                            $value = 'off';
                        }
                        break;
                    case "05":
                        $type = 'grade';
                        switch ($value) {
                            case '00':
                                $value = "auto";
                                break;
                            case '01':
                                $value = "hign";
                                break;
                            case '02':
                                $value = "medial";
                                break;
                            case '03':
                                $value = "low";
                                break;
                        }
                        break;
                    case "06":
                        $type = 'mode';
                        switch ($value) {
                            case '00':
                                $value = "cool";
                                break;
                            case '01':
                                $value = "heat";
                                break;
                            case '02':
                                $value = "fan";
                                break;
                            case '03':
                                $value = "auto";
                                break;
                        }
                        break;
                    case "04":
                        $type = 'operation_1';
                        break;
                    case "07":
                        $type = 'operation_2';
                        break;
                    case "08":
                        $type = 'operation_3';
                        break;
                }


                if ($type == 'on_off') {
                    if ($value == 'on') {
                        $sql = "update device as a left join address as b on a.address = b.address set " . $type . " = '" . $value . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    } else {
                        $sql = "update device as a left join address as b on a.address = b.address set " . $type . " = '" . $value . "',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    }
                } else {
                    $sql = "update device as a left join address as b on a.address = b.address set " . $type . " = '" . $value . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                }

                // echo $type.$value;
                // echo $sql;
                // $sql="insert into record (deviceid,devicetype,on_off,record_date) values ('".$deviceid."','".$devicetype."','".$on_off."',now())";
                // echo $sql;
                mysqli_query($con, $sql);
                break;
            case "e0ed":
                $on_off = substr($msg, 50, 2);
                $on_off = $on_off != '00' ? $on_off = 'on' : $on_off = 'off';
                $devicetype = 'ac';
                $mode = substr($msg, 55, 1);
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
                        $mode = "mode_auto";
                        break;
                }
                $grade = substr($msg, 56, 2);
                $grade = hexdec($grade);
                switch ($grade) {
                    case 0:
                        $grade = "wind_auto";
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
                $coolTmp = hexdec(substr($msg, 52, 2));
                $heatTmp = hexdec(substr($msg, 60, 2));
                $autoTmp = hexdec(substr($msg, 64, 2));
                // $mac = "";
                // $remote = substr($msg,66, 2);
                // if($remote == "02"){
                //     for($i = 0;$i <8 ;$i++){
                //         $start  = 68+($i*2);
                //         $mac  = $mac.'.'.substr($msg,$start, 2);
                //     }
                // }
                if ($on_off == 'on') {
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $mode . "',grade = '" . $grade . "',operation_1 = '" . $coolTmp . "',operation_2 = '" . $heatTmp . "',operation_3 = '" . $autoTmp . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                } else {
                    $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $mode . "',grade = '" . $grade . "',operation_1 = '" . $coolTmp . "',operation_2 = '" . $heatTmp . "',operation_3 = '" . $autoTmp . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                }

                // echo $sql;
                // $sql="insert into record (deviceid,devicetype,on_off,record_date) values ('".$deviceid."','".$devicetype."','".$on_off."',now())";
                // echo $sql;
                mysqli_query($con, $sql);
                break;
        }

    };
    // 执行监听
    $inner_udp_worker->listen();
    //主动读取状态  （会使RSIP崩溃）
//     global $con;
// global $udpProtocol;
// global $udpSocket;
// global $devices;
// $address = '127.0.0.1';
// $port = 6000;
// $sql="SELECT a.id,device,subnetid,deviceid,channel,mac,ip,port,devicetype,a.status,starttime,endtime,a.address FROM device as a left join address as b on a.address = b.address order by address,devicetype ";
// $result = mysqli_query($con,$sql);
// //遍历设备表，发送读取指令
// while ($row = mysqli_fetch_assoc($result)) {
//     sleep(10);
//     $devices[] = $row;
//     // $row = json_decode($row);
//     switch($row["devicetype"]){
//         case "light":
//             $operatorCodefst = '00';
//             $operatorCodesec = '33';
//             $targetDeviceID = $row["deviceid"]? $row["deviceid"] : '';
//             echo  $targetDeviceID;
//             $additionalContentData = $row["channel"] ? [$row["channel"],'00','00','00']:[];
//             $macAddress = $row["mac"] ? explode('.',$row["mac"]): [];
//             $msg  = $udpProtocol->UdpProtocol($operatorCodefst,$operatorCodesec,$targetDeviceID,$additionalContentData,$macAddress);
//             // echo bin2hex($msg);
//             $dest_address = $row["ip"]? $row["ip"] : '192.168.1.255';
//             $dest_port = $row["port"]? $row["port"] : 6000;
//             $hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
//         break;
//         case "ac":
//             $operatorCodefst = 'E0';
//             $operatorCodesec = 'EC';
//             $targetDeviceID = $row["deviceid"]? $row["deviceid"] : '';
//             echo  $targetDeviceID;
//             $additionalContentData = ['00'];
//             $macAddress = $row["mac"] ? explode('.',$row["mac"]): [];
//             $msg  = $udpProtocol->UdpProtocol($operatorCodefst,$operatorCodesec,$targetDeviceID,$additionalContentData,$macAddress);
//             // echo bin2hex($msg);
//             $dest_address = $row["ip"]? $row["ip"] : '192.168.1.255';
//             $dest_port = $row["port"]? $row["port"] : 6000;
//             $hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
//         break;
//         case "led":
//             $operatorCodefst = '00';
//             $operatorCodesec = '33';
//             $targetDeviceID = $row["deviceid"]? $row["deviceid"] : '';
//             $additionalContentData = [];
//             $macAddress = $row["mac"] ? explode('.',$row["mac"]): [];
//             $msg  = $udpProtocol->UdpProtocol($operatorCodefst,$operatorCodesec,$targetDeviceID,$additionalContentData,$macAddress);
//             // echo bin2hex($msg);
//             $dest_address = $row["ip"]? $row["ip"] : '192.168.1.255';
//             $dest_port = $row["port"]? $row["port"] : 6000;
//             $hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
//         break;
//     }
// }
    // 一个定时器，定时发送读取指令 并记录设备运行状态
    // $min = date("i");
    // $min = substr($min,1,1);
    Timer::add(600, function () {
        global $con;
        // global $udpProtocol;
        // global $udpSocket;
        // global $devices;
        //延迟3秒后，将设备运行状态记录到record表
        // sleep(3);
        $sql = "insert into record (device,subnetid,deviceid,channel,mac,ip,port,devicetype,on_off,mode,grade,breed,country,address,floor,room,record_date) select device,subnetid,deviceid,channel,mac,ip,port,devicetype,on_off,mode,grade,breed,country,device.address,floor,room,now() from device left join address on device.address = address.address where on_off = 'on' and (devicetype = 'light' or devicetype = 'led' or devicetype = 'ac') ";
        $result = mysqli_query($con, $sql);
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
    Timer::add(1, function () {
        global $con;
        if (date("s") == '00') {
            $time_1 = date("Y-m-d H:i:s");
            $time_2 = date("H:i");
            $week = lcfirst(date("D"));
            // echo ( $time_1.'</br>'.$time_2.'</br>'.$week);
            $sql = "select * from schedule";
            $schedule = array();
            $result = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                switch ($row['type']) {
                    case "once":
                        if ($row['time_1'] == $time_1) {
                            sendCommand($row['id']);
                        }
                        break;
                    case "day":
                        if ($row['time_2'] == $time_2) {
                            sendCommand($row['id']);
                        }
                        break;
                    case "week":
                        $week = $week == "tue" ? "tues" : $week;
                        $week = $week == "thu" ? "thur" : $week;
                        if ($row[$week] == '1') {
                            if ($row['time_2'] == $time_2) {
                                sendCommand($row['id']);
                            }
                        }
                        break;
                }
            }
        }
    });

});

if (!defined('GLOBAL_START')) {
    Worker::runAll();
}


