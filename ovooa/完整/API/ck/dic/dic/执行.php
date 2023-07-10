<?php

require ('./access/parameter.php');//传入参数


if(preg_match('/^执行 ?(.*?)$/',$msg,$msg)){

if(dic_master()){

echo $msg[1];

}else{

echo '±at %QQ%±@$群昵称 %群号% %QQ%$\n执行不了╮(︶﹏︶)╭';

}

}

