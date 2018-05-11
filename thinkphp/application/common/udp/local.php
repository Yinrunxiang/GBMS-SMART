<?php
require_once __DIR__ . '/vendor/autoload.php';
use PHPSocketIO\SocketIO;
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Lib\Timer;
use app\common\udp\UdpServer;
require_once './UdpServer.php';

$sender_io = new SocketIO(2120);
// 客户端发起连接事件时，设置连接socket的各种事件回调
$sender_io->on('connection', function ($socket) {
    $socket->emit('update_online_count', "连接成功");
});
$sender_io->on('workerStart', function () {
    global $sender_io;
    $loaclPort = '6000';
    $remotePort = '8888';
    $loacl = new UdpServer($sender_io,$loaclPort);
    // $remote = new udpServer($sender_io,$remotePort);
});

if (!defined('GLOBAL_START')) {
    Worker::runAll();
}


