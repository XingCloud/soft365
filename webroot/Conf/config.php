<?php
return array(
		//'配置项'=>'配置值'
		'DEFAULT_MODULE'=>'Index', //默认模块
		'URL_MODEL'=>'2', //URL模式
		'DB_TYPE'    => 'mysql',	    //使用的数据库类型
		'DB_HOST'    => '127.0.0.1',
		'DB_NAME'    => 'pop_soft365',	    //数据库名
		'DB_USER'    => 'root',	    //访问数据库账号
		'DB_PWD'     => '123456',       //访问数据库密码
		'DB_PORT'    => '3306',
		'DB_PREFIX'=>'',	    //表前缀
		'SHOW_PAGE_TRACE'=>false,
		'MEMCACHE_HOST'=>'127.0.0.1',
		'MEMCACHE_PORT'=>11211,
		'DATA_CACHE_TIME'=>3600,
		'DEFAULT_TIMEZONE'=>'Asia/Shanghai', // 设置默认时区
		'SESSION_AUTO_START' => false, //是否开启session
		'SESSION_OPTIONS'=>array(
				'expire'=>28800,
				//	    'path'=> '/tmp/lpsessions',
				'prefix'=> 'think',
		),
		'LOG_RECORD' => true, // 开启日志记录
		'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
		/*多语言*/
			// 'DEFAULT_LANG'          => 'zh_cn', 
			// 默认语言
			'LANG_SWITCH_ON' => true,
			'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
			'LANG_LIST'        => "zh_cn,en_us,es_ar,pt_br,en_au,it_it,es_mx,pl_pl,es_es,zh_tw,th_th,tr_tr,vi_vn", 
			// 允许切换的语言列表 用逗号分隔
			'VAR_LANGUAGE'     => 'l', 
			// 默认语言切换变量
			'VAR_PAGE'         => 'page',
			'VAR_FILTERS'		=> 'htmlspecialchars',
		'TMPL_FILE_DEPR' => '.',
		'PRIVILEGE' => include(dirname(__FILE__).'/privilege.php')
		
);
?>
