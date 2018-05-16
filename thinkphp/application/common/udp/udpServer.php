<?php
namespace app\common\udp;

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Lib\Timer;
// use app\common\udp\Udp;
require_once './Udp.php';
require_once __DIR__ . '/vendor/autoload.php';
// require_once './udp/udp.php';

$con = mysqli_connect('localhost', 'root', 'root');
// if (!$con) {
//     die('Could not connect: ' . mysqli_error($con));
// }
mysqli_select_db($con, "admin");
mysqli_set_charset($con, "utf8");
class UdpServer
{


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
        $color = $this->toHex(round(hexdec("0x" + $str) / 100 * 255));
        return $color;
    }
    function sendCommand($schedule)
    {
        global $con;
        $Udp = new Udp;
        $command = "select subnetid,deviceid,channel,channel_spare,devicetype,ip,port,mac,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,c.operation as udp_type from schedule_command as a left join device as b on a.device = b.id left join address as c on b.address = c.address where schedule = '" . $schedule . "'";
        $command = mysqli_query($con, $command);
        while ($command_row = mysqli_fetch_assoc($command)) {
            $Udp->sendStatusUdp($command_row);
            // usleep(300000);
        }
    }
    function updataLed($channel, $color, $subnetid, $deviceid, $con)
    {
        if ($color != "00") {
            $sql = "update device as a left join address as b on a.address = b.address set on_off = 'on',mode = '" . $color . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
        } else {
            $sql = "update device as a left join address as b on a.address = b.address set on_off = 'off',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
        }
        mysqli_query($con, $sql);
    }
    public function __construct($sender_io, $port)
    {
        global $sender_io;
       
        // define('HEARTBEAT_TIME', 25);

        $devices = array();
        
        // 监听一个UDP端口
        // global $port;
        $port = $port == "" ? "6000" : $port;
        $inner_udp_worker = new Worker('udp://0.0.0.0:' . $port);
             // $inner_udp_worker = new Worker('udp://0.0.0.0:8888');
                // $inner_udp_worker = new Worker('udp://0.0.0.0:59263');
                // 当UDP客户端发来数据时触发
        $inner_udp_worker->onMessage = function ($udp_connection, $data) {
            global $sender_io;
            global $con;
            global $devices;
            $sender_io->emit('new_msg', bin2hex($data));

            $msg = bin2hex($data);
            //获取设备类型,操作码
            $deviceTypeId = substr($msg, 38, 4);
            $operatorCode = substr($msg, 42, 4);
            // 获取设备ID,子网ID
            $subnetid = substr($msg, 34, 2);
            $deviceid = substr($msg, 36, 2);
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

                    $this->updataLed('31', $red, $subnetid, $deviceid, $con);
                    $this->updataLed('32', $green, $subnetid, $deviceid, $con);
                    $this->updataLed('33', $blue, $subnetid, $deviceid, $con);
                    $this->updataLed('34', $mix, $subnetid, $deviceid, $con);
                    $red = $this->tocolor($red);
                    $green = $this->tocolor($green);
                    $blue = $this->tocolor($blue);
                    $mix = $this->tocolor($mix);
                    $color = "#" . $red . $green . $blue;
                    if ($color != "#000000") {
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = 'on',mode = '" . $color . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    } else {
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = 'off',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    }
                    mysqli_query($con, $sql);
                    break;
                case "0034":
                echo $msg;
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
                        $red = $this->tocolor($red);
                        $blue = $this->tocolor($blue);
                        $green = $this->tocolor($green);
                        $mix = $this->tocolor($mix);
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
                        }
                    }

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
                            $type = 'operation_1';
                            break;
                        case "08":
                            $type = 'operation_1';
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
                    if ($on_off == 'on') {
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $mode . "',grade = '" . $grade . "',operation_1 = '" . $coolTmp . "',operation_2 = '" . $heatTmp . "',operation_3 = '" . $autoTmp . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    } else {
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $mode . "',grade = '" . $grade . "',operation_1 = '" . $coolTmp . "',operation_2 = '" . $heatTmp . "',operation_3 = '" . $autoTmp . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    }
                    mysqli_query($con, $sql);
                    break;
            }

        };
            // 执行监听
        $inner_udp_worker->listen();
        Timer::add(600, function () {
            global $con;
            $sql = "insert into record (device,subnetid,deviceid,channel,mac,ip,port,devicetype,on_off,mode,grade,breed,country,address,floor,room,record_date) select device,subnetid,deviceid,channel,mac,ip,port,devicetype,on_off,mode,grade,breed,country,device.address,floor,room,now() from device left join address on device.address = address.address where on_off = 'on' and (devicetype = 'light' or devicetype = 'led' or devicetype = 'ac' or devicetype = 'floorheat') ";
            $result = mysqli_query($con, $sql);
        });
        Timer::add(1, function () {
            global $con;
            if (date("s") == '00') {
                $time_1 = date("Y-m-d H:i:s");
                $time_2 = date("H:i");
                $week = lcfirst(date("D"));
                $sql = "select * from schedule";
                $schedule = array();
                $result = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    switch ($row['type']) {
                        case "once":
                            if ($row['time_1'] == $time_1) {
                                $this->sendCommand($row['id']);
                            }
                            break;
                        case "day":
                            if ($row['time_2'] == $time_2) {
                                $this->sendCommand($row['id']);
                            }
                            break;
                        case "week":
                            $week = $week == "tue" ? "tues" : $week;
                            $week = $week == "thu" ? "thur" : $week;
                            if ($row[$week] == '1') {
                                if ($row['time_2'] == $time_2) {
                                    $this->sendCommand($row['id']);
                                }
                            }
                            break;
                    }
                }
            }
        });




    }

}



