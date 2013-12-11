<?php
class TagStatAction extends Action {

    //
    function __construct() {
        parent::__construct ();
        header ( 'content-type:text/html;charset=utf-8' );
    }

    //
    function index() {
        $model = TagStatModel::getModel();
        $sql = 'select * from ' . TagStatModel::tableName . ';';
        $rows = array_values($model->query($sql));
        $data = array();
        foreach ( $rows as $row ) {
            $tag = $row [TagStatModel::tag_name];
            $user_num = $row [TagStatModel::user_num];
            $click_num = $row [TagStatModel::click_num];
            array_push($data, array($tag, $user_num, $click_num));
        }

        $this->assign ( 'data', $data );
        $this->display();
    }
}