<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
$a = $_GET["msg"];
function unicodeDecode($unicode_str){
    $json = '{"str":"'.$unicode_str.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)) return '';
    return $arr['str'];
}
 if ($a!=""){
   
 
$unicode_str = "$a";
echo unicodeDecode($unicode_str);


}else if($a==""){
  echo "请填写需要解码的内容！";
}
?>