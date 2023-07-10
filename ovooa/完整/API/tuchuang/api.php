<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$url = $_GET["url"];

$w = $_GET["w"]?:'jpg';

if(!$url){

exit(need::json(array('code'=>-1,'text'=>'请输入图片链接！')));

}

if(preg_match('/^need::http(.*?)[jpg|png|jpeg|webp|bmp|0](.*?)$/i',$url)){

$image = need::teacher_curl($url);//图片

$time=need::getMillisecond();

$path = $time.'.'.$w;  //文件路径和文件名

file_put_contents($path, $image);

$img = "http://".$http."/API/tuchuang/".$time.".".$w;

$data = need::teacher_curl('https://sm.ms/api/v2/upload?inajax=1',[
'post'=>$image,
'refer'=>'https://sm.ms/'
//'file'=>new CURLFile(realpath($path))
]
);

echo $data;


fastcgi_finish_request();
time_sleep_until(time()+15);
unlink("".$time.".".$w);

}
