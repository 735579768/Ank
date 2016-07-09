<?php
namespace ainiku;

class Cache {
	private $cache_path      = '';
	static public $_instance = null;

	public function getInstance() {
		if (!\ainiku\Log::$_instance) {
			$log       = new Logger('Ainiku');
			$file_path = DATA_PATH . '/cache/' . BIND_MODULE . '/data/' . date('y-m-d') . '.log';
			if (!file_exists($file_path)) {
				mkdir(dirname($file_path), 0777, true);
				file_put_contents(file_path, '');
			}
			$stream = new StreamHandler($file_path, Logger::DEBUG);
			$log->pushHandler($stream);
			\ainiku\Log::$_instance = $log;

		}
		return \ainiku\Log::$_instance;
	}
	/**
	 * 写缓存
	 * @param  string  $key      缓存key
	 * @param  [type]  $data     缓存的数据
	 * @param  integer $lifeTime 保存的时间
	 * @return [type]            boolean
	 */
	static public function write($key = '', $data = null, $lifeTime = 60) {

	}
	/**
	 * 读缓存
	 * @param  string $key 缓存key
	 * @return [type]      [description]
	 */
	static public function read($key = '') {

	}

	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {

	}

}