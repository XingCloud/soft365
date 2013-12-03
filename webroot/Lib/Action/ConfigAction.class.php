<?php
class ConfigAction extends Action {
	
	// 显示
	function edit() { //die('ff');
		if (empty ( $_POST )) {
			$this->assign ( 'data', ConfigRedisModel::getByUserId ( $_SESSION ['user_id'] ) );
			$this->assign ( 'fields', ConfigRedisModel::$fields );
			$this->assign ( 'title', '全局配置' );
			$this->display ();
		} else
			$this->save ();
	}
	
	// 保存
	function save() {
		// 校验是数字
		foreach ( ConfigRedisModel::$fields as $k => $v ) {
			if (! preg_match ( '/^\d+$/', $_POST [$k] ))
				$this->error ( $v . '必须是数字' );
		}
		
		// 校验不能小于3分钟的字段
		$keys = [ 
				ConfigRedisModel::pop_space_time,
				//ConfigRedisModel::client_request_space_time,
				ConfigRedisModel::first_pop_space_time 
		];
		foreach ( $keys as $v ) {
			if (APP_DEBUG === false && (intval ( $_POST [$v] ) < 4))
				$this->error ( ConfigRedisModel::$fields [$v] . '不可以小于4分钟' );
		}
		
		// 保存
		foreach ( ConfigRedisModel::$fields as $k => $v )
			$data [$k] = $_POST [$k];
			// 保存用户数据
		ConfigRedisModel::save ( $_SESSION ['user_id'], $data );
		$this->success ( 'success', 'edit' );
	}
	
	// 显示系统配置
	function editSys() {
		if (empty ( $_POST )) {
			
			$data [ConfigRedisModel::client_request_space_time] = ConfigRedisModel::client_request_space_time();
			$this->assign ( 'data', $data );
			$fields = array (
					ConfigRedisModel::client_request_space_time => '客户端请求间隔时间(分钟)' 
			);
			$this->assign ( 'fields', $fields );
			$this->assign ( 'title', '系统设置' );
			$this->display ( 'edit' );
		} else
			$this->saveSys ();
	}
	
	//保存系统设置
	function saveSys() {
		// 校验是数字
		foreach ( ConfigRedisModel::$sysFields as $k => $v ) {
			if (! preg_match ( '/^\d+$/', $_POST [$k] ))
				$this->error ( $v . '必须是数字' );
		}
		
		// 校验不能小于3分钟的字段
		$keys = [ 
				ConfigRedisModel::client_request_space_time 
		];
		foreach ( $keys as $v ) {
			if (APP_DEBUG === false && (intval ( $_POST [$v] ) < 4))
				$this->error ( ConfigRedisModel::$fields [$v] . '不可以小于4分钟' );
		}
		
		// 保存
		foreach ( ConfigRedisModel::$sysFields as $k => $v )
			$data [$k] = $_POST [$k];
			// 保存用户数据
		ConfigRedisModel::saveSys ( $data );
		$this->success ( 'success', 'editSys' );
	}
	
	// 显示弹窗列表
	function popList() {
		$this->assign ( 'type', array (
				StdPopRedisModel::type => '标准弹窗',
				ContentPopRedisModel::type => '内容弹窗' 
		) );
		$this->assign ( 'edit_link', array (
				StdPopRedisModel::type => '/StdPop/edit?id=',
				ContentPopRedisModel::type => '/ContentPop/edit?id=' 
		) );
		$user_id = UserAction::is_admin () ? null : $_SESSION ['user_id'];
		//
		if ($date = $_GET ['date']) {
			$std = new StdPopModel ();
			$content = new ContentPopModel ();
			$std_pop = $std->getByDate ( $date, $user_id );
			$content_pop = $content->getByDate ( $date, $user_id );
			$data = PopSortByWeightRedisModel::getSortList ( $std_pop, $content_pop );
			$this->assign ( 'date', $date );
			$this->assign ( 'date_value', $date );
		} else {
			if ($user_id)
				$data = PopSortByWeightRedisModel::getByUserId ( $user_id );
			else
				$data = PopSortByWeightRedisModel::get ();
			$this->assign ( 'date', '今天' );
		}
		foreach ( $data as &$v ) {
			if (empty ( $v ['lang'] ))
				$v ['lang'] = '不限';
			if (empty ( $v ['country'] ))
				$v ['country'] = '全部';
			if (empty ( $v ['max_people'] ))
				$v ['max_people'] = '不限';
			if (empty ( $v ['title'] ))
				$v ['title'] = '默认';
		}
		$this->assign ( 'data', $data );
		$this->display ();
	}
}