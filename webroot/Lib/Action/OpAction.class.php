<?php

// 给监控系统使用
class OpAction extends Action{

 public function memcacheInfo(){
	$start = microtime();
	$cache = new Memcache ;
	$cache->connect('127.0.0.1', 11211);
	$conn = microtime() - $start;
	$set_start = microtime();
    $cache->set('heartbeat_s2s', "test.microtime(true)", MEMCACHE_COMPRESSED, 30);
    $write = microtime() - $set_start;
    $get_start = microtime();
	$cache->get('heartbeat_s2s');
    $read = microtime() - $get_start;
    $del_start = microtime();
    $cache->delete("heartbeat_s2s");
    $delete = microtime() - $del_start;
	if($cache->connection){
       $status = 'ok';      
	}else{
	   $status = 'error';	   
	}	
	$info = array("name" => 'memcache',
	   "status" => $status,
	   'timestamp' => array("connection" => $conn,"write" => $write,'read' => $read,'delete' => $delete),
	   "app" => "pop_soft365",
	   "real_ip" =>$_SERVER['SERVER_ADDR']
	);
	echo json_encode($info);
 }
 
 public function redisInfo(){
 	$status = 'ok';
 	$start = microtime();
 	$cache = new redis() ;
 	if(!$cache->pconnect('127.0.0.1'))
 		$status = 'error';
 	$cache->setOption ( Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP );
 	$conn = microtime() - $start;
 	$set_start = microtime();
 	if(!$cache->set('heartbeat_s2s', microtime(true)))
 		$status = 'error';
 	$write = microtime() - $set_start;
 	$get_start = microtime();
 	if(!$cache->get('heartbeat_s2s'))
 		$status = 'error';
 	$read = microtime() - $get_start;
 	$del_start = microtime();
 	if(!$cache->del("heartbeat_s2s"))
 		$status = 'error';
 	$delete = microtime() - $del_start;
 	
 	$info = array("name" => 'redis',
 			"status" => $status,
 			'timestamp' => array("connection" => $conn,"write" => $write,'read' => $read,'delete' => $delete),
 			"app" => "pop_soft365",
 			"real_ip" =>$_SERVER['SERVER_ADDR']
 	);
 	echo json_encode($info);
 }

 public function phpInfo(){
	try{
		$status = "ok";
		$start = microtime();
		$conn = microtime() - $start;
		$set_start = microtime();
		$write = microtime() - $set_start;
		$get_start = microtime();
		$read = microtime() - $get_start;
		$del_start = microtime();
		$delete = microtime() - $del_start;
	}catch(Exception $e){
		$status = 'error';
	}
	$info = array("name" => 'php',
	   "status" => $status,
	   'timestamp' => array("connection" => $conn,"write" => $write,'read' => $read,'delete' => $delete),
	   "app" => "pop_soft365",
	   "real_ip" =>$_SERVER['SERVER_ADDR']
	);
	echo json_encode($info);
 }

 public function mysqlInfo(){
	
	$host = C("DB_HOST");
	$port = C("DB_PORT");
	$user = C("DB_USER");
	$pwd = C("DB_PWD");
	$db = C("DB_NAME");
	$start = microtime();
	$link = mysql_connect($host, $user, $pwd);
	$conn = microtime() - $start;
	if(!link){
	  $status = 'error';	
	}else{
	  $status = 'ok';	
	}
	$set_start = microtime();
	$sel_db = mysql_select_db($db, $link);
	$write_sql = "create table heartbeat_t(name varchar(255))";
	mysql_query($write_sql);
	$write = microtime() - $set_start;	
    $get_start = microtime();
    $read_sql = "select current_user(),current_time();";
    mysql_query($read_sql);
    $read = microtime() - $get_start;
    $del_start = microtime();
    $del_sql = "drop table heartbeat_t" ;
    mysql_query($del_sql);
    $delete = microtime() - $del_start;	
	mysql_close($link);
	$info = array("name" => 'mysql',
	   "status" => $status,
	   'timestamp' => array("connection" => $conn,"write" => $write,'read' => $read,'delete' => $delete),
	   "app" => "pop_soft365",
	   "real_ip" =>$_SERVER['SERVER_ADDR']
	);
	echo json_encode($info);
 }
	
}
?>