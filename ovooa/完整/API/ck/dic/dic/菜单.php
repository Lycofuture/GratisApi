<?php
require ('./access/parameter.php');//传入参数

if(preg_match('/^(菜单|menu)$/',$msg)){

//dic_str();

$config = read_all("./dic/功能");

for($a = 0 ; $a < count($config) ; $a++){

$encode = $config[$a];

$name = str_replace('.php','',$encode["name"]);

$echo .= "〔".$name."〕\r";

}

echo trim($echo);

echo "\n\n [".date('H:i')."]";

echo "\n [By:San]\n";

}else

if(preg_match('/^(。)$/',$msg)){

dic_str();

echo "在的哦";

}
