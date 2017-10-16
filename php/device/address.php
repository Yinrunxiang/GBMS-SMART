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
        $floor_num = isset($_REQUEST["floor_num"]) ? $_REQUEST["floor_num"] : '';
        $sql="insert into address (country,address,ip,port,mac,lat,lng,floor_num,status) values ('".$country."','".$address."','".$ip."','".$port."','".$mac."','".$lat."','".$lng."','".$floor_num."','enabled')";
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
        $ip = isset($_REQUEST["ip"]) ? $_REQUEST["ip"] : '';
        $port = isset($_REQUEST["port"]) ? $_REQUEST["port"] : '';
        $mac = isset($_REQUEST["mac"]) ? $_REQUEST["mac"] : '';
        $lat = isset($_REQUEST["lat"]) ? $_REQUEST["lat"] : '';
        $lng = isset($_REQUEST["lng"]) ? $_REQUEST["lng"] : '';
        $floor_num = isset($_REQUEST["floor_num"]) ? $_REQUEST["floor_num"] : '';
        $sql="update address set country = '".$country."',address = '".$address."',ip = '".$ip."',port = '".$port."',mac = '".$mac."',lat = '".$lat."',lng = '".$lng."',floor_num = '".$floor_num."' where id = '".$id."'";
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