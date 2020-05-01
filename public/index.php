<?php

//也可以在 index.php 定义一些自己的变量 | 设置
// header("Access-Control-Allow-Origin: *");
// @session_start();

/**
 * 入口文件
 */
define('OM_START_TIME', microtime(true));

/**
 * 定义项目根路径
 */
define('ROOT_PATH', str_replace('\\', '/', realpath(dirname(__FILE__).'/../').'/'));

//加载composer配置文件
if (file_exists(ROOT_PATH.'vendor/autoload.php')) {
	require_once ROOT_PATH.'vendor/autoload.php';
}

//框架执行文件
$app = require_once ROOT_PATH.'om/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

print_r($kernel);


exit();
