<?php

/* Start */
require ("../function.php"); // 引入函数文件

require ('../../need.php');

addApiAccess(1); // 调用统计函数
/* End */

$type = $_GET["type"];

$msg = $_GET["msg"];

if ($msg==''||$msg==null){

if($type == 'text'){

echo "输入为空";exit;

}else{

exit(need::json(array('code'=>'-1','text'=>'输入为空')));

}

}

$m = intval($msg/60);

$h = intval($m/60);

$d = intval($h/24);

$hh = intval($d*24);

$hh = intval($h-$hh);

$mm = intval($hh*60);

$mmm = intval($d*1440);

$m = intval($m-$mm-$mmm);

$sm = intval($m*60);

$sh = intval($mm*60);

$sd = intval($mmm*60);

$ss = intval($msg-$sd-$sm-$sh);

if($type == 'text'){

if($d==0 && $hh!=0 && $m!=0){

echo $hh."小时".$m."分".$ss."秒";

}else

if($d==0 && $hh==0 && $m!=0){

echo $m."分".$ss."秒";

}else

if ($d==0 && $hh==0 && $m==0){

echo $ss."秒";

}else
{

echo $d."天".$hh."小时".$m."分".$ss."秒";

}

}else{

exit(need::json(array('code'=>'1','data'=>array('day'=>$d,'hour'=>$hh,'minute'=>$m,'second'=>$ss))));

}



