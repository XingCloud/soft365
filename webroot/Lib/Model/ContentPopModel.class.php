<?php
//内容弹窗的mysql操作模型
class ContentPopModel extends StdPopModel{
	
	// 内容弹窗类型
	static public $sub_type = array(
		1 => '推广1',
		2 => '推广2'
	); 
	
	// 应用类型
	static public $app_type = array(
		1 => '网址',
		2 => '应用'
	);
	
	protected $_validate = array (
		array ("sub_type",'require','请选择内容弹窗分类。'),
		// 标题留空，让客户端处理
		//array ("title",'require','标题不能为空。'),
		//array ("title","/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",'标题只能为中文，字母，数字，下划线.'),
		array ("text",'require','内容不能为空。'),
		//array ("text","/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",'内容只能为中文，字母，数字，下划线.'),
		array ("app_type",'require','请选择激活应用类型。'),
		array ("app_id",'require','应用ID不能为空。'),
		array ("app_name",'require','应用名称不能为空。'),
		array ("live_time",'require','停留时长不能为空。'),
		array ("live_time",'/^\d{1,4}$/','停留时长只能为9999以内的数字。'),
		array ("start_date",'require','生效开始日期不能为空。'),
		array ("start_date",'/^\d{4}-\d{2}-\d{2}$/','生效开始日期格式不正确。'),				
		array ("end_date",'require','生效结束日期不能为空。'),
		array ("end_date",'/^\d{4}-\d{2}-\d{2}$/','生效结束日期格式不正确。'),
		array ("start_time",'require','生效开始时间不能为空。'),
		array ("start_time",'/^\d{2}:\d{2}(\:\d{2})?$/','生效开始时间格式不正确。'),				
		array ("end_time",'require','生效结束时间不能为空。'),
		array ("end_time",'/^\d{2}:\d{2}(\:\d{2})?$/','生效结束时间格式不正确。'),
		array ("max_times",'require','连续弹出次数不能为空。'),
		array ("max_times",'/^\d{1,4}$/','连续弹出次数只能为9999以内的数字。'),
		array ("weight",'require','权重不能为空。'),
		array ("weight",'/^\d{1,4}$/','权重只能为9999以内的数字。'),
		array ("max_people",'/^\d+$/','指定人数只能是数字。'),
				
	);
	protected $_auto = array (
		array('add_time','time',1,'function'),
		array('up_time','time',1,'function')
	);
}