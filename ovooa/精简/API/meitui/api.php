<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$rand = rand(1,610);

echo need::json(array("code"=>"1","text"=>"http://lkaa.top/数据包/姐姐/".$rand.".jpg"));

?>