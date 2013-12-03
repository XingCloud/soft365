<?php
//管理员控制器，从原来的apps.soft365.com中拿过来，没改过
class UserManageAction extends Action {
	
	// 对应的mysql操作模型
	protected $model;
	
	// 对应的redis操作模型
	protected $redisModel;
	
	// 要处理的字段列表
	protected $fields = array (
			'name',
			'desc',
			'email',
			'status' 
	);
	
	//
	function __construct() {
		parent::__construct ();
		$this->model = new UserModel ();
		$this->assign ( 'status', UserModel::$status );
	}
	
	// 列表
	public function index() {
		import ( "ORG.Util.Page" );
		$order = "id desc";
		$totalRows = $this->model->count ();
		$page = new Page ( $totalRows );
		$data = $this->model->order ( $order )->limit ( $page->firstRow . "," . $page->listRows )->select ();
		$oem_model = new OemidModel ();
		$oems = $oem_model->select ();
		foreach ( $oems as $v )
			$user_oems [$v ['user_id']] [] = $v ['name'];
		foreach ( $data as &$v ) {
			$v ['oemid'] = implode ( ',', $user_oems [$v ['id']] );
		}
		// dump($this->model->getLastSql());
		$pagination = $page->show ();
		$this->assign ( "data", $data );
		$this->assign ( "pagination", $pagination );
		$this->display ();
	}
	
	// 添加
	public function add() {
		$pri = (C ( 'PRIVILEGE' ));
		$this->assign ( 'privilege', $pri );
		$this->display ( "edit" );
	}
	
	// 编辑
	public function edit() {
		$id = $_REQUEST ["id"];
		$user = $this->model->where ( "id=$id" )->find ();
		$user ['privilege'] = explode ( ',', $user ['privilege'] );
		$user ['password'] = '';
		$this->assign ( "user", $user );
		$pri = (C ( 'PRIVILEGE' ));
		$this->assign ( 'privilege', $pri );
		$this->display ();
	}
	
	// 提交
	function save() {
		// 准备数据
		$data = array ();
		foreach ( $this->fields as $v )
			$data [$v] = $_POST [$v];
			// 检查表单
		if ($this->model->create ()) {
			//权限
			$data ['privilege'] = implode ( ',', $_POST ['privilege'] );
			$data ['privilege'] = $data ['privilege'] ? $data ['privilege'] : '';
			
			if ($id = $_POST ['id']) { // 修改
				$data ['id'] = $_POST ['id'];
				if ($_POST ['password'])
					$data ['password'] = md5 ( $_POST ['password'] );
				$this->model->save ( $data );
			} else { // 添加
				$isset = $this->model->where ( "name='{$data['name']}'" )->select ();
				if (! empty ( $isset ))
					$this->error ( "用户名已经存在！" );
				$data ['user_id'] = $_SESSION ['user_id'];
				$data ['add_time'] = time ();
				$data ['add_user'] = $_SESSION ['user_id'];
				$data ['add_time'] = date ( 'Y-m-d H:i:s' );
				$data ['password'] = md5 ( $_POST ['password'] );
				$data ['id'] = $this->model->add ( $data );
			}
			
			// 保存成功
			if (! $error = $this->model->getDbError ()) {
				$this->success ( "success", "index" );
			} else // 如果执行中出错
				$this->error ( $error );
		} else
			$this->error ( $this->model->getError () );
	}
}