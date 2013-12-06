<?php
//redis操作底层模型
class RedisBaseModel {
	const redis_key = '';
	public $redis_key = '';
	
	// 创建对象
	function __construct($redis_key=''){
		$this->redis_key = $redis_key;
	}
	
	// redis单例
	protected static $redis = null;
	
	// 初始化redis
	static protected function initRedis() {
		if (empty ( self::$redis )) {
			self::$redis = new redis ();
			//TODO: 把redis配置写到配置文件中去
			self::$redis->pconnect ( '127.0.0.1' );
			self::$redis->setOption ( Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP );
			// var_dump(self::$redis->keys('*'));
		}
	}
	
	// 销毁redis对象
	static function destroy(){
		self::$redis = null;
	}
	
	// 给动态方法提供键名的接口
	function getKey() {
		return $this->redis_key ? $this->redis_key : static::redis_key;
	}
	
	// 支持用对象成员方法调用redis方法
	function __call($method, $params) {
		return static::methodAdapter ( $method, $params, $this );
	}
	
	// 静态调用的取得建名的方法
	static function key() {
		return static::redis_key;
	}
	
	// 支持用静态方法调用redis方法,$obj是本类的对象
	static function __callStatic($method, $params) {
		return static::methodAdapter ( $method, $params );
	}
	
	// redis方法适配器，让静态方法和动态方法可以通用，并加入批量处理方法（以many结尾）
	static function methodAdapter($method, $params, $obj = null) {
		// 给redis添加批处理方法
		if (strtolower ( substr ( $method, - 4 ) ) == 'many') {
			print_r ( $val );
			$method = substr ( $method, 0, - 4 );
			$ps = array();
			// 果有3个参数,且最后一个是数组
			if (is_array ( $params [2] )) {
				$ps = array_merge (  array(
						$params [0],
						$params [1] 
				), $params [2] );
			} 			// 果有两个参数,且最后一个是数组
			elseif (is_array ( $params [1] )) {
				$ps = array_merge ( array( 
						$params [0] 
				), $params [1] );
			} 			// 果只有一个参数,且它是数组
			elseif (is_array ( $params [0] )) {
				$ps = $params [0];
			}
		} 		// 如果不需要批处理
		else
			$ps = $params;
		if (! is_array ( $ps ))
			$ps = array( 
					$ps 
			);
			// 取得要操作的key
		$key = empty ( $obj ) ? static::key () : $obj->getKey ();
		// 把key强制作为参数
		$ps = array_merge ( array (
				$key 
		), $ps );
		// 初始化redis
		self::initRedis ();
		// 调用redis
		return \call_user_func_array ( array (
				self::$redis,
				$method 
		), $ps );
	}
}
