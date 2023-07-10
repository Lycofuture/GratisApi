<?php
error_reporting(false);
//header('Content-Encoding:gzip');//开启gzip压
header('Connection: keep-alive');//开启TCP常连
//require 'fang.php';
require 'config.php';
// require __DIR__.'/siteMap.php';
// 页面初始化
require_once __DIR__.'/Include/Index.php';

?>