<?php

require ('./access/parameter.php');//传入参数

if(preg_match('/^(测试)$/',$msg)){

if(dic_master()){

echo "是";

}else{

echo "不是";

}


}

