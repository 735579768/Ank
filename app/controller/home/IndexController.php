<?php
namespace controller\home;
use ainiku\Controller;

/**
 *默认控制器
 */
class IndexController extends Controller {

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
		// var_dump($this->view->getTemplateDir());
		// var_dump($this->view);
		//die();
		$this->display('test');
	}
}