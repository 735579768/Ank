<?php
header("Content-type:text/html;charset=utf-8");
//开启调试模式
define('APP_DEBUG', true);
//入口文件目录绝对路径
define('SITE_ROOT', str_replace('\\', '/', __DIR__));
//app目录绝对目录
define('APP_PATH', SITE_ROOT . '/app');
//数据缓存目录绝对目录
define('DATA_PATH', SITE_ROOT . '/data');
//包含自动加载类
$loader = require SITE_ROOT . '/vendor/autoload.php';

//绑定模块名字
define('BIND_MODULE', 'home');
//加载框架初始化文件
require './vendor/ank/ank.php';