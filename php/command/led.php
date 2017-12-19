<?php
require_once __DIR__ .'/sendCommand.php';
$sendCommand = new SendCommand();
class Led
{
    public static function switch_change($val,$mode,$targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        if ($val) {
            $red = $sendCommand->toHex(round(intval("0x".substr($mode,1,2))));
            $green = $sendCommand->toHex(round(intval("0x".substr($mode,3,2))));
            $blue = $sendCommand->toHex(round(intval("0x".substr($mode,5,2))));
            $operatorCodefst = "F0";
            $operatorCodesec = "80";
            $additionalContentData = [$red,$green,$blue,"00","00","00"];
            $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
            
        } else {
            $operatorCodefst = "F0";
            $operatorCodesec = "80";
            $additionalContentData = ["00","00","00","00","00","00"];
            $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
            
        }
        
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function headleChangeColor($mode,$channel, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $red = $sendCommand->toHex(round(intval("0x".substr($mode,1,2))));
        $green = $sendCommand->toHex(round(intval("0x".substr($mode,3,2))));
        $blue = $sendCommand->toHex(round(intval("0x".substr($mode,5,2))));
        $operatorCodefst = "F0";
        $operatorCodesec = "80";
        $additionalContentData = [$red,$green,$blue,"00","00","00"];
        $macAddress = $macAddress == "" ? [] : split(".", $macAddress);
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
}


?>