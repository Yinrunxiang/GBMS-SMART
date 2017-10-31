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
        $kw_usd = isset($_REQUEST["kw_usd"]) ? $_REQUEST["kw_usd"] : '';
        $floor_num = isset($_REQUEST["floor_num"]) ? $_REQUEST["floor_num"] : '';
        $sql="insert into address (country,address,ip,port,mac,lat,lng,floor_num,kw_usd,status) values ('".$country."','".$address."','".$ip."','".$port."','".$mac."','".$lat."','".$lng."','".$floor_num."','".$kw_usd."','enabled')";
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
        $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $oldAddress = isset($_REQUEST["oldAddress"]) ? $_REQUEST["oldAddress"] : '';
        $ip = isset($_REQUEST["ip"]) ? $_REQUEST["ip"] : '';
        $port = isset($_REQUEST["port"]) ? $_REQUEST["port"] : '';
        $mac = isset($_REQUEST["mac"]) ? $_REQUEST["mac"] : '';
        $lat = isset($_REQUEST["lat"]) ? $_REQUEST["lat"] : '';
        $lng = isset($_REQUEST["lng"]) ? $_REQUEST["lng"] : '';
        $kw_usd = isset($_REQUEST["kw_usd"]) ? $_REQUEST["kw_usd"] : '';
        $floor_num = isset($_REQUEST["floor_num"]) ? $_REQUEST["floor_num"] : '';
        $sql="update address set country = '".$country."',address = '".$address."',ip = '".$ip."',port = '".$port."',mac = '".$mac."',lat = '".$lat."',lng = '".$lng."',floor_num = '".$floor_num."',kw_usd = '".$kw_usd."' where address = '".$oldAddress."' ; ";
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
            $sql = " delete from address where id = '".$selection->id."'";
            if (!mysqli_query($con,$sql))
            {
                $re = false;
                $re_str = $re_str." Delete failed: " .$selection->address;
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