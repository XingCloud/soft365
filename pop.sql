-- MySQL dump 10.13  Distrib 5.5.33, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: pop_soft365
-- ------------------------------------------------------
-- Server version	5.5.33-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(45) NOT NULL DEFAULT '' COMMENT '用户id',
  `country` varchar(45) NOT NULL DEFAULT '' COMMENT '所在国家',
  `lang` varchar(45) NOT NULL DEFAULT '' COMMENT '使用语言',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid_UNIQUE` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8 COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (35,'SanDisk_SD5SG2256G1052E_130285401190','China','',1375774608),(36,'SAMSUNGXHD322GJ_S2FRJ9FB401782','China','',1375774732),(37,'1','China','',1375778260),(38,'12345','China','',1375778322),(39,'123123','China','',1375778475),(40,'12312334','China','',1375779223),(41,'9','China','',1375782875),(42,'用户ID','China','',1375784245),(43,'999','China','',1375787500),(44,'20','China','',1375788345),(45,' ','China','cn',1375788497),(46,'null','China','cn',1375788508),(47,'10000','China','cn',1375788529),(48,'SanDiskXSD5SG2256G1052E_130285401190','China','',1375839908),(49,'2','China','',1375840107),(50,'3','China','',1375840117),(51,'4','China','',1375840152),(52,'AanDiskXSD5SG2256G1052E_130285401190','China','',1375840514),(53,'10','China','',1375840961),(54,'100','China','',1375840978),(55,'xueqing1','China','',1375841061),(56,'xueqing2','China','',1375841334),(57,'11','China','',1375842015),(58,'xueqing3','China','',1375842147),(59,'200','China','',1375842595),(60,'1000','China','',1375842703),(61,'sm1','China','',1375844091),(62,'nx1001','China','',1375844216),(63,'sm2','China','',1375845523),(64,'nx1002','China','',1375845532),(65,'sm3','China','',1375846025),(66,'13','China','',1375846464),(67,'14','China','',1375846491),(68,'nx1003','China','',1375846814),(69,'nx1004','China','',1375846990),(70,'nx1005','China','',1375847296),(71,'nx1006','China','',1375847441),(72,'nx1007','China','',1375847513),(73,'nx1008','China','',1375847598),(74,'nx1009','China','',1375847659),(75,'http://pop.soft365.com/Pop/success/?userid=sm','China','',1375847908),(76,'nx1010','China','',1375847910),(77,'http://pohttp://pop.sm/Pop/success/?userid=sm','China','',1375848037),(78,'nx1011','China','',1375848040),(79,'nx1012','China','',1375848067),(80,'nx1013','China','',1375848125),(81,'nx1014','China','',1375848170),(82,'nx1015','China','',1375848253),(83,'nx1016','China','',1375848324);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_pop`
--

DROP TABLE IF EXISTS `content_pop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_pop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `country` varchar(45) NOT NULL DEFAULT '' COMMENT '地区',
  `lang` varchar(45) NOT NULL DEFAULT '' COMMENT '语言',
  `start_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '生效日期(任务可能是每天循环使用的,所以时间和日期分开)',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `description` text COMMENT '备注',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `up_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `live_time` smallint(6) NOT NULL DEFAULT '0' COMMENT '展示时长',
  `weight` smallint(6) NOT NULL DEFAULT '0' COMMENT '权重',
  `text` varchar(500) NOT NULL DEFAULT '' COMMENT '文字',
  `sub_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '要激活的应用类型,1:游戏,2:网站',
  `app_id` varchar(45) NOT NULL DEFAULT '' COMMENT '要激活的应用ID',
  `max_times` tinyint(4) NOT NULL DEFAULT '1' COMMENT '每天最多弹出次数',
  `app_name` varchar(45) NOT NULL DEFAULT '' COMMENT '应用名称',
  `start_time` time NOT NULL DEFAULT '00:00:00' COMMENT '起始时间',
  `end_time` time NOT NULL DEFAULT '00:00:00' COMMENT '结束时间',
  `max_people` int(11) NOT NULL DEFAULT '0' COMMENT '最大人数',
  `counrty` varchar(100) NOT NULL DEFAULT '' COMMENT '国家',
  `app_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '要激活的应用类型,1:游戏,2:网站',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='内容弹窗';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_pop`
--

LOCK TABLES `content_pop` WRITE;
/*!40000 ALTER TABLE `content_pop` DISABLE KEYS */;
INSERT INTO `content_pop` VALUES (21,'网址推广','','','2013-08-08','2013-08-09','',1375777530,1376032144,30,3,'网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广网址推广',1,'20',1,'337','00:00:00','23:59:00',0,'',1),(22,'Gmail推广','','','2013-08-06','2013-08-09','',1375777560,1376031967,0,3,'GmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmailGmail',1,'35',1,'Gmail','00:00:00','23:59:00',0,'',2),(23,'nrcc','','','2013-08-07','2013-08-14','',1375870704,1376032341,10,11,'内容2的弹窗',2,'21',1,'google','00:00:00','23:59:00',0,'',1),(24,'11','','es_es','2013-08-09','2013-08-10','',1376039444,1376040885,0,70,'11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',2,'1',10,'123','00:00:00','23:59:00',0,'',2);
/*!40000 ALTER TABLE `content_pop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '国家名称',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='国家名称';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES ('11'),('12'),('22'),('English'),('中国'),('打倒小日本'),('泰国'),('测试1');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lang`
--

DROP TABLE IF EXISTS `lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang` (
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '语言名称',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='语言名称';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lang`
--

LOCK TABLES `lang` WRITE;
/*!40000 ALTER TABLE `lang` DISABLE KEYS */;
INSERT INTO `lang` VALUES ('en'),('en_us'),('es_es'),('pt'),('pt_br'),('tr'),('tr_tr'),('zh');
/*!40000 ALTER TABLE `lang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `std_pop`
--

DROP TABLE IF EXISTS `std_pop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `std_pop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '弹窗网址',
  `country` varchar(45) NOT NULL DEFAULT '' COMMENT '地区',
  `lang` varchar(45) NOT NULL DEFAULT '' COMMENT '语言',
  `start_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '生效日期(任务可能是每天循环使用的,所以时间和日期分开)',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `description` text COMMENT '备注',
  `weight` smallint(6) NOT NULL DEFAULT '0' COMMENT '权重',
  `max_times` tinyint(4) NOT NULL DEFAULT '1' COMMENT '每天的最大弹出次数',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `up_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `width` varchar(45) NOT NULL DEFAULT '' COMMENT '弹窗宽度',
  `height` varchar(45) NOT NULL DEFAULT '',
  `live_time` smallint(6) NOT NULL DEFAULT '0' COMMENT '展示时长',
  `start_time` time NOT NULL DEFAULT '00:00:00' COMMENT '起始时间',
  `end_time` time NOT NULL DEFAULT '00:00:00' COMMENT '结束时间',
  `counrty` varchar(100) NOT NULL DEFAULT '' COMMENT '国家',
  `max_people` int(11) NOT NULL DEFAULT '0' COMMENT '人数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='标准弹窗';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `std_pop`
--

LOCK TABLES `std_pop` WRITE;
/*!40000 ALTER TABLE `std_pop` DISABLE KEYS */;
INSERT INTO `std_pop` VALUES (31,'标准1','http://www.youku.com','','','2013-08-06','2013-08-07','',5,1,1375777596,1375871875,'340','280',0,'00:00:00','23:59:00','',5),(32,'标准2','http://www.baidu.com','','','2013-08-08','2013-08-09','',12,1,1375777625,1376034298,'340','340',0,'00:00:00','23:59:00','',0),(33,'bjtc','http://www.163.com','','','2013-08-07','2013-08-07','泰国泡泡',10,1,1375868246,1375868616,'400','250',0,'09:00:00','23:59:00','',0),(34,'test','http://www.baidu.com','','es_es','2013-08-08','2013-08-09','',1,1,1375933653,1376032180,'400','280',5,'00:00:00','23:59:00','',0);
/*!40000 ALTER TABLE `std_pop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '数据唯一编号',
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '显示的名字',
  `password` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `email` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `last_login` int(11) DEFAULT NULL COMMENT '最近登录日期',
  `is_elex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否ELEX内部人员    0：否 1:是',
  `is_admin` int(1) NOT NULL DEFAULT '0' COMMENT '是否管理员    0：普通用户 1:管理员',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态    0：未激活 1:正常 2:禁用',
  PRIMARY KEY (`id`),
  KEY `idx_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='登录用户信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'elex','93725b9fc52918f65c42dd16492d7d98','zhangpeng@elex-tech.com',1375067163,1,1,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-12  2:45:31
