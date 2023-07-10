<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数
/* End */
$msg=$_GET["msg"];
$b=$_GET["n"];
$c=$_GET["type"];
$p=$_GET["p"]?:"1";
$sc=$_GET["sc"]?:'10';
$h=$_GET["h"]?:'\r';
function post_data_test($url,$data){
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $url); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($curl, CURLOPT_POST, 1); 
curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
curl_setopt($curl, CURLOPT_HEADER, 0); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl); 
curl_close($curl); 
return $result; 
}

function replace_unicode_escape_sequence($match)
{
return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}

$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=search&count=15&source=kugou&pages=".$p."&name=".urlencode($msg);
$data=post_data_test($url,$data);
$list = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $data);
$list=str_replace('[', '', $list);
$list=str_replace(']', '', $list);
$result = preg_match_all("/\"id\":\"(.*?)\",\"name\":\"(.*?)\",\"artist\":\"(.*?)\"/",$list,$nute);
if($b==null)
{
for ($x=0; $x < $result && $x < $sc ; $x++) 
{
$jec=$nute[2][$x];
$je=$nute[3][$x];
echo ($x+1).'：'.$jec.'-'.$je.''.$h.'';
}
echo '当前为第'.$p.'页'.$h.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';
}
else
if($result<$b)
{for ($x=0; $x < $result && $x < $sc ; $x++) 
{
$jec=$nute[2][$x];
$je=$nute[3][$x];
echo ($x+1).'：'.$jec.'-'.$je.''.$h.'';
}
echo '\r当前为第'.$p.'页'.$h.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';
}
else
if($c=='text'||$c==''||$c==null)
{
$a=($b-1);
$b=$nute[1][$a];
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=pic&id=".$b."&source=kugou";
$data=post_data_test($url,$data);
$data=str_replace('\\', '', $data);
preg_match_all("/url\":\"(.*?)\"/",$data,$dat);
$dat=$dat[1][0];
echo '±img='.$dat.'±';
echo '歌名：'.$nute[2][$a].''.$h.'';
echo '歌手：'.$nute[3][$a].''.$h.'';
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=url&id=".$b."&source=kugou";
$data=post_data_test($url,$data);
$data=str_replace('\\', '', $data);
preg_match_all("/url\":\"(.*?)\"/",$data,$dat);
$dat=$dat[1][0];
echo '链接：'.$dat;
}
else
if($c=='json')
{
$a=($b-1);
$b=$nute[1][$a];
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=pic&id=".$b."&source=kugou";
$data=post_data_test($url,$data);
$data=str_replace('\\', '', $data);
preg_match_all("/url\":\"(.*?)\"/",$data,$dat);
$da=$dat[1][0];
$m=$nute[2][$a];
$n=$nute[3][$a];
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=url&id=".$b."&source=kugou";
$data=post_data_test($url,$data);
$data=str_replace('\\', '', $data);
preg_match_all("/url\":\"(.*?)\"/",$data,$dat);
$dat=$dat[1][0];
echo 'json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]酷狗音乐","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$n.'","jumpUrl":"qq.com","musicUrl":"'.$dat.'","preview":"'.$da.'","sourceMsgId":"0","source_icon":"","source_url":"","tag":"QQ音乐","title":"'.$m.'"}},"config":{"autosize":true,"ctime":1608220163,"forward":true,"token":"223d36909a398c6216a98ca8fc4dfd8a","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":2579988698}"}';
}
else
if($c=="xml")
{
$a=($b-1);
$b=$nute[1][$a];
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=pic&id=".$b."&source=kugou";
$data=post_data_test($url,$data);
$data=str_replace('\\', '', $data);
preg_match_all("/url\":\"(.*?)\"/",$data,$dat);
$da=$dat[1][0];
$m=$nute[2][$a];
$n=$nute[3][$a];
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=url&id=".$b."&source=kugou";
$data=post_data_test($url,$data);
$data=str_replace('\\', '', $data);
preg_match_all("/url\":\"(.*?)\"/",$data,$dat);
$dat=$dat[1][0];
header("Content-type:text/text");
echo "card:3<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><msg serviceID=\"2\" templateID=\"1\" action=\"web\" brief=\"[分享]酷狗音乐\" sourceMsgId=\"0\" url=\"".$dat."\" flag=\"0\" adverSign=\"0\" multiMsgFlag=\"0\"><item layout=\"2\"><audio cover=\"".$da."\" src=\"".$dat."\" /><title>".$m."</title><summary>".$n."</summary></item><source name=\"\" icon=\"\" url=\"\" action=\"app\" a_actionData=\"com.netease.cloudmusic\" i_actionData=\"tencent100495085://\" appid=\"100495085\" /></msg>";
}else{
$a=($b-1);
$b=$nute[1][$a];
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$data="types=pic&id=".$b."&source=kugou";
$data=post_data_test($url,$data);
$data=str_replace('\\', '', $data);
preg_match_all("/url\":\"(.*?)\"/",$data,$dat);
$dat=$dat[1][0];
$url="http://y.webzcz.cn/api.php?callback=jQuery1113044937510914188716_1580188184859";
$date="types=url&id=".$b."&source=kugou";
$date=post_data_test($url,$date);
$date=str_replace('\\', '', $date);
preg_match_all("/url\":\"(.*?)\"/",$date,$datt);
$datt=$datt[1][0];
$array=array("img"=>$dat,"name"=>$nute[2][$a],"singer"=>$nute[3][$a],"url"=>$datt);
echo json_encode($array,JSON_UNESCAPED_UNICODE);
}
?>