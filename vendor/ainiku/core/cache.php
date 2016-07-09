<?php
namespace ainiku;

class Cache {
	private $cache_path      = '';
	static public $_instance = null;

	public function getInstance() {
		if (!\ainiku\Cache::$_instance) {
			//$log        = new Logger('Ainiku');
			$cache_path = DATA_PATH . '/cache/' . BIND_MODULE . '/data/';
			if (!file_exists($file_path)) {
				mkdir(dirname($file_path), 0777, true);
				// file_put_contents(file_path, '');
			}
			\ainiku\Cache::$_instance = new \Doctrine\Common\Cache\FilesystemCache($cache_path);

		}
		return \ainiku\Cache::$_instance;
	}
	/**
	 * 写缓存
	 * @param  string  $key      缓存key
	 * @param  [type]  $data     缓存的数据
	 * @param  integer $lifeTime 保存的时间false为永久
	 * @return [type]            boolean
	 */
	static public function write($key = '', $data = null, $lifeTime = false) {
		return \ainiku\Cache::getInstance()->save($key, $data, $lifeTime);
	}
	/**
	 * 写临时缓存
	 * @param  string $key 缓存key
	 * @return [type]      [description]
	 */
	static public function writeTemp($key = '', $data = null, $lifeTime = false) {
		if ($lifeTime === false) {
			$lifeTime = \ainiku\app::getConfig('cache_time');
		}
		return \ainiku\Cache::getInstance()->save($key, $data, $lifeTime);
	}

	/**
	 * 读缓存
	 * @param  string $key 缓存key
	 * @return [type]      [description]
	 */
	static public function read($key = '') {
		return \ainiku\Cache::getInstance()->fetch($key);
	}
	/**
	 * 删除缓存
	 * @param  string $key 缓存key
	 * @return [type]      [description]
	 */
	static public function delete($key = '') {
		return \ainiku\Cache::getInstance()->delete($key);
	}

	/**
	 * 检查缓存是否存在
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	static public function exists($key = '') {
		return \ainiku\Cache::getInstance()->contains($key);
	}
	/**
	 * 删除全部缓存
	 * @param  string $key 缓存key
	 * @return [type]      [description]
	 */
	static public function deleteAll($key = '') {
		return \ainiku\Cache::getInstance()->deleteAll($key);
	}
	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {

	}

}