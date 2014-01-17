ALTER TABLE `content_pop` ADD COLUMN `app_type` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '要激活的应用类型,1:游戏,2:网站'  ;

 CREATE TABLE `country` (
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '国家名称',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='国家名称';


 CREATE TABLE `lang` (
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '语言名称',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='语言名称';


--2013-08-15
CREATE TABLE `pop_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(45) NOT NULL DEFAULT '' COMMENT '触发动作',
  `pop_id` int(11) NOT NULL DEFAULT '0' COMMENT '弹窗ID',
  `client_id` varchar(80) NOT NULL DEFAULT '' COMMENT '客户ID',
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '时间',
  `has_bind` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否绑定',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型',
  PRIMARY KEY (`id`),
  KEY `all` (`type`,`action`,`pop_id`,`time`,`client_id`,`has_bind`) COMMENT '全部只用一个索引'
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='弹窗日志';



ALTER TABLE `pop_soft365`.`country` ADD COLUMN `index_no` SMALLINT NOT NULL DEFAULT 100 COMMENT '序号'  AFTER `name` ;

--2013-08-26

ALTER TABLE `pop_soft365`.`pop_log` DROP INDEX `all` , 
ADD INDEX `all2` (`time` , `type` , `pop_id` , `action` , `client_id` ) ;

--2013-09-02
ALTER TABLE `pop_soft365`.`std_pop` ADD COLUMN `disabled` TINYINT NOT NULL DEFAULT 0 COMMENT '停用(1,停用;0,启用)'  AFTER `title` ;
ALTER TABLE `pop_soft365`.`content_pop` ADD COLUMN `disabled` TINYINT NOT NULL DEFAULT 0 COMMENT '停用(1,停用;0,启用)'  AFTER `title` ;

--
ALTER TABLE `pop_soft365`.`std_pop` ADD COLUMN `oemid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'oemid,客户端软件的标识'  AFTER `max_people` ;
ALTER TABLE `pop_soft365`.`content_pop` ADD COLUMN `oemid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'oemid,客户端软件的标识'  AFTER `max_people` ;

CREATE TABLE `oemid` (
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '显示名称',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='oemid,客户端软件的标识';

--2013-09-09
CREATE  TABLE `pop_soft365`.`stat` (
  `type` TINYINT NOT NULL DEFAULT 0 COMMENT '弹窗类型' ,
  `pop_id` INT NOT NULL DEFAULT 0 COMMENT '弹窗ID' ,
  `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标题' ,
  `country` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '国家' ,
  `lang` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '语言' ,
  `oemid` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '客户端标识' ,
  `client_count` INT NOT NULL DEFAULT 0 COMMENT '用户数' ,
  `pop_count` INT NOT NULL DEFAULT 0 COMMENT '弹窗数' ,
  `success_count` INT NOT NULL DEFAULT 0 COMMENT '弹窗成功数' ,
  `click_count` INT NOT NULL DEFAULT 0 COMMENT '点击数' ,
  `time_step` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '时间跨度' ,
  `start_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00' COMMENT '开始时间' ,
  `end_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00' COMMENT '结束时间' ,
  `up_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00' COMMENT '刷新时间' ,
  /* start_time和time_step成对出现，type和pop_id成对出现,oemid用pop_id代替 */
  PRIMARY KEY  (`start_time`, `time_step` , `type` , `pop_id`  ,  `end_time` ) )
COMMENT = '统计数据表';

--2013-09-17
ALTER TABLE `pop_soft365`.`client` ADD COLUMN `oemid` VARCHAR(45) NOT NULL DEFAULT '' COMMENT '客户端软件标识'  AFTER `add_time` ;
ALTER TABLE `pop_soft365`.`content_pop` ADD COLUMN `user_id` MEDIUMINT NOT NULL DEFAULT 0 COMMENT '管理员ID'  AFTER `app_type` ;
ALTER TABLE `pop_soft365`.`oemid` ADD COLUMN `user_id` MEDIUMINT NOT NULL DEFAULT 0 COMMENT '管理员ID'  AFTER `name` ;
ALTER TABLE `pop_soft365`.`std_pop` ADD COLUMN `user_id` MEDIUMINT NOT NULL DEFAULT 0 COMMENT '管理员ID'  AFTER `oemid` ;
ALTER TABLE `pop_soft365`.`user` add COLUMN `privilege`  VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '权限'  ;
ALTER TABLE `pop_soft365`.`user` ADD COLUMN `desc` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '说明'  AFTER `privilege` ,
ADD COLUMN `add_user_id` MEDIUMINT NOT NULL DEFAULT 0 COMMENT '添加者ID'  AFTER `desc` ;
ALTER TABLE `pop_soft365`.`user` ADD COLUMN `add_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00' COMMENT '添加时间'  AFTER `add_user_id` ;
ALTER TABLE `pop_soft365`.`user` CHANGE COLUMN `email` `email` VARCHAR(256) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '邮箱'  ,
CHANGE COLUMN `last_login` `last_login` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最近登录日期'  ;



ALTER TABLE `pop_soft365`.`oemid` ADD COLUMN `id` MEDIUMINT NOT NULL AUTO_INCREMENT  FIRST 
, DROP PRIMARY KEY 
, ADD PRIMARY KEY (`id`) 
, ADD UNIQUE INDEX `name_UNIQUE` (`name` ASC) ;
ALTER TABLE `pop_soft365`.`oemid` ADD COLUMN `desc` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '说明'  AFTER `user_id` ;


ALTER TABLE `pop_soft365`.`stat` ADD COLUMN `user_id` INT NOT NULL DEFAULT 0 COMMENT '所属用户'  AFTER `up_time` ;


--2013-12-04
ALTER TABLE `pop_soft365`.`std_pop` ADD COLUMN `tags` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标签'  AFTER `id` ;
ALTER TABLE `pop_soft365`.`std_pop` ADD COLUMN `force` TINYINT NOT NULL DEFAULT 1 COMMENT '强制(1,强制;0,非强制)'  AFTER `disabled` ;


--2014-01-16
ALTER TABLE `pop_soft365`.`std_pop` ADD COLUMN `jsonaction` TEXT NOT NULL DEFAULT '' COMMENT '标签'  AFTER `force` ;


--2014-01-17
 CREATE TABLE `json` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL DEFAULT '' COMMENT 'JsonAction',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='JsonAction';