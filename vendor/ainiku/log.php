<?php
namespace ainiku;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log {
	private $log_path           = '';
	static public $log_instance = null;

	public function getInstance() {
		if (!\ainiku\Log::$log_instance) {
			$log       = new Logger('Ainiku');
			$file_path = DATA_PATH . '/cache/' . MODULE . '/logs/' . date('y-m-d') . '.log';
			if (!file_exists($file_path)) {
				mkdir(dirname($file_path), 0777, true);
				file_put_contents(file_path, '');
			}
			$stream = new StreamHandler($file_path, Logger::DEBUG);
			$log->pushHandler($stream);
			\ainiku\Log::$log_instance = $log;

		}
		return \ainiku\Log::$log_instance;
		// $log->addError('Bar');
		// Create some handlers

		// $firephp = new FirePHPHandler();

		// // Create the main logger of the app
		// $logger = new Logger('my_logger');
		// $logger->pushHandler($stream);
		// $logger->pushHandler($firephp);

		// // Create a logger for the security-related stuff with a different channel
		// $securityLogger = new Logger('security');
		// $securityLogger->pushHandler($stream);
		// $securityLogger->pushHandler($firephp);
	}
	static public function write($str, $leval = 'warning') {
		switch ($leval) {
		case 'warning':
			\ainiku\Log::getInstance()->addWarning($str);
			break;
		case 'error':
			\ainiku\Log::getInstance()->addError($str);
			break;
		case 'debug':
			\ainiku\Log::getInstance()->addDebug($str);
			break;
		case 'alert':
			\ainiku\Log::getInstance()->addAlert($str);
			break;

		default:
			\ainiku\Log::getInstance()->addInfo($str);
			break;
		}

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