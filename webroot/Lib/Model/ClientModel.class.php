<?php
//用户数据的mysql操作模型
class ClientModel extends Model{
    protected $_auto = array (
        array('add_time','time',1,'function'), // 对create_time字段在更新的时候写入当前时间戳
    );
}