<?php
/* Start */
require ("../../admin/functions.php");//引入函数文件
addAccessUser();//调用统计函数
/* End */

$Robot = $_GET["Robot"];

$QQ = $_GET["QQ"];

$Group = $_GET["Group"];

$Skey = $_GET["Skey"];

$Pskey = $_GET["Pskey"];

if ($Robot==''||$Robot==null){

echo "未检测到机器人QQ请稍后重试";exit;

}

if ($Group==''||$Group==null){

echo "未检测到群号请稍后重试";exit;

}

if(file_exists($Robot)){

echo "存在";exit;

}

if(!file_exists($Robot)){

mkdir($Robot);

$rep = fopen("./".$Robot."/".$Group.".php","w");

fwrite($rep,"\n$QQ");

fclose($rep);

}


?>