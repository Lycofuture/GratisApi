<?php

header('content-type: text/text');//不让转义html
error_reporting(0);//抑制报错
require ('./access/lia/dic_notify.php');//引入md5检验
//require ("./assess/parametera.php");
require ('./access/require.php');//传入引用

require ('config.php');//引入key文件

$result = dic_test($key,$_GET["key"]);


if(!$result){

exit('〔状态〕：密匙错误\r〔提示〕：请联系客服'.$result);

}else{
dic_run('./dic/dic');

dic_run('./dic/功能');

dic_run('./dic/授权');

}
