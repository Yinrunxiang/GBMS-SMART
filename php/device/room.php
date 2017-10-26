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
        $sql="update room set room_name = '".$room_name."',image = '".$image."',floor = '".$floor."',address = '".$address."' where room = '".$room."'";
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
    case "setStatus":
    $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : '';
    $re_str = "";
    for ($i = 0; $i  < count($selections); $i++) {
        $selection = json_decode($selections[$i]);
        $sql = " update room set status = '".$status."' where room = '".$selection->room."'";
        if (!mysqli_query($con,$sql))
        {
            $re = false;
            $re_str = $re_str."Failed: " .$selection->room;
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
        $sql="SELECT * FROM room  order by room+0";
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