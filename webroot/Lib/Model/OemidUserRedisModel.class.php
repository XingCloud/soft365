<?php
//内容弹窗的redis操作模型，Hash结构
class OemidUserRedisModel extends RedisBaseModel {
	
	// 弹窗redis键名
	const redis_key = 'oemid_user';
	
	// 刷新数据
	static function refresh(){
		$model = new OemidModel();
		$data = $model->select();
		foreach($data as $v)
			static::hset($v['name'],$v['user_id']);
	}
	
}