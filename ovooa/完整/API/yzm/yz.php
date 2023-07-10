<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(24); // 调用统计函数
/* End */
/**
 * 接受用户登陆时提交的验证码
 */
session_start();
//1. 获取到用户提交的验证码
$verify = (String)@$_REQUEST["code"];
//2. 将session中的验证码和用户提交的验证码进行核对,当成功时提示验证码正确，并销毁之前的session值,不成功则重新提交
// print_r($_SESSION);
if(empty($verify)){
    echo '哈哈';
}else
if(strtolower((String)@$_SESSION["code"]) == strtolower($verify)){
    echo 'true';
    $_SESSION["code"] = "";
}else{
    echo 'false';
}
?>