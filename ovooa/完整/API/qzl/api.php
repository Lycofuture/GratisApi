<?php

header("Content-type: text/html; charset=utf-8");

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(81); // 调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$Skey = @$_GET["s"];

$qq = @$_GET["qq"];

$p = @$_GET["p"];

$name = @$_GET["name"];

$bkn = need::GTK((String)$Skey);


if(!$qq){

exit(need::json(array("code"=>"-1","text"=>"请输入QQ号！")));

}

if(!$Skey){

exit(need::json(array("code"=>"-2","text"=>"请输入Skey！")));

}

if(!$name){

exit(need::json(array("code"=>"-3","text"=>"请输昵称！")));

}

if($qq > 9999999999 || $qq < 10000){

exit(need::json(array("code"=>"-4","text"=>"请输入正确的QQ号！")));

}


$nic = curl('https://r.qzone.qq.com/fcg-bin/cgi_get_score.fcg?mask=7&uins='.$qq,"GET",0,0);

preg_match_all('/\,"(.*?)"\,(.*?)\]\}\)/',$nic,$done);

//$nick = mb_convert_encoding($done[1][0],"UTF-8", "GBK");
$nick = str_replace(' ','+',$nick);
$data = need::teacher_curl('https://ti.qq.com/proxy/domain/oidb.tim.qq.com/v3/oidbinterface/oidb_0x587_52?sdkappid=20344&actype=2&t=0.6744340636679236&g_tk='.$bkn,[
'cookie'=>'uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$Skey.'',
'post'=>'{"str_nick":"'.$name.'"}',
'refer'=>'https://ti.qq.com/hybrid-h5/qq/nick?curNick='.$nick
]);

$JSON = json_decode($data,true);

$code = $JSON["ErrorCode"];

if($code == "0"){

echo need::json(array("code"=>"1","text"=>"修改成功！"));

}else

if($code == "151"){

echo need::json(array("code"=>"-5","text"=>"Skey已经过期了哦"));

}else{

exit(need::json(array("code"=>"-6","text"=>"未知错误！状态码：".$code)));

}


?>