<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */


$msg=$_GET["msg"];

$b=$_GET["n"];

$c=$_GET["type"];

$p=$_GET["p"]?:"1";

$sc=$_GET["sc"]?:'15';

$h=$_GET["h"]?:'\n';

$url = "https://www.xiami.com/api/search/searchSongs?_q=";

$data = urlencode('{"key":"'.$msg.'","pagingVO":{"page":'.$p.',"pageSize":'.$sc.'}}&_s=13a54040f221c91934c565bf0c8e0667');




$data = need::teacher_curl($url.$data,[
'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
'refer'=>'https://www.xiami.com/list?scene=search&type=song&query='.urlencode('{"searchKey":"'.$msg.'"}').'',
]);

if(!$data){

exit('未知错误！');

}

$JSON = json_decode($data, true);

$songs = $JSON["data"]["songs"];

$count = count($songs);

//for($a = 0 ; $a < $sc

echo need::json($JSON);



function post($url,$data){
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $url); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($curl, CURLOPT_POST, 1); 
curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
curl_setopt($curl, CURLOPT_HEADER, 0); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl); 
curl_close($curl); 
return $result; 
}


