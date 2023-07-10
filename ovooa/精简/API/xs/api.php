<?php
header("Content-type: Application/json; charset=utf-8");
/* Start */
require ("../../need.php"); // 引入函数文件

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(34); // 调用统计函数
/* End */

$msg = @$_GET["msg"];

$p = @$_GET["p"];

$b = @$_GET["n"];

$n = @$_GET["y"];

$data=need::teacher_curl('https://h5.reader.qq.com/8/search?key='.$msg.'&pageNo='.$p.'&ywguid=0&ywkey=0&ug=-313085903&b_f=505500&g_f=21585&tt=9036');

$json = json_decode($data, true);

// print_r($json);exit;

$s=count($json["booklist"]);

if ($msg==''||$msg==null){

echo '请输入小说名称';

}else

if ($s==0){

echo "没有搜索到有关于“".$msg."”的小说";

exit;

}
else

if ($b!=''||$b!=null){

$z=($b-1);

$a=$json["booklist"][$z]["id"];//获取id

$dyl=need::teacher_curl('https://h5.reader.qq.com/8/book/chapter?bid='.$a.'&cid='.$n.'&v=true&prefetch=false&multiPrefetch=false&ywguid=0&ywkey=0&ug=-472024118&b_f=304100&g_f=21585&tt=7825', [
	'cookie'=>need::Cookie('vip.qq.com')
]);

$jsona = json_decode($dyl, true);

$te = $jsona["chapter"]["title"];

$tex=$jsona["book"]["title"];

$text=$jsona["content"];

if ($text=='' ||$text==null){

$z=($b-1);

$a=$json["booklist"][$z]["id"];//获取id

$dyl=need::teacher_curl('https://h5.reader.qq.com/8/book/chapter?bid='.$a.'&cid='.$n.'&v=true&prefetch=false&multiPrefetch=false&ywguid=0&ywkey=0&ug=-472024118&b_f=304100&g_f=21585&tt=7825', [
	'cookie'=>need::Cookie('Skey')
]);
$jsona = json_decode($dyl, true);

$te = $jsona["chapter"]["title"];

$tex=$jsona["book"]["title"];

$text=$jsona["content"];


echo "收费章节";exit;

}else{

echo $tex."\n\n".$te."\n\n".$text;

}}else

if (($b-1)>$s){

for ($i = 0; $i < $s; $i++) {

echo ($i+1).".".$json["booklist"][$i]["title"]."--".$json["booklist"][$i]["author"]."\n";

echo '请按照以上序列号选择！';}

}else

if ($msg!=''||$msg!=null||$b==''||$b==null){

for ($i = 0; $i < $s; $i++) {

echo ($i+1).".".$json["booklist"][$i]["title"]."--".$json["booklist"][$i]["author"]."\n";

}echo "请按以上序列号选择！";
}


?>