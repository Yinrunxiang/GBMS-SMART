<?php
namespace app\common\udp;
// use app\common\udp\device\Ac;
// use app\common\udp\device\Light;
// use app\common\udp\device\Led;
// use app\common\udp\device\Curtain;
// use app\common\udp\device\Music;
require_once './device/Ac.php';
require_once './device/Light.php';
require_once './device/Led.php';
require_once './device/Curtain.php';
require_once './device/Music.php';
$ac = new Ac;
$light = new Light;
$led = new Led;
$curtain = new Curtain;
$music = new Music;

class Udp
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
						//usleep(300000);
                        $ac->coolbtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
						//usleep(300000);
                        $ac->cooltmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                      case "fan":
					    //usleep(300000);
                        $ac->fanbtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
						//usleep(300000);
                        $ac->cooltmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                      case "heat":
					    //usleep(300000);
                        $ac->heatbtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
						//usleep(300000);
                        $ac->heattmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                      case "auto":
					    //usleep(300000);
                        $ac->autobtn($targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
						//usleep(300000);
                        $ac->autotmp_change($tmp, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port);
                        break;
                    };
                    switch($command_row['grade']){
                        case 'wind_auto':
						//usleep(300000);
                            $ac->wind_change("0",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                        case 'high':
						//usleep(300000);
                            $ac->wind_change("1",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                        case 'medial':
						//usleep(300000);
                            $ac->wind_change("2",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                        case 'low':
						//usleep(300000);
                            $ac->wind_change("3",$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                        break;
                    }
                }else{
					//usleep(300000);
                    $ac->switch_change(false,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
                
            break;
            case 'light':
                if($command_row['on_off'] == '1'){
					//usleep(300000);
                    $light->switch_change(true,$channel,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                }else{
					//usleep(300000);
                    $light->switch_change(false,$channel,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
            break;
            case 'led':
                if($command_row['on_off'] == '1'){
					//usleep(300000);
                    $led->switch_change(true,$mode,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                }else{
					//usleep(300000);
                    $led->switch_change(false,$mode,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
            break;
            case 'curtain':
                if($command_row['on_off'] == '1'){
					//usleep(300000);
                    $curtain->switch_change(true,$channel,$channel_spare,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                }else{
					//usleep(300000);
                    $curtain->switch_change(false,$channel,$channel_spare,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
            break;
            case 'music':
                if($command_row['on_off'] == '1'){
                    $val = $command_row['status_1'];
                    $val = 79 - intval($val);
                    $musicKey = $command_row['status_4'];
					//usleep(300000);
                    $music->switch_change(true,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
					//usleep(300000);
                    $music->vol_change($val,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
					//usleep(300000);
                    $music->selectSong($musicKey,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
					
                    
                }else{
					//usleep(300000);
                    $music->switch_change(false,$targetSubnetID,$targetDeviceID,$macAddress,$dest_address,$dest_port);
                };
    
            break;
        }
    }
}


?>