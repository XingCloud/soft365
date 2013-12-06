<?php
//标准弹窗的mysql操作模型
class StdPopModel extends Model{
	
	// 是否停用
	static public $disabled = array(
			0 => '启用',
			1 => '停用'
	);

    // 是否强制

    static public $force = array(
        1 => '是',
        0 => '非'
    );
	
	protected $_validate = array (
		// 标题留空，让客户端处理
		//array ("title",'require','标题不能为空。'),
		//array ("title","/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",'标题只能为中文，字母，数字，下划线.'),
		array ("url",'require','弹窗网址不能为空。'),
		array ("url","/^http\:\/\/.+$/",'网址必须以http://开头.'),
		array ("width",'require','宽度不能为空。'),
		array ("height",'require','高度不能为空。'),
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
        array ("tags",'/^\d+(,\d+)*$/','标签以逗号分隔。')
	);
	protected $_auto = array (
		array('add_time','time',1,'function'),
		array('up_time','time',1,'function')
	);
	
	//按应用日期取得数据
	function getByDate($date,$user_id){
		$where = "'{$date}' between start_date and end_date" ;
		if($user_id)
			$where .=" and user_id=".$user_id;
		return $this->where ( $where)->select ();
	}
}