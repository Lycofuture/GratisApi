<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
$msg=$_GET["msg"];
function post_data_test($url,$data){
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
if (curl_errno($curl)) {
echo '错误信息;'.curl_error($curl);
}
curl_close($curl); 
return $result; 
}
$msg=str_replace("换", "\n", $msg);
$url="http://www.yzcopen.com/img/getimgocrhttp";
$data="url=".$msg;
$data=post_data_test($url,$data);
preg_match_all("/restparm\":\"(.*?)\"/",$data,$data);
$data=$data[1][0];
$data=str_replace("\\r\\n", "\n", $data);
echo $data;
?>