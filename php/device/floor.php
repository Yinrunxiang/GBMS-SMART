<?php
include '../database.php';

$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room_num = isset($_REQUEST["room_num"]) ? $_REQUEST["room_num"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
        $comment = isset($_REQUEST["comment"]) ? $_REQUEST["comment"] : '';
        $sql="insert into floor (floor,room_num,image,address,status) values ('".$floor."','".$room_num."','".$image."','".$address."','enabled')";
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "insert failed: " . mysqli_error($con);
            echo(json_encode($message)); 
        }else{
            $room_num = intval($room_num);
            for($i = 1;$i<=$room_num;$i++){
                $insertRoom="insert into room (room,room_name,floor,address,image,comment,status) values ('".$i."','".$i."','".$floor."','".$address."','".$image."','".$comment."','enabled')";
                mysqli_query($con,$insertRoom);
            }
            $message = [];
            $message[0] = true;
            $message[1] = "insert successfully";
            echo(json_encode($message)); 
        }
        break;
    case "update":
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room_num = isset($_REQUEST["room_num"]) ? $_REQUEST["room_num"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $image = isset($_REQUEST["image"]) ? $_REQUEST["image"] : '';
        $comment = isset($_REQUEST["comment"]) ? $_REQUEST["comment"] : '';
        $sql="update floor set room_num = '".$room_num."',image = '".$image."',comment = '".$comment."' where floor = '".$floor."' and address = '".$address."'";
        if (!mysqli_query($con,$sql))
        {
            $message = [];
            $message[0] = false;
            $message[1] = "update failed: " . mysqli_error($con);
            echo(json_encode($message)); 
        }else{
            $getCount = "select count(room) as count from room where address = '".$address."' and floor = '".$floor."'";
            $count = mysqli_query($con,$getCount);
            $count = mysqli_fetch_assoc($count);
            $count = intval($count["count"]);
            $room_num = intval($room_num);
            if($room_num > $count){
                for($i = $count+1;$i<=$room_num;$i++){
                    $insertRoom="insert into room (room,room_name,floor,address,status) values ('".$i."','".$i."','".$floor."','".$address."','enabled')";
                    mysqli_query($con,$insertRoom);
                }
            }else if ($room_num < $count){
                $deleteRoom="delete from room where address = '".$address."' and floor = '".$floor."' and room > ".$room_num;
                $deleteDevice="delete from device where address = '".$address."' and floor = '".$floor."' and room > ".$room_num;
                mysqli_query($con,$deleteRoom);
                mysqli_query($con,$deleteDevice);
            }
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
            $sql = " delete from floor where floor = '".$selection->floor."'";
            if (!mysqli_query($con,$sql))
            {
                $re = false;
                $re_str = $re_str." Delete failed: " .$selection->floor;
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
        $sql = " update floor set status = '".$status."' where floor = '".$selection->floor."'";
        if (!mysqli_query($con,$sql))
        {
            $re = false;
            $re_str = $re_str."Failed: " .$selection->floor;
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
        $sql="SELECT * FROM floor order by address,floor+0 ";
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