<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(9); // 调用统计函数
/* End */
$msg = $_GET['msg'];

$b = $_GET['n'];

$type=$_GET["type"];

$p=$_GET["p"]?:"1";

$h=$_GET["h"]?:'\r';

$sc=$_GET["sc"]?:'10';

$str = 'http://s.music.163.com/search/get/?src=lofter&type=1&filterDj=true&limit=15&offset='.$p.'&s='.$msg.'';


//

http://music.163.com/api/search/pc?type=1&limit=40&s=9420

$str=curl($str,"GET",0,0);

$stre = '/{"id":(.*?),"name":"(.*?)","artists":\[{"id":(.*?),"name":"(.*?)","picUrl":(.*?)}\],"album":{"id":(.*?),"name":"(.*?)","artist":{"id":(.*?),"name":"(.*?)","picUrl":(.*?)},"picUrl":"(.*?)"},"audio/'; 

$result = preg_match_all($stre,$str,$trstr);

if($result== 0){

echo '搜索不到与'.$_GET['msg'].'的相关歌曲，请稍后重试或换个关键词试试。';

}else{

if($b== null){
for( $i = 0 ; $i < $result && $i < $sc ; $i ++ ){

$ga=$trstr[2][$i];//获取歌名

$gb=$trstr[4][$i];//获取歌手

echo ($i+1).'：'.$ga.'--'.$gb.''.$h.'';

}

echo ''.$h.'当前为第'.$p.'页'.$h.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';

}else{

$i=($b-1);

$id=$trstr[1][$i];//id

$ga=$trstr[2][$i];//获取歌名

$t=$trstr[11][$i];//图

$gb=$trstr[4][$i];//获取歌手

if(!$id == ' '){die ('列表中暂无序号为『'.$b.'』的歌曲。');exit;}

if ($b>$result){
for( $i = 0 ; $i < $result && $i < $sc ; $i ++ ){

$ga=$trstr[2][$i];//获取歌名

$gb=$trstr[4][$i];//获取歌手

echo ($i+1).'：'.$ga.'--'.$gb.''.$h.'';

}

echo ''.$h.'当前为第'.$p.'页'.$h.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';exit;}


if($type=='json'){

echo '{"desc":"网易云音乐","singer":"'.$gb.'","url":"http://music.163.com/song/media/outer/url?id='.$id.'","img":"'.$t.'","name":"'.$ga.'"}';

exit;}
if($type=="xml"){
header("Content-type:text/text");
echo 'card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享] '.$ga.'" sourceMsgId="0" url="http://music.163.com/#/song?id=28427707" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$t.'" src="http://music.163.com/song/media/outer/url?id='.$id.'" /><title>'.$ga.'</title><summary>'.$gb.'</summary></item><source name="网易云音乐" icon="http://pp.myapp.com/ma_icon/0/icon_1168851_1579673392/256" url="http://url.cn/5pl4kkd" action="app" a_actionData="com.netease.cloudmusic" i_actionData="tencent100495085://" appid="100495085" /></msg>';
exit;
}
echo '图片：'.$t.''.$h.'歌名：'.$ga.''.$h.'歌手：'.$gb.''.$h.'播放链接：http://music.163.com/song/media/outer/url?id='.$id.'';
}}

?>