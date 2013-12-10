<?php
class TagStatAction extends Action {

    //
    function __construct() {
        parent::__construct ();
        header ( 'content-type:text/html;charset=utf-8' );
    }

    //
    function index() {
        //获取所有存在的tag
        $tags = array();
        if (!mysql_connect("","root","")){
            die("Could not connect to mysql");
        }
        $rs = mysql_list_tables("pop_soft365");
        while($row = mysql_fetch_row($rs)){
            if(strpos($row[0], "tag_") !== false)
                array_push($tags,substr($row[0], 4));
        }

        //逐个统计每个标签
        $data = array();
        foreach ( $tags as $tag ){
            $model = TagModel::getModel($tag);
            $user_count_sql = sprintf("select count(*) from %s where click>0;", TagModel::tableName($tag));
            $click_count_sql = sprintf("select sum(click) from %s;", TagModel::tableName($tag));

            $user_count = array_values($model->query($user_count_sql)[0])[0];
            $click_count = array_values($model->query($click_count_sql)[0])[0];
            array_push($data, array($tag, $user_count, $click_count));
        }

        $this->assign ( 'data', $data );
        $this->display();
    }
}