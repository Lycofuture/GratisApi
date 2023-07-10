<?php
require '../../need.php';
$file = file('./data.txt');
foreach($file as $v)
{
	$str .= need::jiemi(trim($v))."\n";
}
//file_put_Contents('./data2.txt', $str);
//echo $str;
?>