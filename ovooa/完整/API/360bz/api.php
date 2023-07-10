<?php
/* Start */
/*require ("../function.php"); // 引入函数文件
addApiAccess(98); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../need.php');//引用封装好的函数文件
$n = @$_REQUEST["n"];
$p = @$_REQUEST["p"];
$type = @$_REQUEST["type"];
if($n == '1'){
$form = '36';
}else
if($n == '2'){
$form = '6';
}else
if($n == '3'){
$form = '30';
}else
if($n == '4'){
$form = '9';
}else
if($n == '1'){
$form = '36';
}else
if($n == '5'){
$form = '15';
}else
if($n == '6'){
$form = '26';
}else
if($n == '7'){
$form = '11';
}else
if($n == '8'){
$form = '14';
}else
if($n == '9'){
$form = '5';
}else
if($n == '10'){
$form = '12';
}else
if($n == '11'){
$form = '10';
}else
if($n == '12'){
$form = '22';
}else
if($n == '13'){
$form = '16';
}else
if($n == '14'){
$form = '32';
}else
if($n == '15'){
$form = '35';
}else
if($n == '16'){
$form = '1';
}else{
$form = '26';
}
// echo 'http://wallpaper.apc.360.cn/index.php?c=WallPaper&a=getAppsByCategory&cid='.$form.'&start='.$p.'&count=200&from=360chrome';exit;
$p = mt_rand(1,10);
$data = need::teacher_curl('http://wallpaper.apc.360.cn/index.php?c=WallPaper&a=getAppsByCategory&cid='.$form.'&start='.$p.'&count=200&from=360chrome',[
    'refer'=>' '
]);
//$JSON = JSON_decode($data
$num = preg_match_all('/"url":"(.*?)"/',$data,$date);
if(!$num){
    if($type == 'text'){
        exit('获取失败请重试');
    }else{
        exit(need::json(array('code'=>-1,'text'=>'获取失败请重试')));
    }
}
$rand = mt_rand(0,$num-1);
$image = str_replace(['\\', 'http:'], ['', 'https:'], $date[1][$rand]);
/*
if(!stristr($image, 'ssl')){
    $image = str_replace(['http://', 'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8', 'p9'], ['https://', 'p1.ssl', 'p2.ssl', 'p3.ssl', 'p4.ssl', 'p5.ssl', 'p6.ssl', 'p7.ssl', 'p8.ssl', 'p9.ssl'], $image);
}
*/
if($type == 'text'){
    exit($image);
}else
if($type == 'image'){
    //$data = file_get_Contents($image);
    need::send($image, 'location');
}else{
    exit(need::json(array('code'=>1,'text'=>$image)));
}
