-- MySQL dump 10.13  Distrib 5.5.29, for Linux (i686)
--
-- Host: 127.0.0.1    Database: pop
-- ------------------------------------------------------
-- Server version	5.5.29

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='内容弹窗';
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='标准弹窗';
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-07 12:05:53
