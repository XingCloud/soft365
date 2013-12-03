<?php
namespace redis;

//标准弹窗的redis操作模型，Hash结构
class StdPop extends Base {
	
	// redis键名
	const redis_key = 'std_pop';
	
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

	// 语言
	const lang = 'lang';
	
	// 国家
	const country = 'country';
	
	// 最大弹出次数
	const max_times = 'max_times';
	
	// 已停用
	const disabled = 'disabled';
	
	// oemid,客户端软件标识
	const oemid = 'oemid';
	
	// 管理员ID
	const user_id = 'user_id';
	
	
	// 增加弹出次数
	static function addTimes($pop_id, $isFirstTime, &$popAddPeople) {
		$pop = self::hget ( $pop_id );
		$pop [static::poped_times] ++;
		// 如果这个弹窗内容需要记录绑定用户,并且是首次弹窗
		if ($pop [static::max_people] > 0 && $isFirstTime) {
			$pop [static::people_count] ++;
			$popAddPeople = true;
		} else
			$popAddPeople = false;
		self::hset ( $pop_id, $pop );			
	}
}