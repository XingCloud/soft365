<?php
class IndexAction extends Action {
	public $r;
	function __construct()
	{
		parent::__construct();
		header('content-type:text/html;charset=utf-8');
		
	}
		
	public function index2(){
		PopLogModel::autoCreateTable();
	}
	
	public function test4()
	{
		
		//RefreshPopModel::doRefresh();
		$data = PopSortByWeightRedisModel::get();
		foreach($data as $v)
			echo "{$v['type']},{$v['id']},{$v['weight']}<br />";
	}
	
	function test(){
		$this->r = new redis ();
		//TODO: 把redis配置写到配置文件中去
		$this->r->pconnect ( '127.0.0.1' );
		$this->r->setOption ( Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP );
		var_dump($this->r->hGetAll('client_11'));
	}
	
	
    public function std_pop(){
    	
		var_dump(StdPopRedisModel::hgetall());
    }
    
    function refresh_task(){
    	
		var_dump(RefreshTaskRedisModel::hgetall());
    }
    
    
    //-----------------------
}
