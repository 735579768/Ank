<?php
namespace ank;
/**
 *
 */
class Route {

	private $scriptName  = '';
	private $requestUrl  = '';
	private $queryString = '';
	private $controller  = '';
	private $action      = '';

	function __construct() {
		$this->scriptName  = $_SERVER['SCRIPT_NAME'];
		$this->requestUrl  = $_SERVER['REQUEST_URI']; //"/aaa/index.php?p=222&q=333";
		$this->queryString = $_SERVER['QUERY_STRING']; //"p=222&q=333";
		$url_model         = \ank\App::config('url_model');
		$url_suffix        = \ank\App::config('url_suffix');
		// var_dump(\ank\App::config(''));
		if ($url_model == 1) {
			//去掉后缀
			$this->requestUrl = substr($this->requestUrl, 1, strlen($this->requestUrl) - strlen($url_suffix) - 1);
			$reurl            = explode('/', $this->requestUrl);
			$this->controller = $reurl[0];
			$this->action     = isset($reurl[1]) ? $reurl[1] : '';
		} else {
			$this->controller = \ank\request::get('c');
			$this->action     = \ank\request::get('a');
		}
		$this->controller = ucfirst($this->controller);

		$this->controller or ($this->controller = \ank\App::config('default_controller'));
		$this->action or ($this->action = \ank\App::config('default_action'));
		define('CONTROLLER', ucwords($this->controller));
		define('ACTION', $this->action);
	}
	/**
	 * 调度url地址
	 * @return [type] [description]
	 */
	public function dispatch() {
		$modname = "controller\\" . BIND_MODULE . "\\{$this->controller}Controller";
		$mod     = new $modname();
		$param   = [];
		call_user_func_array([$mod, $this->action], $param);
	}
	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {
		// 执行后续操作
		//Hook::listen('action_end');
	}

}