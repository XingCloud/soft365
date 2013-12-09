<?php
//标准弹窗的redis操作模型，Hash结构, 只会保存可用的stdpop,参见 StdPopAction.saveRedis
class StdPopRedisModel extends RedisBaseModel {
	
	// redis键名
	const redis_key = 'std_pop'; //TODO wcl didn't find this key in redis
	
	// 弹窗类型
	const type = 'std_pop';
		
	// 广告的用户数
	const people_count = 'people_count';
	
	// 指定人数
	const max_people = 'max_people';
	
	// 弹出次数
	const poped_times = 'poped_times';
	
	// 开始时间
	const start_time = 'start_time';
	
	// 结束时间
	const end_time = 'end_time';
	
	// 国家
	const country = 'country';
	
	// 最大弹出次数
	const max_times = 'max_times';
	
	// 保存
	static function save($pop){
		$old = static::hget($pop['id']);
		$pop[static::people_count] = $old[static::people_count];
		$pop[static::poped_times] = $old[static::poped_times]; 
	}
}