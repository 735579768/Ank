<?php
namespace ank;
/**
 * 测试类
 */
class Request {
	private $scriptName = '';
	private $requestUrl = '';
	function __construct() {

	}

	public function post($key = '', $filter = '') {
		$data = empty($key) ? '' : (isset($_POST[$key]) ? $_POST[$key] : '');
		return function_exists($filter) ? $filter($data) : $data;
	}

	public function get($key = '', $filter = '') {
		$data = empty($key) ? '' : (isset($_GET[$key]) ? $_GET[$key] : '');
		return function_exists($filter) ? $filter($data) : $data;
	}
}