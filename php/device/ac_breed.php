<?php
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:POST');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
$con = mysqli_connect('localhost','root','root');
if (!$con)
{
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con,"udp");
mysqli_set_charset($con, "utf8");

// $con = mysqli_connect('localhost','huangst_admin','123123');
// if (!$con)
// {
//     die('Could not connect: ' . mysqli_error($con));
// }
// mysqli_select_db($con,"g4ever_news");
// mysqli_set_charset($con, "utf8");

$action =  $_REQUEST["action"];

switch ($action)
{
    case "insert":
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $mode_auto = isset($_REQUEST["mode_auto"]) ? $_REQUEST["mode_auto"] : '';
        $fan = isset($_REQUEST["fan"]) ? $_REQUEST["fan"] : '';
        $cool = isset($_REQUEST["cool"]) ? $_REQUEST["cool"] : '';
        $heat = isset($_REQUEST["heat"]) ? $_REQUEST["heat"] : '';
        $wind_auto = isset($_REQUEST["wind_auto"]) ? $_REQUEST["wind_auto"] : '';
        $low = isset($_REQUEST["low"]) ? $_REQUEST["low"] : '';
        $medium = isset($_REQUEST["medium"]) ? $_REQUEST["medium"] : '';
        $high = isset($_REQUEST["high"]) ? $_REQUEST["high"] : '';
        $run_time = isset($_REQUEST["run_time"]) ? $_REQUEST["run_time"] : '';
        $sql="insert into ac_breed (breed,mode_auto,fan,cool,heat,wind_auto,low,medium,high,run_time,status) values ('".$breed."','".$mode_auto."','".$fan."','".$cool."','".$heat."','".$wind_auto."','".$low."','".$medium."','".$high."','".$run_time."','enabled')";
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
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $mode_auto = isset($_REQUEST["mode_auto"]) ? $_REQUEST["mode_auto"] : '';
        $fan = isset($_REQUEST["fan"]) ? $_REQUEST["fan"] : '';
        $cool = isset($_REQUEST["cool"]) ? $_REQUEST["cool"] : '';
        $heat = isset($_REQUEST["heat"]) ? $_REQUEST["heat"] : '';
        $wind_auto = isset($_REQUEST["wind_auto"]) ? $_REQUEST["wind_auto"] : '';
        $low = isset($_REQUEST["low"]) ? $_REQUEST["low"] : '';
        $medium = isset($_REQUEST["medium"]) ? $_REQUEST["medium"] : '';
        $high = isset($_REQUEST["high"]) ? $_REQUEST["high"] : '';
        $run_time = isset($_REQUEST["run_time"]) ? $_REQUEST["run_time"] : '';
        $sql=" update ac_breed set  mode_auto='".$mode_auto."',fan='".$fan."',cool='".$cool."',heat='".$heat."',wind_auto='".$wind_auto."',low='".$low."',medium='".$medium."',high='".$high."',run_time='".$run_time."' where breed = '".$breed."'";
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
            $sql = " delete from ac_breed where breed = '".$selection->breed."'";
            if (!mysqli_query($con,$sql))
            {
                $re = false;
                $re_str = $re_str." Delete failed: " .$selection->breed;
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
        $sql = " update ac_breed set status = '".$status."' where id = '".$selection->id."'";
        if (!mysqli_query($con,$sql))
        {
            $re = false;
            $re_str = $re_str."Failed: " .$selection->breed;
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
        $sql="SELECT * FROM ac_breed";
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