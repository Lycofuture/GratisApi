<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(79); // 调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$qq = @$_GET["qq"];

$s = @$_GET["s"];

$group = @$_GET["group"];

$uin = @$_GET["uin"];

$p = @$_GET["p"];

$name = @$_GET["name"];

if($qq > 9999999999 || $qq < 10000){

exit(need::json(array("code"=>"-11","text"=>"提供key的QQ号输入错误！")));

}

if($group > 9999999999 || $group < 10000){

exit(need::json(array("code"=>"-13","text"=>"群号输入错误！")));

}

if($uin > 9999999999 || $uin < 10000){

exit(need::json(array("code"=>"-12","text"=>"需要修改名片的QQ号输入错误！")));

}


if(!$qq){

exit(need::json(array("code"=>"-1","text"=>"请输入提供key的QQ")));

}

if(!$group){

exit(need::json(array("code"=>"-2","text"=>"请输入群号")));

}

if(!$s){

exit(need::json(array("code"=>"-3","text"=>"请输入管理员的skey")));

}

if(!$p){

exit(need::json(array("code"=>"-4","text"=>"请输入管理员的pskey")));

}

if(!$name){

exit(need::json(array("code"=>"-5","text"=>"请输入需要更改的名字")));

}

if(!$uin){

exit(need::json(array("code"=>"-6","text"=>"请输入需要更改名字的QQ")));

}


$url = need::teacher_curl("https://qun.qq.com/cgi-bin/qun_mgr/set_group_card",[
'cookie'=>'uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$s.'; p_skey='.$p,
'post'=>[
'gc'=>$group,
'u'=>$uin,
'name'=>$name,
'bkn'=>need::GTK($s)
]
]);

$data = json_decode($url,true);

$code = $data["ec"];

if($code == "0"){

echo need::json(array("code"=>"1","text"=>"修改成功！"));

}else

if($code == "4"){

echo need::json(array("code"=>"-7","text"=>"key失效了"));

}else

if($code == "-100005"){

echo need::json(array("code"=>"-8","text"=>"我不是管理员！或者需要修改的人不在这个群！"));

}else

if($code == "7"){

echo need::json(array("code"=>"-9","text"=>"我不在这个群呀！"));

}else{

exit(need::json(array("code"=>"-10","text"=>"未知错误，错误码：".$code)));

}


?>