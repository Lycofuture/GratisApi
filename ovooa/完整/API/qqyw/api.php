<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */

require ('../../curl.php');//引入curl文件

require ('../../need.php');//封装函数

$Skey = @$_GET["s"];//Skey

$uin = @$_GET["qq"];//qq

if ($Skey == '' || $Skey == null){

exit(need::json(array("code"=>"-3","text"=>"Skey为空白")));}

if ($uin == '' || $uin == null){

exit(need::json(array("code"=>"-4","text"=>"查询账号为空")));

}

$time = need::getMillisecond();//毫秒级时间戳

$html = @need::teacher_curl('https://api.unipay.qq.com/v1/r/1450002155/wechat_query?cmd=7&pf=vip_m-2199-html5&pfkey=pfkey&from_h5=1&session_token=k9bepujAccoDgXDx0sBs5krS6EX348Aa'.$time.'&r=0.589784'.time().'&openid='.$uin.'&openkey='.$Skey.'&session_id=uin&session_type=skey&webversion=h5_mobileqqV0.1&format=json',[
'cookie'=>'uin=o'.$uin.';skey='.$Skey.'',
]);//携带cookie进行get请求

$json = str_replace('"ret" : 0,','"ret" : "0",',$html);

$json = json_decode($json,true);

$count = count($json["service"]);//判断业务列表是否为空

if ($count <= 0){

exit(need::json(array("code"=>"-2","text"=>"key好像过期了")));

}

$data = $json["service"];//解析业务列表

$expire = $data["expire"];//业务时间

for ($i = 0 ; $i < $count ; $i++){

if($data[$i]["year_service_name"] == '' || $data[$i]["year_service_name"] == null){

$year = "";}else{

$year = "(".$data[$i]["year_service_name"].")";

}//判断是否年费

if($data[$i]["upgrade_service_name"] == '' || $data[$i]["upgrade_service_name"] == null){

$name = "";
}else{

$name = "(".$data[$i]["upgrade_service_name"].")";}//判断有没有其它名字

if ($data[$i]["expire"]<=0){
}else{

$echo .= "——·——·——·——·——\\r".($i+1).".业务名称：".$data[$i]["service_name"].$name.$year."\\r距离到期时间：".$data[$i]["expire"]."天\\r";

}}//业务列表

if ($echo == '' || $echo == null){

exit(need::json(array("code"=>"-1","text"=>"无已开通业务")));//判断业务列表是不是空白

}else{

echo need::json(array("code"=>"1","text"=>$echo."——·——·——·——·——"));//返回内容

}

?>