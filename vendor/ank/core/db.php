<?php
namespace ank;
/**
 * 数据库连接类
 */
class Db {
	static public $_instance = null;
	private function __construct() {

	}
	//返回一个数据库实例
	static public function getInstance() {
		if (!\ank\Db::$_instance) {
			\ank\Db::$_instance = new self();
		}
		return \ank\Db::$_instance;
	}
}