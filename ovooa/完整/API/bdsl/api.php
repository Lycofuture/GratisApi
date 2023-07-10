<?php
error_reporting(0);//抑制报错
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件
require ('../../Core/Database/connect.php');

/* End */

$url = @$_POST["url"];

$s=$db->query("SELECT * FROM `mxgapi_api` order by 1 desc")->fetch_all(MYSQLI_ASSOC);

if(need::get_post()){

exit(need::json(array("code"=>"-1","text"=>"请以post方式请求本站")));

}

if(!$url){

exit(need::json(array("code"=>"-2","text"=>"参数致命错误")));

}

if($url == '2354452553'){

$urls = array();

foreach($s as $k=>$v){

$urls[$k] = 'http://lkaa.top/?action=doc&id='.$s[$k]['id'];//.',';

}

}else{

$urls = array(
    $url,
    'http://lkaa.top/'
);

}

if(preg_match('/(www)/',$url)){

$api = 'http://data.zz.baidu.com/urls?site=www.lkaa.top&token=3TfwX8jFCrJNvNUs';

}else{

$api = 'http://data.zz.baidu.com/urls?site=lkaa.top&token=3TfwX8jFCrJNvNUs';

}

$ch = curl_init();

$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);

curl_setopt_array($ch, $options);

$result = curl_exec($ch);

$data = json_decode($result);

$remain = $data->remain;//剩余数量

$success = $data->success;//成功提交数量

foreach($data->not_same_site as $k=>$v){

$not_same .= $data->not_same_site[$k]."\r";

}//不是本站链接且未处理列表

if(!$not_same){

$not_same = "无";

}else{

$not_same = $not_same;

}

foreach($data->not_valid as $k=>$v){

$not_valid .= $data->not_valid[$k]."\r";

}//不合法数量

if(!$not_valid){

$not_valid = "无";

}else{

$not_valid = $not_valid;

}

$type = @$_POST["type"];

if($type == "text"){

echo "提示：提交成功\r";

echo "提交数量：".$success."\r";

echo "剩余数量：".$remain."\r";

echo "未处理链接：".trim($not_same)."\r";

echo "不合法链接：".trim($not_valid);

exit();

}else{

exit(need::json(array("code"=>"1","text"=>"提交成功！","data"=>array("success"=>$success,"remain"=>$remain,"not_same"=>$not_same,"not_valid"=>$not_valid))));

}

