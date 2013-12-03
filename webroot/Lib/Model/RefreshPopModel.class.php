<?php
// 刷新redis中的弹窗数据
class RefreshPopModel {
	
	// 刷新弹窗数据
	static function doRefresh() {
		// 要更新的数据
		$models = array (
				array (
						'model' => 'StdPopModel',
						'redisModel' => 'StdPopRedisModel' 
				),
				array (
						'model' => 'ContentPopModel',
						'redisModel' => 'ContentPopRedisModel' 
				) 
		);
		$today = date ( 'Y-m-d' );
		// 循环更新
		$data = array ();
		foreach ( $models as $v ) {
			$model = new $v ['model'] ();
			$redisModel = new $v ['redisModel'] ();
			// 查找数据
			$data = $model->getByDate ( $today );
			//echo "'{$today}' between start_date and end_date";die;
			//var_dump($data);die;
			// 取得旧数据
			$old = $redisModel->hgetall ();
			// 清空redis中的旧数据
			$redisModel->del ();
			// 保存进redis
			if (empty ( $data ))
				continue;
			foreach ( $data as $v2 ) {
				//保留弹出次数
				$v2['poped_times'] = $old[$v2['id']]['poped_times'];
				//把绑定人数更新到新数据中，这里不在当天的数据就自动过滤掉了
				$v2['people_count'] = $old[$v2['id']]['people_count'];
				$redisModel->hset ( $v2 ['id'], $v2 );
			}
		}
		// 生成按权重排序的列表
		PopSortByWeightRedisModel::refresh ();
		// 记录更新时间
		ConfigRedisModel::hset ( ConfigRedisModel::pop_refresh_date, $today );
	}
		
	// 自动决定是否刷新数据,这个可以在用户访问时调用
	static function autoRefresh() {
		// 如果今天尚未刷新过数据
		if (ConfigRedisModel::hget ( ConfigRedisModel::pop_refresh_date ) != date ( 'Y-m-d' ))
			static::doRefresh ();
	}
}
