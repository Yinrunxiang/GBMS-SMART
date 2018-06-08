<?php
namespace app\common\udp;
class UdpSocket
{
    function __construct($address = '', $port = 0, $dest_address = '', $dest_port = 0, $msg = '')
    {
        //创建socket连接
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        //设置广播属性
        socket_set_option($socket, 65535, SO_BROADCAST, 1);

        // $address = gethostbyname($_SERVER["SERVER_NAME"]) == '127.0.0.1'?$address:gethostbyname($_SERVER["SERVER_NAME"]);
        // $port = gethostbyname($_SERVER["SERVER_NAME"]) == '127.0.0.1'?'0':'8888';
        // echo $address.':'.$port;
        socket_bind($socket, $address, $port);
          
        // socket_bind( $socket, '39.108.129.244', '8888' );

        //发送广播
        $dest_address = gethostbyname($dest_address);
        // echo '发送'.$dest_address.$dest_port;
        socket_sendto($socket, $msg, strlen($msg), 0, $dest_address, $dest_port);
        // socket_close($socket);
    }
}