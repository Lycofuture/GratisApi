<?php
exit('已关闭');
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(95); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$msg = @$_GET["msg"];

$type = @$_GET["type"];

if(!$msg){

if($type == "text"){

exit("缺少必填参数");

}else{

exit(need::json(array("code"=>"-1","text"=>"缺少必填参数！")));

}
}

$data = need::teacher_curl('https://ai.qq.com/cgi-bin/wxappdemo_textquiz',[
"post"=>"session_id=4e0756c1-0004-3ff3-90cd-7b626ac0c953&query=".$msg
]);

//print_r($data);

if(!$data){

exit(need::json(array("code"=>"-2","text"=>"请求错误")));

}

$data = json_decode($data,true);

$code = $data["ret"];//状态码

if($code=="0"){

$desc = $data["data"]["result"];

$picture = $data["data"]["image_url"];

if($type == "text"){

exit($desc);

}else{

if(!$picture){

exit(need::json(array("code"=>"1","text"=>$desc)));

}else{

exit(need::json(array("code"=>"1","text"=>$desc,"picture"=>$picture)));

}

}

}else

if($code=="4096"){

if($type == "text"){

exit("返回数据为空");

}else{

exit(need::json(array("code"=>"-3","text"=>"返回数据为空")));

}
}else{

exit(need::json(array("code"=>"-4","text"=>"未知错误，状态码：".$code."")));

}



