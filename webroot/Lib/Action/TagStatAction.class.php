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
        $data = array_values($model->query($sql));

        $this->assign ( 'data', $data );
        $this->display();
    }
}