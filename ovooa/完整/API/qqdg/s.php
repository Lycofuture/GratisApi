<?php
     $counter = intval(file_get_contents("counter.dat"));  
     $_SESSION['#'] = true;  
     $counter++;  
     $fp = fopen("counter.dat","w");  
     fwrite($fp, $counter);  
     fclose($fp); 
 ?>
<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(11); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

//require("curlid.php");

$msg = $_GET['msg'];

$b = $_GET['n'];

$type =$_GET["type"];

$p=$_GET["p"]?:'1';

$h=$_GET['h']?:'\n';

$sc=$_GET["sc"]?:'15';

$tail = $_REQUEST['tail']?:'来自QQ音乐';

if($h == 'Robot'){

    die(need::Robot('../../','y.qq.com'));
    
}

$Robot = need::Robot('../../','Robot');

$key = $key;

$data=need::teacher_curl('https://c.y.qq.com/soso/fcgi-bin/client_search_cp?ct=24&qqmusic_ver=1298&new_json=1&remoteplace=txt.yqq.center&searchid=36399371100683628&t=0&aggr=1&cr=1&catZhida=1&lossless=0&flag_qc=0&p='.$p.'&n='.$sc.'&w='.urlencode($msg).'&g_tk_new_20200303=797991061&g_tk='.need::GTK($key).'&loginUin='.$Robot.'&hostUin='.$Robot.'&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=0',[
    'cookie'=>'qqmusic_uin=o'.$Robot.'; qqmusic_key='.$key.'; qqmusic_fromtag=30; ts_last=y.qq.com/portal/player.html; p_skey='.$key.'; skey='.need::Robot('../../','Skey').'; uin=o'.$Robot.'; p_uin=o'.$Robot.'; qm_keyst='.$key.'; o_cookie='.$Robot.'; '
]);

$json = json_decode($data, true);

//print_r($json);exit;
$s=count($json["data"]["song"]["list"]);

if($s==0){

exit("抱歉，返回数据为空。");

}
if($b==""||$b==null){

for( $i = 0 ; $i < $s && $i < $sc ; $i ++ ){

$ga=$json["data"]["song"]["list"][$i]["name"];

$gb=$json["data"]["song"]["list"][$i]["singer"][0]["name"];

$pay = $json["data"]["song"]["list"][$i]["pay"]["pay_play"];

if($pay=="0"){

$pay='[免费]';

}else{

$pay='[收费]';

}

echo ($i+1).'：'.$ga.'--'.$gb.''.$pay.''.$h.'';

}

echo ''.$h.'当前为第'.$p.'页'.$h;//.'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';

}else{

$i=($b-1);

$mid=$json["data"]["song"]["list"][$i]["mid"];

$j=curl_id($mid);

$tu="http://y.gtimg.cn/music/photo_new/T002R500x500M000".$json["data"]["song"]["list"][$i]["album"]["pmid"].".jpg";

$ga=$json["data"]["song"]["list"][$i]["name"];//获取歌名

$gb=$json["data"]["song"]["list"][$i]["singer"][0]["name"];//获取歌手

if ($b>$sc || $b < 1){

for( $i = 0 ; $i < $s && $i < $sc ; $i ++ ){

$ga=$json["data"]["song"]["list"][$i]["name"];

$gb=$json["data"]["song"]["list"][$i]["singer"][0]["name"];

$pay = $json["data"]["song"]["list"][$i]["pay"]["pay_play"];

if($pay=="0"){

$pay='[免费]';

}else{

$pay='[收费]';

}

echo ($i+1).'：'.$ga.'--'.$gb.''.$pay.''.$h.'';

}

echo ''.$h.'当前为第'.$p.'页'.$h.'请按照以上序列号选择！';//'您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';

}else

if ($type=='json'||$type=='JSON'){

echo 'json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"QQ音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gb.'","jumpUrl":"https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=9060506&hosteuin=oio5oKEsoenzNv**&songmid='.$mid.'&type=0&appsongtype=1&_wv=1&source=qq&sharefrom=gedan","musicUrl":"'.$j.'","preview":"'.$tu.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$ga.'"}},"prompt":"[分享]'.$ga.'","ver":"0.0.0.1","view":"music"}';

//echo 'json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$ga.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gb.'","jumpUrl":"https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=9060506&hosteuin=oio5oKEsoenzNv**&songmid='.$mid.'&type=0&appsongtype=1&_wv=1&source=qq&sharefrom=gedan","musicUrl":"'.$j.'","preview":"'.$tu.'","sourceMsgId":"0","source_icon":"","source_url":"","tag":"QQ音乐","title":"'.$ga.'"}},"config":{"autosize":true,"ctime":1575625127,"forward":true,"token":"7fef9b7d1e63b3500a42462126e9bc3d","type":"normal"},"text":"","sourceAd":""}';//,"config":{"forward":0,"showSender":0}

}else

if($type == 'json_org'){

echo need::json(array('code'=>'1','data'=>array('desc'=>'QQ音乐','singer'=>$gb,'image'=>$tu,'musicname'=>$ga,'musicurl'=>$gh)));

exit();

}else

if ($type=='xml'){

$gh=str_replace('&','&amp;',$j);

header("Content-type:text/text");

echo 'card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]'.$ga.'" sourceMsgId="0" url="'.$gh.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$tu.'" src="'.$gh.'" /><title>'.$ga.'</title><summary>'.$gb.'</summary></item><source name="QQ音乐" icon="https://i.gtimg.cn/open/app_icon/01/07/98/56/1101079856_100_m.png?date=20200331&amp;_tcvassp_0_=750shp&amp;_tcvassp_0_1765997760=750shp" action="app" a_actionData="" i_actionData="" appid="" /></msg>';

}else

if ($type=='text'){

exit('±img='.$tu.'±'.$h.'歌名：'.$ga.''.$h.'歌手：'.$gb.''.$h.'播放链接：'.$j);

}else

if ($type == 'X6'){

    echo 'json:{"app":"com.tencent.structmsg","desc":"QQ音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$ga.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gb.'","jumpUrl":"https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=9060506&hosteuin=oio5oKEsoenzNv**&songmid='.$mid.'&type=0&appsongtype=1&_wv=1&source=qq&sharefrom=gedan","musicUrl":"'.$j.'","preview":"'.$tu.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$ga.'"}},"config":{"autosize":true,"forward":true,"type":"normal"},"text":"","sourceAd":"","extra":""}';
    exit();
    
}else{

echo need::json(array('code'=>'1','data'=>array('desc'=>'QQ音乐','singer'=>$gb,'image'=>$tu,'musicname'=>$ga,'musicurl'=>$j)));

exit();

}

}



function get_millisecond(){
list($usec, $sec) = explode(" ", microtime());
$msec=round($usec*1000);
return $msec;
}

function curl_id($songmid) {
list($usec, $sec) = explode(" ", microtime());
$msec=round($usec*1000);
$post='{"comm":{"uin":"'.need::Robot('../../','Robot').'","authst":"'.need::Robot('../../','y.qq.com').'","mina":1,"appid":1109523715,"ct":29},"urlReq":{"module":"vkey.GetVkeyServer","method":"CgiGetVkey","param":{"guid":"'.$msec.'","songmid":["'.$songmid.'"],"songtype":[0],"uin":"'.need::Robot('../../','Robot').'","loginflag":1,"platform":"23","h5to":"speed"}}}';
$curl=curl_init();
curl_setopt($curl,CURLOPT_URL,"https://u.y.qq.com/cgi-bin/musicu.fcg");
curl_setopt($curl,CURLOPT_POST,1);
curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl,CURLOPT_REFERER,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.30 Safari/537.36');
curl_setopt($curl,CURLOPT_COOKIE,'qqmusic_uin=o'.need::Robot('../../','Robot').'; qqmusic_key='.need::Robot('../../','y.qq.com').'; qqmusic_fromtag=30; ts_last=y.qq.com/portal/player.html; p_skey='.need::Robot('../../','y.qq.com').'; skey='.need::Robot('../../','Skey').'; uin=o'.need::Robot('../../','Robot').'; p_uin=o'.need::Robot('../../','Robot').'; qm_keyst='.need::Robot('../../','y.qq.com').'; o_cookie=2830877581; ');
curl_setopt($curl,CURLOPT_USERAGENT,"http://y.qq.com/portal/player.html");
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
$result=curl_exec($curl);
curl_close($curl);

$data = json_decode($result,true);//格式化JSON

//print_r($data);exit();
/*
$open = fopen('1.txt','w');

fwrite($open,need::json($data));

fclose($open);
*/
   $URL = $data["urlReq"]["data"]["midurlinfo"][0]["purl"]?:$data["urlReq"]["data"]["testfilewifi"];
/*
preg_match_all('/"purl":"(.*?)"/',$result,$c);
$j=$c[1][0];
preg_match_all('/"testfilewifi": "(.*?)"/',$result,$c);
$j1=$c[1][0];
if($j==""){
$j=$j1;}

*/
//return "http://dl.stream.qqmusic.qq.com/".$j;

return 'http://dl.stream.qqmusic.qq.com/C200'.$URL;
}



