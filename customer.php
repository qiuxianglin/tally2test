<?php
// 应用入口文件
require_once './Public/inc/status.config.php';
// 设置页面编码
header("Content-type:text/html;charset=utf-8");
//设置时区
date_default_timezone_set('Asia/Shanghai');
// 引入状态配置文件
require_once './Public/inc/status.config.php';
// 引入返回码配置文件
require_once './Public/inc/code.config.php';
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

// 定义应用目录
define('APP_PATH','./Application/');

// 绑定Home模块到当前入口文件
define('BIND_MODULE','Customer');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';