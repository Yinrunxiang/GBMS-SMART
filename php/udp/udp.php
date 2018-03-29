<?php
require_once __DIR__ .'/../command/ac.php';
require_once __DIR__ .'/../command/light.php';
require_once __DIR__ .'/../command/led.php';
require_once __DIR__ .'/../command/curtain.php';
require_once __DIR__ .'/../command/music.php';
$ac = new Ac;
$light = new Light;
$led = new Led;
$curtain = new Curtain;
$music = new Music;
class UDP
{
    public static function sendStatusUdp($command_row){
        global $ac;
        global $led;
        global $light;
        global $curtain;
        global $music;
        if($command_row['on_off'] == '1'){
            $time = isset($command_row['time']) ? intval($command_row['time']):0; 
            // echo($time);
            usleep($time * 1000000);
        }
        $targetSubnetID = $command_row['subnetid']; 
        $targetDeviceID = $command_row['deviceid']; 
        $macAddress = $command_row['mac'];
        $dest_address = $command_row['ip'];
        $dest_port = $command_row['port'];
        $devicetype = $command_row['devicetype'];
        $channel = $command_row['channel'];
        $channel_spare = $command_row['channel_spare'];
        $mode = $command_row['mode'];
        $grade = $command_row['grade'];
        //判断UDP发送模式
        $udp_type = $command_row['udp_type'];
        if ($udp_type== 1) {
            $dest_address = $dest_address ? $dest_address : '255.255.255.255';
            $macAddress = $macAddress ?explode(".",$macAddress) : [];
            $dest_port = 8888;
        }else{
            $dest_address = '255.255.255.255';
            $macAddress = [];
            $dest_port = 6000;
            
        }
        switch($devicetype){
            case 'ac':
                $tmp = $command_row['status_1'];
                if($command_row['on_off'] == '1'){
                    $ac->switch_change(true,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                    switch($command_row['mode']){
                        case "cool":
                        $ac->coolbtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        $ac->cooltmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                      case "fan":
                        $ac->fanbtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        $ac->cooltmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                      case "heat":
                        $ac->heatbtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        $ac->heattmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                      case "auto":
                        $ac->autobtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        $ac->autotmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                    };
                    switch($command_row['grade']){
                        case 'wind_auto':
                            $ac->wind_change("0",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                        case 'high':
                            $ac->wind_change("1",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                        case 'medial':
                            $ac->wind_change("2",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                        case 'low':
                            $ac->wind_change("3",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                    }
                }else{
                    $ac->switch_change(false,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
                
            break;
            case 'light':
                if($command_row['on_off'] == '1'){
                    $light->switch_change(true,$channel,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                }else{
                    $light->switch_change(false,$channel,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
            break;
            case 'led':
                if($command_row['on_off'] == '1'){
                    $led->switch_change(true,$mode,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                }else{
                    $led->switch_change(false,$mode,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
            break;
            case 'curtain':
                if($command_row['on_off'] == '1'){
                    $curtain->switch_change(true,$channel,$channel_spare,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                }else{
                    $curtain->switch_change(false,$channel,$channel_spare,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
            break;
            case 'music':
                if($command_row['on_off'] == '1'){
                    $val = $command_row['status_1'];
                    $val = 79 - intval($val);
                    $musicKey = $command_row['status_4'];
                    $music->vol_change($val,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                    $music->selectSong($musicKey,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                    $music->switch_change(true,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                    
                }else{
                    $music->switch_change(false,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
    
            break;
        }
    }
}


?>