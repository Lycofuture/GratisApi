<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数
/* End */
//echo "你好";
$t=$_GET["type"];
$header=[
'User-Agent: okhttp/3.8.1',
];
$data=json_decode(curl("http://floor.huluxia.com/post/list/ANDROID/2.1?platform=2&gkey=000000&app_version=4.0.0.5.3&versioncode=20141430&market_id=floor_huluxia&_key=&device_code=%5Bw%5Dc0%3A9f%3A05%3A08%3A1a%3A13-%5Bi%5D862020033909636&start=0&count=40&cat_id=70&tag_id=0&sort_by=1","GET",$header,0),true);
$rand=array_rand($data["posts"]);
if(!strstr($data["posts"][$rand]["title"],"【")){
$rand=array_rand($data["posts"]);
}
if(!strstr($data["posts"][$rand]["title"],"【")){
$rand=array_rand($data["posts"]);
}
//preg_match_all("/【(.*?)截图(.*?)】/",$data["posts"][$rand]["detail"],$Screenshot);
preg_match_all("/【(.*?)】/",$data["posts"][$rand]["title"],$type);
$activity=str_replace(array("\n",'\n\n'),array('\n','\n'),$data["posts"][$rand]["detail"]);
preg_match_all("/【(.*?)介绍(.*?)】(.*?)【/",$activity,$Introduction);
preg_match_all("/【(.*?)日期(.*?)】(.*?)【/",$activity,$time);
if(!$time[3][0]){
preg_match_all("/【(.*?)时间(.*?)】(.*?)【/",$activity,$time);
}
preg_match_all("/【(.*?)规则(.*?)】(.*?)【/",$activity,$rule);
preg_match_all("/【(.*?)方式(.*?)】(.*?)【/",$activity,$manner);
preg_match_all("/【(.*?)说明(.*?)】(.*?)【/",$activity,$explanation);
if($explanation==""||$explanation==null){
$explanation="无说明";
}
if(strstr($explanation[3][0],"关注")||strstr($explanation[3][0],"反馈")){
$explanation="已被过滤！";
}else{
$explanation=$explanation[3][0];
}
if(!$manner[3][0]){
	echo json_encode(array("code"=>1001,"text"=>"线报获取失败！","reason"=>"返回数据为空白可能被拉黑了"),JSON_UNESCAPED_UNICODE);
exit();
}
//print_r($data["posts"][$rand]);
$array=array(
"code"=>1000,
"data"=>array(
"type"=>$type[1][0],
"title"=>strip_tags(str_replace($type[0][0],"",$data["posts"][$rand]["title"])),
"Time"=>str_replace('\n',"",$time[3][0]),
"rule"=>str_replace('\n',"",$rule[3][0]),
"manner"=>str_replace('\n',"",$manner[3][0]),
"explanation"=>str_replace('\n',"",$explanation),
"Introduction"=>str_replace('\n',"",$Introduction[3][0]),
"Picture"=>$data["posts"][$rand]["images"],
),
"user"=>array(
"userID"=>$data["posts"][$rand]["user"]["userID"],
"nick"=>strip_tags($data["posts"][$rand]["user"]["nick"]),
));
//print_r($array);
if ($t=="json"){
echo json_encode($array,JSON_UNESCAPED_UNICODE);}
else{
echo "☆活动标题：".strip_tags(str_replace($type[0][0],"",$data["posts"][$rand]["title"]))."\n☆活动时间：".str_replace('\n',"",$time[3][0])."\n☆参与方式：".str_replace('\n',"",$manner[3][0])."\n".str_replace('\n',"",$manner[3][0])."\n☆活动详情：".str_replace('\n',"",$explanation)."\n☆活动类型：".$type[1][0]."\n☆活动图片：±img=".$data["posts"][$rand]["images"][0]."±";
}
?>