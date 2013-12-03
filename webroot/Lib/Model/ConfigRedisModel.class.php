<?php
//全局配置管理，Hash结构
class ConfigRedisModel extends RedisBaseModel {
	
	// 弹窗redis键名
	const redis_key = 'config';
	
	// 弹窗间隔时间(分钟)
	const pop_space_time = 'pop_space_time';
	
	// 弹窗数据最后一次刷新的时间，每天刷新一次，所以格式是日期
	const pop_refresh_date = 'pop_refresh_date';
	
	// 开机到首次弹窗间隔时间
	const first_pop_space_time = 'first_pop_space_time';
	
	// 某用户的数据
	protected static $data;
	
	// 客户端请求间隔时间(分钟)
	const client_request_space_time = 'client_request_space_time';
	
	// 可修改的数据
	public static $fields = array (
			'pop_space_time' => '弹窗间隔时间(分钟)',
			'max_pop_times' => '单用户每日最大弹窗次数',
			'first_pop_space_time' => '启动到首次弹窗间隔时间' 
	);
	
	// 系统数据
	public static $sysFields = array (
			'client_request_space_time' => '客户端请求间隔时间(分钟)' 
	);
	
	// 保存数据
	static function save($user_id, $data) {
		static::hset ( $user_id, $data );
		static::$data [$user_id] = $data;
	}
	
	// 取得一个用户的所有数据
	static function getByUserId($user_id) {
		if (empty ( static::$data [$user_id] ))
			static::$data [$user_id] = static::hget ( $user_id );
		return static::$data [$user_id];
	}
	
	// 弹窗间隔时间
	static function pop_space_time($user_id) {
		static::getByUserId ( $user_id );
		return static::$data [$user_id] ['pop_space_time'];
	}
	
	// 最大弹出次数
	static function max_pop_times($user_id) {
		static::getByUserId ( $user_id );
		return static::$data [$user_id] ['max_pop_times'];
	}
	
	// 请求间隔时间，由管理员统一设置
	static function client_request_space_time() {
		return static::hget ( static::client_request_space_time );
	}
	
	// 设置请求时间间隔
	static function saveSys($data) {
		foreach ( $data as $k => $v )
			static::hset ( $k, $v );
	}
}