<?php
/**
 * validate request params, including pname, cname
 * @author wangqi
 *
 */
class LoginBehavior extends Behavior {
	
	// 行为扩展的执行入口必须是run
	public function run(&$params) {
		$excepts = array (
				'User.login',
				'Pop.get',
				'Pop.success',
				'Pop.config',
				'Pop.get2',
				'Pop.success2',
				'Pop.config2',
				'Op.mysqlInfo',
				'Op.redisInfo',
		);
		$action = $_GET ['_URL_'] [0];
		$method = $_GET ['_URL_'] [1];
		$actionStr = $action . '.' . $method;
		if ($actionStr === 'User.login') {
			session ( C ( 'SESSION_OPTIONS' ) );
			session ( '[start]' );
		}
		// 如果访问的地址需要登陆
		if (! in_array ( $actionStr, $excepts )) {
			//检查维护状态
			if (MAINTAIN_STATUS) {
				if ($actionStr !== 'Index.maintain') {
					redirect ( '/Index/maintain' );
				}
				return false;
			}
			session ( C ( 'SESSION_OPTIONS' ) );
			session ( '[start]' );
			//如果已经登录了，直接返回
			if (isset ( $_SESSION ['user_id'] )) {
				return true;
			} else { //如果没有登录，跳转到登录页面
				redirect ( '/User/login' );
				exit ();
			}
		}
	}
}