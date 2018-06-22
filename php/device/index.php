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
function toHex($num)
{
    $num = dechex($num);
    if (strlen($num) < 2) {
        $num = '0' . $num;
    }
    return $num;
}

$action = $_REQUEST["action"];

switch ($action) {
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
        $operation_1 = isset($_REQUEST["operation_1"]) ? $_REQUEST["operation_1"] : '';
        $operation_1 = toHex($operation_1);
        $devicetype = isset($_REQUEST["devicetype"]) ? $_REQUEST["devicetype"] : '';
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $x_axis = isset($_REQUEST["x_axis"]) ? $_REQUEST["x_axis"] : '';
        $y_axis = isset($_REQUEST["y_axis"]) ? $_REQUEST["y_axis"] : '';
        $comment = isset($_REQUEST["comment"]) ? $_REQUEST["comment"] : '';
        $sql = "insert into device (device,subnetid,deviceid,channel,channel_spare,operation_1,address,floor,room,x_axis,y_axis,devicetype,breed,comment,status) values ('" . $device . "','" . $subnetid . "','" . $deviceid . "','" . $channel . "','" . $channel_spare . "','" . $operation_1 . "','" . $address . "','" . $floor . "','" . $room . "','" . $x_axis . "','" . $y_axis . "','" . $devicetype . "','" . $breed . "','" . $comment . "','enabled')";
        if (!mysqli_query($con, $sql)) {
            $message = [];
            $message[0] = false;
            $message[1] = "insert failed: " . mysqli_error($con);
            echo (json_encode($message));
        } else {
            $getMaxID = "select max(id) as id from device";
            $getMaxID = mysqli_query($con, $getMaxID);


            while ($row = mysqli_fetch_assoc($getMaxID)) {
                $maxID = $row["id"];
            }
            $message = [];
            $message[0] = true;
            $message[1] = "insert successfully";
            $message[2] = $maxID;
            echo (json_encode($message));
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
        $operation_1 = isset($_REQUEST["operation_1"]) ? $_REQUEST["operation_1"] : '';
        $operation_1 = toHex($operation_1);
        $devicetype = isset($_REQUEST["devicetype"]) ? $_REQUEST["devicetype"] : '';
        $breed = isset($_REQUEST["breed"]) ? $_REQUEST["breed"] : '';
        $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
        $floor = isset($_REQUEST["floor"]) ? $_REQUEST["floor"] : '';
        $room = isset($_REQUEST["room"]) ? $_REQUEST["room"] : '';
        $x_axis = isset($_REQUEST["x_axis"]) ? $_REQUEST["x_axis"] : '';
        $y_axis = isset($_REQUEST["y_axis"]) ? $_REQUEST["y_axis"] : '';
        $comment = isset($_REQUEST["comment"]) ? $_REQUEST["comment"] : '';
        if($devicetype == 'curtain'){
            $sql = "update device set device = '" . $device . "', subnetid = '" . $subnetid . "', deviceid = '" . $deviceid . "', channel = '" . $channel . "',channel_spare = '" . $channel_spare . "',operation_1 = '" . $operation_1 . "', devicetype = '" . $devicetype . "', breed = '" . $breed . "', address = '" . $address . "', floor = '" . $floor . "', room = '" . $room . "', x_axis = '" . $x_axis . "', y_axis = '" . $y_axis . "', comment = '" . $comment . "' where id = '" . $id . "'";
        }
        else{
            $sql = "update device set device = '" . $device . "', subnetid = '" . $subnetid . "', deviceid = '" . $deviceid . "', channel = '" . $channel . "',channel_spare = '" . $channel_spare . "', devicetype = '" . $devicetype . "', breed = '" . $breed . "', address = '" . $address . "', floor = '" . $floor . "', room = '" . $room . "', x_axis = '" . $x_axis . "', y_axis = '" . $y_axis . "', comment = '" . $comment . "' where id = '" . $id . "'";
        }
        if (!mysqli_query($con, $sql)) {
            $message = [];
            $message[0] = false;
            $message[1] = "update failed: " . mysqli_error($con);
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo (json_encode($message));
        }
        break;
    case "updateLocation":
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
        $x_axis = isset($_REQUEST["x_axis"]) ? $_REQUEST["x_axis"] : '';
        $y_axis = isset($_REQUEST["y_axis"]) ? $_REQUEST["y_axis"] : '';
        $sql = "update device set x_axis = '" . $x_axis . "', y_axis = '" . $y_axis . "' where id = '" . $id . "'";
        if (!mysqli_query($con, $sql)) {
            $message = [];
            $message[0] = false;
            $message[1] = "update failed: " . mysqli_error($con);
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo (json_encode($message));
        }
        break;
    case "delete":

        $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
        $re_str = "";
        for ($i = 0; $i < count($selections); $i++) {
            $selection = json_decode($selections[$i]);
            $sql = " delete from device where id = '" . $selection->id . "'";
            if (!mysqli_query($con, $sql)) {
                $re = false;
                $re_str = $re_str . " Delete failed: " . $selection->device;
            } else {
                $re = true;
            }

        }
        if (!$re) {
            $message = [];
            $message[0] = false;
            $message[1] = $re_str;
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "Delete successfully";
            echo (json_encode($message));
        }
        break;
    case "setStatus":
        $selections = isset($_REQUEST["selections"]) ? $_REQUEST["selections"] : '';
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : '';
        $re_str = "";
        for ($i = 0; $i < count($selections); $i++) {
            $selection = json_decode($selections[$i]);
            $sql = " update device set status = '" . $status . "' where id = '" . $selection->id . "'";
            if (!mysqli_query($con, $sql)) {
                $re = false;
                $re_str = $re_str . "Failed: " . $selection->device;
            } else {
                $re = true;
            }

        }
        if (!$re) {
            $message = [];
            $message[0] = false;
            $message[1] = $re_str;
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "Successfully";
            echo (json_encode($message));
        }
        break;

    case "setColor":
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
        $color = isset($_REQUEST["color"]) ? $_REQUEST["color"] : '';
        $sql = "update device set mode = '" . $color . "' where id = '" . $id . "'";
        if (!mysqli_query($con, $sql)) {
            $message = [];
            $message[0] = false;
            $message[1] = "update failed: " . mysqli_error($con);
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo (json_encode($message));
        }
        break;
    case "getIrOperation":
        $device = isset($_REQUEST["device"]) ? $_REQUEST["device"] : '';
        $sql = "select id,device,ir_key,ir_name,ir_value from ir_operation where device = '" . $device . "'";
        $result = mysqli_query($con, $sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
        $json_results = str_replace("\/", "/", json_encode($results));
        echo $json_results;
        break;
    case "insertIrOperation":
        $device = isset($_REQUEST["device"]) ? $_REQUEST["device"] : '';
        $ir_key = isset($_REQUEST["ir_key"]) ? $_REQUEST["ir_key"] : '';
        $ir_name = isset($_REQUEST["ir_name"]) ? $_REQUEST["ir_name"] : '';
        $ir_value = isset($_REQUEST["ir_value"]) ? $_REQUEST["ir_value"] : '';
        $sql = "insert into  ir_operation (device,ir_key,ir_name,ir_value) values ('" . $device . "','" . $ir_key . "','" . $ir_name . "','" . $ir_value . "')";
        if (!mysqli_query($con, $sql)) {
            $message = [];
            $message[0] = false;
            $message[1] = "insert failed " . mysqli_error($con);
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo (json_encode($message));
        }
        break;
    case "updateIrOperation":
        $device = isset($_REQUEST["device"]) ? $_REQUEST["device"] : '';
        $ir_key = isset($_REQUEST["ir_key"]) ? $_REQUEST["ir_key"] : '';
        $ir_name = isset($_REQUEST["ir_name"]) ? $_REQUEST["ir_name"] : '';
        $ir_value = isset($_REQUEST["ir_value"]) ? $_REQUEST["ir_value"] : '';
        $sql = "update  ir_operation set ir_name = '" . $ir_name . "',ir_value = '" . $ir_value . "' where  device = '" . $device . "' and ir_key = '" . $ir_key . "'";
        if (!mysqli_query($con, $sql)) {
            $message = [];
            $message[0] = false;
            $message[1] = "insert failed " . mysqli_error($con);
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo (json_encode($message));
        }
        break;
    case "setIr":
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
        $key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : '';
        $value = isset($_REQUEST["value"]) ? $_REQUEST["value"] : '';
        $sql = "update device set " . $key . " = '" . $value . "' where id = '" . $id . "'";
        if (!mysqli_query($con, $sql)) {
            $message = [];
            $message[0] = false;
            $message[1] = "update failed: " . mysqli_error($con);
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "update successfully";
            echo (json_encode($message));
        }
        break;

    case "setTime":
        $selection = isset($_REQUEST["selection"]) ? $_REQUEST["selection"] : '';
        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : '';
        $re_str = "";
        $selection = json_decode($selection);
        if ($type == 'start') {
            $sql = " update device set starttime = '" . $selection->starttime . "' where id = '" . $selection->id . "'";
        } else {
            $sql = " update device set endtime = '" . $selection->endtime . "' where id = '" . $selection->id . "'";
        }

        if (!mysqli_query($con, $sql)) {
            $re = false;
            $re_str = $re_str . "Failed: " . $selection->device;
        } else {
            $re = true;
        }
        if (!$re) {
            $message = [];
            $message[0] = false;
            $message[1] = $re_str;
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "Successfully";
            echo (json_encode($message));
        }
        break;
    case "search":
        $sql = " SELECT a.id,maxid,device,subnetid,deviceid,channel,channel_spare,b.id as addressid,mac,ip,port,b.lat,b.lng,a.floor,a.room,devicetype,case when on_off = 'on' then now() - run_date else null end as run_time,on_off,mode,grade,breed,country,a.address,a.status,starttime,endtime,a.floor,a.room,room_name,x_axis,y_axis,operation_1,operation_2,operation_3,operation_4,operation_5,operation_6,operation_7,operation_8,operation_9,operation_10,operation_11,operation_12,operation_13,operation_14,operation_15,operation_16,operation_17,operation_18,operation_19,operation_20,operation_21,a.comment,b.operation as udp_type FROM device as a left join address as b on a.address = b.address left join room as r on a.address = r.address and a.floor = r.floor and a.room = r.room left join (select max(id) as maxid from device) as c on 1=1  order by a.address,a.floor,a.room + 0,devicetype,breed,id ";
        $result = mysqli_query($con, $sql);
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['on_off'] = $row['on_off'] == 'on' ? true : false;
            $results[] = $row;
        }
        $json_results = str_replace("\/", "/", json_encode($results));
        echo $json_results;
        break;
    case "getRunTime":
    // $record = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light' and id > 0 ";
        $device_id = $device_id = isset($_REQUEST["device_id"]) ? $_REQUEST["device_id"] : '';
        $sql = "SELECT * FROM runtime where device_id = '" . $device_id . "'";
        $result = mysqli_query($con, $sql);
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    // $data = [$ac_breed,$record];
        $json_results = str_replace("\/", "/", json_encode($data));
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
        if ($_REQUEST["type"] == 'insert') {
            $sql = "insert into runtime (device_id,sun_up,sun_down,sun_status,mon_up,mon_down,mon_status,tues_up,tues_down,tues_status,wed_up,wed_down,wed_status,thur_up,thur_down,thur_status,fri_up,fri_down,fri_status,sat_up,sat_down,sat_status) values ('" . $device_id . "','" . $sun_up . "','" . $sun_down . "','" . $sun_status . "','" . $mon_up . "','" . $mon_down . "','" . $mon_status . "','" . $tues_up . "','" . $tues_down . "','" . $tues_status . "','" . $wed_up . "','" . $wed_down . "','" . $wed_status . "','" . $thur_up . "','" . $thur_down . "','" . $thur_status . "','" . $fri_up . "','" . $fri_down . "','" . $fri_status . "','" . $sat_up . "','" . $sat_down . "','" . $sat_status . "')";
        } else {
            $sql = "update runtime set device_id = '" . $device_id . "',sun_up = '" . $sun_up . "',sun_down = '" . $sun_down . "',sun_status = '" . $sun_status . "',mon_up = '" . $mon_up . "',mon_down = '" . $mon_down . "',mon_status = '" . $mon_status . "',tues_up = '" . $tues_up . "',tues_down = '" . $tues_down . "',tues_status = '" . $tues_status . "',wed_up = '" . $wed_up . "',wed_down = '" . $wed_down . "',wed_status = '" . $wed_status . "',thur_up = '" . $thur_up . "',thur_down = '" . $thur_down . "',thur_status = '" . $thur_status . "',fri_up = '" . $fri_up . "',fri_down = '" . $fri_down . "',fri_status = '" . $fri_status . "',sat_up = '" . $sat_up . "',sat_down = '" . $sat_down . "',sat_status = '" . $sat_status . "'";
        }
        if (!mysqli_query($con, $sql)) {
            $message[0] = false;
            $message[1] = 'Failed';
            echo (json_encode($message));
        } else {
            $message = [];
            $message[0] = true;
            $message[1] = "Successfully";
            echo (json_encode($message));
        }
        break;
    // case "getrecord":
    //     // $record = "SELECT * FROM record where devicetype = 'ac' or devicetype = 'light' and id > 0 ";
    //     $start = $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : '';
    //     $end = $end = isset($_REQUEST["end"]) ? $_REQUEST["end"] : '';
    //     $record = "SELECT device,devicetype,on_off,mode,grade,breed,record_date,country,address,floor,room FROM record where on_off = 'on' and (devicetype = 'ac' or devicetype = 'light') order by record_date  limit ".$start." , ".$end."";
    //     $result = mysqli_query($con,$record);
    //     $record = array();
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $record[] = $row;
    //     }
    //     // $data = [$ac_breed,$record];
    //     $json_results = str_replace("\/","/",json_encode($record)); 
    //     echo $json_results;
    // break;
    case "getRecordCount":
        $beginDate = isset($_REQUEST["beginDate"]) ? $_REQUEST["beginDate"] : '';
        $endDate = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : '';
        $sql = "SELECT count(device) as count FROM record where record_date >= '" . $beginDate . "' and record_date<= '" . $endDate . "' and (devicetype = 'ac' or devicetype = 'light')";

        $result = mysqli_query($con, $sql);

        $count = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $count[] = $row;
        }
        // $data = [$ac_breed,$record];
        $json_results = str_replace("\/", "/", json_encode($count));
        echo $json_results;
        break;
    case "getrecord":
        $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : '';
        $end = isset($_REQUEST["end"]) ? $_REQUEST["end"] : '';
        $beginDate = isset($_REQUEST["beginDate"]) ? $_REQUEST["beginDate"] : '';
        $endDate = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : '';
        $record = "SELECT device,devicetype,on_off,mode,grade,breed,record_date,country,address,floor,room FROM record where record_date >= '" . $beginDate . "' and record_date<= '" . $endDate . "' and on_off = 'on' and (devicetype = 'ac' or devicetype = 'light') order by record_date  limit " . $start . " , " . $end . "";
        $result = mysqli_query($con, $record);
        $record = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $record[] = $row;
        }
        // $data = [$ac_breed,$record];
        $json_results = str_replace("\/", "/", json_encode($record));
        echo $json_results;
        break;
    case "updateDatabase":
        $version = '1.6.1';
        echo $version;
        $selectVersion = "select version from base";
        $result = mysqli_query($con, $selectVersion);
        $row = mysqli_fetch_assoc($result);
        if ($row && $row["version"] == $version) return;
        $updateVersion = "";
        if ($row) {
            $updateVersion = "update base set version = '" . $version . "'";
        } else {
            $updateVersion = "insert into base (version) values ('" . $version . "')";
        }
        $createMacro = "CREATE TABLE if not exists `macro` (`id` int(11) NOT NULL AUTO_INCREMENT,`macro` varchar(30) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC; ";
        $createMacroComment = " CREATE TABLE if not exists `macro_command` ( `id` int(11) NOT NULL AUTO_INCREMENT,  `macro` varchar(30) DEFAULT NULL, `device` varchar(50) DEFAULT NULL, `on_off` varchar(1) DEFAULT NULL, `mode` varchar(20) DEFAULT NULL, `grade` varchar(20) DEFAULT NULL, `status_1` varchar(20) DEFAULT NULL, `status_2` varchar(20) DEFAULT NULL, `status_3` varchar(20) DEFAULT NULL, `status_4` varchar(20) DEFAULT NULL, `status_5` varchar(20) DEFAULT NULL, `time` varchar(10) DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC; ";
        $createIrOperation = "CREATE TABLE if not exists `ir_operation` ( `id` int(11) NOT NULL AUTO_INCREMENT, `device` varchar(50) DEFAULT NULL, `ir_key` varchar(20) DEFAULT NULL, `ir_name` varchar(20) DEFAULT NULL, `ir_value` varchar(20) DEFAULT NULL,  PRIMARY KEY (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8; ";
    // $addAddressOperation = "alter table address add operation varchar(1)";
        $addAddressOperation = "alter table address add operation varchar(1) DEFAULT '0'; ";
        $updateAddressOperation = "update address set operation = '0' where operation = '' or operation is null";
        $addAddressImage = "alter table address add image varchar(255); ";
        $addAddressComment = "alter table address add comment varchar(255); ";
        $addFloorImage = "alter table floor add image varchar(255); ";
        $addFloorComment = "alter table floor add comment varchar(255); ";
        $addRoomImage = "alter table room add image varchar(255); ";
        $addRoomComment = "alter table room add comment varchar(255); ";
        $addRoomLat = "alter table room add lat varchar(10); ";
        $addRoomLng = "alter table room add lng varchar(10); ";
        $addRoomWidth = "alter table room add width varchar(10); ";
        $addRoomHeight = "alter table room add height varchar(10); ";
        $addDeviceComment = "alter table device add comment varchar(255); ";
        $addMoodOnOff = "alter table mood add on_off varchar(1); ";
        $addMoodMode = "alter table mood add mode varchar(20); ";
        $addMoodGrade = "alter table mood add grade varchar(20); ";
        mysqli_query($con, $updateVersion);
        mysqli_query($con, $createMacro);
        mysqli_query($con, $createMacroComment);
        mysqli_query($con, $createIrOperation);
        mysqli_query($con, $addAddressOperation);
        mysqli_query($con, $updateAddressOperation);
        mysqli_query($con, $addAddressImage);
        mysqli_query($con, $addAddressComment);
        mysqli_query($con, $addFloorImage);
        mysqli_query($con, $addFloorComment);
        mysqli_query($con, $addRoomImage);
        mysqli_query($con, $addRoomComment);
        mysqli_query($con, $addRoomLat);
        mysqli_query($con, $addRoomLng);
        mysqli_query($con, $addRoomWidth);
        mysqli_query($con, $addRoomHeight);
        mysqli_query($con, $addDeviceComment);
        mysqli_query($con, $addMoodOnOff);
        mysqli_query($con, $addMoodMode);
        mysqli_query($con, $addMoodGrade);
    //   if (!mysqli_query($con,$sql))
    //   {
    //       $message[0] = false;
    //       $message[1] = 'Failed';
    //       $message[2] = mysqli_error($con);
    //       echo(json_encode($message)); 
    //   }
    //   else{
    //       $message = [];
    //       $message[0] = true;
    //       $message[1] = "Successfully";
    //       echo(json_encode($message)); 
    //   }
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