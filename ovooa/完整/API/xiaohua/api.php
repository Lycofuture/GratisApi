<?php

/* Start */

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(85); // 调用统计函数

require "../../need.php";//引入封装好的函数

/* End */

$type = @$_REQUEST["type"];

//获取句子文件的绝对路径
$path = dirname(__FILE__);
$file = file($path."/xiaohua.txt");
 
//随机读取一行
$arr  = mt_rand( 0, count( $file ) - 1 );
$content  = trim($file[$arr]);
 
//编码判断，用于输出相应的响应头部编码
if (isset($_GET['charset']) && !empty($_GET['charset'])) {
    $charset = $_GET['charset'];
    if (strcasecmp($charset,"gbk") == 0 ) {
        $content = mb_convert_encoding($content,'gbk', 'utf-8');
    }
} else {
    $charset = 'utf-8';
}
 
//格式化判断，输出js或纯文本
if ($type === 'js') {
    echo "function binduyan(){document.write('" . $content ."');}";
}else if($type === 'json'){
    header('Content-type:text/json');
//    $content = array('text'=>$content);
    echo need::json(array("code"=>"1","text"=>$content));
}else {
    echo $content;
}

?>