<?php
namespace ank;
/**
 * 数据模型创建类
 */
class Model {
	protected $_db = null;
	/**
	 * 模型表
	 * @param string $name        表名字
	 * @param string $tablePrefix 表前缀
	 * @param string $connection  连接字符串
	 */
	function __construct($name = '', $tablePrefix = '', $db_config = null) {
		$this->_db = \ank\Db::getInstance($db_config);
		// 模型初始化
		$this->_initialize();
	}
	protected function _initialize() {

	}
}