<?php
//Oemid的mysql操作模型
class OemidModel extends Model {
	protected $_validate = array (
			// 
			array (
					"name",
					'require',
					'请填写oemid' 
			),
			array (
					"user_id",
					'require',
					'请选择所属用户'
			),
	);
	// oemid是键
	static function oemidUserData() {
	}
}