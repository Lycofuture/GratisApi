<?php

require ('./access/parameter.php');//传入参数

$date = dic_cache_get("./dic/缓存/".$qq."/点歌.php");

preg_match_all('/"'.jiami("模式").'":"(.*?)"/',$date,$song_date);

$song_date=$song_date[1][0];
echo '$发送$';
if(!$song_date){

dic_store_data($qq,"模式","QQ","点歌.php");

}else{}

preg_match_all('/"'.jiami("输出方式").'":"(.*?)"/',$date,$song_chu);

$song_chu=$song_chu[1][0];

if(!$song_chu){

dic_store_data($qq,"输出方式","json","点歌.php");

}else{}

if(preg_match('/^点歌 ?(.*?)$/',$msg,$diange)){

dic_str();

if(!$diange[1]){

dic_head();

echo "〔事件〕：点歌\n";

echo "〔歌源〕：".$song_date."音乐\n";

echo "〔发送〕：点歌+歌名\n";

echo "〔发送〕：选+序列号\n";

echo "〔发送〕：音乐切换+歌源\n";

echo "〔发送〕：音乐输出+格式";

}else{

if(!$song_date || $song_date=='QQ'){

$song = teacher_curl("http://lkaa.top/API/qqdg/api.php?msg=".$diange[1]);

dic_store_data($qq,"歌名",$diange[1],"点歌.php");

echo $song;

}else

if($song_date=="酷狗"){

$song = teacher_curl("http://lkaa.top/API/kgdg/api.php?msg=".$diange[1]);

dic_store_data($qq,"歌名",$diange[1],"点歌.php");

//dic_store_data($qq,"模式","酷狗","点歌.php");

echo $song;

}else

if($song_date == "酷我"){

$song = teacher_curl("http://lkaa.top/API/kwdg/api.php?msg=".$diange[1]);

dic_store_data($qq,"歌名",$diange[1],"点歌.php");

//dic_store_data($qq,"模式","酷我","点歌.php");

echo $song;

}else

if($song_date=="网易云"){

$song = teacher_curl("http://lkaa.top/API/wydg/api.php?msg=".$diange[1]);

dic_store_data($qq,"歌名",$diange[1],"点歌.php");

//dic_store_data($qq,"模式","网易云","点歌.php");

echo $song;

}else

if($song_date == "咪咕"){

$song = teacher_curl("http://lkaa.top/API/migu/api.php?msg=".$diange[1]);

dic_store_data($qq,"歌名",$diange[1],"点歌.php");

//dic_store_data($qq,"模式","咪咕","点歌.php");

echo $song;

}

}

}else

if(preg_match('/^选 ?([0-9]+)$/',$msg,$song_dian)){

//dic_str();

preg_match_all('/"'.jiami("输出方式").'":"(.*?)"/',$date,$song_chu);

$song_chu=$song_chu[1][0];

preg_match_all('/"'.jiami("歌名").'":"(.*?)"/',$date,$song_name);

$song_name = $song_name[1][0];

if(!$song_date || $song_date=='QQ'){

$song = teacher_curl("http://lkaa.top/API/qqdg/api.php?msg=".$song_name."&n=".$song_dian[1]."&type=".$song_chu);

echo $song;

}else

if($song_date=="酷狗"){

$song = teacher_curl("http://lkaa.top/API/kgdg/api.php?msg=".$song_name."&n=".$song_dian[1]."&type=".$song_chu);


echo $song;

}else

if($song_date == "酷我"){

$song = teacher_curl("http://lkaa.top/API/kwdg/api.php?msg=".$song_name."&n=".$song_dian[1]."&type=".$song_chu);

echo $song;

}else

if($song_date=="网易云"){

$song = teacher_curl("http://lkaa.top/API/wydg/api.php?msg=".$song_name."&n=".$song_dian[1]."&type=".$song_chu);

echo $song;

}else

if($song_date == "咪咕"){

$song = teacher_curl("http://lkaa.top/API/migu/api.php?msg=".$song_name."&n=".$song_dian[1]."&type=".$song_chu);

echo '$替换 @ '.dic_rep($song,'%','％').'@％@%25@$';

}

}else

if(preg_match('/^音乐切换 ?(QQ|酷狗|酷我|网易云|咪咕)$/',$msg,$song_curl)){

dic_str();

dic_head();

echo "〔事件〕：音源切换\n";

echo "〔当前〕：".$song_date."音乐\n";

echo "〔切换〕：".$song_curl[1]."音乐";

dic_store_data($qq,"模式",$song_curl[1],"点歌.php");

}


if(preg_match('/^音乐输出 ?(json|xml|text)/',$msg,$song_shu)){

dic_str();

dic_head();

echo "〔事件〕：音乐输出切换\n";

echo "〔当前〕：输出".$song_chu."\n";

echo "〔切换〕：输出".$song_shu[1];


dic_store_data($qq,"输出方式",$song_shu[1],"点歌.php");

}

