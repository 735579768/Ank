<?php
namespace ainiku;
use NoahBuscher\Macaw\Macaw;

/**
 * 应用初始化类
 */
class app {
	static public $config = [];
	public function __construct() {
	}
	static public function start() {
		// 设定错误和异常处理
		register_shutdown_function('ainiku\app::fatalError');
		set_error_handler('ainiku\app::appError');
		set_exception_handler('ainiku\app::appException');

		//加载框架配置和公共配置文件
		$frame_config        = require_once __SITE_ROOT__ . '/vendor/ainiku/config.php';
		$common_config       = require_once APP_PATH . '/config/common/config.php';
		\ainiku\app::$config = array_merge($frame_config, $common_config);

		defined('DEFAULT_THEME') or define('DEFAULT_THEME', \ainiku\app::getConfig('default_theme'));
		Macaw::get('fuck', function ($a, $b) {
			echo "成功！";
			var_dump(Macaw::$routes);
			var_dump(Macaw::$patterns);
			var_dump(Macaw::$callbacks);
			var_dump($a);
			var_dump($b);

		});

		Macaw::get('(:any)', function ($request) {
			global $loader;
			// echo '未匹配到路由<br>' . $request;
			$module     = \ainiku\request::get('m');
			$controller = \ainiku\request::get('c');
			$action     = \ainiku\request::get('a');

			if (!defined('BIND_MODULE')) {
				$module or ($module = 'home');
				define('BIND_MODULE', strtolower($module));
			}

			$controller or ($controller = \ainiku\app::getConfig('default_controller'));
			$action or ($action = \ainiku\app::getConfig('default_action'));
			$loader->addPsr4('controller\\' . BIND_MODULE . '\\', APP_PATH . '/controller/' . BIND_MODULE);
			define('CONTROLLER', ucwords($controller));
			define('ACTION', $action);
			$module_config       = require_once APP_PATH . '/config/' . BIND_MODULE . '/config.php';
			\ainiku\app::$config = array_merge(\ainiku\app::$config, $module_config);
			$modname             = "controller\\" . BIND_MODULE . "\\{$controller}Controller";
			$mod                 = new $modname();
			$mod->$action();
		});
		Macaw::error(function () {
			echo '404 :: Not Found';
		});
		Macaw::dispatch();
	}
	/**
	 * 返回应用加载过的配置
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	static public function getConfig($key = '') {
		$re = \ainiku\app::$config;
		if (!empty($key)) {
			$keyarr = explode('.', $key);
			foreach ($keyarr as $key => $value) {
				$re = $re[$value];
			}
		}
		return $re;

	}
	/**
	 * 自定义异常处理
	 * @access public
	 * @param mixed $e 异常对象
	 */
	static public function appException($e) {
		$error            = array();
		$error['message'] = $e->getMessage();
		$trace            = $e->getTrace();
		if ('E' == $trace[0]['function']) {
			$error['file'] = $trace[0]['file'];
			$error['line'] = $trace[0]['line'];
		} else {
			$error['file'] = $e->getFile();
			$error['line'] = $e->getLine();
		}
		$error['trace'] = $e->getTraceAsString();
		// Log::record($error['message'], Log::ERR);
		// 发送404信息
		header('HTTP/1.1 404 Not Found');
		header('Status:404 Not Found');
		self::halt($error);
	}

	/**
	 * 自定义错误处理
	 * @access public
	 * @param int $errno 错误类型
	 * @param string $errstr 错误信息
	 * @param string $errfile 错误文件
	 * @param int $errline 错误行数
	 * @return void
	 */
	static public function appError($errno, $errstr, $errfile, $errline) {
		switch ($errno) {
		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_USER_ERROR:
			ob_end_clean();
			$errorStr = "$errstr " . $errfile . " 第 $errline 行.";
			if (C('LOG_RECORD')) {
				Log::write("[$errno] " . $errorStr, Log::ERR);
			}

			self::halt($errorStr);
			break;
		default:
			$errorStr = "[$errno] $errstr " . $errfile . " 第 $errline 行.";
			// self::trace($errorStr, '', 'NOTIC');
			break;
		}
	}

	// 致命错误捕获
	static public function fatalError() {
		// Log::save();
		if ($e = error_get_last()) {
			switch ($e['type']) {
			case E_ERROR:
			case E_PARSE:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
				ob_end_clean();
				self::halt($e);
				break;
			}
		}
	}

	/**
	 * 错误输出
	 * @param mixed $error 错误
	 * @return void
	 */
	static public function halt($error) {
		\ainiku\Log::write($error['message']);
		$e = array();
		if (APP_DEBUG || IS_CLI) {
			//调试模式下输出错误信息
			if (!is_array($error)) {
				$trace        = debug_backtrace();
				$e['message'] = $error;
				$e['file']    = $trace[0]['file'];
				$e['line']    = $trace[0]['line'];
				ob_start();
				debug_print_backtrace();
				$e['trace'] = ob_get_clean();
			} else {
				$e = $error;
			}

			if (IS_CLI === true) {
				exit($e['message'] . PHP_EOL . 'FILE: ' . $e['file'] . '(' . $e['line'] . ')' . PHP_EOL . $e['trace']);
			}
		} else {
			//否则定向到错误页面
			$error_page = C('ERROR_PAGE');
			if (!empty($error_page)) {
				redirect($error_page);
			} else {
				$message      = is_array($error) ? $error['message'] : $error;
				$e['message'] = C('SHOW_ERROR_MSG') ? $message : C('ERROR_MESSAGE');
			}
		}
		// 包含异常页面模板
		$exceptionFile = __SITE_ROOT__ . '/vendor/ainiku/tpl/ainiku_exception.tpl';
		include $exceptionFile;
		exit;
	}
}