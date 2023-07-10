<?php
//header('Content-type: application/json');

require ("../../Core/Database/connect.php");

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(67); // 调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$ts = $_GET["ts"]?:"10";//显示条数默认10条

$p = $_GET["p"]?:"1";//页数默认1页

$n = $_GET["n"];//选择查看哪个

$s=$db->query("SELECT * FROM `mxgapi_api` order by 1 desc")->fetch_all(MYSQLI_ASSOC);//获取数据库文件

$ps = intval($p-1);//数组0为1所以-1

$count = count($s);//获取总数量

$sum = intval(($count/$ts)+1);//计算总页数

$num = intval($ps*$ts);//计算当前页数显示到几个了

$numa = intval($p*$ts);//计算当前页显示几个

//foreach($s as $k=>$v){

//echo $v["name"];

//}

if(!$n){

for($i = $num ; $i < $numa && $i < $count ; $i++){

$echo .= ($i+1).".".$s[$i]["name"]."\\r";

}

exit(need::json(array("code"=>"1","text"=>"本站共".$count."条接口\\n".$echo."第".$p."/".$sum."页")));

}

if($n > $count || $n <= 0){


for($i = $num ; $i < $numa && $i < $count ; $i++){

$echoa .= ($i+1).".".$s[$i]["name"]."\\r";

}

echo need::json(array("code"=>"1","text"=>"请按以下序列号选择！\\r".$echoa."第".$p."/".$sum."页"));

}else{


$na = ($n - 1);

$s3 = json_decode($s[$na]["error_code"],true);

$s1 = json_decode($s[$na]["request_parameter"],true);

$s2 = json_decode($s[$na]["return_parameter"],true);

$url = $s[$na]["url"];//请求链接

$format = $s[$na]["format"];//请求方式

$example_url = $s[$na]["example_url"];//请求示例

$request = count($s1["data"]);//请求参数

$return = count($s2["data"]);//获取返回参数总数

$error = count($s3["data"]);//返回状态码数量

for ($at = 0 ; $at < $request ; $at++){

$sc = json_decode($s[$na]["request_parameter"],true);

$requesta .= ($at+1).".参数名称：".$sc["data"][$at]["name"]."\\r是否必填：".$sc["data"][$at]["required"]."\\r参数说明：".$sc["data"][$at]["info"]."\\r——·——·——·——·——\\r";

}

for ($t = 0 ; $t < $return ; $t++){

$sa = json_decode($s[$na]["return_parameter"],true);

$returna .= ($t+1).".返回参数：".$sa["data"][$t]["name"]."\\r参数解释：".$sa["data"][$t]["msg"]."\\r——·——·——·——·——\\r";

}

for ($i = 0 ; $i < $error ; $i++){

$sb = json_decode($s[$na]["error_code"],true);

$codea .= ($i+1).".状态码：".$sb["data"][$i]["code"]."\\r状态码说明：".$sb["data"][$i]["msg"]."\\r——·——·——·——·——\\r";

}

echo need::json(array("code"=>"1","text"=>"请求链接：http://lkaa.top/API/".$url."\\r——·——·——·——·——\\r返回格式：".$format."\\r——·——·——·——·——\\r请求参数：↓\\r——·——·——·——·——\\r".$requesta."返回参数↓\\r——·——·——·——·——\\r".$returna."返回状态码↓\\r——·——·——·——·——\\r".$codea."请求示例：http://lkaa.top/API/".$example_url));

}


?>