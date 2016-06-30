<?php
namespace ainiku;
/**
 *
 */
abstract class Controller {
	/**
	 * 视图实例对象
	 * @var view
	 * @access protected
	 */
	protected $view = null;

	function __construct() {

	}
	/**
	 * 模板显示 调用内置的模板引擎显示方法，
	 */
	protected function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
		$this->view->display($templateFile, $charset, $contentType, $content, $prefix);
	}

	/**
	 * 输出内容文本可以包括Html 并支持内容解析
	 */
	protected function show($content, $charset = '', $contentType = '', $prefix = '') {
		$this->view->display('', $charset, $contentType, $content, $prefix);
	}

	/**
	 *  获取输出页面内容
	 */
	protected function fetch($templateFile = '', $content = '', $prefix = '') {
		return $this->view->fetch($templateFile, $content, $prefix);
	}

	/**
	 *  创建静态页面
	 */
	protected function buildHtml($htmlfile = '', $htmlpath = '', $templateFile = '') {
		$content  = $this->fetch($templateFile);
		$htmlpath = !empty($htmlpath) ? $htmlpath : HTML_PATH;
		$htmlfile = $htmlpath . $htmlfile . C('HTML_FILE_SUFFIX');
		Storage::put($htmlfile, $content, 'html');
		return $content;
	}

	/**
	 * 模板主题设置
	 */
	protected function theme($theme) {
		$this->view->theme($theme);
		return $this;
	}

	/**
	 * 模板变量赋值
	 */
	protected function assign($name, $value = '') {
		$this->view->assign($name, $value);
		return $this;
	}
	/**
	 * 操作错误跳转的快捷方法
	 */
	protected function error($message = '', $jumpUrl = '', $ajax = false) {
		$this->dispatchJump($message, 0, $jumpUrl, $ajax);
	}

	/**
	 * 操作成功跳转的快捷方法
	 */
	protected function success($message = '', $jumpUrl = '', $ajax = false) {
		$this->dispatchJump($message, 1, $jumpUrl, $ajax);
	}

	/**
	 * Ajax方式返回数据到客户端
	 */
	protected function ajaxReturn($data, $type = '', $json_option = 0) {
		if (empty($type)) {
			$type = C('DEFAULT_AJAX_RETURN');
		}

		switch (strtoupper($type)) {
		case 'JSON':
			// 返回JSON数据格式到客户端 包含状态信息
			header('Content-Type:application/json; charset=utf-8');
			exit(json_encode($data, $json_option));
		case 'XML':
			// 返回xml格式数据
			header('Content-Type:text/xml; charset=utf-8');
			exit(xml_encode($data));
		case 'JSONP':
			// 返回JSON数据格式到客户端 包含状态信息
			header('Content-Type:application/json; charset=utf-8');
			$handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
			exit($handler . '(' . json_encode($data, $json_option) . ');');
		case 'EVAL':
			// 返回可执行的js脚本
			header('Content-Type:text/html; charset=utf-8');
			exit($data);
		default:
			// 用于扩展其他返回格式数据
			Hook::listen('ajax_return', $data);
		}
	}
	/**
	 * 默认跳转操作 支持错误导向和正确跳转
	 * 调用模板显示 默认为public目录下面的success页面
	 * 提示页面为可配置 支持模板标签
	 * @param string $message 提示信息
	 * @param Boolean $status 状态
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @access private
	 * @return void
	 */
	private function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false) {
		if (true === $ajax || IS_AJAX) {
			$data           = is_array($ajax) ? $ajax : array();
			$data['info']   = $message;
			$data['status'] = $status;
			$data['url']    = $jumpUrl;
			$this->ajaxReturn($data);
		}
		if (is_int($ajax)) {
			$this->assign('waitSecond', $ajax);
		}

		if (!empty($jumpUrl)) {
			$this->assign('jumpUrl', $jumpUrl);
		}

		// 提示标题
		$this->assign('msgTitle', $status ? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
		//如果设置了关闭窗口，则提示完毕后自动关闭窗口
		if ($this->get('closeWin')) {
			$this->assign('jumpUrl', 'javascript:window.close();');
		}

		$this->assign('status', $status); // 状态
		//保证输出不受静态缓存影响
		C('HTML_CACHE_ON', false);
		if ($status) {
			//发送成功信息
			$this->assign('message', $message); // 提示信息
			// 成功操作后默认停留1秒
			if (!isset($this->waitSecond)) {
				$this->assign('waitSecond', '1');
			}

			// 默认操作成功自动返回操作前页面
			if (!isset($this->jumpUrl)) {
				$this->assign("jumpUrl", $_SERVER["HTTP_REFERER"]);
			}

			$this->display(C('TMPL_ACTION_SUCCESS'));
		} else {
			$this->assign('error', $message); // 提示信息
			//发生错误时候默认停留3秒
			if (!isset($this->waitSecond)) {
				$this->assign('waitSecond', '3');
			}

			// 默认发生错误的话自动返回上页
			if (!isset($this->jumpUrl)) {
				$this->assign('jumpUrl', "javascript:history.back(-1);");
			}

			$this->display(C('TMPL_ACTION_ERROR'));
			// 中止执行  避免出错后继续执行
			exit;
		}
	}
	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {
		// 执行后续操作
		Hook::listen('action_end');
	}
}