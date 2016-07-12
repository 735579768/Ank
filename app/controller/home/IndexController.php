<?php
namespace controller\home;
use ank\Controller;

/**
 *默认控制器
 */
class IndexController extends Controller {

	public function index($value = '') {
		var_dump(\ank\Db::getInstance());
		var_dump(new \ank\Model());
		// echo 'home index;';
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
		//
		// echo \ank\Cache::write('key', [1, 2, 3, 4, 5, 6, 7]);
		// echo \ank\Cache::read('key');

		// echo \ank\app::config('sys.key.val', 'aa');
		var_dump($_GET);
		var_dump(\ank\App::config(''));
		$this->display('test');
	}
	public function verify() {
		$conf = array(
			'imageH'   => 50,
			'imageW'   => 150,
			'fontSize' => 20,
			'bg'       => array(255, 255, 255),
			'length'   => 4,
			'useNoise' => false, // 是否添加杂点
		);
		$verify = new \ank\extend\verify($conf);
		$verify->entry(1);
	}

	public function image() {
		$img = new \ank\extend\Image();
		var_dump($img);
	}
}