<?php

/*获取参数*/

$msg = URLdecode($_GET["msg"]);//内容

$group = $_GET["group"];//群号

$Skey = $_GET["Skey"];//Skey

$qq = $_GET["uin"];//发言人QQ

$uinname = $_GET["name"];//发言人名字

$Robot = $_GET["Robot"];//机器人账号

$key_key = $_GET["key"];//密匙

$group_name = $_GET["group_name"];//群名

$pskey = $_GET["pskey"];

$at = $_GET["at"];

$master = $_GET["master"];

$admin = $_GET["admin"];

$img = $_GET["img"];


if(!$qq){

exit('参数错误');

}

if(!$group){

exit('参数错误');

}


if(!$Robot){

exit('参数错误');

}

if(!$key_key){

exit('参数错误');

}

if(!$master){

exit('参数错误');

}

if(!$admin){

exit('参数错误');

}

if(!$Skey){

exit('本词库必须授权Skey功能');

}

