<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
$msg=@$_GET["msg"];
$n=@$_GET["n"];
if ($msg==""){
echo '请输入需要生成的内容至少1位';
}else if($n=="1"||$n==null){
$img ='http://jiuli.xiaoapi.cn/i/yzm.php?code='.$msg;
$img = file_get_contents($img,true);
header("Content-Type: image/jpeg;text/html; charset=utf-8");
echo $img;
exit;
 }else if($n=="2"){
echo 'http://jiuli.xiaoapi.cn/i/yzm.php?code='.$msg;
}
 ?>