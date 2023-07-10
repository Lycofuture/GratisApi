<?php

/* Start */

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(43); // 调用统计函数

require "../../need.php";//引入封装好的函数

/* End */

$ip = @$_REQUEST["IP"];

$type = @$_REQUEST["type"];
if(!$ip || !preg_match('/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$/m', $ip))
{
	exit(need::json(array("code"=>"-1","text"=>"请填写正确的IP！")));
}
$se=json_decode(curl("http://opendata.baidu.com/api.php?query=".$ip."&co=&resource_id=6006&t=1433920989928&ie=utf8&oe=utf-8&format=json","GET",0,0),true);

if($se["data"][0]["location"]==""){

exit(need::json(array("code"=>"-1","text"=>"请填写正确的IP！")));

}else{

if($ip==""){

exit(need::json(array("code"=>"-2","text"=>"请填写参数IP")));

}

else if($ip!="")

{

if($type == "text"){

echo $se["data"][0]["location"];

}else{

echo need::json(array("code"=>"1","text"=>$se["data"][0]["location"]));

}

}

}

?>