<?php
/* Start */

require ("../function.php"); // 引入函数文件

addApiAccess(43); // 调用统计函数
addAccess();//调用统计函数
require "../../need.php";//引入封装好的函数


/* End */

$ip = $_GET["ip"];

$msg = $_GET["msg"];

$type = $_GET["type"];

if(!$ip && !$msg){

exit(need::json(array("code"=>"-1","text"=>"请输入IP地址！或者位置！")));

}

if($ip){

$A = curl("http://whois.pconline.com.cn/ipJson.jsp?callback=&ip=".$ip."","GET",0,0);

//echo $A;
//exit;

if(!$A){

exit(need::json(array("code"=>"-3","text"=>"IP数据获取失败！")));

}

$encode = mb_detect_encoding($A, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
$str_encode = mb_convert_encoding($A, 'UTF-8', $encode);
preg_match("/addr\":\"(.*?)\"/",$str_encode,$addr);
//echo $addr[1];
//Exit;
$B=urlencode($addr[1]);
$C = curl("https://apis.map.qq.com/jsapi?qt=geoc&key=UGMBZ-CINWR-DDRW5-W52AK-D3ENK-ZEBRC&output=jsonp&pf=jsapi&ref=jsapi&cb=jsapi&addr=".$B."","GET",0,0);
//echo $C;exit;

$D = mb_convert_encoding($C, 'UTF-8', $encode);
//echo $D;exit;

preg_match('/"error":(.*?),/',$D,$code);

$code = $code[1][0];

if($code == "0"){

preg_match("/pointx\":\"(.*?)\"/",$D,$X_zb);
//echo $X_zb[1];exit;

preg_match("/pointy\":\"(.*?)\"/",$D,$Y_zb);

if(!$type){

echo 'json:{"app":"com.tencent.map","desc":"地图","view":"LocationShare","ver":"0.0.0.1","prompt":"[应用]地图","meta":{"Location.Search":{"id":"","name":"位置分享","address":"'.$addr[1].'","lat":"'.$Y_zb[1].'","lng":"'.$X_zb[1].'","from":"plusPanel"}},"config":{"forward":1,"autosize":1,"type":"card"}}';

}else{

$W = curl("https://apis.map.qq.com/jsapi?qt=geoc&key=UGMBZ-CINWR-DDRW5-W52AK-D3ENK-ZEBRC&output=jsonp&pf=jsapi&ref=jsapi&cb=jsapi&addr=".$B."","GET",0,0);

preg_match("/pointx\":\"(.*?)\"/",$W,$X);

preg_match("/pointy\":\"(.*?)\"/",$W,$Y);
//echo $X[1];echo 1;exit;

echo need::json(array("code"=>"1","data"=>array("name"=>$msg,"lat"=>$Y[1],"lng"=>$X[1],"tips"=>"仅供参考！")));

}}
}else

if($msg){

$W = curl("https://apis.map.qq.com/jsapi?qt=geoc&key=UGMBZ-CINWR-DDRW5-W52AK-D3ENK-ZEBRC&output=jsonp&pf=jsapi&ref=jsapi&cb=jsapi&addr=".$msg."","GET",0,0);

preg_match("/pointx\":\"(.*?)\"/",$W,$X);

preg_match("/pointy\":\"(.*?)\"/",$W,$Y);

echo 'json:{"app":"com.tencent.map","desc":"地图","view":"LocationShare","ver":"0.0.0.1","prompt":"[应用]地图","meta":{"Location.Search":{"id":"","name":"位置分享","address":"'.$msg.'","lat":"'.$Y[1].'","lng":"'.$X[1].'","from":"plusPanel"}},"config":{"forward":1,"autosize":1,"type":"card"}}';

}else{

exit(need::json(array("code"=>"-4","text"=>"未知错误！状态码：".$code)));

}

