<?php
namespace app\common\udp;
use app\common\udp\UdpProtocol;
use app\common\udp\UdpSocket;

class SendCommand
{
    public static function send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress, $dest_address, $dest_port)
    {
        $udpProtocol = new UdpProtocol;
        $msg = $udpProtocol->UdpProtocol($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress);
        //广播地址
        new UdpSocket("0.0.0.0", 0, $dest_address, $dest_port, $msg);
        usleep(300000);
    }
    public static function toHex($num)
    {
        $num = dechex($num);
        if (strlen($num) < 2) {
            $num = '0' . $num;
        }
        return $num;
    }
}


?>