<?php

function dic_ql($name,$num,$time){

if(!is_file('./dic/缓存/抢楼.php')){

$data = fopen("./dic/缓存/抢楼/".$num.".php","w");

$array = JSON_encode(
array(
"QQ"=>$name,
"floor"=>$num,
"time"=>$time),
320
);

fwrite($data,$array);

fclose($data);

}else

$floor = dic_cache_get('./dic/缓存/抢楼/'.$num.'.php');

if(!$floor){

$data = fopen("./dic/缓存/抢楼/".$num.".php","w");

$array = JSON_encode(array("QQ"=>$name,"floor"=>$num,"time"=>$time),320);

fwrite($data,$array);

fclose($data);

}else{

$data = fopen("./dic/缓存/抢楼/".$num.".php","w");

$array = JSON_encode(array("QQ"=>$name,"floor"=>$num,"time"=>$time),320);

fwrite($data,$floor."\n".$array);

fclose($data);

}

}



