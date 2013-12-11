<?php
/**
 * Created by JetBrains PhpStorm.
 * User: snake
 * Date: 13-12-11
 * Time: 上午11:45
 * To change this template use File | Settings | File Templates.
 */
class TagStatModel extends Model{
    const tableName = 'stat_tag';

    const tag_name = 'tagName';
    const user_num = 'userNum';
    const click_num = 'countNum';

    static function getModel() {
        return M(static::tableName);
    }

    static function autoCreateTagStatTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `{static::tableName}` (
		  `tagName` varchar(45) binary primary key COMMENT '标签名称',
		  `userNum` int(11) NOT NULL DEFAULT '0' COMMENT '点击人数',
		  `countNum` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
		  key `idx_userNum`(`userNum`),
		  key `idx_countNum`(`countNum`)
		) ENGINE=MyIsam DEFAULT CHARSET=utf8 COMMENT='标签统计信息'";
        $model = new StatModel();
        $model->query($sql);
    }
}
