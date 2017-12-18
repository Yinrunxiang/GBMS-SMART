<?php
require_once '/udp/UdpProtocol.php';
require_once '/udp/UdpSocket.php';
class SendCommand
{
    public static function send($operatorCodefst,$operatorCodesec,$targetSubnetID,$targetDeviceID,$additionalContentData,$macAddress,$dest_address,$dest_port)
    {
        $udpProtocol = new UdpProtocol();
        $msg = $udpProtocol->UdpProtocol($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress);
        //本机地址
        $address = '127.0.0.1';
        $port = 6000;
        //广播地址
        $dest_address = $dest_address? $dest_address : '255.255.255.255';
        $dest_port = $dest_port? $dest_port : 6000;
        $udpSocket = new UdpSocket();
        $hear = $udpSocket->UdpSocket($address,$port,$dest_address,$dest_port,$msg);
        usleep(100000);
    }
    public static function toHex($num) {
        $num = dechex($num);
        if(strlen($num) < 2){
            $num = '0'.$num;
        }
        return $num ;
    }
}


?>