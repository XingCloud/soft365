<?php
/**
 * Created by PhpStorm.
 * User: witwolf
 * Date: 12/9/13
 * Time: 4:46 PM
 */

class TagModel extends Model{
    const client_id = 'client_id';
    const click = 'click';

    static function tableName($tag){
        return 'tag_' . $tag ;
    }

    static function getModel($tag){
        return M(static::tableName($tag));
    }

    static function autoCreateTagTable($tag){
        $tableName = static::tableName($tag);
        $sql = "CREATE TABLE IF NOT EXISTS `{$tag}` (
		  `clientId` varchar(45) binary primary key COMMENT '用户id',
		  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
		) ENGINE=MyIsam AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='标签点击日志'";
        $model = new StatModel();
        $model->query($sql);
    }
}