#!/usr/local/php54/bin/php
<?php
if (isset($_SERVER['HTTP_HOST']) || isset($_SERVER['REMOTE_ADDR'])) exit('denied');
define('MODE_NAME', 'cli');
define('APP_PATH', dirname(__FILE__).'/');
define('APP_DEBUG',true);
date_default_timezone_set('UTC');
require APP_PATH.'Common/def.php';
require dirname(APP_PATH).'/ThinkPHP/ThinkPHP.php';