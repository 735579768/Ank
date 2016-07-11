<?php
namespace ank;
/**
 * 测试类
 */
class Request {
	static public $_instance = null;
	private $_param          = null;

	private function __construct() {
		// $this->scriptName  = $_SERVER['SCRIPT_NAME'];
		$this->requestUrl  = $_SERVER['REQUEST_URI']; //"/aaa/index.php?p=222&q=333";
		$this->queryString = $_SERVER['QUERY_STRING']; //"p=222&q=333";
		$url_model         = \ank\App::config('url_model');
		$url_suffix        = \ank\App::config('url_suffix');
		if ($url_model == 1) {
			//去掉后缀
			$this->requestUrl = substr($this->requestUrl, 1, strlen($this->requestUrl) - strlen($url_suffix) - 1);
			$reurl            = explode('/', $this->requestUrl);
			for ($i = 2; $i < count($reurl); $i++) {
				$this->_param[$reurl[$i]] = $reurl[++$i];
			}
		}

	}

	static public function getInstance() {
		if (!\ank\Request::$_instance) {
			\ank\Request::$_instance = new self();
		}
		return \ank\Request::$_instance;
	}
	/**
	 * 取数据
	 * @param  string $key     [description]
	 * @param  string $default [description]
	 * @param  string $filter  [description]
	 * @return [type]          [description]
	 */
	public function post($key = '', $default = '', $filter = '') {
		$data = empty($key) ? '' : (isset($_POST[$key]) ? $_POST[$key] : '');
		if (empty($data)) {
			return $default;
		}
		return function_exists($filter) ? $filter($data) : $data;
	}

	/**
	 * 取get数据
	 * @param  string $key     [description]
	 * @param  string $default [description]
	 * @param  string $filter  [description]
	 * @return [type]          [description]
	 */
	public function get($key = '', $default = '', $filter = '') {
		if (empty($key)) {
			return '';
		}
		$data = isset($this->_param[$key]) ? $this->_param[$key] : '';
		if (!empty($data)) {
			return $data;
		}
		$data = (isset($_GET[$key]) ? $_GET[$key] : '');
		if (empty($data)) {
			return $default;
		}
		return function_exists($filter) ? $filter($data) : $data;
	}
	/**
	 * 取参数
	 * @param  string $key     [description]
	 * @param  string $default [description]
	 * @param  string $filter  [description]
	 * @return [type]          [description]
	 */
	public function param($key = '', $default = '', $filter = '') {
		$data = $this->get($key, $default, $filter);
		if (!empty($data)) {
			return $data;
		}
		return $this->post($key, $default, $filter);
	}

}