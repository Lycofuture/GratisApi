<?php
header("Content-Type:text/html;charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(14); // 调用统计函数
addAccess();//调用统计函数
require "../../need.php";//引入封装好的函数
/* End */
$type = @$_REQUEST["type"];
$msg = urlencode((String) @$_REQUEST["msg"]);
$url = "https://wxapp.translator.qq.com/api/translate?sourceText=".$msg."&source=auto&target=auto&platform=MQQAPP&candidateLangs=zh%7Cen&guid=wxapp_openid_1576171882_ptxba365xp";
$rst= get_curl($url,0);
$nr1 = '/"targetText":"(.*?)"/';
preg_match_all($nr1,$rst,$nr1);

if ($msg!=''){
    if ($type==''||$type==null){
        echo "内容：".$_REQUEST['msg']."\n翻译内容：".$nr1[1][0];
    }else
    if ($type=="male"){
        echo $nr1[1][0];
    }else{
        echo need::json(array('code'=>1,'text'=>$nr1[1][0]));
    }
}else{
    echo "请输入需要翻译的内容！";
}

function get_curl($url,$post=0,$referer=1,$cookie=0,$header=0,$ua=1,$nobaody=0,$json=0){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$httpheader[] = "Accept:application/json";
$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
$httpheader[] = "Connection:close";
if($json){
$httpheader[] = "Content-Type:application/json; charset=utf-8";}
curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
if($post){
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);}
if($header){
curl_setopt($ch, CURLOPT_HEADER, TRUE);}
if($cookie){
curl_setopt($ch, CURLOPT_COOKIE, $cookie);}
if($referer){
if($referer==1){
curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
}else{
curl_setopt($ch, CURLOPT_REFERER, $referer);
}}
if($ua){
curl_setopt($ch, CURLOPT_USERAGENT,$ua);
}else{
curl_setopt($ch, CURLOPT_USERAGENT,'Dalvik/2.1.0 (Linux; U; Android 9; 16s Build/PKQ1.190202.001)');}
if($nobaody){
curl_setopt($ch, CURLOPT_NOBODY,1);}
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_ENCODING, "gzip");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$ret = curl_exec($ch);
curl_close($ch);
return $ret;}

?>