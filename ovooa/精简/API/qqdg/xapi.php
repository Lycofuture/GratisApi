<?php
     $counter = intval(file_get_contents("counter.dat"));  
     $_SESSION['#'] = true;  
     $counter++;  
     $fp = fopen("counter.dat","w");  
     fwrite($fp, $counter);  
     fclose($fp); 
 ?>
<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

require("curlid.php");
$msg = $_GET['msg'];
$b = $_GET['n'];
$type =$_GET["type"];
$p=$_GET["p"]?:"1";
$h=$_GET['h']?:'\r';
$sc=$_GET["sc"]?:'10';
$data=curl('https://c.y.qq.com/soso/fcgi-bin/client_search_cp?ct=24&qqmusic_ver=1298&new_json=1&remoteplace=txt.yqq.center&searchid=36399371100683628&t=0&aggr=1&cr=1&catZhida=1&lossless=0&flag_qc=0&p='.$p.'&n='.$sc.'&w='.urlencode($msg).'&g_tk_new_20200303=797991061&g_tk=797991061&loginUin=2354452553&hostUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=0',"GET", 0, 0);
$json = json_decode($data, true);
//print_r($json);exit;
$s=count($json["data"]["song"]["list"]);
if($s==0){exit("抱歉，返回数据为空。");}
if($b==""||$b==null){
for( $i = 0 ; $i < $s && $i < $sc ; $i ++ ){
$ga=$json["data"]["song"]["list"][$i]["name"];
$gb=$json["data"]["song"]["list"][$i]["singer"][0]["name"];
$pay = $json["data"]["song"]["list"][$i]["pay"]["pay_play"];
if($pay=="0"){
$pay='[免费]';}else{
$pay='[收费]';
}
echo ($i+1).'：'.$ga.'--'.$gb.''.$pay.''.$h.'';}
echo ''.$h.'当前为第'.$p.'页'.$h.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';
}else{
$i=($b-1);
$mid=$json["data"]["song"]["list"][$i]["mid"];
$j=curl_id($mid);
$tu="http://y.gtimg.cn/music/photo_new/T002R500x500M000".$json["data"]["song"]["list"][$i]["album"]["pmid"].".jpg";
$ga=$json["data"]["song"]["list"][$i]["name"];//获取歌名
$gb=$json["data"]["song"]["list"][$i]["singer"][0]["name"];//获取歌手
if ($b>15){
for( $i = 0 ; $i < $s && $i < $sc ; $i ++ ){
$ga=$json["data"]["song"]["list"][$i]["songname"];
$gb=$json["data"]["song"]["list"][$i]["singer"][0]["name"];
$pay = $json["data"]["song"]["list"][$i]["pay"]["pay_play"];
if($pay=="0"){
$pay='[免费]';}else{
$pay='[收费]';
}
echo ($i+1).'：'.$ga.'--'.$gb.''.$pay.''.$h.'';}
echo ''.$h.'当前为第'.$p.'页'.$h.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';
}else
if ($type=='json'||$type=='JSON'){
echo need::json(array("code"=>"1","data"=>array("picture"=>$tu,"songname"=>$ga,"singer"=>$gb,"url"=>$j)));
}else
if ($type=='xml'){
$gh=str_replace('&','&amp;',$j);
header("Content-type:text/text");
echo 'card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]'.$ga.'" sourceMsgId="0" url="'.$gh.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$tu.'" src="'.$gh.'" /><title>'.$ga.'</title><summary>'.$gb.'</summary></item><source name="QQ音乐" icon="https://i.gtimg.cn/open/app_icon/01/07/98/56/1101079856_100_m.png?date=20200331&amp;_tcvassp_0_=750shp&amp;_tcvassp_0_1765997760=750shp" action="app" a_actionData="" i_actionData="" appid="" /></msg>';
}else
{

echo '±img='.$tu.'±'.$h.'歌名：'.$ga.''.$h.'歌手：'.$gb.''.$h.'播放链接：'.$j;


}
}



?>