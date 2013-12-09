<?php
//管理员控制器，从原来的apps.soft365.com中拿过来，没改过
class UserAction extends Action {
	public function login() {
		if (isset ( $_POST ['username'] ) && isset ( $_POST ['password'] )) {
			//check user login info
			$userModel = new UserModel ();
			$user = $userModel->where ( "name='{$_POST['username']}' and password='" . md5 ( $_POST ['password'] ) . "'" )->find ();
			if (! empty ( $user )) {
				if ($user ['status'] != 1)
					$this->error ( '您的账号已停用！' );
				$user['last_login'] = date('Y-m-d H:i:s');
				$userModel->save($user);
				$_SESSION ['user_id'] = intval ( $user ['id'] );
				$_SESSION ['user_name'] = $user ['name'];
				$_SESSION ['privilege'] = explode ( ',', $user ['privilege'] );
				$_SESSION ['user'] = $user; 
				redirect ( '/' );
			}
		}
		$this->display ();
	}
	
	public function logout() {
		session_destroy ();
		redirect ( '/User/login' );
	}
	public function view() {
		$userModel = new UserModel ();
		$user = $userModel->where ( "id={$_SESSION['user_id']}" )->find ();
		$this->assign ( 'user', $user );
		$this->display ();
	}
	public function edit() {
		$userModel = new UserModel ();
		$user = $userModel->where ( "id={$_SESSION['user_id']}" )->find ();
		$this->assign ( 'user', $user );
		$this->display ();
	}
	public function change() {
		$userModel = new UserModel ();
		$user = $userModel->where ( "id={$_SESSION['user_id']}" )->find ();
		$this->assign ( 'user', $user );
		$this->display ();
	}
	public function set() {
		$email = $_POST ['email'];
		$uid = $_SESSION ['user_id'];
		
		$userModel = new UserModel ();
		if (! $userModel->check ( $email, 'email' )) {
			$this->error ( 'email格式错误！' );
		}
		$user ['email'] = $email;
		$user ['last_update'] = $_SERVER ['REQUEST_TIME'];
		try {
			$ret = $userModel->where ( "id=$uid" )->save ( $user );
			$this->success ( 'ok！' );
		} catch ( Exception $e ) {
			$this->error ( '操作失败！' );
		}
	}
	public function setPw() {
		$password = $_POST ['old_assword'];
		$newPassword = $_POST ['new_password'];
		$uid = $_SESSION ['user_id'];
		if(strlen($newPassword)<4)
		$this->error ( '新密码不可以少于4位！' );
		if(empty($newPassword))
			$this->error ( '密码不可以为空！' );
		if($_POST ['new_password']!=$_POST ['new_password_confirm'])
			$this->error ( '两次输入的新密码不一致！' );
		$userModel = new UserModel ();
		$user = $userModel->where ( "id={$uid} and password='" . md5 ( $password ) . "'" )->find ();
		
		if (! $user) {
			$this->error ( '原密码错误！' );
		}
		$user ['password'] = md5 ( $newPassword );
		$user ['last_update'] = $_SERVER ['REQUEST_TIME'];
		try {
			$ret = $userModel->where ( "id=$uid" )->save ( $user );
			$this->success ( 'ok！' );
		} catch ( Exception $e ) {
			$this->error ( '操作失败！' );
		}
	}
	
	static public function is_admin(){
		return $_SESSION['user']['is_admin']=='1';
	}
}
