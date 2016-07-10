<?php
header("Content-type:text/html;charset=utf-8");
//开启调试模式
define('APP_DEBUG', true);
//入口文件目录绝对路径
define('SITE_ROOT', str_replace('\\', '/', __DIR__));
//绑定模块名字
// define('BIND_MODULE', 'home');
//加载框架初始化文件
require './vendor/ank/ank.php';