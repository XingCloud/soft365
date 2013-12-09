<?php
/**
 * Created by PhpStorm.
 * User: witwolf
 * Date: 12/9/13
 * Time: 4:46 PM
 */

class Tag extends Model{

    protected $_validate = array (
        array('tagid','/^[a-zA-Z]+$/','标签只能由大小写字母组合而成'),
    );

    protected $userId ;

    protected $click ;

    //按id获取数据
    function getById($tag){
        $where ="tag=".$tag;
        return $this->where ( $where)->select ();
    }

    static function tableName($tag){
        return 'tag_' . $tag ;
    }

    static function getModel($tag){
        return M(static::tableName($tag));
    }

    static function autoCreateTagTable($tag){
        $tableName = static::tableName($tag);
        $sql = "CREATE TABLE IF NOT EXISTS `{$tag}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		  `tag` varchar(45) NOT NULL DEFAULT '' COMMENT '标签',
		  `userId` varchar(45) NOT NULL DEFAULT  '' COMMENT '用户id'
		  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
		  PRIMARY KEY (`id`),
		) ENGINE=MyIsam AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='标签点击日志'";
        $model = new StatModel();
        $model->query($sql);
    }
}