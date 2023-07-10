<?php
//$time = time();

require './need.php';

$data = need::teacher_curl('http://ovooa.com/Data/api.php?type=getAdminInfo&apikey=3367468');

$json = json_decode($data,true);
$api = $json["data"]["api"];//接口数量

$access = $json["data"]["access"];//总调用

$access_data = $json["data"]["access_data"][4];//今日调用

$echo1 = '接口数量:'.$api.'个 ';

$echo2 = '本月调用:'.$access.'次 ';

$echo3 = '今日调用:'.$access_data.'次 ';

$echo4 = '更新时间:'.date('Y-m-d H:i:s');

$file = './api.txt';

unlink($file);

$fopen = fopen('./api.txt','w');

fwrite($fopen,'加崽空白请刷新页面！'.$echo1.$echo2.$echo3.$echo4);

fclose($fopen);

//file_put_contents($file,$echo1.$echo2.$echo3.$echo4);





