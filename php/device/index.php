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
        $devicetype = isset($_REQUEST["devicetype"]) ? $_REQUEST["devicetype"] : '';
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $sql="insert into device (device,subnetid,deviceid,channel,address,devicetype,breed,status) values ('".$device."','".$subnetid."','".$deviceid."','".$channel."','".$address."','".$devicetype."','".$breed."','enabled')";
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
    case "update":
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
        $device = isset($_REQUEST["device"]) ? $_REQUEST["device"] : '';
        $subnetid = isset($_REQUEST["subnetid"]) ? $_REQUEST["subnetid"] : '';
        $deviceid = isset($_REQUEST["deviceid"]) ? $_REQUEST["deviceid"] : '';
        $channel = isset($_REQUEST["channel"]) ? $_REQUEST["channel"] : '';
        $devicetype = isset($_REQUEST["devicetype"]) ? $_REQUEST["devicetype"] : '';
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $sql="update device set device = '".$device."', subnetid = '".$subnetid."', deviceid = '".$deviceid."', channel = '".$channel."', devicetype = '".$devicetype."', breed = '".$breed."', address = '".$address."' where id = '".$id."'";
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "update failed: " . mysqli_error($con);
            echo(json_encode($message)); 
        }else{
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo(json_encode($message)); 
        }
        break;
    case "delete":
        
        $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
        $re_str = "";
        for ($i = 0; $i  < count($selections); $i++) {
            $selection = json_decode($selections[$i]);
            $sql = " delete from device where id = '".$selection->id."'";
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
        $sql = " update device set status = '".$status."' where id = '".$selection->id."'";
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
            $sql = " update device set starttime = '".$selection->starttime."' where id = '".$selection->id."'";
        }else{
            $sql = " update device set endtime = '".$selection->endtime."' where id = '".$selection->id."'";
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
        $sql="SELECT a.id,device,subnetid,deviceid,channel,mac,ip,port,lat,lng,devicetype,on_off,mode,grade,breed,country,a.address,a.status,starttime,endtime FROM device as a left join address as b on a.address = b.address order by address,devicetype ";
        $result = mysqli_query($con,$sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($results)); 
        echo $json_results;
    break;  
    // case "getrecord":
    //     $record = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light'";
    //     $result = mysqli_query($con,$record);
    //     $record = array();
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $record[] = $row;
    //     }
    //     // $data = [$ac_breed,$record];
    //     $json_results = str_replace("\/","/",json_encode($record)); 
    //     echo $json_results;
    // break;  
    case "getrecord":
        $record = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light' and id > 0 ";
        $result = mysqli_query($con,$record);
        $record = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $record[] = $row;
        }
        // $data = [$ac_breed,$record];
        $json_results = str_replace("\/","/",json_encode($record)); 
        echo $json_results;
    break;    
    // case "getrecord":
    //     $read  =  ture;
    //     $start =  0;
    //     $end = 1000;
    //     $record = array();
    //     while($read)
    //     {    
        
    //         $sql = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light' and id > '".$start."' and id <= '".$end."'";
    //         $result = mysqli_query($con,$sql);
    //         $data = array();
    //         while ($row = mysqli_fetch_assoc($result)) {
                
    //             $data[] = $row;
    //         }
    //         // $data = [$ac_breed,$record];
            
            
    //         if(count($record) == 0)
    //         {    
    //             $read = false;
    //             // break;数据为空,直接停止了循环
    //         }
    //         array_push($record,$data);
    //         $start = $end;
    //         $end += 1000;
        
    //     }
    //     $json_results = str_replace("\/","/",json_encode($record)); 
    //     echo $json_results;
    // break;  
};
mysqli_close($con);


?>