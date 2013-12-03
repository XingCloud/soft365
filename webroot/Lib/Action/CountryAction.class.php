<?php
//
class CountryAction extends Action {
	
	public $name = '国家';
	
	public $model_name = 'CountryModel';
	
	// 列表
	public function index() {
		$model = new $this->model_name ();
		if (empty ( $_POST )) {
			$data = $model->select ();
			$this->assign ( 'data', $data );
			$this->assign('name',$this->name);
			$this->display ('Country:index');
		} else {
			foreach($_POST as $k => $v)
				$_POST[$k] = addslashes(trim($v));
			if (empty ( $_POST['name'] ))
				$this->error ( $this->name."名称不可以为空！" );
			if ($model->where ( "name='{$_POST['name']}'" )->select ())
				$this->error ( $this->name."名称已存在！" );
			$model->add ( $_POST);
			redirect ( '/'.$_GET['_URL_'][0].'/' );
		}
	}
	
	// 删除
	public function destroy() {
		$model = new $this->model_name ();
		$name = addslashes ( $_REQUEST ['name'] );
		$model->where ( "name = '$name'" )->delete ();
		redirect ( '/'.$_GET['_URL_'][0].'/' );
	}
}

?>