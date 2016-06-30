<?php
header("Content-type:text/html;charset=utf-8");
define('APP_DEBUG', true);
define('__SITE_ROOT__', str_replace('\\', '/', __DIR__));
define('APP_PATH', __SITE_ROOT__ . '/app');
define('DATA_PATH', __SITE_ROOT__ . '/data');
$loader = require __SITE_ROOT__ . '/vendor/autoload.php';
//添加新的自动加载命令空间
//$loader->add('controller', APP_PATH . '/controller');
\ainiku\app::start();
// require './vendor/ainiku/ank.php';
