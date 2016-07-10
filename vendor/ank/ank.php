<?php
//app目录绝对目录
defined('APP_PATH') or define('APP_PATH', SITE_ROOT . '/app');
//数据缓存目录绝对目录
defined('DATA_PATH') or define('DATA_PATH', SITE_ROOT . '/data');
//定义模块
defined('BIND_MODULE') or define('BIND_MODULE', 'home');
//包含自动加载类
$loader = require SITE_ROOT . '/vendor/autoload.php';
\ank\App::start();