<?php
namespace ainiku;
use NoahBuscher\Macaw\Macaw;

/**
 * 应用初始化类
 */
class app {
	public function __construct() {

	}
	static public function start() {
		// 设定错误和异常处理
		register_shutdown_function('ainiku\app::fatalError');
		set_error_handler('ainiku\app::appError');
		set_exception_handler('ainiku\app::appException');
		$config = require_once __DIR__ . '/config.php';

		Macaw::get('fuck', function () {
			echo "成功！";
		});

		Macaw::get('(:all)', function ($fu) {
			global $loader;
			// echo '未匹配到路由<br>' . $fu;
			$module     = \ainiku\request::get('m');
			$controller = \ainiku\request::get('c');
			$action     = \ainiku\request::get('a');

			$module or ($module = 'home');
			$controller or ($controller = 'Index');
			$action or ($action = 'index');
			$loader->addPsr4('controller\\home\\', APP_PATH . '/controller/' . $module);
			define('MODULE', $module);
			define('CONTROLLER', $controller);
			define('ACTION', $action);
			// // activate the autoloader
			// $loader->register();

			// // to enable searching the include path (eg. for PEAR packages)
			// $loader->setUseIncludePath(true);
			$modname = "controller\\home\\{$controller}Controller";
			$mod     = new $modname();
			$mod->$action();
		});

		Macaw::dispatch();
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