<?php

require ('./access/parameter.php');//传入参数

if(preg_match('/^(接口数据)$/',$msg)){

if($master == $qq|| preg_match('/(.*?)'.$qq.'(.*?)/',$admin)){

$data = teacher_curl('http://lkaa.top/api.php');

echo $data;

}

}

