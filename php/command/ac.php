<?php
require_once './sendCommand.php';
class Ac
{
    public static function switch_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        if ($val) {
            $operatorCodefst = "E3";
            $operatorCodesec = "D8";
            $additionalContentData = ["03,01"];
            $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
            
        } else {
            $operatorCodefst = "E3";
            $operatorCodesec = "D8";
            $additionalContentData = ["03,00"];
            $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
            
        }
        
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function autotmp_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["08",$sendCommand->toHex($val)];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function cooltmp_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["04",$sendCommand->toHex($val)];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function heattmp_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["07",$sendCommand->toHex($val)];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function wind_change($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["05",$sendCommand->toHex($val)];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function autobtn($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","03"];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function fanbtn($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","02"];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function coolbtn($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","00"];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function heatbtn($val, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new sendCommand();
        $operatorCodefst = "E3";
        $operatorCodesec = "D8";
        $additionalContentData = ["06","01"];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
}


?>