<?php
require 'data.php';
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(102); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
$msg = @$_REQUEST["msg"];
$type = @$_REQUEST["type"];
$num = @$_REQUEST["num"]?:200;
if(empty($msg)){
    if($type == 'text'){
        need::send('请输入内容','text');
    }else{
        need::send(array('code'=>-1,'text'=>'请输入内容'),1);
    }
}
$type = @$_REQUEST["type"];
$num = @$_REQUEST["num"]?:'200';
$bullshit = new Bullshit();
$bullshit = $bullshit->generator($msg, $num);
if($type == 'text'){
    echo  $bullshit;
    exit();
}else{
    echo need::json(array('code'=>1,'text'=>$bullshit));
    exit();
}
