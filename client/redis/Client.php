<?php

namespace redis;

// 用户数据的redis操作模型，Hash结构
class Client extends Base {
	// redis前缀
	const redis_key = 'client_';
	
	// 标准弹窗记录,redis键名
	const std_poped = 'std_poped';
	
	// 内容弹窗记录,redis键名
	const content_poped = 'content_poped';
	
	// 当天标准弹窗的弹出次数,redis键名
	const today_std_pop_times = 'today_std_pop_times';
	
	// 当天内容弹窗的弹出次数,redis键名
	const today_content_pop_times = 'today_content_pop_times';
	
	// 最后一次弹窗时间,redis键名
	const last_pop_time = 'last_pop_time';
	
	// 用户状态,redis键名
	const status = 'status';
	
	// 数据所属的日期,redis键名。
	// 所有的数据都是这一天的数据
	const date = 'date';
	
	// 弹出次数
	const poped_times = 'poped_times';
	
	// 读取次数
	const read_times = 'read_times';
	
	// 国家
	const country = 'country';
	
	// 已绑定
	const binded = 'binded';
	
	// 用户ID
	public $client_id = '';
	
	// 用户数据
	protected $data = array ();

    // 标签数据
    const tags = 'tags';
	
	// 创建对象时必须传入用户ID
	function __construct($client_id) {
		$this->client_id = $client_id;
	}
	
	// 取得数据
	function hget($key) {
		if (empty ( $this->data [$key] ))
			$this->data = $this->hgetall ();
		return $this->data [$key];
	}
	
	// 保存数据
	function hset($key, $value) {
		if (self::$redis->hSet ( $this->getKey (), $key, $value ))
			$this->data [$key] = $value;
	}
	
	// 取得键名
	function getKey() {
		return static::redis_key . $this->client_id;
	}
	
	// 检查用户是否存在
	function checkClient($ip, &$country) {
		$re = $this->exists ();
		if (empty ( $re )) {
			if (empty ( $country )) {
				$country = strtolower ( geoip_country_code_by_name ( $ip ) );
			}
			// 如果不存在，初始化用户
			$this->initClient ( $country );
			return false;
		} else {
			// 如果存在，检查是否需要更新日期
			$today = date ( 'Y-m-d' );
			$status = $this->hget ( static::status );
			if ($status ['date'] != $today) {
				$this->refreshClient ();
			}
			return true;
		}
	}
	
	// 刷新用户数据
	// TODO::这个方法有一个风险，如果弹窗数据没有刷新，用户数据刷新了。
	// 这样用户数据在刷新的时候不会把过期的数据删掉。
	// 但没太大的关系，数据不删掉不会影响流程，并且这种情况发生的概率也不大。
	function refreshClient() {
		$today = date ( 'Y-m-d' );
		// 更新用户状态
		$status = $this->hget ( static::status );
		$status [static::date] = $today;
		$status [static::today_std_pop_times] = 0;
		$status [static::today_content_pop_times] = 0;
		$this->hset ( static::status, $status );
		
		// 刷新弹窗记录，把需要绑定用户的弹窗记录保留下来
		$keys = array (
				array (
						'redisModel' => new StdPop (),
						'poped_key' => static::std_poped 
				),
				array (
						'redisModel' => new ContentPop (),
						'poped_key' => static::content_poped 
				) 
		);
		foreach ( $keys as $v ) {
			// 取得用户的弹窗记录
			$old = $this->hget ( $v ['poped_key'] );
			// 取得弹窗内容
			$pops = $v ['redisModel']->hgetall (); //
			// 把需要保留的找出来
			$stay = array ();
			if (! empty ( $old )) {
				foreach ( $old as $k2 => $v2 ) {
					// 如果弹窗记录没有失效，并且要求绑定用户,把绑定关系保留，弹出次数清空
					if (! empty ( $pops [$k2] ) && $pops [$k2] ['max_people']) {
						// 把没用完的读取次数保留下来,防止跨天时success上报不成功
						$v2 [static::read_times] = $v2 [static::read_times] - $v2 [static::poped_times];
						// 清空弹窗次数要放在后面，否则read_times就减不掉了
						$v2 [static::poped_times] = 0;
						// 保留绑定关系，static::bind 字段自动保留
						$stay [$k2] = $v2;
					}
				}
			}
			// 把需要保留的数据再放回redis中                                                k2                   v2
			$this->hset ( $v ['poped_key'], $stay ); //client_uid => {"status"=>{},"std_poped" => {"素材1"=> {read_times=> ,pop_times=> } , "素材2"=> {}}}
		}
	}
	
	// 初始化用户
	function initClient($country) {
		$data = array (
				// 后一次弹窗时间
				static::last_pop_time => 0,
				// 当日日期
				static::date => date ( 'Y-m-d' ),
				// 当日标准弹窗次数
				static::today_std_pop_times => 0,
				// 当日标准弹窗次数
				static::today_content_pop_times => 0,
				// 国家
				static::country => $country 
		);
		$this->hSet ( static::status, $data );
	}
	
	// 弹窗反馈
	function popSuccess($type, $pop_id, &$popAddPeople) {
		// 反馈标准弹窗或内容弹窗的弹出记录
		if ($type == \redis\StdPop::type) {
			$poped_key = static::std_poped;
			$times_key = static::today_std_pop_times;
			$model = new StdPop ();
		} else {
			$poped_key = static::content_poped;
			$times_key = static::today_content_pop_times;
			$model = new ContentPop ();
		}
		
		// 是否是某条记录的第一次弹窗
		$isFirstTime = false;
		// 增加记录次数
		if (! $this->addTimes ( $pop_id, $poped_key, $times_key, $isFirstTime ))
			return false;
			// 让弹窗内容也增加绑定次数
		$model->addTimes ( $pop_id, $isFirstTime, $popAddPeople );
		return true;
	}
	
	// 增加弹出次数
	function addTimes($pop_id, $poped_key, $times_key, &$isFirstTime) {
		// 记录到用户弹窗记录中
		$data = $this->hget ( $poped_key );
		// 校验返回次数是否合法,成功次数不能大于读取次数
		if ($data [$pop_id] [static::poped_times] >= $data [$pop_id] [static::read_times])
			return false;
			// 弹窗次数增加
		$data [$pop_id] [static::poped_times] = intval ( $data [$pop_id] [static::poped_times] ) + 1;
		// 是否第一次弹窗
		if (empty ( $data [$pop_id] [static::binded] )) {
			$isFirstTime = true;
			$data [$pop_id] [static::binded] = 1;
		}
		$this->hset ( $poped_key, $data );
		// 更新用户状态
		$status = $this->hget ( static::status );
		$status [static::last_pop_time] = time ();
		$status [$times_key] = $status [$times_key] + 1;
		$this->hset ( static::status, $status );
		return true;
	}
	
	// 取得现在要弹出的信息
	public function nowPop($request) {
		$user_id = $request [\redis\StdPop::user_id];
		// 检验时间
		$pop_space_time = \redis\Config::pop_space_time ( $user_id ) * 60;
		$status = $this->hget ( static::status );
		// 如果没到弹窗时间，不弹
		if (time () - $status [static::last_pop_time] < $pop_space_time) {
			return false;
		}
		// 如果弹窗总次数已到最大，不弹
		$max_times = \redis\Config::max_pop_times ( $user_id ); //TODO wcl config is global not user specific? 这个user_id是产品的id,为1或者2,见Pop.getUserId
		if ($status [static::today_std_pop_times] + $status [static::today_content_pop_times] >= $max_times)
			return false;
		$pop = $this->getOnePop ( $status, $request );
		return $pop;
	}
	
	// 取得一个可用的弹窗数据
	public function getOnePop($status, $request) {
		$user_id = $request [\redis\StdPop::user_id];
		$pops = \redis\PopSortByWeight::get ();
		if (empty ( $pops )) {
			return false;
		}
		// 取得当前用户的弹窗状态
		$std_poped = $this->hget ( static::std_poped );
		$content_poped = $this->hget ( static::content_poped );
		// 当前时间
		$time = date ( 'H:i:s' );
		// 按权重遍历所有弹窗，碰到没用的往下走，碰到有用的返回
		foreach ( $pops as $v ) {
			// 要搜索的记录
			$poped = ($v ['type'] == \redis\StdPop::type) ? $std_poped : $content_poped;

            // 首先检查是否已经停用了
            if ($v [\redis\StdPop::disabled]) {
                continue;
            }

			// 所属管理员是否匹配
			if ($v [\redis\StdPop::user_id] != $user_id) {
				continue;
			}

			// 如果限制了时间
			if ($v [StdPop::end_time] != '00:00:00') {
				// 检查时间
				if ($time <= $v [StdPop::start_time] || $time >= $v [StdPop::end_time]) {
					continue;
				}
			}
			// 如果设置了oemid。前面已经检查过管理员，所以这里设置成所有的时候，不会出错
			if ($v [StdPop::oemid]) {
				// 检查oemid
				if (strtolower ( $v [StdPop::oemid] ) != strtolower ( $request [StdPop::oemid] )) {
					continue;
				}
			}
			// 如果设置了语言
			if ($v [StdPop::lang]) {
				// 检查语言
				if (strtolower ( $v [StdPop::lang] ) != strtolower ( $request [StdPop::lang] )) {
					continue;
				}
			}
			// 如果设置了国家
			if ($v [StdPop::country]) {
				//检查国家
				if (strtolower ( $v [StdPop::country] ) != strtolower ( $status [static::country] )) {
					continue;
				}
			}
			// 如果设置了弹出次数
			if ($v [StdPop::max_times]) {
				// 检查弹出次数
				if ($v [StdPop::max_times] <= $poped [$v ['id']] [StdPop::poped_times]) {
					continue;
				}
			}
			// 检查人数限制
			if ($v [StdPop::max_people]) {
				// 如果不存在匹配关系
				if (empty ( $poped [$v ['id']] )) {
					// 操作模型
					$redisModel = ($v ['type'] == \redis\StdPop::type) ? new StdPop () : new ContentPop ();
					// 取得本条弹窗数据
					$popStatus = $redisModel->hget ( $v ['id'] );
					// 如果匹配人数已经满了
					if ($popStatus [StdPop::max_people] <= $popStatus [StdPop::people_count])
						continue;
				}
			}

            // 需要判断弹窗广告标签是否符合用户标签
            $canPop = false;
            // 基于用户之前的tags进行判断
            $client_tags = $this->hget(static::tags);
            if ($client_tags == null) {
                $client_tags = array();
            }

            $std_poped_tags = explode (',',$v [StdPop::tags]);
            foreach ($std_poped_tags as $std_poped_tag) {
                $counter = $client_tags[$std_poped_tag];
		    if (!array_key_exists($std_poped_tag, $client_tags)) {
                    // 之前没有点过类似tag的广告，添加tag信息到client info，允许弹
                    $client_tags[$std_poped_tag] = 0;
                    $canPop = true;
                    //更新mysql的tags信息
                    \redis\UidTagQueue::rpush($this->client_id);
                } else if ($counter > 0) {
                    // 之前点过相同tag的广告(有一个命中即可弹窗)
                    $canPop = true;
                }
            }
            //更新tag信息
            $this->hset(static::tags, $client_tags);

            if (!$v [StdPop::force] && !$canPop) {
                // 没有match到符合的tag，不弹窗
                continue;
            }


			// 如果通过了所有的检查,记录读取次数并返回结果
			$poped_key = ($v ['type'] == \redis\StdPop::type) ? static::std_poped : static::content_poped;
			$poped [$v ['id']] [static::read_times] ++;
			$this->hset ( $poped_key, $poped );
			return $v;
		}
		// 如果没有找到可以用的
		return false;
	}
}
