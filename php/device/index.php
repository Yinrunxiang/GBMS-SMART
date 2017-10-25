<?php
// header("Content-type: text/html; charset=utf-8");
// // 指定允许其他域名访问  
// header('Access-Control-Allow-Origin:*');  
// // 响应类型  
// header('Access-Control-Allow-Methods:POST');  
// // 响应头设置  
// header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
// $con = mysqli_connect('localhost','root','root');
// if (!$con)
// {
//     die('Could not connect: ' . mysqli_error($con));
// }
// mysqli_select_db($con,"udp");
// mysqli_set_charset($con, "utf8");

include '../database.php';
function toHex($num) {
    $num = dechex($num);
    if(strlen($num) < 2){
        $num = '0'.$num;
    }
    return $num ;
}

$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $device = isset($_REQUEST["device"]) ? $_REQUEST["device"] : '';
        $subnetid = isset($_REQUEST["subnetid"]) ? $_REQUEST["subnetid"] : '';
        $subnetid = toHex($subnetid);
        $deviceid = isset($_REQUEST["deviceid"]) ? $_REQUEST["deviceid"] : '';
        $deviceid = toHex($deviceid);
        $channel = isset($_REQUEST["channel"]) ? $_REQUEST["channel"] : '';
        $channel = toHex($channel);
        $channel_spare = isset($_REQUEST["channel_spare"]) ? $_REQUEST["channel_spare"] : '';
        $channel_spare = toHex($channel_spare);
        $devicetype = isset($_REQUEST["devicetype"]) ? $_REQUEST["devicetype"] : '';
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $x_axis = isset($_REQUEST["x_axis"]) ? $_REQUEST["x_axis"] : '';
        $y_axis = isset($_REQUEST["y_axis"]) ? $_REQUEST["y_axis"] : '';
        $sql="insert into device (device,subnetid,deviceid,channel,channel_spare,address,floor,room,x_axis,y_axis,devicetype,breed,status) values ('".$device."','".$subnetid."','".$deviceid."','".$channel."','".$channel_spare."','".$address."','".$floor."','".$room."','".$x_axis."','".$y_axis."','".$devicetype."','".$breed."','enabled')";
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
        $subnetid = toHex($subnetid);
        $deviceid = isset($_REQUEST["deviceid"]) ? $_REQUEST["deviceid"] : '';
        $deviceid = toHex($deviceid);
        $channel = isset($_REQUEST["channel"]) ? $_REQUEST["channel"] : '';
        $channel = toHex($channel);
        $channel_spare = isset($_REQUEST["channel_spare"]) ? $_REQUEST["channel_spare"] : '';
        $channel_spare = toHex($channel_spare);
        $devicetype = isset($_REQUEST["devicetype"]) ? $_REQUEST["devicetype"] : '';
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $x_axis = isset($_REQUEST["x_axis"]) ? $_REQUEST["x_axis"] : '';
        $y_axis = isset($_REQUEST["y_axis"]) ? $_REQUEST["y_axis"] : '';
        $sql="update device set device = '".$device."', subnetid = '".$subnetid."', deviceid = '".$deviceid."', channel = '".$channel."',channel_spare = '".$channel_spare."', devicetype = '".$devicetype."', breed = '".$breed."', address = '".$address."', floor = '".$floor."', room = '".$room."', x_axis = '".$x_axis."', y_axis = '".$y_axis."' where id = '".$id."'";
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
        case "updateLocation":
            $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
            $x_axis = isset($_REQUEST["x_axis"]) ? $_REQUEST["x_axis"] : '';
            $y_axis = isset($_REQUEST["y_axis"]) ? $_REQUEST["y_axis"] : '';
            $sql="update device set x_axis = '".$x_axis."', y_axis = '".$y_axis."' where id = '".$id."'";
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
        $sql=" SELECT a.id,maxid,device,subnetid,deviceid,channel,channel_spare,b.id as addressid,mac,ip,port,lat,lng,a.floor,a.room,devicetype,case when on_off = 'on' then now() - run_date else null end as run_time,on_off,mode,grade,breed,country,a.address,a.status,starttime,endtime,a.floor,a.room,room_name,x_axis,y_axis FROM device as a left join address as b on a.address = b.address left join room as r on a.address = r.address and a.floor = r.floor and a.room = r.room left join (select max(id) as maxid from device) as c on 1=1  order by a.address,a.floor,a.room + 0,devicetype,breed,id ";
        $result = mysqli_query($con,$sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/","/",json_encode($results)); 
        echo $json_results;
    break;  
    case "getRecordCount":
    $sql = "SELECT count(device) as count FROM record where devicetype = 'ac' or devicetype = 'light'";
    
    $result = mysqli_query($con,$sql);
    
    $count = array();
   
    while ($row = mysqli_fetch_assoc($result)) {
        $count[] = $row;
    }
    // $data = [$ac_breed,$record];
    $json_results = str_replace("\/","/",json_encode($count)); 
    echo $json_results;
    break;
    case "getRunTime":
    // $record = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light' and id > 0 ";
    $device_id = $device_id = isset($_REQUEST["device_id"]) ? $_REQUEST["device_id"] : '';
    $sql = "SELECT * FROM runtime where device_id = '".$device_id."'";
    $result = mysqli_query($con,$sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    // $data = [$ac_breed,$record];
    $json_results = str_replace("\/","/",json_encode($data)); 
    echo $json_results;
    break;
    case "setRunTime":
    // $record = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light' and id > 0 ";
    $device_id = $device_id = isset($_REQUEST["device_id"]) ? $_REQUEST["device_id"] : '';
    $sun_up = $sun_up = isset($_REQUEST["sun_up"]) ? $_REQUEST["sun_up"] : '';
    $sun_down = $sun_down = isset($_REQUEST["sun_down"]) ? $_REQUEST["sun_down"] : '';
    $sun_status = $sun_status = isset($_REQUEST["sun_status"]) ? $_REQUEST["sun_status"] : '';
    $mon_up = $mon_up = isset($_REQUEST["mon_up"]) ? $_REQUEST["mon_up"] : '';
    $mon_down = $mon_down = isset($_REQUEST["mon_down"]) ? $_REQUEST["mon_down"] : '';
    $mon_status = $mon_status = isset($_REQUEST["mon_status"]) ? $_REQUEST["mon_status"] : '';
    $tues_up = $tues_up = isset($_REQUEST["tues_up"]) ? $_REQUEST["tues_up"] : '';
    $tues_down = $tues_down = isset($_REQUEST["tues_down"]) ? $_REQUEST["tues_down"] : '';
    $tues_status = $tues_status = isset($_REQUEST["tues_status"]) ? $_REQUEST["tues_status"] : '';
    $wed_up = $wed_up = isset($_REQUEST["wed_up"]) ? $_REQUEST["wed_up"] : '';
    $wed_down = $wed_down = isset($_REQUEST["wed_down"]) ? $_REQUEST["wed_down"] : '';
    $wed_status = $wed_status = isset($_REQUEST["wed_status"]) ? $_REQUEST["wed_status"] : '';
    $thur_up = $thur_up = isset($_REQUEST["thur_up"]) ? $_REQUEST["thur_up"] : '';
    $thur_down = $thur_down = isset($_REQUEST["thur_down"]) ? $_REQUEST["thur_down"] : '';
    $thur_status = $thur_status = isset($_REQUEST["thur_status"]) ? $_REQUEST["thur_status"] : '';
    $fri_up = $fri_up = isset($_REQUEST["fri_up"]) ? $_REQUEST["fri_up"] : '';
    $fri_down = $fri_down = isset($_REQUEST["fri_down"]) ? $_REQUEST["fri_down"] : '';
    $fri_status = $fri_status = isset($_REQUEST["fri_status"]) ? $_REQUEST["fri_status"] : '';
    $sat_up = $sat_up = isset($_REQUEST["sat_up"]) ? $_REQUEST["sat_up"] : '';
    $sat_down = $sat_down = isset($_REQUEST["sat_down"]) ? $_REQUEST["sat_down"] : '';
    $sat_status = $sat_status = isset($_REQUEST["sat_status"]) ? $_REQUEST["sat_status"] : '';
    if($_REQUEST["type"] == 'insert'){
        $sql = "insert into runtime (device_id,sun_up,sun_down,sun_status,mon_up,mon_down,mon_status,tues_up,tues_down,tues_status,wed_up,wed_down,wed_status,thur_up,thur_down,thur_status,fri_up,fri_down,fri_status,sat_up,sat_down,sat_status) values ('".$device_id."','".$sun_up."','".$sun_down."','".$sun_status."','".$mon_up."','".$mon_down."','".$mon_status."','".$tues_up."','".$tues_down."','".$tues_status."','".$wed_up."','".$wed_down."','".$wed_status."','".$thur_up."','".$thur_down."','".$thur_status."','".$fri_up."','".$fri_down."','".$fri_status."','".$sat_up."','".$sat_down."','".$sat_status."')";
    }else{
        $sql = "update runtime set device_id = '".$device_id."',sun_up = '".$sun_up."',sun_down = '".$sun_down."',sun_status = '".$sun_status."',mon_up = '".$mon_up."',mon_down = '".$mon_down."',mon_status = '".$mon_status."',tues_up = '".$tues_up."',tues_down = '".$tues_down."',tues_status = '".$tues_status."',wed_up = '".$wed_up."',wed_down = '".$wed_down."',wed_status = '".$wed_status."',thur_up = '".$thur_up."',thur_down = '".$thur_down."',thur_status = '".$thur_status."',fri_up = '".$fri_up."',fri_down = '".$fri_down."',fri_status = '".$fri_status."',sat_up = '".$sat_up."',sat_down = '".$sat_down."',sat_status = '".$sat_status."'";
    }
    if (!mysqli_query($con,$sql))
    {
        $message[0] = false;
        $message[1] = 'Failed';
        echo(json_encode($message)); 
    }
    else{
        $message = [];
        $message[0] = true;
        $message[1] = "Successfully";
        echo(json_encode($message)); 
    }
    break;
    case "getrecord":
        // $record = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light' and id > 0 ";
        $start = $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : '';
        $end = $end = isset($_REQUEST["end"]) ? $_REQUEST["end"] : '';
        $record = "SELECT device,devicetype,on_off,mode,grade,breed,record_date,country,address,floor,room FROM record where on_off = 'on' and (devicetype = 'ac' or devicetype = 'light') order by record_date  limit ".$start." , ".$end."";
        $result = mysqli_query($con,$record);
        $record = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $record[] = $row;
        }
        // $data = [$ac_breed,$record];
        $json_results = str_replace("\/","/",json_encode($record)); 
        echo $json_results;
    break;

    // case "getDetailAddress":
    //     $address = "SELECT a.id,a.room,floor,a.address,country FROM record as a left join address as b on a.address = b.address ";
    //     $result = mysqli_query($con,$address);
    //     $address = array();
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $address[] = $row;
    //     }
    //     // $data = [$ac_breed,$record];
    //     $json_results = str_replace("\/","/",json_encode($address)); 
    //     echo $json_results;
    // break;
};
mysqli_close($con);


?>