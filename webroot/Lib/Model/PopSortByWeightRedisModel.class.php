<?php
// 按权重排序的数据列表，redis操作模型，List结构
class PopSortByWeightRedisModel extends RedisBaseModel {
	
	// redis键名
	const redis_key = 'pop_sort_by_weight';
	
	// 生成排序好的列表：当有弹窗被修改，或弹窗数据全部刷新时，触发此操作
	static function refresh() {
		// 取出所有标准弹窗，加类别标记
		$std_pop = StdPopRedisModel::hvals ();
		$content_pop = ContentPopRedisModel::hvals ();
		$data = static::getSortList($std_pop, $content_pop);
		// 存进列表
		static::set ( $data );
	}
	
	// 根据用户ID取出弹窗数据
	static function getByUserId($user_id){
		$data = static::get();
		foreach($data as $v)
			if($v['user_id']==$user_id)
				$re[] = $v;
		return $re;
	}
	
	// 给弹窗任务排序
	static function getSortList($std_pop, $content_pop) {
		// 取出所有标准弹窗，加类别标记
		if (empty ( $std_pop ))
			$std_pop = array ();
		foreach ( $std_pop as $k => $v )
			$std_pop [$k] ['type'] = StdPopRedisModel::type;
			// 取出所有内容弹窗，加类别标记
		if (empty ( $content_pop ))
			$content_pop = array ();
		foreach ( $content_pop as $k => $v )
			$content_pop [$k] ['type'] = ContentPopRedisModel::type;
			// 合并数据
		$data = array_merge ( $std_pop, $content_pop );


		// 按权重排序
		usort ( $data, function ($a, $b) {
			$a ['weight'] = intval ( $a ['weight'] );
			$b ['weight'] = intval ( $b ['weight'] );
			if ($a ['weight'] > $b ['weight'])
				return - 1;
			elseif ($a ['weight'] < $b ['weight'])
				return 1;
			else
				return 0;
		} );
		return $data;
	}
}