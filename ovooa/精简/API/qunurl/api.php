<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(89); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$Group = @$_GET["group"];

if(!$Group){

exit(need::json(array("code"=>"-2","text"=>"群号不可为空")));

}

$qq = @$_GET["qq"];

if(!$qq){

exit(need::json(array("code"=>"-1","text"=>"QQ号不可为空")));

}

$Skey = @$_GET["s"];


if(!$Skey){

exit(need::json(array("code"=>"-3","text"=>"Skey不可为空")));

}

$bkn = need::GTK($Skey);

if(!need::is_num($qq)){

exit(need::json(array("code"=>"-10","text"=>"请输入正确的QQ号")));

}

if(!need::is_num($Group)){

exit(need::json(array("code"=>"-11","text"=>"请输入正确的群号")));

}


$data = need::teacher_curl('https://admin.qun.qq.com/cgi-bin/qun_admin/get_join_link',[
'post'=>[
'gc'=>$Group,
'type'=>'1',
'bkn'=>$bkn
],
'cookie'=>'uin=o'.$qq.'; skey='.$Skey.''
]);

$JSON = json_decode($data,true);

$code = $JSON["ec"];//状态码

if ($code=='0'){

exit(need::json(array("code"=>"1","text"=>$JSON["url"])));

}else

if($code=="4"){

exit(need::json(array("code"=>"-4","text"=>"Skey过期了哦")));

}else

if($code=="5"){

exit(need::json(array("code"=>"-5","text"=>"参数不正确请重试")));

}else

if($code=="7"){

exit(need::json(array("code"=>"-6","text"=>"账号不存在请重试")));

}

else

{

exit(need::json(array("code"=>"-8","text"=>"未知错误！状态码：".$code)));

}

