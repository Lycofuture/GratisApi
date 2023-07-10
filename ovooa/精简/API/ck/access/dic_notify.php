<?php

/*
*验证检验
*dic_test(URLdecode($_GET["msg"]),$key,$_GET["key"],$_GET["md5"]);
*/

function dic_test($msg,$key1,$key2){

$data = strtoupper(md5($msg));//消息md5

$mdkey = strtoupper(md5($key1));//将keymd5

$date = $data.$mdkey;

echo $date."\r".$key2;

if($data == $key2){

return true;

}else{

return false;

}

}

