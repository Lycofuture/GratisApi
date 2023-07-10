<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(65); // 调用统计函数

require "../../need.php";//引入封装好的函数

/* End */

$msg = $_GET['msg'];

$b = $_GET['n'];

$p = $_GET["p"]?:"1";

$sc = $_GET["sc"]?:"15";

$type = $_GET["type"];

$data=curl('http://mvsearch.kugou.com/mv_search?page='.$p.'&pagesize='.$sc.'&userid=-1&clientver=&platform=WebFilter&tag=em&filter=10&iscorrection=1&privilege_filter=0&keyword='.$msg,"GET", 0, 0);

$data = str_replace('<e','',$data);

$data = str_replace('m>','',$data);

$data = str_replace('<\/e','',$data);

$json = json_decode($data, true);

$s=count($json["data"]["lists"]);

if($s==0){exit(need::json(array("code"=>"-1","text"=>"抱歉，返回数据为空。")));}

if($b==""){

for( $i = 0 ; $i < $s ; $i ++ ){

$ga=$json["data"]["lists"][$i]["MvName"];


$gb=$json["data"]["lists"][$i]["SingerName"];

$echo .= ($i+1)."：".$ga."--".$gb."\\n";

}

echo need::json(array("code"=>"1","text"=>$echo."\\n提示：请发送以上序号中的任意一个\\n例如：选酷狗MV1"));

}

else

{

$i=($b-1);

$ga=$json["data"]["lists"][$i]["MvName"];

$gb=$json["data"]["lists"][$i]["SingerName"];

$hash=$json["data"]["lists"][$i]["MvHash"];

$r = 'http://www.kugou.com/webkugouplayer/?isopen=0&chl=yueku_index';

$u="Mozilla/5.0 (Linux; Android 9; 16s Build/PKQ1.190202.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 MQQBrowser/6.2 TBS/045140 Mobile Safari/537.36 V1_AND_SQ_8.3.5_1392_YYB_D QQ/8.3.5.4555 NetType/WIFI WebP/0.3.0 Pixel/1080 StatusBarHeight/72 SimpleUISwitch/0 QQTheme/1000";

$data=curl('http://m.kugou.com/app/i/mv.php?cmd=100&hash='.$hash.'&ismp3=1&ext=mp4',"GET",array('IPHONE_UA'=>0,'REFERER'=>$r,'USERAGENT'=>$u), 0);

$json = json_decode($data, true);

$url=$json["mvdata"]["sq"]["downurl"];

if($url==""){

$url=$json["mvdata"]["le"]["downurl"];}

if($url==""){exit(need::json(array("code"=>"-2","text"=>"抱歉，解析失败。")));}

$img=str_replace(array('{size}'), array('', ''), $json["mvicon"]);

if($type == 'text'){

echo '±img='.$img.'±';
echo '名字：'.$ga.'\r';
echo '作者：'.$gb.'\r';
echo '播放链接：'.$url.'';

}else{



echo need::json(array('code'=>1,'data'=>array('imgurl'=>$img,'name'=>$ga,'author'=>$gb,'url'=>$url)));

}}
 
?>