<?php
//
class OemidAction extends CountryAction {
	public $model;
	
	// 要处理的字段列表
	protected $fields = array (
			'name',
			'user_id',
			'desc' 
	);
	
	//
	function __construct() {
		parent::__construct ();
		$this->model = new OemidModel ();
	}
	
	// 列表
	public function index() {
		import ( "ORG.Util.Page" );
		$order = "id desc";
		$totalRows = $this->model->count ();
		$page = new Page ( $totalRows );
		$data = $this->model->order ( $order )->limit ( $page->firstRow . "," . $page->listRows )->select ();
		//
		$users_tmp = M ( 'user' )->select ();
		foreach ( $users_tmp as $v )
			$users [$v ['id']] = $v;
		foreach ( $data as &$obj ) {
			$obj ['user'] = $users [$obj ['user_id']] ['name'];
		}
		$this->assign ( 'data', $data );
		$pagination = $page->show ();
		$this->assign ( "pagination", $pagination );
		$this->display ();
	}
	
	// 添加
	public function add() {
		$users = M ( 'user' )->select ();
		$this->assign ( 'user', $users );
		$this->display ( "edit" );
	}
	
	// 编辑
	public function edit() {
		$users = M ( 'user' )->select ();
		$this->assign ( 'user', $users );
		#
		$id = $_REQUEST ["id"];
		$oemid = $this->model->where ( "id={$id}" )->find ();
		$this->assign ( "oemid", $oemid );
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
			$data ['name'] = strtolower($data ['name']);
			if ($id = $_POST ['id']) { // 修改
				$data ['id'] = $_POST ['id'];
				$this->model->save ( $data );
			} else { // 添加
				$isset = $this->model->where ( "name='{$data['name']}'" )->select ();
				if (! empty ( $isset ))
					$this->error ( "Oemid已经存在！" );
				$this->model->add ( $data );
			}
			
			// 保存成功
			if (! $error = $this->model->getDbError ()) {
				// 刷新redis数据
				OemidUserRedisModel::refresh();
				$this->success ( "success", "index" );
			} else // 如果执行中出错
				$this->error ( $error );
		} else
			$this->error ( $this->model->getError () );
	}
	
	
	// 删除
	public function destroy() {
		$id = $_REQUEST [id];
		$this->model->where ( "id = '$id'" )->delete ();
		redirect ( "index" );
	}
}

?>