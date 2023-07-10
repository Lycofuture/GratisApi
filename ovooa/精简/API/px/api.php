<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
header("Content-type:text/text");
$msg=$_GET["msg"];
$n=$_GET["n"];
if ($msg==""){
echo "请输入需要排序的内容";
}else if ($n==""){
function px($i){
$str=$i;
$arr1=str_split($str);
sort($arr1);
foreach($arr1 as $value){
echo $value;
}
}
}else if ($n=="1"){
function px($i){
$str=$i;
$arr1=str_split($str);
sort($arr1);
foreach($arr1 as $value){
echo ''.$value.'';
}
}
}else if($n=="2"){
function px($i){
$str=$i;
$arr1=str_split($str);
rsort($arr1);
foreach($arr1 as $value){
echo ''.$value.'';
}
}
}
px($msg);
?>