<?php

header('content-type:application/json');

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(115); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$array = json_decode(file_get_contents('./l.json'),true);

$rand = @array_rand($array,1);

$type = @$_REQUEST['type'];

if($type == 'text'){

    echo @$array[$rand]['zh'];
    echo "\n";
    echo @$array[$rand]['en'];

}else{

    $zh = @$array[$rand]['zh'];
    
    $en = @$array[$rand]['en'];

    echo need::json(array('code'=>1,'text'=>'获取成功','data'=>array('zh'=>$zh,'en'=>$en)));

}