<?php
header("Content-type:text/html;charset=utf-8");
//开启调试模式
define('APP_DEBUG', true);
//入口文件目录绝对路径
define('__SITE_ROOT__', str_replace('\\', '/', __DIR__));
//app目录绝对目录
define('APP_PATH', __SITE_ROOT__ . '/app');
//数据缓存目录绝对目录
define('DATA_PATH', __SITE_ROOT__ . '/data');
//包含自动加载类
$loader = require __SITE_ROOT__ . '/vendor/autoload.php';
//加载框架初始化文件
require './vendor/ainiku/ank.php';
