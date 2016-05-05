<?php
define('_SITE_ROOT_', str_replace('\\', '/', dirname(__FILE__)));
$loader = require _SITE_ROOT_ . '/vendor/autoload.php';
//添加新的自动加载命令空间
//$loader->add('Acme\\Test\\', __DIR__);

require './include/init.php';

//判断来源设备
$detect = new \MobileDetect();
if (!$detect->isMobile()) {
	//pc端
	//header('location:http://www.jd.com/');
}
