<?php
define('_SITE_ROOT_', str_replace('\\', '/', dirname(__FILE__)));
define('APP_PATH', str_replace('\\', '/', __DIR__ . '/app'));
$loader = require _SITE_ROOT_ . '/vendor/autoload.php';
//添加新的自动加载命令空间
//$loader->add('controller', APP_PATH . '/controller');

require './vendor/ainiku/ank.php';
