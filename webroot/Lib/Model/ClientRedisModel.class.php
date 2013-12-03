<?php
//用户数据的redis操作模型，Hash结构
class ClientRedisModel extends RedisBaseModel {
	// redis前缀
	const redis_key = 'client_';
	
	// 标准弹窗记录redis键名
	const std_poped = 'std_poped';
	
	// 内容弹窗记录redis键名
	const content_poped = 'content_poped';
	
	// 用户状态redis键名
	const status = 'status';
	
	// 用户ID
	public $client_id = '';
	
	// 创建对象时必须传入用户ID
	function __construct($client_id) {
		$this->client_id = $client_id;
	}
	
	// 取得键名
	function getKey() {
		return static::redis_key . $this->client_id;
	}
	
	// ------------------------------static methods--------------
	
	//
	static function allKeys() {
		return static::keys ( static::redis_key . '*' );
	}
}