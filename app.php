<?php
// 应用入口文件
// 允许APP端AJAX跨域请求
header ( "Access-Control-Allow-Origin: *" );
// 自动加载
require_once './Public/inc/autoload.php';

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);
// 开启日志调试模式，不记录日志设为false
define('APPLOG_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

// 绑定App模块到当前入口文件
define('BIND_MODULE','App');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';