<?php
//
class ContentPopAction extends StdPopAction {
	
	// 准备数据
	protected $fields = array (
			'title',
			'text',
			'sub_type',
			'app_type',
			'app_id',
			'app_name',
			'live_time',
			'start_date',
			'end_date',
			'start_time',
			'end_time',
			'max_times',
			'weight',
			'description',
			'country',
			'lang',
			'max_people',
			'disabled',
			'oemid' 
	);
	
	// 构造方法
	function __construct() {
		parent::__construct ();
		$this->popModel = new ContentPopModel ();
		$this->redisModel = new ContentPopRedisModel ();
		$this->assign ( 'sub_type', ContentPopModel::$sub_type );
		$this->assign ( 'app_type', ContentPopModel::$app_type );
	}
}

?>