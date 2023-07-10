<?php
header("Content-type: text/html; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数
/* End */
$a = urlencode($_GET["msg"]);
 $b = urlencode($_GET["n"]);
 $p = $_GET["p"]?:"0";
$type= $_GET["type"];
$h=$_GET["h"]?:'\r';
$sc=$_GET["sc"]?:'10';
$data = curl("http://search.kuwo.cn/r.s?client=kt&pn=".$p."&rn=15&uid=1179647890&ver=kwplayer_ar_9.2.7.1&vipver=1&show_copyright_off=1&newver=1&correct=1&ft=music&cluster=0&strategy=2012&encoding=utf8&rformat=json&vermerge=1&mobi=1&issubtitle=1&all=".urlencode($a),"GET",0,0);//用已经封装好的curl进行GET请求

$result = preg_match_all('/"FARTIST":"(.*?)","FORMAT":"(.*?)","FSONGNAME":"(.*?)","KMARK":"(.*?)","MINFO":"(.*?)","MUSICRID":"MUSIC_(.*?)","MVFLAG":"(.*?)","MVPIC":"(.*?)","MVQUALITY":"(.*?)","NAME":"(.*?)","NEW":"(.*?)","ONLINE":"(.*?)","PAY":"(.*?)","PROVIDER":"(.*?)","SONGNAME":"(.*?)"/',$data,$v);//正则匹配需要的东西
if($result== 0){
echo '搜索不到与'.$_GET['msg'].'的相关歌曲，请稍后重试或换个关键词试试。';
}else{
if($b== null){
for( $i = 0 ; $i < $result && $i < $sc ; $i ++ ){
$ga=urldecode($v[15][$i]);//获取名称
$gb=urldecode($v[1][$i]);//歌手
echo ($i+1).'：'.$ga.'——'.$gb.''.$h.'';
}
echo ''.$h.'当前为第'.$p.'页'.$h.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';
}else{
//
$i=($b-1);
$ga=$v[15][$i];//获取名称
$gb=$v[1][$i];//歌手
$id=$v[6][$i];//rid
if(!$b == ' '){
die ('列表中暂无序号为『'.$b.'』的相关内容，请输入存在的序号进行搜索。');
}
$t = curl("http://artistpicserver.kuwo.cn/pic.web?user=867401041651110&android_id=f243cc2225eac3c9&prod=kwplayer_ar_9.2.8.1&corp=kuwo&source=kwplayer_ar_9.2.8.1_qq.apk&type=rid_pic&pictype=url&content=list&size=320&rid=".$id,"GET",0,0);//http://mobile.kuwo.cn/mpage/html5/songinfoandlrc?mid=40900571
$l = curl("http://www.kuwo.cn/url?format=falc&rid=".$id."&response=url&type=convert_url3&br=128kmp3&from=web&t=&reqId=","GET",0,0);//http://antiserver.kuwo.cn/anti.s?type=convert_url&rid=MUSIC_40900571&format=mp3&response=url
preg_match_all('/"url": "(.*?)"/',$l,$l);
$l=$l[1][0];
if($type=="json"){
echo 'json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$ga.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gb.'","jumpUrl":"qq.com","musicUrl":"'.$l.'","preview":"'.$t.'","sourceMsgId":"0","source_icon":"","source_url":"","tag":"酷我音乐","title":"'.$ga.'"}},"text":"","sourceAd":""}';
}else
if($type=="xml"){
$g=str_replace('&','&amp;',$gb);
header("Content-type:text/text");
echo 'card:3<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]酷我音乐" sourceMsgId="0" url="'.$l.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$t.'" src="'.$l.'" /><title>'.$ga.'</title><summary>'.$g.'</summary></item><source name="酷我音乐" icon="http://pp.myapp.com/ma_icon/0/icon_11771_1593680980/96" action="app" a_actionData="com.netease.cloudmusic" i_actionData="tencent100495085://" appid="100495085" /></msg>';
}else
if ($type=='text'||$type==''||$type==null){
echo '图片：'.$t.''.$h.'歌名：'.$ga.''.$h.'歌手：'.$gb.''.$h.'播放链接：'.$l.'';
}else{
$array=array("img"=>$t,"name"=>$ga,"singer"=>$gb,"url"=>$l);
echo json_encode($array,JSON_UNESCAPED_UNICODE);
}
}}
?>