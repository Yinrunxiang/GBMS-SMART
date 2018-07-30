<?php
// +----------------------------------------------------------------------
// | Description: 更新数据库
// +----------------------------------------------------------------------
// | Author: Jensen
// +----------------------------------------------------------------------

namespace app\admin\controller;

class DataBase extends ApiCommon
{

    public function updateDataBase()
    {
        $userInfo = $GLOBALS['userInfo'];
        $database_name = $userInfo['database_name'];
        $con = mysqli_connect('localhost', 'root', 'root');
        if (!$con) {
            die('Could not connect: ' . mysqli_error($con));
        }
        mysqli_select_db($con, $database_name);
        mysqli_set_charset($con, "utf8");
        $version = '1.7.8';
        $selectVersion = "select version  from base";
        $result = mysqli_query($con, $selectVersion);
        $row = mysqli_fetch_assoc($result);
        if ($row && $row["version"] == $version) {
            return resultArray(['data' => $version]);
        }
        $updateVersion = "";
        if ($row) {
            $updateVersion = "update base set version = '" . $version . "'";
        } else {
            $updateVersion = "insert into base (version) values ('" . $version . "')";
        }
        $createMacro = "CREATE TABLE if not exists `macro` (`id` int(11) NOT NULL AUTO_INCREMENT,`macro` varchar(30) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC; ";
        $createMacroComment = " CREATE TABLE if not exists `macro_command` ( `id` int(11) NOT NULL AUTO_INCREMENT,  `macro` varchar(30) DEFAULT NULL, `device` varchar(50) DEFAULT NULL, `on_off` varchar(1) DEFAULT NULL, `mode` varchar(20) DEFAULT NULL, `grade` varchar(20) DEFAULT NULL, `status_1` varchar(20) DEFAULT NULL, `status_2` varchar(20) DEFAULT NULL, `status_3` varchar(20) DEFAULT NULL, `status_4` varchar(20) DEFAULT NULL, `status_5` varchar(20) DEFAULT NULL, `time` varchar(10) DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC; ";
        $createIrOperation = "CREATE TABLE if not exists `ir_operation` ( `id` int(11) NOT NULL AUTO_INCREMENT, `device` varchar(50) DEFAULT NULL, `ir_key` varchar(20) DEFAULT NULL, `ir_name` varchar(20) DEFAULT NULL, `ir_value` varchar(20) DEFAULT NULL,  PRIMARY KEY (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8; ";
        $createBase = "CREATE TABLE if not exists `base` ( `id` int(11) NOT NULL AUTO_INCREMENT, `version` varchar(20) DEFAULT NULL,  PRIMARY KEY (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8; ";
        $createAlexa = "CREATE TABLE if not exists `base` ( `id` int(11) NOT NULL AUTO_INCREMENT, `alexa_token` varchar(2000) DEFAULT NULL,   PRIMARY KEY (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8; ";
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
        $addMarcoComment = "alter table marco add comment varchar(200); ";
        $addScheduleComment = "alter table schedule add comment varchar(200); ";
        $addRoomAlexa = "alter table room add alexa varchar(200); ";
        $addDeviceAlexa = "alter table device add alexa varchar(200); ";
        //v1.7.3  2018-07-03
        mysqli_query($con, $updateVersion);
        mysqli_query($con, $createMacro);
        mysqli_query($con, $createMacroComment);
        mysqli_query($con, $createIrOperation);
        mysqli_query($con, $createBase);
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
        mysqli_query($con, $addMarcoComment);
        mysqli_query($con, $addScheduleComment);
        mysqli_query($con, $addRoomAlexa);
        mysqli_query($con, $addDeviceAlexa);
        return resultArray(['data' => $version]);
    }
}
 