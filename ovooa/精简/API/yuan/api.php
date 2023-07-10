<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(112); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */

$Type = $_REQUEST['type'];
$url = 'http://service.aibizhi.adesk.com/v1/wallpaper/album/5fc8ba69e7bce72c57a29158/wallpaper?limit=30&adult=false&first=0&order=new&skip='.rand(0,20);
$data = json_decode(need::teacher_curl($url,['ua'=>'picasso,276,nearme']),true);
$data = $data['res']['wallpaper'];
$rand = array_rand($data,1);
$image = $data[$rand]['img'];
if($Type == 'text'){
    echo $image;
    exit();
}else
if($Type == 'image'){
    need::send($image,'image');
}else{
    echo need::json(array('code'=>1,'text'=>$image));
    exit;
}