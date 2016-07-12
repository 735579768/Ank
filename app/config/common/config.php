<?php
return [
	//数据库配置
	'db_config' => [
		// 必须配置项
		'database_type' => 'mysql',
		'database_name' => 'oxisi',
		'server'        => 'localhost',
		'username'      => 'root',
		'password'      => '123456',
		'charset'       => 'utf8',
		'port'          => 3306,
		'prefix'        => 'kl_',

		// 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
		// 'option'        => [
		// 	PDO::ATTR_CASE => PDO::CASE_NATURAL,
		// ],
	],
];