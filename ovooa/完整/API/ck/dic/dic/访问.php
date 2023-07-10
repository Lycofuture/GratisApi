<?php

require ('./access/parameter.php');//传入参数

if(preg_match('/^访问 ?(.*?)$/',$msg,$msg_url)){

if(dic_master()){

if($msg_url[1]){

echo '$访问 '.$msg_url[1].'$';

}

}else{

dic_str();

dic_head();

echo "〔事件〕：访问\n";

echo "〔提示〕：您没有权限";

}

}

