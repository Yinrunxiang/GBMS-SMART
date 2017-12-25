<?php
// error_reporting(E_ALL^E_NOTICE^E_WARNING);
class UdpSocket
{
    function udpSocket($address = '',$port = 0,$dest_address = '',$dest_port = 0,$msg = ''){
        //创建socket连接
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        //设置广播属性
        socket_set_option($socket,65535,SO_BROADCAST,1);
        socket_bind( $socket, '', '6000' );
        // socket_bind( $socket,  gethostbyname($_ENV['COMPUTERNAME']), '6000' );
        // socket_bind( $socket, '192.168.1.77', '8888' );
        // socket_bind( $socket, '192.168.43.246', '8888' );
        // socket_bind( $socket, '172.18.88.223', '8888' );
        // echo $dest_address.' ';
        // echo $dest_port;
        //发送广播
        $dest_address = gethostbyname($dest_address);
        while(socket_sendto($socket, $msg, strlen($msg), 0, $dest_address, $dest_port))
        {
            return $hear = '发送成功';
        }
        return $hear = '发送失败';
             
    }
}