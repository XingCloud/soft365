<?php

//不允许从浏览器访问
if (isset($_SERVER['HTTP_HOST']) || isset($_SERVER['REMOTE_ADDR'])) exit('denied');
//用于定时任务的控制器
class CrontabAction extends Action {
	
	//开始运行时输出一个时间
	function __construct(){
		parent::__construct();
		echo date('Y-m-d H:i:s'),"\n";
	}
	
	function index(){debug_print_backtrace();}
	
	//把临时用户存放到mysql中去
	//调用命令：[ /var/www/pop.soft365.com/webroot/cli.php Crontab saveClientToMysql ]
	function saveClientToMysql(){
		$model = new ClientModel();
		//一个一个插到mysql中
		while($client = TmpClientRedisModel::lpop())
		{
			$model->add($client);
			//如果发现错误，退出
			if($model->getError())
			{
				//把数据插回去
				TmpClientRedisModel::Rpush($client);
				break;
			}
			if(++$i%500==0)
				echo $i,"\n";
		}
	}
	
	//把弹窗日志存放到mysql中去
	//调用命令：[ /var/www/pop.soft365.com/webroot/cli.php Crontab savePopLogToMysql ]
	function savePopLogToMysql(){
		PopLogModel::autoCreateTable();
		//一个一个插到mysql中
		while($log = PopLogRedisModel::lpop())
		{
			//日期
			$log['time_day'] = substr($log['time'],0,10);
			$log['time_ten_minute'] = substr($log['time'],0,15);
			$log['time_hour'] = substr($log['time'],0,13);
			// 插入到指定日期的表中去（非常重要）
			$date = date('Ymd',strtotime($log['time']));
			$model = PopLogModel::getModel($date);
			$model->add($log);
			//如果发现错误，退出
			if($model->getError())
			{
				//把数据插回去
				PopLogRedisModel::Rpush($log);
				break;
			}
			if(++$i%500==0)
				echo $i,"\n";
		}
	}
	
	// 刷新统计数据
	function refreshStat(){
		$date = null;
		if(!empty($GLOBALS['argv'][3]))
			$date = $GLOBALS['argv'][3];
		$model = new StatModel();
		$data = $model->flushStat($date);
	}
	
	//刷新弹窗数据
	//调用命令：[ /var/www/pop.soft365.com/webroot/cli.php Crontab refreshPop ]
	function refreshPop(){
		RefreshPopModel::doRefresh ();
	}

    //同步redis里的tags信息到mysql
    function syncTagsInfoToMysql() {
        $i = 0;
        $client_ids = array();
        while ($client_id = UidTagQueueRedisModel::lpop()) {
            $client_ids [$client_id] = 1;
            if(++$i%1000==0) {
                $this->updateMysql($client_ids);
                $client_ids = array();
                echo $i.$client_id,"\n";
            }
        }
        if (count($client_ids) > 0) {
            $this->updateMysql($client_ids);
        }
    }

    function updateMysql($client_ids) {
        // 生成sql语句
        $tag_sql_set = array();
        foreach ($client_ids as $client_id_update => $val) {
            $clientModel = new ClientRedisModel($client_id_update);
            $client_tags = $clientModel->hget(ClientRedisModel::tags);
            foreach ($client_tags as $tag => $counter) {
                $sqls = $tag_sql_set [$tag];
                $sql = 'replace into ' . TagModel::tableName($tag) . '('. TagModel::client_id . ',' . TagModel::click
                    . ') values("' . $client_id_update . '",' . $counter . ');';
                $sqls = $sqls . $sql;
                $tag_sql_set [$tag] = $sqls;
            }
        }

        // 批量执行sql语句
        foreach ($tag_sql_set as $tag => $sqls) {
            $model = TagModel::getModel($tag);
            $model->query($sqls);
        }
    }
}

