<?php
namespace ank;
/**
 * 测试类
 */
class request {

	function __construct() {

	}

	static public function post($key = '') {
		return empty($key) ? '' : (isset($_POST[$key]) ? $_POST[$key] : '');
	}

	static public function get($key = '') {
		return empty($key) ? '' : (isset($_GET[$key]) ? $_GET[$key] : '');
	}
}