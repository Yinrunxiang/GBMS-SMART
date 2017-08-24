<?php
header("Content-type: text/html; charset=utf-8");
// require_once '/login.php';
use Workerman\Worker;
include '../../vendor/autoload.php';

$worker = new Worker('udp://0.0.0.0:8888');
$worker->onMessage = function($connection, $data){
    
    // if($data == "get"){
    //    global $str;
    //    $connection->send($str);
    // }
    // else {
    //     global $str;
    //     $str = $data."+".$str;
    //     $connection->send("接收：".$data);
    // }
    var_dump($data);
    var_dump($connection);
    // $obj = json_decode($data);
    // $connection->send($obj);
    // $udp_id = $obj->$udp_id;
    // $udp_type = $obj->$udp_type;
    // $udp_flag = $obj->$udp_flag;
    // $udp_content = $obj->$udp_content;
    // $sql="insert into udp (udp_id,udp_type,udp_flag,udp_content) values (".$udp_id."','".$udp_type."','".$udp_flag."','".$udp_content."')";
    // if (!mysqli_query($con,$sql))
    // {
    //     $connection->send("Send failed: " . mysqli_error($con));
    // }else{
    //     $connection->send("Send successfully");
    // }
    
};
// $worker->onWorkerStart = function($worker)
// {
//     echo "Worker starting...\n";
// };
Worker::runAll();