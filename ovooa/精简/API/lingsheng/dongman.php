<?php
header("Content-type: text/html; charset=utf-8");

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(17); // 调用统计函数
/* End */

require ('../../curl.php');//引入curl文件

require ('../../need.php');//引入bkn文件

$type = @$_REQUEST["type"];

$arr=range(0,25);

shuffle($arr);

foreach($arr as $values);

$aaa=file_get_contents("http://ring.djduoduo.com/ring_enc.php?cmd=getlist&q=Ao4N2N2CMUnCTUNaOOC%2FELMqq3cDNeTIoixgzDPIYbW9LWMHXrYRZ4AF271Ox0IxatF2qKy079S9LWMHXrYRZ6UNtm%2FaczBAiX7IAEsTEFBQ%2FdTj2GbNN727OeVlFIVrSm%2FzB4%2FlUaborsOKJkFoPzzvRWUuqCjkSbAEXSqQ0eQvzv8QvJvvhD2W7AOdl3yBEfh0bpzd2ESAQHGYckurpFyP9KZF3SjcrN6oE1qidT2qHWG1FKA9CASW9XMv%2FewMba8pkmSNekG8k%2B2hY3Zv%2Fl%2FVvI83xi76cFcxQVndzPdV%2FXrW%2FYavow%3D%3D");

preg_match_all("/name=\"(.*?)\"/",$aaa,$bbb);

$bbb=$bbb[1][$values];

preg_match_all("/artist=\"(.*?)\"/",$aaa,$fff);

$fff=$fff[1][$values];

preg_match_all("/head_url=\"http:\/\/cdnuserprofilebd\.shoujiduoduo\.com\/(.*?)\"/",$aaa,$ccc);

$ccc = @$ccc[1][$values] ?: null;

preg_match_all("/mp3url=\"(.*?)\"/",$aaa,$eee);
$eee=$eee[1][$values];

if($type=="json"){
echo 'json:{"app":"com.tencent.structmsg","desc":"动漫铃声","view":"music","ver":"0.0.0.1","prompt":"[分享]动漫铃声","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$fff.'","jumpUrl":"http://cdnringbd.shoujiduoduo.com'.$eee.'","musicUrl":"http://cdnringbd.shoujiduoduo.com'.$eee.'","preview":"http://cdnuserprofilebd.shoujiduoduo.com/'.$ccc.'","sourceMsgId":"0","source_icon":"","source_url":"","tag":"动漫铃声","title":"'.$bbb.'"}},"config":{"autosize":true,"ctime":1612080759,"forward":true,"token":"4d074400cb116a11abe00f9eee5eb49b","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":202347537}"}';

}else

if($type == "xml"){

header("Content-type: text; charset=utf-8");

echo 'card:3<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]动漫铃声" sourceMsgId="0" url="http://cdnringbd.shoujiduoduo.com'.$eee.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="http://cdnuserprofilebd.shoujiduoduo.com/'.$ccc.'" src="http://cdnringbd.shoujiduoduo.com'.$eee.'" /><title>'.$bbb.'</title><summary>'.$fff.'</summary></item><source name="" icon="" action="app" a_actionData="com.netease.cloudmusic" i_actionData="tencent100495085://" appid="100495085" /></msg>';

}else{

echo '±img=http://cdnuserprofilebd.shoujiduoduo.com/'.$ccc.'±';
echo "铃声名字：".$bbb."\r";
echo "铃声分享人：".$fff."\r";
echo "播放链接：http://cdnringbd.shoujiduoduo.com".$eee."";

}

?>