<?php
use NoahBuscher\Macaw\Macaw;

$config = require_once __DIR__ . '/config.php';
$smarty = new Smarty;
//$smarty->left_delimiter = "{#";
//$smarty->right_delimiter = "#}";
$smarty->caching      = true; //是否使用缓存，项目在调试期间，不建议启用缓存
$smarty->template_dir = _SITE_ROOT_ . "/templates"; //设置模板目录
$smarty->compile_dir  = _SITE_ROOT_ . "/data/cache/templates_c"; //设置编译目录
$smarty->config_dir   = _SITE_ROOT_ . '/templates/smarty_configs';
$smarty->cache_dir    = _SITE_ROOT_ . "/data/cache/smarty_cache"; //缓存文件夹
//$smarty->force_compile = true;
$smarty->debugging      = false;
$smarty->caching        = false;
$smarty->cache_lifetime = 120;

Macaw::get('fuck', function () {
	echo "成功！";
});

Macaw::get('(:all)', function ($fu) {
	global $loader;
	// echo '未匹配到路由<br>' . $fu;
	$module     = ainiku\request::get('m');
	$controller = ainiku\request::get('c');
	$action     = ainiku\request::get('a');

	$module or ($module = 'home');
	$controller or ($controller = 'Index');
	$action or ($action = 'index');
	$loader->addPsr4('controller\\home\\', APP_PATH . '/controller/' . $module);
	// activate the autoloader
	$loader->register();

	// to enable searching the include path (eg. for PEAR packages)
	$loader->setUseIncludePath(true);
	$modname = "controller\\home\\{$controller}Controller";
	$mod     = new $modname();
	$mod->$action();
});

Macaw::dispatch();