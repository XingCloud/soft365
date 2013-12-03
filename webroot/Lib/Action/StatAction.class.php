<?php
class StatAction extends Action {
	
	//
	function __construct() {
		parent::__construct ();
		header ( 'content-type:text/html;charset=utf-8' );
	}
	
	//
	function index() {
		$model = new StatModel ();
		// 开始时间
		$start = $_GET ['start'];
		if (empty ( $start ))
			$start = $_GET ['start'] = date ( 'Y-m-d' ) . ' 00:00:00';
			// 结束时间
		$end = $_GET ['end'];
		if (empty ( $end ))
			$end = $_GET ['end'] = date ( 'Y-m-d' ) . ' 23:59:59';
			// 时间分段
		$time_step = addslashes ( $_GET ['time_step'] );
		// 按时间段，如果没有选择，先按天查，再加起来,所以最后的数据误差为+-10分钟
		if (empty ( $time_step ) || empty ( StatModel::$time_step [$time_step] )) {
			$view_total = true;
			$time_step = "day";
		}
		// 查询数据
		$where = "(start_time between '{$start}' and '{$end}') and time_step='{$time_step}'";
		if(!UserAction::is_admin())
			$where .= " and user_id=".$_SESSION['user_id'];
		$model = new StatModel ();
		$data = $model->where ( $where )->order ( 'type asc,pop_id asc' )->select ();
		// 如果要看总数
		if ($view_total) {
			// 需要求和的字段
			$add_fields = array (
					'client_count',
					'pop_count',
					'success_count',
					'click_count' 
			);
			$re = array ('1'=>array(),'2'=>array());
			foreach ( $data as $v ) {
				// 按ID分类
				if (empty ( $re [$v ['type']] [$v ['pop_id']] )) {
					// 把数据加入列表
					$re [$v ['type']] [$v ['pop_id']] = $v;
					// 添加开始和结束时间
					$model = $v['type']==1 ? M('std_pop') : M('content_pop');
					$pop = $model->where('id='.$v['pop_id'])->select();
					$pop = current($pop);
					$re [$v ['type']] [$v ['pop_id']]['pop_start_time'] = $pop['start_time'];
					$re [$v ['type']] [$v ['pop_id']]['pop_end_time'] = $pop['end_time'];
				} else {
					// 求和
					foreach ( $add_fields as $field )
						$re [$v ['type']] [$v ['pop_id']] [$field] += $v [$field];
				}
			}
			$data = array_merge ( $re [1], $re [2] );
			//var_dump($re);
		}
		// 数据表现处理
		foreach ( $data as &$v ) {
			// 需求：CTR项（点击次数/弹窗成功数）
			$v ['ctr'] = $v ['click_count'] / $v ['success_count'] * 100;
			$v ['ctr'] = number_format ( $v ['ctr'], 2, '.', '' ) . '%';
			
			$v ['type'] = $v ['type'] == '1' ? '标准弹窗' : '内容弹窗';
			if (empty ( $v ['lang'] ))
				$v ['lang'] = '不限';
			if (empty ( $v ['country'] ))
				$v ['country'] = '全部';
			if (empty ( $v ['max_people'] ))
				$v ['max_people'] = '不限';
			if (empty ( $v ['title'] ))
				$v ['title'] = '默认';
		}
		$this->assign ( 'get', $_GET );
		$this->assign ( 'data', $data );
		//var_dump($data);
		//$this->assign ( 'total', $model->popdataTotal ( $start, $end ) );
		$this->assign ( 'time_step', StatModel::$time_step );
		$this->display ();
	}
}
