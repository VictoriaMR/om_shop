<?php

/*
 * om 框架的容器文件 参考 Laravel 实现 依赖注入 和 控制反转
 */

$GLOBALS = [];

//加载 config 参数
require_once ROOT_PATH.'om/config.php';

//加载 env 配置参数
require_once ROOT_PATH.'om/env.php';

//加载 容器 类
require_once ROOT_PATH.'om/container.php';

//加载框架解析类
require_once ROOT_PATH.'om/mvc.php';

//加载 全局函数
require_once ROOT_PATH.'om/function.php';

mvc::execute();

exit();