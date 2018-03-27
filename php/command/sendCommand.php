<?php
require_once __DIR__ . '/../udp/UdpProtocol.php';
require_once __DIR__ . '/../udp/UdpSocket.php';
class SendCommand
{
    public static function send($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress, $dest_address, $dest_port)
    {
        $udpProtocol = new UdpProtocol();
        $msg = $udpProtocol->UdpProtocol($operatorCodefst, $operatorCodesec, $targetSubnetID, $targetDeviceID, $additionalContentData, $macAddress);
        //广播地址
        
        // $dest_address = $dest_address ? $dest_address : '255.255.255.255';
        // $dest_port = $dest_port ? $dest_port : 6000;
        $udpSocket = new UdpSocket();
        // $onlineip = '';
        // if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        //     $onlineip = getenv('HTTP_CLIENT_IP');
        // } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        //     $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        // } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        //     $onlineip = getenv('REMOTE_ADDR');
        // } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        //     $onlineip = $_SERVER['REMOTE_ADDR'];
        // }
        // echo $onlineip;
        $hear = $udpSocket->UdpSocket("0.0.0.0", 0, $dest_address, $dest_port, $msg);
        // $hear = $udpSocket->UdpSocket($onlineip, '6000', $dest_address, $dest_port, $msg);
        usleep(100000);
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