<?php

require ('./access/parameter.php');//传入参数

echo '$发送$';

if(preg_match('/^(天气)$/',$msg)){

dic_str();

dic_head();

echo "〔事件〕：天气\n";

echo "〔发送〕：天气+地区";

}else

if(preg_match('/^天气选 ?([0-9]+)$/',$msg,$data_num)){

dic_str();

//dic_head();

$data = dic_cache_get('./dic/缓存/'.$qq.'/天气.php');

if(!$data){

echo "〔事件〕：查询天气\n";

echo "〔提示〕：请先发送天气+地区";

}else{

preg_match('/"'.jiami($qq).'":"(.*?)"/',$data,$data);

$data = $data[1];

if(!$data){

echo "〔事件〕：查询天气\n";

echo "〔提示〕：请先发送天气+地区";

}else{

$data = teacher_curl('http://lkaa.top/API/tqtq/api.php?msg='.$data.'&b='.$data_num[1]);

echo $data;

//dic_delete('./dic/缓存/'.$qq.'/天气.php');

}

}
}else

if(preg_match('/^天气 ?(.*?)$/',$msg,$data_msg)){

dic_str();

dic_head();

$data_msg = $data_msg[1];

$data = teacher_curl('http://lkaa.top/API/tqtq/api.php?msg='.$data_msg);

echo $data;

dic_open($qq,$data_msg,$qq."/天气.php");

}






