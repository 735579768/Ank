<?php
return [
	//默认配置
	'default_theme'      => 'default',
	'default_module'     => 'home',
	'default_controller' => 'Index',
	'default_action'     => 'index',
	'url_suffix'         => '.html',
	//默认系统url配置
	'url_model'          => '0',

	//缓存配置
	'cache_time'         => 60, //单位秒

	//系统自动加载空间
	'sys_auto_load'      => [
		//加载的空间名=>自动加载的路径
	],
	//模块自动加载空间
	'auto_load'          => [
		//加载的空间名=>自动加载的路径
	],
];