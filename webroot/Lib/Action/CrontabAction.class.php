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
}

