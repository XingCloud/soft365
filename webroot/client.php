<?php
//用户访问的入口程序
define('APPLICATION_PATH', dirname(dirname(__FILE__)).'/client');
//加载配置文件
$application = new \Yaf\Application( APPLICATION_PATH . "/conf/application.ini");
//关闭自动调用视图层
\Yaf\Dispatcher::getInstance()->disableView();
//从bootstrap开始，运行mvc模式
$application->bootstrap()->run();
?>
