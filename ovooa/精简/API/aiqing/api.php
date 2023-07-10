<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(92); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$type = @$_REQUEST["type"];

//获取句子文件的绝对路径
$path = dirname(__FILE__);
$file = file($path."/ai.txt");
 
//随机读取一行
$arr  = mt_rand( 0, count( $file ) - 1 );
$content  = trim($file[$arr]);
 
//编码判断，用于输出相应的响应头部编码
if (isset($_REQUEST['charset']) && !empty(@$_REQUEST['charset'])) {
    $charset = @$_REQUEST['charset'];
    if (strcasecmp($charset,"gbk") == 0 ) {
        $content = mb_convert_encoding($content,'gbk', 'utf-8');
    }
} else {
    $charset = 'utf-8';
}
 
//格式化判断，输出js或纯文本
if (@$_REQUEST['type'] === 'js') {
    echo "function binduyan(){document.write('" . $content ."');}";
}else if(@$_REQUEST['type'] === 'json'){
    header('Content-type:text/json');
//    $content = array('text'=>$content);
    echo need::json(array("code"=>"1","text"=>$content));
}else {
    echo $content;
}

?>