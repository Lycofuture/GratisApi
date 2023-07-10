<?php
error_reporting(0);
define('VERSION', '1.11');
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
define('ROOT_QQ', '2354452553');
define('SYS_KEY', '1008611');
date_default_timezone_set("PRC");
$date = date("Y-m-d H:i:s");
session_start();
include_once(SYSTEM_ROOT.'pclzip.php');

include_once(SYSTEM_ROOT."expansion.php");

include_once(SYSTEM_ROOT."function.php");

//error_reporting(E_ALL^E_NOTICE^E_WARNING);
?>