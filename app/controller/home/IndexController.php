<?php
namespace controller\home;
/**
 *默认控制器
 */
class IndexController {

	function __construct() {

	}
	public function index($value = '') {
		echo 'home index;';
		//判断来源设备
		$detect = new \Mobile_Detect();
		if (!$detect->isMobile()) {
			//pc端
			//header('location:http://www.jd.com/');
			echo 'is pic';
		} else {
			echo 'is mobile';
		}
		$smarty->display('index.html');
	}
}