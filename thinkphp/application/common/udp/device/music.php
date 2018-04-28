<?php
namespace app\common\udp\device;
use app\common\udp\SendCommand;
$sendCommand = new SendCommand();
class Music
{
    public static function switch_change($val,$targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        if ($val) {
            $operatorCodefst = "02";
            $operatorCodesec = "18";
            $additionalContentData = ['04','03','00','00'];
        } else {
            $operatorCodefst = "02";
            $operatorCodesec = "18";
            $additionalContentData = ['04','04','00','00'];
        }
        
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function vol_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "02";
        $operatorCodesec = "18";
        $additionalContentData = ["05","01","03",$sendCommand->toHex($val)];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function mode_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "02";
        $operatorCodesec = "18";
        $additionalContentData = ["02",$val];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function selectSong($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "02";
        $operatorCodesec = "18";
        $albumNo = substr($val, 0, 2);
        $songNoHigh = substr($val, 2, 2);
        $songNoLow = substr($val, 4, 2);
        $additionalContentData = ["06",$albumNo,$songNoHigh,$songNoLow];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
}


?>