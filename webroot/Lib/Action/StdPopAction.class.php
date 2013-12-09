<?php
//
class StdPopAction extends Action {
	
	// 对应的mysql操作模型
	protected $popModel;
	
	// 对应的redis操作模型
	protected $redisModel;

    protected $tagModel;
	
	// 要处理的字段列表
	protected $fields = array (
			'title',
			'url',
			'start_date',
			'end_date',
			'width',
			'height',
			'live_time',
			'max_times',
			'start_time',
			'end_time',
			'weight',
			'description',
			'country',
			'lang',
			'max_people',
			'disabled',
			'oemid' ,
            'tags' ,
            'force'
	);
	
	//
	function __construct() {
		parent::__construct ();
		$this->popModel = new StdPopModel ();
		$this->redisModel = new StdPopRedisModel ();
		$this->assign ( 'disabled', StdPopModel::$disabled );
        $this->assign ( 'force', StdPopModel::$force);
	}
	
	// 列表
	public function index() {
		$where = '1 ';
		if (! UserAction::is_admin ())
			$where .= 'and user_id=' . $_SESSION ['user_id'];
			//echo $where;
			//
		import ( "ORG.Util.Page" );
		$order = "id desc";
		$totalRows = $this->popModel->where ( $where )->count ();
		$page = new Page ( $totalRows );
		$data = $this->popModel->where ( $where )->order ( $order )->limit ( $page->firstRow . "," . $page->listRows )->select ();
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
		// dump($this->model->getLastSql());
		$pagination = $page->show ();
		$this->assign ( "data", $data );
		$this->assign ( "pagination", $pagination );
		$this->display ();
	}
	
	// 添加
	public function add() {
		if ($id = intval ( $_GET ['copy_from_id'] )) {
			$pop = $this->popModel->where ( "id=$id" )->find ();
			unset ( $pop ['id'] );
		} else {
			$pop = array (
					'weight' => 10,
					'live_time' => 0,
					'max_people' => 0,
					'max_times' => 1,
					'start_time' => '00:00',
					'end_time' => '23:59' 
			);
		}
		$this->assign ( 'pop', $pop );
		$this->assign ( 'countrys', D ( 'Country' )->order ( 'index_no asc' )->select () );
		$this->assign ( 'langs', D ( 'Lang' )->select () );
		$where = UserAction::is_admin () ? '1' : 'user_id=' . $_SESSION ['user_id'];
		$this->assign ( 'oemids', D ( 'Oemid' )->where ( $where )->select () );
		$this->display ( "edit" );
	}
	
	// 编辑
	public function edit() {
		$id = $_REQUEST ["id"];
		$pop = $this->popModel->where ( "id=$id" )->find ();
		$this->assign ( 'countrys', D ( 'Country' )->order ( 'index_no asc' )->select () );
		$this->assign ( 'langs', D ( 'Lang' )->select () );
		$where = UserAction::is_admin () ? '1' : 'user_id=' . $_SESSION ['user_id'];
		$this->assign ( 'oemids', D ( 'Oemid' )->where ( $where )->select () );
		$this->assign ( "pop", $pop );
		$this->display ();
	}
	
	// 提交
	function save() {
		// 准备数据
		$data = array ();
		foreach ( $this->fields as $v ) {
			$data [$v] = $_POST [$v];
		}

		// 检查表单
		if ($this->popModel->create ()) {
			$is_admin = UserAction::is_admin ();
			// 校验日期和时间
			if ($data ['start_date'] > $data ['end_date'])
				$this->error ( '开始日期不能晚于结束日期！' );
			if ($data ['start_time'] > $data ['end_time']) {
				$this->error ( '开始时间不能晚于结束时间！' );
			}
			// oemid可以留空，但除了管理员，都只能添加自己的oemid
			if (! $is_admin && $data ['oemid'])
				if (OemidUserRedisModel::hget ( $data ['oemid'] ) != $_SESSION ['user_id'])
					$this->error ( 'oemid非法！' );
			// 如果是超级管理员添加的, 则需要把管理员设置成oemid所属的管理员，这样弹窗才能生效
			if($is_admin && $data['oemid']){
				// 查找oemid所属的管理员
				$user_id = OemidUserRedisModel::hget($data['oemid']);
				if($user_id)
					$data['user_id'] = $user_id;
				else
				{
					// 如果没有oemid所属的管理员，则管理员设置成超级管理员
					$data ['user_id'] = $_SESSION ['user_id'];
				}
			}
			else{
				// 如果是普通用户，或oemid为空，则管理员为当前用户
				$data ['user_id'] = $_SESSION ['user_id'];
			}
			// 修改	
			if ($id = $_POST ['id']) {
				// 除了管理员，都只能修改自己的消息
				if (! $is_admin)
					if (! $this->popModel->where ( "id={$id} and user_id={$_SESSION['user_id']}" )->count ())
						$this->error ( '操作非法！' );
				$data ['id'] = $_POST ['id'];
				$data ['up_time'] = time ();
				$this->popModel->save ( $data );

                // 新建 tag表


			} else { // 添加
				$data ['add_time'] = time ();
				$data ['id'] = $this->popModel->add ( $data );
			}
			
			// 保存成功
			if (! $error = $this->popModel->getDbError ()) {
                $data['tags'] = explode(',',$data['tags']);
				$this->saveRedis ( $data );
				$this->success ( "success", "index" );
			} else // 如果执行中出错
				$this->error ( $error );
		} else
			$this->error ( $this->popModel->getError () );
	}
	
	// 保存redis
	function saveRedis($data) {
		$date = date ( 'Y-m-d' );
		// 把没用的删掉，[没开始的],[过期的]
		if ($data ['start_date'] > $date || $data ['end_date'] < $date)
			$this->redisModel->hdel ( $data ['id'] );
		else {
			// 否则保存
			$this->redisModel->save ( $data );
		}
		// 刷新弹窗顺序列表,这个和下面那个，开一个就行
		//PopSortByWeightRedisModel::refresh();
		// 刷新弹窗数据和弹窗顺序列表
		RefreshPopModel::doRefresh ();
	}
	
	// 删除
	public function destroy() {
		$id = $_REQUEST [id];
		$this->popModel->where ( "id = '$id'" )->delete ();
		$this->redisModel->hdel ( $id );
		redirect ( "index" );
	}
}

?>