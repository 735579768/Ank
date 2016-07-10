<?php
namespace ank;
/**
 * 测试类
 */
class Request {
	static public $_instance = null;

	function __construct() {

	}

	static public function post($key = '', $filter = '') {
		$data = empty($key) ? '' : (isset($_POST[$key]) ? $_POST[$key] : '');
		return function_exists($filter) ? $filter($data) : $data;
	}

	static public function get($key = '', $filter = '') {
		$data = empty($key) ? '' : (isset($_GET[$key]) ? $_GET[$key] : '');
		return function_exists($filter) ? $filter($data) : $data;
	}
}