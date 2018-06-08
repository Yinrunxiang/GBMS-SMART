<?php
namespace app\common\udp\device;
use \app\common\udp\device\SendCommand;
require_once __DIR__.'/SendCommand.php';

class Curtain
{
    public static function switch_change($val,$channel,$channel_spare,$targetSubnetID, $targetDeviceID, $macAddress,$dest_address,$dest_port)
    {
        $sendCommand = new SendCommand();
        if ($val) {
            $operatorCodefst = "00";
            $operatorCodesec = "31";
            $additionalContentData = [$channel,"64","00","00"];       
        } else {
            $operatorCodefst = "00";
            $operatorCodesec = "31";
            $additionalContentData = [$channel_spare,"00","00","00"];          
        }
        
        $msg = $sendCommand->send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress,$dest_address,$dest_port);
    }
}


?>