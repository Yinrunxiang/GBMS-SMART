# Host: localhost  (Version: 5.5.53)
# Date: 2018-07-10 16:00:58
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "ac_breed"
#

CREATE TABLE `ac_breed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `breed` varchar(10) DEFAULT NULL,
  `mode_auto` decimal(8,0) DEFAULT NULL,
  `fan` decimal(8,0) DEFAULT NULL,
  `cool` decimal(8,0) DEFAULT NULL,
  `heat` decimal(8,0) DEFAULT NULL,
  `wind_auto` decimal(8,0) DEFAULT NULL,
  `low` decimal(8,0) DEFAULT NULL,
  `medium` decimal(8,0) DEFAULT NULL,
  `high` decimal(8,0) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `run_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "ac_breed"
#


#
# Structure for table "address"
#

CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `port` varchar(10) DEFAULT NULL,
  `mac` varchar(25) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `lat` decimal(15,10) DEFAULT NULL,
  `lng` decimal(15,10) DEFAULT NULL,
  `floor_num` int(3) DEFAULT NULL,
  `kw_usd` varchar(20) DEFAULT NULL,
  `operation` varchar(1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "address"
#

INSERT INTO `address` VALUES (1,'Armenia','Test','','','','enabled',38.9136110000,-77.0132220000,10,'0.1','0','','');

#
# Structure for table "alexa"
#

CREATE TABLE `alexa` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "alexa"
#


#
# Structure for table "base"
#

CREATE TABLE `base` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "base"
#

INSERT INTO `base` VALUES (1,'1.7.5');

#
# Structure for table "device"
#

CREATE TABLE `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(100) NOT NULL DEFAULT '',
  `subnetid` varchar(2) NOT NULL DEFAULT '',
  `deviceid` varchar(2) NOT NULL DEFAULT '',
  `channel` varchar(2) NOT NULL DEFAULT '',
  `channel_spare` varchar(2) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `floor` varchar(10) DEFAULT NULL,
  `room` varchar(10) DEFAULT NULL,
  `devicetype` varchar(50) DEFAULT NULL,
  `on_off` varchar(5) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `icon` varchar(20) DEFAULT NULL,
  `starttime` varchar(20) DEFAULT NULL,
  `endtime` varchar(20) DEFAULT NULL,
  `mode` varchar(20) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `breed` varchar(20) DEFAULT NULL,
  `x_axis` int(6) DEFAULT '0',
  `y_axis` int(6) DEFAULT '0',
  `run_date` datetime DEFAULT NULL,
  `operation_1` varchar(3) DEFAULT NULL,
  `operation_2` varchar(3) DEFAULT NULL,
  `operation_3` varchar(3) DEFAULT NULL,
  `operation_4` varchar(3) DEFAULT NULL,
  `operation_5` varchar(3) DEFAULT NULL,
  `operation_6` varchar(3) DEFAULT NULL,
  `operation_7` varchar(3) DEFAULT NULL,
  `operation_8` varchar(3) DEFAULT NULL,
  `operation_10` varchar(3) DEFAULT NULL,
  `operation_11` varchar(3) DEFAULT NULL,
  `operation_12` varchar(3) DEFAULT NULL,
  `operation_13` varchar(3) DEFAULT NULL,
  `operation_14` varchar(3) DEFAULT NULL,
  `operation_15` varchar(3) DEFAULT NULL,
  `operation_16` varchar(3) DEFAULT NULL,
  `operation_17` varchar(3) DEFAULT NULL,
  `operation_18` varchar(3) DEFAULT NULL,
  `operation_19` varchar(3) DEFAULT NULL,
  `operation_20` varchar(3) DEFAULT NULL,
  `operation_9` varchar(3) DEFAULT NULL,
  `operation_21` varchar(3) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `alexa` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Data for table "device"
#

INSERT INTO `device` VALUES (1,'Light1','01','d5','01','','1','1','1','light','0','enabled',NULL,'','',NULL,NULL,'',0,0,'2018-07-10 15:34:58',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Light one'),(2,'Light2','01','d5','02','','1','1','1','light','0','enabled',NULL,'','',NULL,NULL,'',0,0,'2018-07-10 15:34:58',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Light two'),(3,'Light3','01','d5','03','','1','1','1','light','0','enabled',NULL,'','',NULL,NULL,'',0,0,'2018-07-10 15:34:58',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Light three'),(4,'AC','01','d4','','','1','1','1','ac','','enabled',NULL,'','',NULL,NULL,'',0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','AC'),(5,'LED','01','d6','','','1','1','1','led','on','enabled',NULL,'','','#0000ff',NULL,'',0,0,'2018-07-10 15:34:57',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','LED'),(6,'Music','01','d3','','','1','1','1','music','','enabled',NULL,'','',NULL,NULL,'',0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','Music');

#
# Structure for table "floor"
#

CREATE TABLE `floor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `floor` varchar(100) NOT NULL DEFAULT '',
  `room_num` int(3) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(200) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "floor"
#

INSERT INTO `floor` VALUES (1,'1',10,'','1','enabled',NULL),(2,'2',0,'','1','enabled',NULL),(3,'3',0,'','1','enabled',NULL),(4,'4',0,'','1','enabled',NULL),(5,'5',0,'','1','enabled',NULL),(6,'6',0,'','1','enabled',NULL),(7,'7',0,'','1','enabled',NULL),(8,'8',0,'','1','enabled',NULL),(9,'9',0,'','1','enabled',NULL),(10,'10',0,'','1','enabled',NULL);

#
# Structure for table "ir"
#

CREATE TABLE `ir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(100) NOT NULL DEFAULT '',
  `subnetid` varchar(2) NOT NULL DEFAULT '',
  `deviceid` varchar(2) NOT NULL DEFAULT '',
  `channel` varchar(2) NOT NULL DEFAULT '',
  `channel_spare` varchar(2) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `floor` varchar(10) DEFAULT NULL,
  `room` varchar(10) DEFAULT NULL,
  `devicetype` varchar(50) DEFAULT NULL,
  `on_off` varchar(5) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `icon` varchar(20) DEFAULT NULL,
  `starttime` varchar(20) DEFAULT NULL,
  `endtime` varchar(20) DEFAULT NULL,
  `mode` varchar(20) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `breed` varchar(20) DEFAULT NULL,
  `x_axis` int(6) DEFAULT '0',
  `y_axis` int(6) DEFAULT '0',
  `run_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Data for table "ir"
#


#
# Structure for table "ir_operation"
#

CREATE TABLE `ir_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(50) DEFAULT NULL,
  `ir_key` varchar(20) DEFAULT NULL,
  `ir_name` varchar(20) DEFAULT NULL,
  `ir_value` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "ir_operation"
#


#
# Structure for table "led_breed"
#

CREATE TABLE `led_breed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `breed` varchar(10) DEFAULT NULL,
  `watts` decimal(8,0) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `run_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "led_breed"
#


#
# Structure for table "light_breed"
#

CREATE TABLE `light_breed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `breed` varchar(10) DEFAULT NULL,
  `watts` decimal(8,0) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `run_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "light_breed"
#


#
# Structure for table "macro"
#

CREATE TABLE `macro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `macro` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Data for table "macro"
#


#
# Structure for table "macro_command"
#

CREATE TABLE `macro_command` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `macro` varchar(30) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `on_off` varchar(1) DEFAULT NULL,
  `mode` varchar(20) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `status_1` varchar(20) DEFAULT NULL,
  `status_2` varchar(20) DEFAULT NULL,
  `status_3` varchar(20) DEFAULT NULL,
  `status_4` varchar(20) DEFAULT NULL,
  `status_5` varchar(20) DEFAULT NULL,
  `time` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Data for table "macro_command"
#


#
# Structure for table "mood"
#

CREATE TABLE `mood` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mood` varchar(20) DEFAULT NULL,
  `device` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `floor` varchar(20) DEFAULT NULL,
  `room` varchar(20) DEFAULT NULL,
  `status_1` varchar(20) DEFAULT NULL,
  `status_2` varchar(20) DEFAULT NULL,
  `status_3` varchar(20) DEFAULT NULL,
  `status_4` varchar(20) DEFAULT NULL,
  `status_5` varchar(20) DEFAULT NULL,
  `status_6` varchar(20) DEFAULT NULL,
  `on_off` varchar(1) DEFAULT NULL,
  `mode` varchar(20) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "mood"
#


#
# Structure for table "record"
#

CREATE TABLE `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(100) DEFAULT NULL,
  `subnetid` varchar(2) DEFAULT NULL,
  `deviceid` varchar(2) DEFAULT NULL,
  `channel` varchar(2) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `port` varchar(10) DEFAULT NULL,
  `devicetype` varchar(50) NOT NULL DEFAULT '',
  `on_off` varchar(5) DEFAULT NULL,
  `mode` varchar(20) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `breed` varchar(20) DEFAULT NULL,
  `record_date` datetime DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `floor` varchar(10) DEFAULT NULL,
  `room` varchar(10) DEFAULT NULL,
  `mac` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`devicetype`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "record"
#

INSERT INTO `record` VALUES (1,'LED','01','d6','',NULL,NULL,'led','on','#0000ff',NULL,'','2018-07-10 15:42:21',NULL,'1','1','1',NULL),(2,'LED','01','d6','',NULL,NULL,'led','on','#0000ff',NULL,'','2018-07-10 15:52:21',NULL,'1','1','1',NULL);

#
# Structure for table "room"
#

CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room` varchar(100) NOT NULL DEFAULT '',
  `room_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `floor` varchar(10) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `lat` varchar(10) DEFAULT NULL,
  `lng` varchar(10) DEFAULT NULL,
  `width` varchar(10) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `collect` varchar(1) DEFAULT NULL,
  `alexa` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "room"
#

INSERT INTO `room` VALUES (1,'1','1','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'2a54024a84ad8b4703eda326b2757fc0'),(2,'2','2','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'fcb34a2bfd2050aad3e1ea5951c89098'),(3,'3','3','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'5744adef74ce6cee98aad5e4d3a0406b'),(4,'4','4','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'f786b84f4f54a2f542d40cc6eec231e8'),(5,'5','5','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'b625af0851690fbee77c9a3eb8fdefd7'),(6,'6','6','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'81e78a7a9e3ffc9e96c28b9370331497'),(7,'7','7','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'ee2e4262d3ce6b02d8591f7d82b68029'),(8,'8','8','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'5cc6e6666492db675aaedd76419d8121'),(9,'9','9','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'dcf9fec574f8a8205ac7852049fae3c9'),(10,'10','10','','1','1','enabled',NULL,NULL,NULL,NULL,NULL,NULL,'8e5bf5d167f698602ffa785834ad4612');

#
# Structure for table "runtime"
#

CREATE TABLE `runtime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `sun_up` varchar(20) DEFAULT NULL,
  `sun_down` varchar(20) DEFAULT NULL,
  `mon_up` varchar(20) DEFAULT NULL,
  `mon_down` varchar(20) DEFAULT NULL,
  `tues_up` varchar(20) DEFAULT NULL,
  `tues_down` varchar(20) DEFAULT NULL,
  `wed_up` varchar(20) DEFAULT NULL,
  `wed_down` varchar(20) DEFAULT NULL,
  `thur_up` varchar(20) DEFAULT NULL,
  `thur_down` varchar(20) DEFAULT NULL,
  `fri_up` varchar(20) DEFAULT NULL,
  `fri_down` varchar(20) DEFAULT NULL,
  `sat_up` varchar(20) DEFAULT NULL,
  `sat_down` varchar(20) DEFAULT NULL,
  `sun_status` int(1) DEFAULT NULL,
  `mon_status` int(1) DEFAULT NULL,
  `tues_status` int(1) DEFAULT NULL,
  `wed_status` int(1) DEFAULT NULL,
  `thur_status` int(1) DEFAULT NULL,
  `fri_status` int(1) DEFAULT NULL,
  `sat_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "runtime"
#


#
# Structure for table "schedule"
#

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule` varchar(30) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `time_1` varchar(50) DEFAULT NULL,
  `time_2` varchar(50) DEFAULT NULL,
  `mon` varchar(20) DEFAULT NULL,
  `tues` varchar(20) DEFAULT NULL,
  `wed` varchar(20) DEFAULT NULL,
  `thur` varchar(20) DEFAULT NULL,
  `fri` varchar(20) DEFAULT NULL,
  `sat` varchar(20) DEFAULT NULL,
  `sun` varchar(20) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "schedule"
#


#
# Structure for table "schedule_command"
#

CREATE TABLE `schedule_command` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule` varchar(30) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `on_off` varchar(1) DEFAULT NULL,
  `mode` varchar(20) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `status_1` varchar(20) DEFAULT NULL,
  `status_2` varchar(20) DEFAULT NULL,
  `status_3` varchar(20) DEFAULT NULL,
  `status_4` varchar(20) DEFAULT NULL,
  `status_5` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "schedule_command"
#

