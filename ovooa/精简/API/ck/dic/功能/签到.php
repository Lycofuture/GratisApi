<?php

require ('./access/parameter.php');//传入参数

echo '$发送$';

if(preg_match('/^(签到)$/',$msg)){

dic_str();

$qiandao = dic_cache_get('./dic/缓存/签到时间.php');

$time = date('Y-m-d');

$nameqq = jiami($qq);

preg_match_all('/'.$nameqq.'":"(.*?)"/',$qiandao,$timea);

//exit($qiandao."\r\r");

$qtime = $timea[1][0];

dic_head();

if($qtime == $time){

echo "〔事件〕：签到\r";
echo "〔状态〕：失败\r";
echo "〔提示〕：您今天已经签到过了哦";

}else{

$qiandaorand = rand(1,10);

echo "〔事件〕：签到\r";
echo "〔状态〕：成功\r";
echo "〔提示〕：获得金币".$qiandaorand."枚";

dic_open($qq,$time,'签到时间.php');

dic_money_all($qq,$qiandaorand);

}

}
