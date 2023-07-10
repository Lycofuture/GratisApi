<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(26); // 调用统计函数
/* End */
header("Content-type: application/json; charset=utf-8");
$n=@$_REQUEST['n']?:'10';
$h=@$_REQUEST['h']?:"\n";
$time=date("md");
$list=curl('https://lishishangdejintian.bmcx.com/'.$time.'__lishishangdejintian/',"GET",0,0);
preg_match_all('/<ul class="list">(.*?)<\/li><\/ul>/',$list,$lit);
$lit=$lit[1][0];
$lit=str_replace('&nbsp;','',$lit);
$result = preg_match_all('/<li>(.*?)<a href=\'(.*?)\' target=\'_blank\'>(.*?)<\/a>/',$lit,$nute);
if(!$n || !is_numEric($n) || $n<1){
	$n = 10;
}
$a=($n-1);
for ($x=0; $x < $result && $x < $n; $x++) 
{
	$jec=$nute[3][$x];
	$je=$nute[1][$x];
	echo ($x+1).'：'.$je.'-'.$jec.''.$h.'';
}
