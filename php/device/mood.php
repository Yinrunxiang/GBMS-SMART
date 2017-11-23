<?php
include '../database.php';

$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $mood = isset($_REQUEST["mood"]) ? $_REQUEST["mood"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $devicetypes = isset($_REQUEST["devicetypes"]) ? $_REQUEST["devicetypes"] : '';
        $re = 1;
        for ($i = 0; $i  < count($devicetypes); $i++) {
            $devicetype = json_decode($devicetypes[$i]);
            $insertMood = "insert into mood (mood,address,floor,room,device,status_1,status_2,status_3,status_4,status_5) select ".$mood.",".$address.",".$floor.",".$room.",id,on_off,mode,grade,operation_1,operation_2,operation_3 where address = '".$address."' and floor = '".$floor."' and room  = '".$room."' and devicetype = '".$devicetype."'";
            if (!mysqli_query($con,$sql))
            {
                $re = $re * -1;
                $re_str = $re_str." Delete failed: " .$selection->room;
            }
            else{
                
            }
            
        }
        if ($re == -1)
        {
            $message = [];
            $message[0] = false;
            $message[1] = $re_str;
            echo(json_encode($message)); 
        }
        else{
            $message = [];
            $message[0] = true;
            $message[1] = "inseet successfully";
            echo(json_encode($message)); 
        }
        break;
    case "delete":
        $mood = isset($_REQUEST["mood"]) ? $_REQUEST["mood"] : '';
        $sql="delete from  mood where mood = '".$mood."'";
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "delete failed: " . mysqli_error($con);
            echo(json_encode($message)); 
        }else{
            $message = [];
            $message[0] = true;
            $message[1] = "delete successfully";
            echo(json_encode($message)); 
        }
        break;
    case "search":
        $sql="SELECT id,mood,device,address,floor,room,subnetid,deviceid,status_1,status_2,status_3,status_4,status_5 FROM mood as a left join device as b on a.device = b.id where a.address = '".$address."' and a.floor = '".$floor."' and a.room  = '".$room."'";
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