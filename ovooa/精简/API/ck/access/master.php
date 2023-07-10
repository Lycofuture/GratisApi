<?php

/*
*权限判断
*判断是否是群主/主人
*判断是否是机器人管理员
*/

//require ('./access/parameter.php');

function dic_master(){

$qq_match = teacher_curl('http://lkaa.top/API/qunl/api.php?qq='.$_REQUEST["Robot"].'&s='.$_REQUEST["Skey"].'&group='.$_REQUEST["group"].'&type=1');

$qq_match = json_decode($qq_match);

$qq_match = json_encode($qq_match->text,320);

if($qq_match==$_REQUEST["uin"] || $_REQUEST["uin"] == $_REQUEST["master"] || preg_match('/('.$_REQUEST["uin"].')/',$_REQUEST["admin"])){

return true;

}else{

return false;

}

}

/*
*判断发言人是否是管理员
*避免对方是管理
*触发违禁词***
*尬的一批
*/


function dic_admin(){

$qq_match = teacher_curl('http://lkaa.top/API/qunl/api.php?qq='.$_REQUEST["Robot"].'&s='.$_REQUEST["Skey"].'&group='.$_REQUEST["group"].'&type=1');

$qq_match = json_decode($qq_match);

$qq_match = json_encode($qq_match->text,320);

$qq_admin = json_decode(teacher_curl('http://lkaa.top/API/qunl/api.php?qq='.$_REQUEST["Robot"].'&s='.$_REQUEST["Skey"].'&group='.$_REQUEST["group"].'&type=2'));

$qq_admin = json_encode($qq_admin->text,320);

if($qq_match==$_REQUEST["uin"] || $_REQUEST["uin"] == $_REQUEST["master"] || preg_match('/('.$_REQUEST["uin"].')/',$_REQUEST["admin"]) || preg_match('/('.$qq_admin.')/',$_REQUEST["uin"])){

return true;

}else{

return false;

}

}


/*
*判断机器人是否是
*管理员
*群主
*还是普通群员
*/



function dic_admin_Robot(){

$qq_match = teacher_curl('http://lkaa.top/API/qunl/api.php?qq='.$_REQUEST["Robot"].'&s='.$_REQUEST["Skey"].'&group='.$_REQUEST["group"].'&type=1');

$qq_match = json_decode($qq_match);

$qq_match = json_encode($qq_match->text,320);

$qq_admin = json_decode(teacher_curl('http://lkaa.top/API/qunl/api.php?qq='.$_REQUEST["Robot"].'&s='.$_REQUEST["Skey"].'&group='.$_REQUEST["group"].'&type=2'));

$qq_admin = json_encode($qq_admin->text,320);


if($_REQUEST["Robot"] == $qq_match || preg_match('/('.$_REQUEST["Robot"].')/',$qq_admin)){

return true;

}else{

return false;

}

}





