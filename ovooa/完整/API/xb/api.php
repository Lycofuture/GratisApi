<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
header("Content-type: text/html; charset=utf-8");
$curl=curl_init();
curl_setopt($curl,CURLOPT_URL,"http://lost.52msr.cn/xb/api2.php");
curl_setopt($curl,CURLOPT_TIMEOUT,30);
curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
$return=curl_exec($curl);
curl_close($curl);
$return=json_decode($return,true);
if($return["code"]==1000){
echo "●线报类型:".$return["data"]["type"]."\r";
echo "●线报标题:".$return["data"]["title"]."\r";
echo "●截止日期:".$return["data"]["Time"]."\r";
echo "●参与规则:".$return["data"]["rule"]."\r";
echo "●参与方式:".$return["data"]["manner"]."\r";
echo "●活动截图:±img=".$return["data"]["Picture"][0]."±";
}else{
echo "抱歉，获取出错了，请联系客服";
}
?>