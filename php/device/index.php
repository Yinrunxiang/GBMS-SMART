<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:POST');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
$con = mysqli_connect('localhost','root','root');
if (!$con)
{
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"udp");
mysqli_set_charset($con, "utf8");

// $con = mysqli_connect('localhost','huangst_admin','123123');
// if (!$con)
// {
//     die('Could not connect: ' . mysqli_error($con));
// }
// mysqli_select_db($con,"g4ever_news");
// mysqli_set_charset($con, "utf8");

$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $device = isset($_REQUEST["device"]) ? $_REQUEST["device"] : '';
        $subnetid = isset($_REQUEST["subnetid"]) ? $_REQUEST["subnetid"] : '';
        $deviceid = isset($_REQUEST["deviceid"]) ? $_REQUEST["deviceid"] : '';
        $channel = isset($_REQUEST["channel"]) ? $_REQUEST["channel"] : '';
        $mac = isset($_REQUEST["mac"]) ? $_REQUEST["mac"] : '';
        $ip = isset($_REQUEST["ip"]) ? $_REQUEST["ip"] : '';
        $port = isset($_REQUEST["port"]) ? $_REQUEST["port"] : '';
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $devicetype = isset($_REQUEST["devicetype"]) ? $_REQUEST["devicetype"] : '';
        $sql="insert into device (device,subnetid,deviceid,channel,mac,ip,port,country,address,devicetype,status) values ('".$device."','".$subnetid."','".$deviceid."','".$channel."','".$mac."','".$ip."','".$port."','".$country."','".$address."','".$devicetype."','enabled')";
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "insert failed: " . mysqli_error($con);
            echo(json_encode($message)); 
        }else{
            $message = [];
            $message[0] = true;
            $message[1] = "insert successfully";
            echo(json_encode($message)); 
        }
        break;
    case "delete":
        
        $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
        $re_str = "";
        for ($i = 0; $i  < count($selections); $i++) {
            $selection = json_decode($selections[$i]);
            $sql = " delete from device where device = '".$selection->device."' and devicetype = '".$selection->devicetype."' and address = '".$selection->address."'";
            if (!mysqli_query($con,$sql))
            {
                $re = false;
                $re_str = $re_str." Delete failed: " .$selection->device;
            }
            else{
                $re = true;
            }
            
        }
        if (!$re)
        {
            $message = [];
            $message[0] = false;
            $message[1] = $re_str;
            echo(json_encode($message)); 
        }
        else{
            $message = [];
            $message[0] = true;
            $message[1] = "Delete successfully";
            echo(json_encode($message)); 
        }
        break;
    case "setStatus":
    $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : '';
    $re_str = "";
    for ($i = 0; $i  < count($selections); $i++) {
        $selection = json_decode($selections[$i]);
        $sql = " update device set status = '".$status."' where device = '".$selection->device."' and devicetype = '".$selection->devicetype."' and address = '".$selection->address."'";
        if (!mysqli_query($con,$sql))
        {
            $re = false;
            $re_str = $re_str."Failed: " .$selection->device;
        }
        else{
            $re = true;
        }
        
    }
    if (!$re)
    {
        $message = [];
        $message[0] = false;
        $message[1] = $re_str;
        echo(json_encode($message)); 
    }
    else{
        $message = [];
        $message[0] = true;
        $message[1] = "Successfully";
        echo(json_encode($message)); 
    }
    break;
    case "setTime":
    $selection = isset($_REQUEST["selection"]) ? $_REQUEST["selection"] : '';
    $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : '';
    $re_str = "";
        $selection = json_decode($selection);
        if($type == 'start'){
            $sql = " update device set starttime = '".$selection->starttime."' where device = '".$selection->device."' and devicetype = '".$selection->devicetype."' and address = '".$selection->address."'";
        }else{
            $sql = " update device set endtime = '".$selection->endtime."' where device = '".$selection->device."' and devicetype = '".$selection->devicetype."' and address = '".$selection->address."'";
        }
        
        if (!mysqli_query($con,$sql))
        {
            $re = false;
            $re_str = $re_str."Failed: " .$selection->device;
        }
        else{
            $re = true;
        }
    if (!$re)
    {
        $message = [];
        $message[0] = false;
        $message[1] = $re_str;
        echo(json_encode($message)); 
    }
    else{
        $message = [];
        $message[0] = true;
        $message[1] = "Successfully";
        echo(json_encode($message)); 
    }
    break;
    case "search":
        $sql="SELECT * FROM device";
        $result = mysqli_query($con,$sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($results)); 
        echo $json_results;
    break;  
    case "getrecord":
        $ac_breed="SELECT * FROM ac_breed";
        $result = mysqli_query($con,$ac_breed);
        $ac_breed = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $ac_breed[] = $row;
        }
        $light_breed="SELECT * FROM light_breed";
        $result = mysqli_query($con,$light_breed);
        $light_breed = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $light_breed[] = $row;
        }
        $record = "SELECT * FROM record where devicetype = 'ac'";
        $result = mysqli_query($con,$record);
        $record = array();
        while ($row = mysqli_fetch_assoc($result)) {
            // switch($row["devicetype"]){
            //     case "ac" :
            //     foreach ($ac_breed as $key => $value) { 
            //        if($value["breed"] ==  $row["breed"]){
            //         $row["watts"] = intval($value[$row["mode"]])+intval($value[$row["grade"]]);
            //        }
            //     }
                   
            //     break;
            //     case "light" :
            //     foreach ($light_breed as $key => $value) { 
            //         if($value["breed"] ==  $row["breed"]){
            //          $row["watts"] = intval($value["watts"]);
            //         }
            //      }
            //     break;
            // }
            $record[] = $row;
        }
        $data = [$ac_breed,$record];
        $json_results = str_replace("\/","/",json_encode($data)); 
        echo $json_results;
    break;     
};
mysqli_close($con);


?>