<?php
include '../database.php';
include '../udp/udp.php';
$UDP = new UDP();
$action =  $_REQUEST["action"];

switch ($action)
{
    case "delete":
        $ids = isset($_REQUEST["ids"]) ? $_REQUEST["ids"] : [];
        $re_str = "";
        $re = true;
        for ($i = 0; $i  < count($ids); $i++) {
            $id = json_decode($ids[$i]);
            $deleteMacro = " delete from macro where id = '".$id."'";
            $deleteCommand = " delete from macro_command where macro = '".$id."'";
            if (!mysqli_query($con,$deleteMacro))
            {
                $re = false;
                $re_str = $re_str." Delete failed: " .$id ;
            }else{
                if (!mysqli_query($con,$deleteCommand))
                {
                    $re = false;
                    $re_str = $re_str." Delete failed: " .$id ;
                }
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
    case "delete_command":
        
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
        $re = true;
        $sql = " delete from macro_command where id = '".$id."'";
        if (!mysqli_query($con,$sql))
        {
            $re = false;
            $re_str = " Delete failed: " .$id;
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
    case "search":
        $keywords = isset($_REQUEST["keywords"]) && $_REQUEST["keywords"]!= ''? " and macro like '%".$_REQUEST["keywords"]."%'" : '';
        $page = intval(isset($_REQUEST["page"]) ? $_REQUEST["page"] : 0);
        $limit = intval(isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : 0);
        $start = $limit*($page-1);
        $end = $limit*($page);
        $getMacro="SELECT * FROM macro where 1=1  ".$keywords." limit ".$start.",".$end."";
        $macro = mysqli_query($con,$getMacro);
        $macros = array();
        while ($row = mysqli_fetch_assoc($macro)) {
            $macros[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($macros)); 
        echo $json_results;
    break;    
    case "insert_command":
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
    $macro = isset($_REQUEST["macro"]) ? $_REQUEST["macro"] : '';
    $devices = isset($_REQUEST["devices"]) ? $_REQUEST["devices"] : [];
    
    if($id == ''){
        $updateMacro = "insert into macro (macro) values ('".$macro."')";
        mysqli_query($con,$updateMacro);
        $id = "select max(id) as id from macro";
        $id = mysqli_query($con,$id);
        $id = mysqli_fetch_assoc($id);
        $id = $id['id'];
    }
    else{
        $updateMacro = "update macro set macro = '".$macro."' where id = '".$id."'";
        mysqli_query($con,$updateMacro);
    }
    $deleteCommand = "delete from macro_command where macro = '".$id."'";
    mysqli_query($con,$deleteCommand);
    $re = true;
    $re_str = "";
    for ($i = 0; $i  < count($devices); $i++) {
        $device = json_decode($devices[$i]);
        $insertCommand = "insert into macro_command (macro,device,on_off,mode,grade,status_1,status_2,status_3,status_4,status_5,time) values ('".$id."','".$device->id."','".$device->on_off."','".$device->mode."','".$device->grade."','".$device->operation_1."','".$device->operation_2."','".$device->operation_3."','".$device->operation_4."','".$device->operation_5."','".$device->time."')";
        if (!mysqli_query($con,$insertCommand))
        {
            $re = false;
            $re_str = $re_str." Delete failed: " .$device->device;
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
        $message[1] = "Insert successfully";
        echo(json_encode($message)); 
    }
    break; 
    case "search_command":
    $macro = isset($_REQUEST["macro"]) ? $_REQUEST["macro"] : '';

    $sql="SELECT macro,a.id as macro_id,a.device as id,subnetid,deviceid,b.device as device,devicetype,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,b.address,b.floor,b.room,room_name,a.time  FROM  macro_command as a left join device as b on a.device = b.id left join room as c on b.room = c.room and b.address = c.address and b.floor = c.floor  where  macro = '".$macro."' order by a.id";
        $result = mysqli_query($con,$sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($results)); 
        echo $json_results;
    break; 
    case "run":
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
    $macro = isset($_REQUEST["macro"]) ? $_REQUEST["macro"] : '';
    $sql="SELECT a.id as macro_id,a.device as id,subnetid,deviceid,channel,channel_spare,devicetype,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,ip,port,mac,a.time as time  FROM  macro_command as a left join device as b on a.device = b.id left join address as c on b.address = c.address where  a.macro = '".$id."' order by a.id";
    $result = mysqli_query($con,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $UDP->sendStatusUdp($row);
    }
    break; 
};
mysqli_close($con);


?>