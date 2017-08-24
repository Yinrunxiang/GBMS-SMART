<?php
// error_reporting(E_ALL^E_NOTICE^E_WARNING);
class UdpSocket
{
    function udpSocket($address = '',$port = 0,$dest_address = '',$dest_port = 0,$msg = ''){
        //创建socket连接
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        //设置广播属性
        socket_set_option($socket,65535,SO_BROADCAST,1);
        // socket_bind( $socket, $address, $port );
        //发送广播
        while(socket_sendto($socket, $msg, strlen($msg), 0, $dest_address, $dest_port))
        {
            return $hear = '发送成功';
        }
        return $hear = '发送失败';
        //接收广播

        // $socket = stream_socket_client("udp://{$dest_address}:{$dest_port}", $errno, $errstr);  
        // // if( !$socket ){  
        // //     die("ERROR: {$errno} - {$errstr}\n");  
        // // }  
        // fwrite($socket, $msg."\n"); 


        // $hear = '';
        // while($hear = socket_read($socket, 1024, PHP_BINARY_READ)){
        //         $hear = bin2hex($hear);
        // }
        // return $hear;
        // $hear = "";
        // while ( true ) {
        //     $from = "";
        //     $port = 0;
        //     socket_recvfrom( $socket, $buf,1024, 0, $from, $port );
        //     $hear = $hear.$buf;
        //     return $hear;
        //     usleep( 1000 );
        // }
        
        // $hear = "";
        // $hear = socket_read($socket, 1024, PHP_BINARY_READ);  // 采用2进制方式接收数据
        // $hear = bin2hex($hear);
        // return $hear;
        
    }
}