<?php
namespace app\common\udp\device;
use app\common\udp\SendCommand;
$sendCommand = new SendCommand();
class Ac
{
    public static function switch_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        if ($val) {
            $operatorCodefst = "E3";
            $operatorCodesec = "D8";
            $additionalContentData = ["03","01"];
            
        } else {
            $operatorCodefst = "E3";
            $operatorCodesec = "D8";
            $additionalContentData = ["03","00"];           
        }
        
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function autotmp_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["08",$sendCommand->toHex($val)];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function cooltmp_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["04",$sendCommand->toHex($val)];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function heattmp_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["07",$sendCommand->toHex($val)];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function wind_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["05",$sendCommand->toHex($val)];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function autobtn($targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","03"];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function fanbtn($targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","02"];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function coolbtn($targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","00"];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function heatbtn($targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","01"];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function send($schedule)
    {
        global $sendCommand;
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","01"];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
}


?>