<?php
namespace app\common\udp\device;
// use app\common\udp\SendCommand;
require_once '../SendCommand.php';
$sendCommand = new SendCommand();
class Light
{
    public static function switch_change($val,$channel,$targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        if ($val) {
            $operatorCodefst = "00";
            $operatorCodesec = "31";
            $additionalContentData = [$channel,"64","00","00"];
            
        } else {
            $operatorCodefst = "00";
            $operatorCodesec = "31";
            $additionalContentData = [$channel,"00","00","00"];
            
        }
        
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
    public static function slider_change($val,$channel, $targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        global $sendCommand;
        $operatorCodefst = "00";
        $operatorCodesec = "31";
        $additionalContentData = [$channel,$sendCommand->toHex($val),"00","00"];
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
}


?>