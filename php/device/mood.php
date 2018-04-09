<?php
include '../database.php';
include '../udp/udp.php';
$UDP = new UDP();
$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $mood = isset($_REQUEST["mood"]) ? $_REQUEST["mood"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $devicetypes = isset($_REQUEST["devicetypes"]) ? $_REQUEST["devicetypes"] : '';
        $curtains = isset($_REQUEST["curtains"]) ? $_REQUEST["curtains"] : '';
        $re = 1;
        $re_str = "";
        $checkMood = "select id from mood where mood = '".$mood."' and address = '".$address."' and floor = '".$floor."' and room  = '".$room."'";
        $result = mysqli_query($con,$checkMood);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        if(count($results) >0){
            $message = [];
            $message[0] = false;
            $message[1] = "Repetition of mood names";
            echo(json_encode($message)); 
            return;
        }
        for ($i = 0; $i  < count($devicetypes); $i++) {
            $devicetype = $devicetypes[$i];
            $insertMood = "insert into mood (mood,address,floor,room,device,on_off,mode,grade,status_1,status_2,status_3) select '".$mood."','".$address."','".$floor."','".$room."',id,case when on_off = 'on' then '1' when on_off='off' or on_off='' or on_off is null then '0' end as on_off,mode,grade,operation_1,operation_2,operation_3 from device where address = '".$address."' and floor = '".$floor."' and room  = '".$room."' and devicetype = '".$devicetype."'";
            if (!mysqli_query($con,$insertMood))
            {
                $re = $re * -1;
                $re_str = $re_str." Save failed: " .$devicetype;
            }
            else{
                
            }
            
        }
        for ($i = 0; $i  < count($curtains); $i++) {
            
            if(isset($curtains[$i])){
                $curtain = json_decode($curtains[$i]);
            
            $on_off = $curtain->on_off?'1':'0';
            $updateMood = "update mood set on_off = '".$on_off."' where mood = '".$mood."' and  address = '".$address."' and floor = '".$floor."' and room  = '".$room."' and device = '".$curtain->id."'";
            if (!mysqli_query($con,$updateMood))
            {
                $re = $re * -1;
                $re_str = $re_str." Save failed: " .$curtain->device;
            }
            else{
                
            }
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
            $message[1] = "Save successfully";
            echo(json_encode($message)); 
        }
        break;
    case "delete":
        $mood = isset($_REQUEST["mood"]) ? $_REQUEST["mood"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $sql="delete from  mood where mood = '".$mood."' and address = '".$address."' and floor = '".$floor."' and room  = '".$room."'";
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
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $sql="SELECT a.id,a.mood,a.device,a.address,a.floor,a.room,subnetid,deviceid,channel,channel_spare,devicetype,mac,ip,port,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5 FROM mood as a left join device as b on a.device = b.id left join address as c on b.address = c.address where a.address = '".$address."' and a.floor = '".$floor."' and a.room  = '".$room."'";
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
    $mood = isset($_REQUEST["mood"]) ? $_REQUEST["mood"] : '';
    $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
    $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
    $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
    $sql="SELECT mood,a.device as id,subnetid,deviceid,channel,channel_spare,devicetype,a.on_off,a.mode,a.grade,status_1,status_2,status_3,status_4,status_5,ip,port,mac,c.operation as udp_type  FROM  mood as a left join device as b on a.device = b.id left join address as c on b.address = c.address where  mood = '".$mood."' and a.address = '".$address."' and a.floor = '".$floor."' and a.room = '".$room."' order by a.id";
    echo $sql;
    $result = mysqli_query($con,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $UDP->sendStatusUdp($row);
        // usleep(300000);
    }
    break;       
};
mysqli_close($con);


?>