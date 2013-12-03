<?php
//管理员模型，从原来的apps.soft365.com中拿过来，没改过
class UserModel extends Model {
	protected $_validate = array (
			array (
					"name",
					'require',
					'请填写用户名。' 
			) 
	);
	
	// 是否停用
	public static $status = array (
			'1' => '正常',
			'2' => '禁用' 
	);
	protected $_auto = array (
			array (
					'create_time',
					'time',
					1,
					'function' 
			)  // 对create_time字段在更新的时候写入当前时间戳
	);
}