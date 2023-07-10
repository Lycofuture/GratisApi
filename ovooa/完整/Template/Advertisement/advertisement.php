<?php
$id = (int)$_GET['id'];
if($result = $db->query("select * from `advertising` where `id` = '{$id}';")->fetch_array(1))
{
	$url = $result['url'];
	$access = $result['access'] + 1;
	$db->query("update `advertising` set `access` = '{$access}' where `id` = {$id};");
} else {
	$url = 'http://'.$_SERVER['HTTP_HOST'];
}
