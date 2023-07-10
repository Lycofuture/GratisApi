<?php
include "./assist/common.php";
require_once "./config.php";
require_once "./assist/lib/dic_notify.class.php";
include_once("./assist/parametercheck.php");
$GLOBALS["thehost"] = $thehost;
//计算得出通知验证结果
$alidicNotify = new AlidicNotify($config);
$verify_result = $alidicNotify->verifyReturn();

if ($verify_result) {
    extended_run("dic/授权");
    extended_run("dic/信息");
    extended_run("dic/配置");
} else {
    echo "验效失败";
}