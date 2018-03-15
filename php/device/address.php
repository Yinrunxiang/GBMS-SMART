<?php
include '../database.php';

$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $ip = isset($_REQUEST["ip"]) ? $_REQUEST["ip"] : '';
        $port = isset($_REQUEST["port"]) ? $_REQUEST["port"] : '';
        $mac = isset($_REQUEST["mac"]) ? $_REQUEST["mac"] : '';
        $lat = isset($_REQUEST["lat"]) ? $_REQUEST["lat"] : '';
        $lng = isset($_REQUEST["lng"]) ? $_REQUEST["lng"] : '';
        $image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
        $comment = isset($_REQUEST["comment"]) ? $_REQUEST["comment"] : '';
        $kw_usd = isset($_REQUEST["kw_usd"]) ? $_REQUEST["kw_usd"] : '';
        $floor_num = isset($_REQUEST["floor_num"]) ? $_REQUEST["floor_num"] : '';
        $sql="insert into address (country,address,ip,port,mac,lat,lng,floor_num,kw_usd,image,comment,status) values ('".$country."','".$address."','".$ip."','".$port."','".$mac."','".$lat."','".$lng."','".$floor_num."','".$kw_usd."','".$image."','".$comment."','enabled')";
        
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "insert failed: " . mysqli_error($con);
            echo(json_encode($message)); 
        }else{
            $floor_num = intval($floor_num);
            for($i = 1;$i<=$floor_num;$i++){
                $insertFloor="insert into floor (floor,room_num,address,status) values ('".$i."',0,'".$address."','enabled')";
                mysqli_query($con,$insertFloor);
            }
            $message = [];
            $message[0] = true;
            $message[1] = "insert successfully";
            echo(json_encode($message)); 
        }
        break;
    case "update":
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $oldAddress = isset($_REQUEST["oldAddress"]) ? $_REQUEST["oldAddress"] : '';
        $ip = isset($_REQUEST["ip"]) ? $_REQUEST["ip"] : '';
        $port = isset($_REQUEST["port"]) ? $_REQUEST["port"] : '';
        $mac = isset($_REQUEST["mac"]) ? $_REQUEST["mac"] : '';
        $lat = isset($_REQUEST["lat"]) ? $_REQUEST["lat"] : '';
        $lng = isset($_REQUEST["lng"]) ? $_REQUEST["lng"] : '';
        $image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
        $comment = isset($_REQUEST["comment"]) ? $_REQUEST["comment"] : '';
        $kw_usd = isset($_REQUEST["kw_usd"]) ? $_REQUEST["kw_usd"] : '';
        $floor_num = isset($_REQUEST["floor_num"]) ? $_REQUEST["floor_num"] : '';
        $sql="update address set country = '".$country."',address = '".$address."',ip = '".$ip."',port = '".$port."',mac = '".$mac."',lat = '".$lat."',lng = '".$lng."',floor_num = '".$floor_num."',kw_usd = '".$kw_usd."',image = '".$image."',comment = '".$comment."' where address = '".$oldAddress."' ; ";
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "update failed: " . mysqli_error($con);
            echo(json_encode($message)); 
        }else{
            $updateDevice = " update device set address = '".$address."' where address = '".$oldAddress."'";
            mysqli_query($con,$updateDevice);
            $updateFloor = " update floor set address = '".$address."' where address = '".$oldAddress."'";
            mysqli_query($con,$updateFloor);
            $updateRoom = " update room set address = '".$address."' where address = '".$oldAddress."'";
            mysqli_query($con,$updateRoom);
            $updateRecord = " update record set address = '".$address."' where address = '".$oldAddress."'";
            mysqli_query($con,$updateRecord);
            $getFloor = "select count(floor) as count from floor where address = '".$address."'";
            $floor_count = mysqli_query($con,$getFloor);
            $floor_count = mysqli_fetch_assoc($floor_count);
            $floor_count = intval($floor_count["count"]);
            $floor_num = intval($floor_num);
            if($floor_num > $floor_count){
                for($i = $floor_count+1;$i<=$floor_num;$i++){
                    $insertFloor="insert into floor (floor,room_num,address,status) values ('".$i."',0,'".$address."','enabled')";
                    mysqli_query($con,$insertFloor);
                }
            }else if ($floor_num < $floor_count){
                $deleteFloor ="delete from floor where address = '".$address."' and floor > ".$floor_num;
                $deleteRoom ="delete from room where address = '".$address."' and floor > ".$floor_num;
                $deleteDevice ="delete from device where address = '".$address."' and floor > ".$floor_num;
                mysqli_query($con,$deleteFloor);
            }
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo(json_encode($message)); 
        }
       
        
        // $sql = $sql + $updateDevice;
       
    break;
    case "delete":
        
        $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
        $re_str = "";
        for ($i = 0; $i  < count($selections); $i++) {
            $selection = json_decode($selections[$i]);
            $deleteDevice = " delete from device where address = '".$selection->address."'";
            $deleteFloor =" delete from floor where address = '".$selection->address."'";
            $deleteRoom = " delete from room where address = '".$selection->address."'";
            $sql = " delete from address where address = '".$selection->address."'";
            mysqli_query($con,$deleteDevice);
            mysqli_query($con,$deleteFloor);
            mysqli_query($con,$deleteRoom);
            if (!mysqli_query($con,$sql))
            {
                $re = false;
                $re_str = $re_str." Delete failed: " .$selection->address;
            }else{
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
        $sql = " update address set status = '".$status."' where id = '".$selection->id."'";
        if (!mysqli_query($con,$sql))
        {
            $re = false;
            $re_str = $re_str."Failed: " .$selection->address;
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
    case "search":
        $sql="SELECT * FROM address ";
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