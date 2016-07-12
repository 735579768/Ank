<?php
namespace ank;
/**
 * 数据库连接类
 */
class Db extends \Medoo {
	static public $instance  = [];
	static public $_instance = null;
	//返回一个数据库实例
	static public function getInstance($config = null) {
		if (!$config) {
			$config = \ank\App::config('db_config');
		}
		$md5 = md5(serialize($config));
		if (!isset(self::$instance[$md5])) {
			self::$instance[$md5] = self::$_instance = new self($config);
		}
		return self::$_instance;
	}
}