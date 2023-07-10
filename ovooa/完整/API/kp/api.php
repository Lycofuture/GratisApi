<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数
/* End */
//获取绝对路径
//如果你介意别人可能会拖走这个文本，可以把文件名自定义一下，或者通过Nginx禁止拉取也行。
$path = dirname(__FILE__);
$file = file($path."/img.txt");//本地地址

//随机读取一行
$arr = mt_rand( 0, count( $file ) - 1 );
$content = trim($file[$arr]);
echo $content;
?>
