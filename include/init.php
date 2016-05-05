<?php
//require _SITE_ROOT_ . '/vendor/smarty3/libs/Smarty.class.php';

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
