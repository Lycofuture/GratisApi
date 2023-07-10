<?php
header('content-type: application/json; charset=utf-8;');
/* Start */

require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */


//获取句子文件的绝对路径

$rand = mt_rand(1,3);

$n = @$_REQUEST["n"]?:$rand;

$type = @$_REQUEST["type"];

if(!preg_match('/^[1-3]$/',$n)){

if($type == 'text'){

exit('参数错误');

}else{

exit(need::json(array('code'=>-1,'text'=>'参数错误')));

}

}

$file = @file('./'.$n.'.txt');
 
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
    echo "function yiyan(){document.write('" . $content ."');}";
}else if($type === 'json'){
    //header('Content-type:text/json');
    $content = array('code'=>"1",'text'=>$content);
    echo need::json($content);
}else {
    echo $content;
}
