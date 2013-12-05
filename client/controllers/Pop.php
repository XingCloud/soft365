<?php

namespace Controller;

//弹窗行为控制器
class Pop extends \Yaf\Controller_Abstract {
	//用户操作模型
	protected $clientR;
	
	//设置编码,yaf会自动调这个方法
	function init() {
		$this->initClient ();
		header ( "content-type:text/html;charset=utf-8;" );
	}
	
	// 初始化用户
	function initClient() {
		$client_id = $_GET ['userid'];
		// userid是取机器上的唯一码的md5值,所以不会有特殊字符
		if (empty ( $client_id ) || ! preg_match ( '/^[a-zA-Z0-9]{1,64}$/', $client_id ))
			die ( 'invaild userid' );
		$this->clientR = new \redis\Client ( $client_id );
	}
	
	// 检查并自动新建用户
	function checkClient() {
		// 测试用的方法
		$country = $_GET ['country'];
		// 如果用户首次来，记录进mysql，
		// $country由此方法中传回，可以减少获取IP的次数，获取IP很耗时间
		if (! $this->clientR->checkClient ( $_SERVER ['REMOTE_ADDR'], $country )) {
			$data ['client_id'] = strval ( $this->clientR->client_id );
			$data ['country'] = strval ( $country );
			$data ['lang'] = strval ( $_GET ['language'] );
			$data ['add_time'] = time ();
			//把数据插入到临时列表中
			\redis\TmpClient::rpush ( $data );
		}
	}
	
	// 用户获取配置文件
	public function configAction() {
		$this->checkClient ();
		$user_id = $this->getUserId ();
		$conf = \redis\Config::hget ( $user_id );
		$array = array (
				// 客户端请求间隔时间(分钟)
				'tminterva' => \redis\Config::hget ( \redis\Config::client_request_space_time ),
				// 开机到首次弹窗间隔时间
				'tmstart ' => $conf [\redis\Config::first_pop_space_time] 
		);
		echo json_encode ( $array );
	}
	
	// 用户反馈点击数据
	public function clickAction() {
		// 用户是否存在
		if (! $this->clientR->exists ())
			die ( '0_3 invaid user' );
			// 弹窗类型
		if ($_GET ['type'] == '1') // 返回值'1'表示标准弹窗
			$type = \redis\StdPop::type;
		elseif ($_GET ['type'] == '2') // 返回值'2'表示内容弹窗
			$type = \redis\ContentPop::type;
		else { // 其他认为出错
			die ( '0_1 invaid type' );
		}
		// 返回成功状态的弹窗ID
		$pop_id = intval ( $_GET ['notifyid'] );
		if (empty ( $pop_id )) {
			die ( '0_2 invaid notifyid' );
		}

        //更新redis里的client tags信息
        $client_tags = $this->clientR->hget(\redis\Client::tags);
        $pops = \redis\PopSortByWeight::get ();
        foreach ($pops as $pop) {
            $pop_id_tmp = $pop['id'];
            if ($pop_id == $pop_id_tmp) {
                // 找到匹配id,更新tags的信息
                $pop_tags = $pop['tags'];
                foreach($pop_tags as $tag) {
                    $counter = $client_tags[$tag];
                    if ($counter == null) {
                        $counter = 0;
                    }
                    $client_tags[$tag] = ++$counter;
                }
            }
        }
        $this->clientR->hset(\redis\Client::tags, $client_tags);

		// 记录日志
        // redis日志
        $log = array (
            'time' => date ( 'Y-m-d H:i:s' ),
            'client_id' => $this->clientR->client_id,
            'type' => $_GET ['type'],
            'pop_id' => $_GET ['notifyid'],
            'action' => 'click'
        );
        \redis\PopLog::rpush ( $log );

		// 数据正常，记一个文本log
		// $log = "时间,用户ID,弹窗类型,弹窗内容ID,是否增加过绑定关系\n";
		$log = date ( 'Y-m-d H:i:s' ) . ",{$this->clientR->client_id},{$type},{$pop_id}\n";
		file_put_contents ( '../log/click/' . date ( 'Y-m-d' ) . '.log', $log, FILE_APPEND );
		
		echo '1';
	}
	
	// 用户反馈弹窗成功
	public function successAction() {
		// 用户是否存在 
		if (! $this->clientR->exists ())
			die ( '0_3 invaid user' );
			// 弹窗类型
		if ($_GET ['type'] == '1') // 返回值'1'表示标准弹窗
			$type = \redis\StdPop::type;
		elseif ($_GET ['type'] == '2') // 返回值'2'表示内容弹窗
			$type = \redis\ContentPop::type;
		else { // 其他认为出错
			die ( '0_1 invaid type' );
		}
		// 返回成功状态的弹窗ID
		$pop_id = intval ( $_GET ['notifyid'] );
		if (empty ( $pop_id )) {
			die ( '0_2 invaid notifyid' );
		}
		//记录数据
		$popAddPeople = false;
		if (! $this->clientR->popSuccess ( $type, $pop_id, $popAddPeople )) {
			die ( '0_4 add poped times failed' );
		}
		
		// 数据正常，记一个文本log
		$hasBind = $popAddPeople ? '1' : '0';

        // redis日志
        $log = array (
            'time' => date ( 'Y-m-d H:i:s' ),
            'client_id' => $this->clientR->client_id,
            'type' => $_GET ['type'],
            'pop_id' => $_GET ['notifyid'],
            'has_bind' => $hasBind,
            'action' => 'success'
        );
        \redis\PopLog::rpush ( $log );

		// $log = "时间,用户ID,弹窗类型,弹窗内容ID,是否增加过绑定关系\n";
		$log = date ( 'Y-m-d H:i:s' ) . ",{$this->clientR->client_id},{$type},{$pop_id},{$hasBind}\n";
		file_put_contents ( '../log/success/' . date ( 'Y-m-d' ) . '.log', $log, FILE_APPEND );
		
		echo '1';
	}
	
	// 用户获取弹窗消息内容
	public function getAction() {
		$this->checkClient ();
		// 用户 
		$user_id = $this->getUserId ();
		// 取得弹窗内容
		$request = array (
				\redis\StdPop::lang => $_GET ['language'],
				\redis\StdPop::oemid => $_GET ['product'],
				\redis\StdPop::user_id => $user_id 
		);
		$pop = $this->clientR->nowPop ( $request );
		if (empty ( $pop )) {
			die ();
		}
		// 把所有的数据转成字符串
		foreach ( $pop as $k => $v ) {
			$pop [$k] = strval ( $v );
		}
		
		// 记录日志
        // redis日志
        $log = array (
            'time' => date ( 'Y-m-d H:i:s' ),
            'client_id' => $this->clientR->client_id,
            'type' => $pop ['type'] == \redis\StdPop::type ? 1 : 2,
            'pop_id' => $pop ['id'],
            'action' => 'pop'
        );
        \redis\PopLog::rpush ( $log );

		// $log = "时间,用户ID,弹窗类型,弹窗内容ID,\n";
		$log = date ( 'Y-m-d H:i:s' ) . ",{$this->clientR->client_id},{$pop['type']},{$pop['id']}\n";
		file_put_contents ( '../log/pop/' . date ( 'Y-m-d' ) . '.log', $log, FILE_APPEND );
		// 执行弹窗
		if ($pop ['type'] == \redis\StdPop::type) {
			// 标准弹窗
			$this->stdPop ( $pop );
		} else {
			// 内容弹窗
			$this->ContentPop ( $pop );
		}
	}
	
	// 获取userid,如果管理员把oemid设置成“所有”,则必须按user_id比较
	protected function getUserId() {
		// 根据oemid获取user_id
		if ($oemid = strtolower ( trim ( $_GET ['product'] ) )) {
			$user_id = \redis\OemidUser::hget ( $oemid );
		}
		// 如果没有user_id，把它丢给系统管理员（没有oemid的用户认为是我们自己的）
		if (empty ( $user_id ))
			$user_id = '1';
		return $user_id;
	}
	
	# 标准弹窗：
	# 标准弹窗模板,由薛晴提供
	# <content>
	# 	<title>标题</title>
	# 	<url>链接地址</url>
	# 	<width>弹窗宽度</width>
	# 	<height>弹窗高度</height>
	# 	<closet>弹窗自动关闭时间，0为不自动关闭</closet>
	# </content>
	protected function stdPop($pop) {
		// 格式化数据
		$result = array (
				'type' => '1',
				'notifyid' => $pop ['id'],
				'content' => array (
						'title' => $pop ['title'],
						'url' => $pop ['url'],
						// xueqing(薛晴) 2013-09-02 10:33:12 ：高度减去30，宽度减去6
						// 为了适应客户端
						'width' => strval ( intval ( $pop ['width'] ) + 6 ),
						'height' => strval ( intval ( $pop ['height'] ) + 30 ),
						'closet' => $pop ['live_time'] 
				) 
		);
		echo json_encode ( $result );
	}
	
	# 内容弹窗：
	# 内容弹窗模板,由薛晴提供
	# <content>
	#    <subtype>内容弹窗分类</subtype>
	#    <style>见下文分类,直接补起来,不需要style节点</style>
	# </content>
	# subtype 为 1：推广1
	#
	# <style>
	# 	<title>标题</title>
	# 	<content>内容</content>
	# 	<id>应用的id</id>
	# 	<name>应用名称</name>
	# 	<closet>弹窗自动关闭时间，0为不自动关闭</closet>
	# </style>
	# subtype 为 2：推广2
	#
	# <style>
	# 	<content>内容</content>
	# 	<id>应用的id</id>
	# 	<name>应用名称</name>
	# 	<closet>弹窗自动关闭时间，0为不自动关闭</closet>
	# </style>
	protected function ContentPop($pop) {
		// 格式化数据
		$result = array (
				'type' => '2',
				'notifyid' => $pop ['id'],
				'apptype' => $pop ['app_type'],
				'content' => array (
						'subtype' => $pop ['sub_type'],
						'title' => $pop ['title'],
						'content' => $pop ['text'],
						'id' => $pop ['app_id'],
						'name' => $pop ['app_name'],
						'closet' => $pop ['live_time'] 
				) 
		);
		echo json_encode ( $result );
	}
}
