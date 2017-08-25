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
        $grade_list = ["grade_auto","high","medial","low"];
        $mode_list = ["mode_auto","fan","cool","heat"];
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $grade_auto = isset($_REQUEST["grade_auto"]) ? $_REQUEST["grade_auto"] : '';
        $high = isset($_REQUEST["high"]) ? $_REQUEST["high"] : '';
        $medial = isset($_REQUEST["medial"]) ? $_REQUEST["medial"] : '';
        $low = isset($_REQUEST["low"]) ? $_REQUEST["low"] : '';
        $mode_auto = isset($_REQUEST["mode_auto"]) ? $_REQUEST["mode_auto"] : '';
        $fan = isset($_REQUEST["fan"]) ? $_REQUEST["fan"] : '';
        $cool = isset($_REQUEST["cool"]) ? $_REQUEST["cool"] : '';
        $heat = isset($_REQUEST["heat"]) ? $_REQUEST["heat"] : '';
        for($m = 0;$m < count($mode_list); $m++){
            for($g = 0;$g < count($mode_list); $g++){
                $mode = $mode_list[$m];
                $grade = $grade_list[$g];
                $sql="insert into ac_breed (breed,mode,grade,status) values ('".$breed."','".$mode."','".$grade."','enabled')";
                if (!mysqli_query($con,$sql)){
                    $result = false;
                }
                else{
                    $result = true;
                }
            }
        }
        if (!$result)
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
    case "delete":
        
        $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
        $re_str = "";
        for ($i = 0; $i  < count($selections); $i++) {
            $selection = json_decode($selections[$i]);
            $sql = " delete from ac_breed where breed = '".$selection->breed."'";
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
        $sql = " update ac_breed set status = '".$status."' where breed = '".$selection->breed."'";
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