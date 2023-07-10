<?php
 //error_reporting(0);//防止不致命错误报错影响使用。
header("Content-type: text/html; charset=UTF-8");
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */
$string = @$_REQUEST["msg"];

$type = @$_REQUEST["type"];

if($string==''){

exit(need::json(array('code'=>-1,'text'=>'请输入需要加密的内容')));

}

if($type == '1'){

exit(need::json(array("code"=>1,"text"=>need::jiemi($string))));


}else{

exit(need::json(array("code"=>1,"text"=>need::jiami($string))));

}




?>