<?php

/*定义查看货币*/

function dic_money($qq){

if(!is_dir('./dic/缓存/'.$qq)){

mkdir('./dic/缓存/'.$qq);

}else{}

if(!is_file('./dic/缓存/'.$qq.'/money.php')){

$data = fopen("./dic/缓存/".$qq."/money.php","w");

fwrite($data,"0");

fclose($data);

$money=file_get_contents('./dic/缓存/'.$qq.'/money.php');

return $money;

}else{

$money=file_get_contents('./dic/缓存/'.$qq.'/money.php');

if($money<='0'){

return $money;

}else{

return $money;

}

}

}//读取

/*定义储存货币*/


function dic_money_all($qq,$num){

if(!is_dir('./dic/缓存/'.$qq)){

mkdir('./dic/缓存/'.$qq,0777,true);

}else{}

if(!is_file('./dic/缓存/'.$qq.'/money.php')){

$data = fopen("./dic/缓存/".$qq."/money.php","w");

fwrite($data,$num);

fclose($data);

}else{

$data = @file_get_contents('./dic/缓存/'.$qq.'/money.php');

$nums = ($data+$num);

$money = fopen("./dic/缓存/".$qq."/money.php","w");

fwrite($money,$nums);

fclose($money);

}

}



/*未完成的货币重命名*/

function dic_money_name(){

return "金币";

}
//货币名字

