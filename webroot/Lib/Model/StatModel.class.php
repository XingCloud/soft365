<?php

//统计
class StatModel extends Model {
	
	//type,id,title,country,lang,oemid,client_count,pop_count,success_count,
	//click_count,time_step,start_time,end_time
	

	// 时间段间隔
	static $time_step = array (
			//'minute' => '每分钟',
			'ten_minute' => '每10分钟',
			'hour' => '每小时',
			'day' => '每天' 
	);
	
	// 时间段剪切位置
	protected $time_cut = array (
			'minute' => 16,
			'ten_minute' => 15,
			'hour' => 13,
			'day' => 10 
	);
	
	// 时间段开始时间需要补全的字符
	protected $time_f = array (
			'minute' => ':00',
			'ten_minute' => '0:00',
			'hour' => ':00:00',
			'day' => ' 00:00:00' 
	);
	
	// 时间段结束时间需要补全的字符
	protected $time_l = array (
			'minute' => ':59',
			'ten_minute' => '9:59',
			'hour' => ':59:59',
			'day' => ' 23:59:59' 
	);
	
	// 时间段分别存储在不同的字段中，方便查询
	protected $group_fields = array (
			'ten_minute' => 'time_ten_minute',
			'hour' => 'time_hour',
			'day' => 'time_day' 
	);
	
	// 统计结果表中的字段
	protected $fields = array (
			'type',
			'pop_id',
			'title',
			'country',
			'lang',
			'oemid',
			'client_count',
			'pop_count',
			'success_count',
			'click_count',
			'time_step',
			'start_time',
			'end_time',
			'up_time',
			'user_id' 
	);
	
	// 统计数据
	function popdata($time_step = 'day', $date = null, $pop_type = '', $pop_id = '') {
		$tableName = PopLogModel::tableName ( $date );
		var_dump ( $time_step );
		if (! empty ( $time_step )) {
			$time_step_group = ',' . $this->group_fields [$time_step];
			$time_step_field = $time_step_group . ' time';
			$time_f = $this->time_f [$time_step];
			$time_l = $this->time_l [$time_step];
		}
		$sql = "
		/*最后返回的数据是统计数据和弹窗的ID,标题,类型*/
		select t.*,type,if(type='1' , s.title , c.title) title,
				   if(type='1' , s.country , c.country) country,
				   if(type='1' , s.start_time , c.start_time) start_time,
				   if(type='1' , s.end_time , c.end_time) end_time,
				   if(type='1' , s.oemid , c.oemid) oemid,
				   if(type='1' , s.user_id , c.user_id) user_id
		from (
			/* 统计数据包括用户数,弹出次数,弹出成功次数,点击次数 */
			select type,pop_id,count(distinct(client_id)) client_count,sum(action='pop') pop_count,
			sum(action='success') success_count,sum(action='click') click_count {$time_step_field} 
			/* 添加where条件 */
			from {$tableName}
			/* 按类型,弹窗ID,时间段,来给数据分组 */
			 group by type,pop_id {$time_step_group}
		) t 
		/* 先查出统计数据让记录条数变少,再联表查弹窗数据 */
		left join std_pop s on t.pop_id=s.id
		left join content_pop c on t.pop_id=c.id
		/* 把标准弹窗显示在上面,内容弹窗在下面,然后按ID排序 */
		order by type asc,pop_id asc;";
		//echo $sql;
		$data = $this->query ( $sql );
		foreach ( $data as &$v ) {
			$v ['start'] = $v ['time'] . $time_f;
			$v ['end'] = $v ['time'] . $time_l;
		}
		
		//if(empty($data))
		//	var_dump($model->getDbError());
		return $data;
	}
	
	// 按日期刷新统计数据
	function flushStat($date) {
		foreach ( static::$time_step as $k => $v ) {
			$data = $this->popdata ( $k, $date );
			//var_dump($data);
			if (empty ( $data ))
				continue;
			$this->replaceMany ( $data, $k );
		}
	}
	
	// 一次插入多条
	function replaceMany($data, $time_step) {
		$fields = implode ( ',', $this->fields );
		$values = array ();
		foreach ( $data as &$row ) {
			$row ['time_step'] = $time_step;
			$row ['up_time'] = date ( 'Y-m-d H:i:s' );
			$row ['start_time'] = $row ['start'];
			$row ['end_time'] = $row ['end'];
			foreach ( $this->fields as $v ) {
				$tmp_array [$v] = addslashes ( $row [$v] );
			}
			$tmp = '"' . implode ( '","', array_values ( $tmp_array ) ) . '"';
			$values [] = "({$tmp})";
		}
		$sql_value = implode ( ',', $values );
		$sql = "replace into stat ({$fields})values{$sql_value}";
		echo $sql, "\n";
		$this->query ( $sql );
	}
}