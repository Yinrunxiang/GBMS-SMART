<?php
require_once __DIR__ .'/sendCommand.php';
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
            $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        } else {
            $operatorCodefst = "02";
            $operatorCodesec = "18";
            $additionalContentData = ['04','04','00','00'];
            $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
            
        }
        
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function vol_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "02";
        $operatorCodesec = "18";
        $additionalContentData = ["05","01","03",$sendCommand->toHex($val)];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function mode_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "02";
        $operatorCodesec = "18";
        $additionalContentData = ["02",$val];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
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
        var_dump( $additionalContentData);
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
}


?>