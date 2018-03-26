<?php
// error_reporting(E_ALL^E_NOTICE^E_WARNING);
class UdpSocket
{
    function udpSocket($address = '', $port = 0, $dest_address = '', $dest_port = 0, $msg = '')
    {
        //创建socket连接
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        //设置广播属性
        socket_set_option($socket, 65535, SO_BROADCAST, 1);
        // if ($port != 0) {
        //     socket_bind($socket, $address, "6000");
        // }
        socket_bind($socket, $address, $port);
        // socket_bind($socket, $address, $port);
        // socket_bind( $socket, '172.18.88.223', '8888' );
        //发送广播
        $dest_address = gethostbyname($dest_address);
        socket_sendto($socket, $msg, strlen($msg), 0, $dest_address, $dest_port);
        // socket_close($socket);

    }
}