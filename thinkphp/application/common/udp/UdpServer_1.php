<?php
namespace app\common\udp;

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Lib\Timer;
use \app\common\udp\device\SendCommand;
// use app\common\udp\Udp;
require_once './Udp.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once './device/SendCommand.php';

// require_once './udp/udp.php';

$con = mysqli_connect('localhost', 'root', 'root');
// if (!$con) {
//     die('Could not connect: ' . mysqli_error($con));
// }
mysqli_select_db($con, "admin");
mysqli_set_charset($con, "utf8");
class UdpServer_1
{


    function toHex($num)
    {
        $num = dechex($num);
        if (strlen($num) < 2) {
            $num = '0' . $num;
        }
        return $num;
    }
    function toTmp($tmp)
    {
        $tmp = hexdec($tmp);
        $tmp = $tmp >= 128 ? -256 + $tmp : $tmp;
        return $tmp;
    }
    function strToarr($arr, $str)
    {
        $len = count($str) / 2;
        for ($i = 0; $i < $len; $i++) {
            array_push($arr, substr($str, $i * 2, 2));
        }
        return $arr;
    }
    function strToarr4($arr, $str)
    {
        $len = count($str) / 4;
        for ($i = 0; $i < $len; $i++) {
            array_push($arr, substr($str, $i * 4, 4));
        }
        return $arr;
    }
    function tocolor($str)
    {
        $color = $this->toHex(round(hexdec("0x" + $str) / 100 * 255));
        return $color;
    }
    function getadditional($msg = "", $num = 0, $len = 0)
    {
        $num = $num ? $num : 0;
        $len = $len ? $len : 0;
        $msg = substr($msg, (50 + $num * 2), 2 * ($len + 1));
        return $msg;
    }
    function getAddress($subnetid, $deviceid)
    {
        global $con;
        $sql = "select mac,ip,port,b.operation as udp_type from device as a left join address as b on a.address = b.address where subnetid = '" . $subnetid . "' and deviceid = '" . $deviceid . "'";
        $data = mysqli_query($con, $sql);
        $data = mysqli_fetch_assoc($data);
        if ($data) {
            if ($data['udp_type'] == 1) {
                $data['mac'] = $data['mac'] ? explode(".", $data['mac']) : [];
                $data['ip'] = $data['ip'] ? $data['ip'] : '255.255.255.255';
                $data['port'] = $data['port'] ? $data['port'] : 8888;
            } else {
                $data['mac'] = [];
                $data['ip'] = '255.255.255.255';
                $data['port'] = 6000;
            }

            return $data;
        } else {
            return [];
        }
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
    function updataLed($channel, $color, $subnetid, $deviceid, $con, $sender_io)
    {
        $switch = $color != "00" ? true : false;
        $brightness = hexdec('0x' + $color);
        $deviceProperty = ['on_off' => $switch, 'brightness' => $brightness];
        $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'deviceProperty' => $deviceProperty];
        $sender_io->emit('udp', $udp);
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
            // $sender_io->emit('new_msg', bin2hex($data));

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
                    $on_off = $brightness != '00' ? 'on' : 'off';
                    $switch = $brightness != '00' ? true : false;
                    $deviceProperty = ['on_off' => $switch, 'brightness' => hexdec($brightness)];
                    $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'channel' => $channel, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                    $sender_io->emit('udp', $udp);
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

                    $this->updataLed('31', $red, $subnetid, $deviceid, $con, $sender_io);
                    $this->updataLed('32', $green, $subnetid, $deviceid, $con, $sender_io);
                    $this->updataLed('33', $blue, $subnetid, $deviceid, $con, $sender_io);
                    $this->updataLed('34', $mix, $subnetid, $deviceid, $con, $sender_io);
                    $red = $this->tocolor($red);
                    $green = $this->tocolor($green);
                    $blue = $this->tocolor($blue);
                    $mix = $this->tocolor($mix);
                    $color = "#" . $red . $green . $blue;
                    $on_off = $color != "#000000" ? 'on' : 'off';
                    $switch = $color != "#000000" ? true : false;
                    if ($switch) {
                        $deviceProperty = ['on_off' => $switch, 'red' => $red, 'green' => $green, 'blue' => $blue, 'mix' => $mix, 'color' => $color];
                    } else {
                        $deviceProperty = ['on_off' => $switch];
                    }
                    $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                    $sender_io->emit('udp', $udp);
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
                        $this->updataLed('31', $red, $subnetid, $deviceid, $con, $sender_io);
                        $this->updataLed('32', $green, $subnetid, $deviceid, $con, $sender_io);
                        $this->updataLed('33', $blue, $subnetid, $deviceid, $con, $sender_io);
                        $this->updataLed('34', $mix, $subnetid, $deviceid, $con, $sender_io);
                             //普通LED模式
                        $red = $this->tocolor($red);
                        $blue = $this->tocolor($blue);
                        $green = $this->tocolor($green);
                        $mix = $this->tocolor($mix);
                        $color = "#" . $red . $green . $blue;
                        $on_off = $color != "#000000" ? true : false;
                        if ($on_off) {
                            $deviceProperty = ['on_off' => $on_off, 'red' => $red, 'green' => $green, 'blue' => $blue, 'mix' => $mix, 'color' => $color];
                        } else {
                            $deviceProperty = ['on_off' => $on_off];
                        }
                        $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                        $sender_io->emit('udp', $udp);
                        if ($color != "#000000") {
                            $on_off = true;
                            $sql = "update device as a left join address as b on a.address = b.address set on_off = 'on',mode = '" . $color . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and devicetype = 'led'";
                            mysqli_query($con, $sql);
                        } else {
                            $on_off = false;
                            $sql = "update device as a left join address as b on a.address = b.address set on_off = 'off',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and devicetype = 'led'";
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
                                $switch = true;
                            } else {
                                $on_off = "off";
                                $switch = false;
                            }

                            $channel = dechex($i);
                            if (strlen($channel) < 2) {
                                $channel = '0' . $channel;
                            }
                            $deviceProperty = ['on_off' => $switch, 'brightness' => hexdec($brightness)];
                            $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'channel' => $channel, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                            $sender_io->emit('udp', $udp);
                                // echo  $channel;
                            if ($on_off == 'on') {
                                $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "' and mode = '" . $brightness . "',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
                            } else {
                                $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',run_date = null  where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "' and  channel = '" . $channel . "'";
                            }

                            mysqli_query($con, $sql);
                        }
                    }

                    break;
                case "1901":
                    $cool_strat = $this->toTmp(substr($msg, 50, 2));
                    $cool_end = $this->toTmp(substr($msg, 52, 2));
                    $heat_strat = $this->toTmp(substr($msg, 54, 2));
                    $heat_end = $this->toTmp(substr($msg, 56, 2));
                    $auto_strat = $this->toTmp(substr($msg, 58, 2));
                    $auto_end = $this->toTmp(substr($msg, 60, 2));
                    break;
                case "e3d9":
                    $type = substr($msg, 50, 2);
                    $value = substr($msg, 52, 2);
                    $channel = substr($msg, 54, 2);
                    $key = "";
                    switch ($type) {
                        //空调模块
                        case "03":
                            $type = 'on_off';
                            $key = $type;
                            if ($value == "01") {
                                $value = true;

                            } else {
                                $value = false;
                            }
                            break;
                        case "05":
                        //[wind_auto,high,medium,low]
                            $type = 'grade';
                            $key = $type;
                            switch ($value) {
                                case '00':
                                    $value = 0;
                                    break;
                                case '01':
                                    $value = 1;
                                    break;
                                case '02':
                                    $value = 2;
                                    break;
                                case '03':
                                    $value = 3;
                                    break;
                            }
                            break;
                        case "06":
                            $type = 'mode';
                            $key = $type;
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
                            $key = 'coolTmp';
                            $value = $this->toTmp($value);
                            break;
                        case "07":
                            $type = 'operation_1';
                            $key = 'heatTmp';
                            $value = $this->toTmp($value);
                            break;
                        case "08":
                            $type = 'operation_1';
                            $key = 'autoTmp';
                            $value = $this->toTmp($value);
                            break;
                            //地热模块
                        case "14":
                            $type = 'on_off';
                            $key = $type;
                            if ($value == "01") {
                                $value = true;

                            } else {
                                $value = false;
                            }
                            break;
                        case "15":
                            $type = 'mode';
                            $key = $type;
                            switch ($value) {
                                case '01':
                                    $value = "manual";
                                    break;
                                case '02':
                                    $value = "day";
                                    break;
                                case '03':
                                    $value = "night";
                                    break;
                                case '04':
                                    $value = "away";
                                    break;
                                case '05':
                                    $value = "alarm";
                                    break;
                            }
                            break;
                        case "17":
                            $type = 'manualTemperature';
                            $key = $type;
                            $value = hexdec($value);
                            break;
                    }
                    if ($key != "") {
                        $deviceProperty = [$key => $value];
                        $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'channel' => $channel, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                        $sender_io->emit('udp', $udp);
                        if ($type == 'on_off') {
                            if ($value) {
                                $sql = "update device as a left join address as b on a.address = b.address set on_off = 'on',run_date = now() where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                            } else {
                                $sql = "update device as a left join address as b on a.address = b.address set on_off = 'off',run_date = null where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                            }
                        } else {
                            $sql = "update device as a left join address as b on a.address = b.address set " . $type . " = '" . $value . "' where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                        }
                        mysqli_query($con, $sql);
                    }
                    break;
                case "e0ed":
                    $on_off = substr($msg, 50, 2);
                    $on_off = $on_off != '00' ? 'on' : 'off';
                    $switch = $on_off != '00' ? true : false;
                    $devicetype = 'ac';
                    $mode = substr($msg, 54, 1);
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
                    $grade = substr($msg, 55, 1);
                    $grade = hexdec($grade);
                    switch ($grade) {
                        case 0:
                            $grade = 0;
                            break;
                        case 1:
                            $grade = 1;
                            break;
                        case 2:
                            $grade = 2;
                            break;
                        case 3:
                            $grade = 3;
                            break;
                    }
                    $coolTmp = $this->toTmp(substr($msg, 52, 2));
                    $heatTmp = $this->toTmp(substr($msg, 60, 2));
                    $autoTmp = $this->toTmp(substr($msg, 64, 2));

                    $deviceProperty = ['on_off' => $switch, 'mode' => $mode, 'grade' => $grade, 'coolTmp' => $coolTmp, 'heatTmp' => $heatTmp, 'autoTmp' => $autoTmp];

                    $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                    $sender_io->emit('udp', $udp);
                    if ($on_off == 'on') {
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $mode . "',grade = '" . $grade . "',operation_1 = '" . $coolTmp . "',operation_2 = '" . $heatTmp . "',operation_3 = '" . $autoTmp . "',run_date = now()  where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    } else {
                        $sql = "update device as a left join address as b on a.address = b.address set on_off = '" . $on_off . "',mode = '" . $mode . "',grade = '" . $grade . "',operation_1 = '" . $coolTmp . "',operation_2 = '" . $heatTmp . "',operation_3 = '" . $autoTmp . "',run_date = null  where subnetid = '" . $subnetid . "' and  deviceid = '" . $deviceid . "'";
                    }
                    mysqli_query($con, $sql);
                    break;
                case "03c8":
                //地热模块
                    $channel = $this->getadditional($msg, 0);
                    //获取各个模式的温度
                    $manualTemperature = hexdec($this->getadditional($msg, 1));
                    $dayTemperature = hexdec($this->getadditional($msg, 3));
                    $nightTemperature = hexdec($this->getadditional($msg, 5));
                    $awayTemperature = hexdec($this->getadditional($msg, 7));

                    $insideSensorSubnetID = $this->getadditional($msg, 9);
                    $insideSensorDeviceID = $this->getadditional($msg, 10);
                    $insideSensorChannel = $this->getadditional($msg, 11);
                    $deviceProperty = ['manualTemperature' => $manualTemperature, 'dayTemperature' => $dayTemperature, 'nightTemperature' => $nightTemperature, 'awayTemperature' => $awayTemperature, 'insideSensorSubnetID' => $insideSensorSubnetID, 'insideSensorDeviceID' => $insideSensorDeviceID, 'insideSensorChannel' => $insideSensorChannel];

                    $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'channel' => $channel, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                    $sender_io->emit('udp', $udp);
                    //获取该建筑发在区域的mac地址目标ip目标端口
                    $address = $this->getAddress($subnetid, $deviceid);
                    $macAddress = $address["mac"];
                    $dest_address = $address["ip"];
                    $dest_port = $address["port"];
                    //获取温度
                    $sendCommand = new SendCommand();
                    $sendCommand->send('E3', 'E7', $insideSensorSubnetID, $insideSensorDeviceID, ['01'], $macAddress, $dest_address, $dest_port);
                    break;
                case "e3e8":
                    //地热模块
                    if (!$this->getadditional($msg, 0)) {
                        return;
                    }
                    $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'operatorCode' => $operatorCode, 'udp' => $msg];
                    $sender_io->emit('udp', $udp);
                    break;
                case '03ca':
                    //获取该建筑发在区域的mac地址目标ip目标端口
                    $address = $this->getAddress($subnetid, $deviceid);
                    $macAddress = $address["mac"];
                    $dest_address = $address["ip"];
                    $dest_port = $address["port"];
                    $sendCommand = new SendCommand();
                    $sendCommand->send('03', 'CB', $subnetid, $deviceid, [], $macAddress, $dest_address, $dest_port);
                    break;
                case "03cc":
                    //地热模块
                    $dayTimeHour = hexdec($this->getadditional($msg, 0));
                    $dayTimeMinute = hexdec($this->getadditional($msg, 1));
                    $nightTimeHour = hexdec($this->getadditional($msg, 2));
                    $nightTimeMinute = hexdec($this->getadditional($msg, 3));

                    $dayTime = $dayTimeHour . ':' . $dayTimeMinute;
                    $nightTime = $nightTimeHour . ':' . $nightTimeMinute;
                    $deviceProperty = ['dayTime' => $dayTime, 'nightTime' => $nightTime];
                    $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                    $sender_io->emit('udp', $udp);
                    break;
                case "e3db":
                    $channel = $this->getadditional($msg, 2);
                    $type = $this->getadditional($msg, 0);
                    $value = $this->getadditional($msg, 1);
                    $key = "";
                    switch ($type) {
                        case "14":
                            $type = 'on_off';
                            $key = $type;
                            if ($value == "01") {
                                $value = true;

                            } else {
                                $value = false;
                            }
                            break;
                        case "15":
                            $type = 'mode';
                            $key = $type;
                            switch ($value) {
                                case '01':
                                    $value = "manual";
                                    break;
                                case '02':
                                    $value = "day";
                                    break;
                                case '03':
                                    $value = "night";
                                    break;
                                case '04':
                                    $value = "away";
                                    break;
                                case '05':
                                    $value = "alarm";
                                    break;
                            }
                    }
                    if ($key != "") {
                        $deviceProperty = [$key => $value];
                        $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'channel' => $channel, 'operatorCode' => $operatorCode, 'deviceProperty' => $deviceProperty];
                        $sender_io->emit('udp', $udp);
                    }
                    break;
                case "02e1":
                    $sender_io->emit('music', $msg);
                        // $msglen = count($msg);
                        // $stop = $msglen - 52 - 4;
                        // $source = $this->getadditional($msg, 0);
                        // $albumpack = subst($msg, 52, $stop);
                        // $additionalContentData = [$source];
                        // $additionalContentData = $this->strToarr($additionalContentData, $albumpack);
                        // //获取该建筑发在区域的mac地址目标ip目标端口
                        // $address = $this->getAddress($subnetid, $deviceid);
                        // $macAddress = $address["mac"];
                        // $dest_address = $address["ip"];
                        // $dest_port = $address["port"];
                        // //发送UDP
                        // $sendCommand = new SendCommand();
                        // $sendCommand->send('02', 'E2', $subnetid, $deviceid, $additionalContentData, $macAddress, $dest_address, $dest_port);
                    break;
                case "02e3":
                    $sender_io->emit('music', $msg);
                        //  //获取该建筑发在区域的mac地址目标ip目标端口
                        //     $address = $this->getAddress($subnetid, $deviceid);
                        //     $macAddress = $address["mac"];
                        //     $dest_address = $address["ip"];
                        //     $dest_port = $address["port"];

                        //     $msglen = count($msg);
                        //     $stop = $msglen - 52 - 4;
                        //     $source = $this->getadditional($msg, 2);
                        //     $firstAlbumNo = $this->getadditional($msg, 5);
                        //     $albumnum = $this->getadditional($msg, 4);
                        //     $albumlist = substr($msg, 60);
                        //     for ($i = 0; $i < $albumnum; $i++) {
                        //         $albumNo = substr($albumlist . $albumCount, 2);
                        //         $albumLength = substr($albumlist, $albumCount + 2, 2);
                        //         $albumLength = intval('0x' + $albumLength) * 2;
                        //         $albumNameList = substr($albumlist, $albumCount + 4, $albumLength);
                        //         $albumNameList = $this->strToarr4($albumNameList);
                        //         $albumName = [];
                        //         foreach ($albumNameList as $k => $v) {
                        //             array_push($albumName, chr($v));
                        //         }
                        //         $albumName = implode("", $albumName);
                        //         $albumObj = [
                        //             'albumName' => $albumName,
                        //             'albumNo' => $albumNo
                        //         ];
                        //         $albumObj = json_encode($albumObj);
                        //         array_push($albumList, $albumObj);
                        //         $albumNoList[$albumNo] = false;
                        //         $albumCount = $albumCount + $albumLength + 4;
                        //     }
                        //     //发送socket给前端
                        //     $udp = ['subnetid' => $subnetid, 'deviceid' => $deviceid, 'source' => $source, 'albumList' => $albumList];
                        //     $sender_io->emit('udp', $udp);
                        //     //发送UDP
                        //     $sendCommand = new SendCommand();
                        //     foreach ($albumNoList as $k => $v) {
                        //         $sendCommand->send('02', 'E4', $subnetid, $deviceid, [$source, $k], $macAddress, $dest_address, $dest_port);
                        //         usleep(100000);
                        //     }
                    break;
                case "02e5":
                    $sender_io->emit('music', $msg);
                        //  //获取该建筑发在区域的mac地址目标ip目标端口
                        //     $address = $this->getAddress($subnetid, $deviceid);
                        //     $macAddress = $address["mac"];
                        //     $dest_address = $address["ip"];
                        //     $dest_port = $address["port"];

                        //     $source = $this->getadditional($msg, 0);
                        //     $albumno = $this->getadditional($msg, 1);
                        //     $songpack = substr($msg, 54, 2);
                        //     $songpack = intval("0x" + $songpack);
                        //     //发送UDP
                        //     $sendCommand = new SendCommand();
                        //     for ($i = 0; $i < $songpack; $i++) {
                        //         $sendCommand->send('02', 'E6', $subnetid, $deviceid, [$source, $albumno, $this->toHex($i)], $macAddress, $dest_address, $dest_port);
                        //         usleep(100000);
                        //     }
                    break;
                case "02e7":
                    $sender_io->emit('music', $msg);
                        //  //获取该建筑发在区域的mac地址目标ip目标端口
                        //     $address = $this->getAddress($subnetid, $deviceid);
                        //     $macAddress = $address["mac"];
                        //     $dest_address = $address["ip"];
                        //     $dest_port = $address["port"];

                        //     $source = $this->getadditional($msg, 2);
                        //     $songNum = $this->getadditional($msg, 5);
                        //     $songNum = intval("0x" + $songNum);
                        //     $songCount = 0;
                        //     $songForNum = 0;
                        //     $songpack = substr($msg, 54, 2);
                        //     $songpack = intval("0x" + $songpack);
                        //     //发送UDP
                        //     $sendCommand = new SendCommand();
                        //     for ($i = 0; $i < $songpack; $i++) {
                        //         $sendCommand->send('02', 'E6', $subnetid, $deviceid, [$source, $albumno, $this->toHex($i)], $macAddress, $dest_address, $dest_port);
                        //         usleep(100000);
                        //     }
                    break;
                case "000f":
                    $length = strlen($msg) - 50 - 4;
                    $remark = substr($msg, 50, $length);
                    $arr = [];
                    $strArr = [];
                    $this->strToarr($arr,$remark);
                    foreach($arr as $k =>$v){
                        $str = chr(hexdec($v));
                        $strArr.push($str);
                    }
                    $remark = join($strArr,"");
                    $udp = ['subnetid' => hexdec($subnetid), 'deviceid' => hexdec($deviceid),'deviceTypeId'=>hexdec($deviceTypeId),'operatorCode' => $operatorCode, 'remark' => $remark];
                    $sender_io->emit('originalDevices', $udp);
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



