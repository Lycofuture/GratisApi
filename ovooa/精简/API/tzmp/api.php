<?php
error_reporting(E_ALL || ~E_NOTICE);//禁止显示PHP错误
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(97); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件

/* End */

$QQ=$_GET["qq"];

if(!$QQ){
	need::send(array("code"=>"-1","text"=>"请输入QQ"));
}
if(!need::is_num($QQ))
{
	exit(need::json(array("code"=>"-10","text"=>"请输入正确的QQ号")));
}
$ua = $_SERVER['HTTP_USER_AGENT'];
if(!$ua || preg_match('/Android/i', $ua))
{
	$url = "mqq://card/show_pslcard?src_type=internal&version=1&uin=".$QQ."&card_type=person&source=sharecard";
} else {
	$url = 'tencent://message/?Menu=yes&uin='.$QQ.'&Site=80fans&Service=300&sigT=45a1e5847943b64c6ff3990f8a9e644d2b31356cb0b4ac6b24663a3c8dd0f8aa12a545b1714f9d45';//'tencent://message/?site=6&server=300&sinT=true&uin='.$QQ;
}

header("Location:{$url}");