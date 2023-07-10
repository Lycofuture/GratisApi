<?php

require ('./access/parameter.php');//传入参数

require ('./dic/扩展/抢楼.php');

echo '$发送$';

if(!is_dir('./dic/缓存/抢楼')){

mkdir('./dic/缓存/抢楼');

}else{}//判断是否有抢楼文件夹如果没有则创建

$data1 = dic_cache_get('./dic/缓存/抢楼/1.php');//1楼

$data2 = dic_cache_get('./dic/缓存/抢楼/2.php');//2楼

$data3 = dic_cache_get('./dic/缓存/抢楼/3.php');//3楼

$data4 = dic_cache_get('./dic/缓存/抢楼/4.php');//4楼

$data5 = dic_cache_get('./dic/缓存/抢楼/5.php');//5楼

$date = dic_cache_get('./dic/缓存/抢楼/时间判断.php');

$data_lou = dic_cache_get('./dic/缓存/抢楼/抢楼.php');

if(preg_match('/^(抢楼)$/',$msg)){

preg_match('/"'.jiami('time').'":"(.*?)"/',$date,$floor);

preg_match('/"'.jiami("抢楼".$group).'":"(.*?)"/',$data_lou,$data_lou);

$time = $data_lou[1];

if($floor[1]!=date('Y-m-d-H')&&$time!=date('Y-m-d-H')){

$read = read_all('./dic/缓存/抢楼/');

foreach ($read as $k=>$v){

$read_path = $read[$k]["path"];

$read_name = $read[$k]["name"];

$unlink = $read_path.$read_name;

unlink($unlink);//循环删除掉所有文件

}

echo "〔事件〕：抢楼\n";

echo "〔提示〕：抢楼开始喽！";

dic_open("抢楼".$group,date('Y-m-d-H'),"抢楼/抢楼.php");

dic_open("time",date('Y-m-d-H'),"抢楼/时间判断.php");

}else


if($data5){

dic_str();

dic_head();

echo "〔事件〕：抢楼\n";

echo "〔提示〕：本次抢楼结束了哦~";

}else{

$floor_all = $data1.$data2.$data3.$data4.$data5;

if(preg_match('/('.$qq.')/',$floor_all)){

dic_str();

dic_head();

echo "〔事件〕：抢楼\n";

echo "〔提示〕：您本次整点已经抢过楼了哦~";

}else

if(!$data1){

dic_str();

dic_head();

echo "〔事件〕：抢楼\n";

echo "〔提示〕：成功抢到了1楼\n";

echo "〔奖励〕：5枚金币";

dic_money_all($qq,'5');

dic_ql($qq,'1',date('Y-m-d-H'));

}else

if(!$data2){

dic_str();

dic_head();

echo "〔事件〕：抢楼\n";

echo "〔提示〕：成功抢到了2楼\n";

echo "〔奖励〕：4枚金币";

dic_money_all($qq,'4');

dic_ql($qq,'2',date('Y-m-d-H'));

}else

if(!$data3){

dic_str();

dic_head();

echo "〔事件〕：抢楼\n";

echo "〔提示〕：成功抢到了3楼\n";

echo "〔奖励〕：3枚金币";

dic_money_all($qq,'3');

dic_ql($qq,'3',date('Y-m-d-H'));

}else

if(!$data4){

dic_str();

dic_head();

echo "〔事件〕：抢楼\n";

echo "〔提示〕：成功抢到了4楼\n";

echo "〔奖励〕：2枚金币";

dic_money_all($qq,'2');

dic_ql($qq,'4',date('Y-m-d-H'));

}else

if(!$data5){

dic_str();

dic_head();

echo "〔事件〕：抢楼\n";

echo "〔提示〕：成功抢到了5楼\n";

echo "〔奖励〕：1枚金币";

dic_money_all($qq,'1');

dic_ql($qq,'5',date('Y-m-d-H'));

}

}


}else

if(preg_match('/^抢楼排行$/',$msg)){

preg_match('/"QQ":"([0-9]+)"/',$data1,$floor1);

preg_match('/"QQ":"([0-9]+)"/',$data2,$floor2);

preg_match('/"QQ":"([0-9]+)"/',$data3,$floor3);

preg_match('/"QQ":"([0-9]+)"/',$data4,$floor4);

preg_match('/"QQ":"([0-9]+)"/',$data5,$floor5);

if($data1){

$floor1 = $floor1[1];

}else{

$floor1 = "无";

}

if($data2){

$floor2 = $floor2[1];

}else{

$floor2 = "无";

}

if($data3){

$floor3 = $floor3[1];

}else{

$floor3 = "无";

}

if($data4){

$floor4 = $floor4[1];

}else{

$floor4 = "无";

}

if($data5){

$floor5 = $floor5[1];

}else{

$floor5 = "无";

}

if(!$data1){

$text_1 = "本次整点楼全空呢~";

}else

if(!$data2){

$text_1 = "加油哦还有四层楼呢~";

}else

if(!$data3){

$text_1 = "加油哦还有三层楼呢~";

}else

if(!$data4){

$text_1 = "加油哦还有二层楼呢~";

}else

if(!$data5){

$text_1 = "加油哦还有一层楼呢~";

}else{

$text_1 = "本次抢楼圆满结束了呢~";

}

echo "〔事件〕：抢楼排行\r";

echo "〔一楼〕：".$floor1."\r";

echo "〔二楼〕：".$floor2."\r";

echo "〔三楼〕：".$floor3."\r";

echo "〔四楼〕：".$floor4."\r";

echo "〔五楼〕：".$floor5."\r";

echo "〔提示〕：".$text_1;

}

else{

preg_match('/"'.jiami('time').'":"(.*?)"/',$date,$floor);

preg_match('/"'.jiami("抢楼".$group).'":"(.*?)"/',$data_lou,$data_lou);

$time = $data_lou[1];

if($floor[1]==date('Y-m-d-H')&&$time!=date('Y-m-d-H')){

echo "〔事件〕：抢楼\n";

echo "〔提示〕：抢楼开始喽！";

dic_open("抢楼".$group,date('Y-m-d-H'),"抢楼/抢楼.php");

}else

if($floor[1]!=date('Y-m-d-H')&&$time!=date('Y-m-d-H')){

$read = read_all('./dic/缓存/抢楼/');

foreach ($read as $k=>$v){

$read_path = $read[$k]["path"];

$read_name = $read[$k]["name"];

$unlink = $read_path.$read_name;

unlink($unlink);//循环删除掉所有文件

}

echo "〔事件〕：抢楼\n";

echo "〔提示〕：抢楼开始喽！";

dic_open("抢楼".$group,date('Y-m-d-H'),"抢楼/抢楼.php");

dic_open("time",date('Y-m-d-H'),"抢楼/时间判断.php");

}else{}
}




