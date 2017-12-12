<?php
include '../database.php';

$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $room_name = isset($_REQUEST["room_name"]) ? $_REQUEST["room_name"] : '';
        $image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $sql="insert into room (room,room_name,image,floor,address) values ('".$room."','".$room_name."','".$image."','".$floor."','".$mac."','".$address."','enabled')";
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
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $room_name = isset($_REQUEST["room_name"]) ? $_REQUEST["room_name"] : '';
        $image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $sql="update room set room_name = '".$room_name."',image = '".$image."' where room = '".$room."' and floor = '".$floor."' and address = '".$address."'";
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
            $sql = " delete from room where room = '".$selection->room."'";
            if (!mysqli_query($con,$sql))
            {
                $re = false;
                $re_str = $re_str." Delete failed: " .$selection->room;
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
    case "search":
        $schedule = isset($_REQUEST["schedule"]) ? " and schedule = '".$_REQUEST["schedule"]."'" : '';
        $keywords = isset($_REQUEST["schedule"]) ? " and schedule like '%".$_REQUEST["schedule"]."%'" : '';
        $page = intval(isset($_REQUEST["page"]) ? $_REQUEST["page"] : 0);
        $limit = intval(isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : 0);
        $start = $limit*($page-1) +1;
        $end = $limit*($page);
        // $getDataCount = "select count(id) as count from schedule";
        // $getDataCount = mysqli_query($con,$getDataCount);
        // $getDataCount = mysqli_fetch_assoc($getDataCount);
        // $getDataCount = $getDataCount["count"];
        $sql="SELECT * FROM schedule where 1=1  ".$schedule." ".$keywords." limit ".$start.",".$end."";
        // $sql="SELECT * FROM schedule as a left join schedule_command as b on a.id = b.schedule left join device as c on b.device =c.id";
        $result = mysqli_query($con,$sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($results)); 
        echo $json_results;
    break;    
    case "search_command":
    $schedule = isset($_REQUEST["schedule"]) ? $_REQUEST["schedule"] : '';
    $devices = isset($_REQUEST["devices"]) ? $_REQUEST["devices"] : '';
    $deleteCommand = "delete from schedule_command where schedule = '".$schedule."'";
    mysqli_query($con,$deleteCommand);

    
    for ($i = 0; $i  < count($devices); $i++) {
        $selection = json_decode($devices[$i]);
        $insertCommand = "insert into schedule_command (schedule,device,on_off,mode,grade,status_1,status_2,status_3) values ('".$schedule."','".$devices->id."','".$devices->on_off."','".$devices->mode."','".$devices->grade."','".$devices->operation_1."','".$devices->operation_2."','".$devices->operation_3."')";
        mysqli_query($con,$deleteCommand);
    }
        $sql="SELECT schedule,a.device,a.on_off,a.mode,a.grade,a.operation_1,a.operation_2,a.operation_3,a.operation_4,a.operation_5,subnetid,deviceid,channel,channel_spare FROM  schedule_command as a  left join device as b on a.device =b.id where  schedule = '".$schedule."'";
        
        $result = mysqli_query($con,$sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($results)); 
        echo $json_results;
    break; 
    case "insert_command":
    $schedule = isset($_REQUEST["schedule"]) ? $_REQUEST["schedule"] : '';

    $sql="SELECT schedule,a.device,a.on_off,a.mode,a.grade,a.operation_1,a.operation_2,a.operation_3,a.operation_4,a.operation_5,subnetid,deviceid,channel,channel_spare FROM  schedule_command as a  left join device as b on a.device =b.id where  schedule = '".$schedule."'";
        
        $result = mysqli_query($con,$sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($results)); 
        echo $json_results;
    break; 
};
mysqli_close($con);


?>