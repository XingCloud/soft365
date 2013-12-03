<?php
//国家名称的mysql操作模型
class PopLogModel{
	
	// 表名
	static function tableName($date=null){
		if(empty($date))
			$date = date('Ymd');
		$table = "pop_log_{$date}";
		return $table;
	}
	
	// 取得指定日期的日志的model,这里不怕重复获取,M方法会保证一个表的类不被new多次
	static function getModel($date=null){
		$tableName = static::tableName($date);
		return M($tableName);
	}
	
	// 自动创建表格
	static function autoCreateTable($tableName=null){
		if(empty($tableName))
			$tableName = static::tableName();
		$sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		  `action` varchar(45) NOT NULL DEFAULT '' COMMENT '触发动作',
		  `pop_id` int(11) NOT NULL DEFAULT '0' COMMENT '弹窗ID',
		  `client_id` varchar(80) NOT NULL DEFAULT '' COMMENT '客户ID',
		  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '时间',
		  `has_bind` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否绑定',
		  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型',
		  `time_day` char(10) NOT NULL DEFAULT '0000-00-00' COMMENT '按天分组',
		  `time_ten_minute` char(15) NOT NULL DEFAULT '0000-00-00 00:0' COMMENT '按10分钟分组',
		  `time_hour` char(13) NOT NULL DEFAULT '0000-00-00 00' COMMENT '按小时分组',
		  PRIMARY KEY (`id`),
		  KEY `day_group` (`type`,`pop_id`,`time_day`,`action`,`client_id`),
		  KEY `ten_minute_group` (`type`,`pop_id`,`time_ten_minute`,`action`,`client_id`),
		  KEY `hour_group` (`type`,`pop_id`,`time_hour`,`action`,`client_id`)
		) ENGINE=MyIsam AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='弹窗日志'";
		$model = new StatModel();
		$model->query($sql);
		
	}
}