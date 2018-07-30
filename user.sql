# Host: localhost  (Version: 5.5.53)
# Date: 2018-07-30 10:45:50
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(100) DEFAULT NULL COMMENT '管理后台账号',
  `password` varchar(100) DEFAULT NULL COMMENT '管理后台密码',
  `database_name` varchar(100) DEFAULT NULL COMMENT '数据库',
  `remark` varchar(100) DEFAULT NULL COMMENT '用户备注',
  `create_time` datetime DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL COMMENT '状态,1启用0禁用',
  `port` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

#
# Data for table "user"
#

INSERT INTO `user` VALUES (4,'test','test','hospital','test','2017-10-16 11:31:25',1,'6001'),(6,'test1','a7168f0f249e3add33da11a59e228a57','test',NULL,NULL,1,NULL),(7,'admin','0c7540eb7e65b553ec1ba6b20de79608','admin','admin',NULL,1,'2120');
