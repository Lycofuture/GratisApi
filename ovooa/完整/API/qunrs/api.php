<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(69); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */
$s=@$_GET["s"];
$p=@$_GET["p"];
$qq=@$_GET["qq"];
$Group=@$_GET["group"];
$type = @$_GET["type"];
if($s == '' || $s == null){

echo need::json(array("code"=>"-1","text"=>"请输入skey"));exit;

}else

if($p == '' || $p == null){

echo need::json(array("code"=>"-2","text"=>"请输入pskey"));exit;

}else

if(!need::is_num($qq)){

echo need::json(array("code"=>"-3","text"=>"请输入QQ号"));exit;

}else

if($Group == '' || $Group == null){

echo need::json(array("code"=>"-4","text"=>"请输入群号"));exit;

}else{

$bkn=need::GTK($s);

$post = need::teacher_curl('https://qun.qq.com/cgi-bin/qun_mgr/search_group_members',[
'cookie'=>'skey='.$s.';uin=o'.$qq.';p_skey='.$p.';p_uin=o'.$qq.'',
'post'=>[
'gc'=>$Group,
'st'=>'0',
'end'=>'20',
'sort'=>'0',
'bkn'=>$bkn
]
]);

$post = json_decode($post, true);

//$post = json_encode($post,JSON_UNESCAPED_UNICODE);

$count = isset($post['mems']) ? count(@$post["mems"]) : 0;

$ren = @$post["count"];

if($count == 0){

echo need::json(array("code"=>"-5","text"=>"key过期或者输入QQ不在查询群内！"));exit;

}

if($type == 'list'){

echo "共有".$ren."人\r如下：";

for ($a = 0 ; $a < $count ; $a++){

echo "\r\r".($a+1).".QQ：".$post["mems"][$a]["uin"]."\r昵称：".$post["mems"][$a]["nick"]."";

}}else{

echo need::json(array("code"=>"1","num"=>$ren));

}}

?>