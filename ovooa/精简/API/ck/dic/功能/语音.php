<?php

require ('./access/parameter.php');//传入参数

echo '$发送$';

if(preg_match('/^语音$/',$msg)){

dic_str();

dic_head();

echo "〔事件〕：语音\n";

echo "〔发送〕：说+内容\n";

echo "〔发送〕：语音点歌+内容";

}else

if(preg_match('/^说 ?(.*?)$/',$msg,$msg_shuo)){

if($msg_shuo[1]){

$data = teacher_curl('http://lkaa.top/API/yuyin/api.php?msg='.$msg_shuo[1].'&type=text');

echo '±ptt '.$data.'±';

}else{

dic_str();

dic_head();

echo "〔事件〕：语音说话\n";

echo "〔提示〕：请输入需要说的内容";

}

}else

if(preg_match('/^语音点歌 ?(.*?)$/',$msg,$msg_song)){

if($msg_song[1]){

$data = teacher_curl('http://lkaa.top/API/yydg/api.php?msg='.$msg_song[1]);

dic_str();

echo $data;

echo "提示：发送语音选+序号";

dic_open("语音点歌",$msg_song[1],$qq."/点歌.php");

}else{

dic_str();

dic_head();

echo "〔事件〕：语音点歌\n";

echo "〔提示〕：请输入歌名！";

}

}else

if(preg_match('/^语音选 ?([0-9]+)/',$msg,$msg_num)){

if($msg_num[1]){

$date = dic_cache_get('./dic/缓存/'.$qq.'/点歌.php');

preg_match('/"'.jiami("语音点歌").'":"(.*?)"/',$date,$date);

if(!$date[1]){

dic_str();

dic_head();

echo "〔事件〕：语音点歌\n";

echo "〔提示〕：您还没有点歌哦";

}else{

$data = teacher_curl('http://lkaa.top/API/yydg/api.php?msg='.$date[1].'&n='.$msg_num[1].'');

echo '±ptt '.$data.'±';

}

}

}





