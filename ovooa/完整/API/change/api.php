<?
header("Content-type: application/json; charset=utf-8");
require_once __DIR__ . '/../../need.php';

$file = need::read_all(__DIR__ . '/poetry/ci', 'json');
// print_r($file);
$array = [];
/*
foreach($file as $v)
{
	$name = $v['file'];
	$data = json_decode(file_get_Contents($name), true);
	$array = array_merge($array, $data);
	unset($data, $name);
}
*/
$change = json_decode(file_get_contents(__DIR__ . '/cache/cache.json'), true);
// $ci = str_replace(array_keys($change), array_values($change), file_get_contents(__DIR__ . '/cache/ci.json'));
// $tang = str_replace(array_keys($change), array_values($change), file_get_contents(__DIR__ . '/cache/tang.json'));
// print_r(file_put_contents(__DIR__ . '/cache/tang.json', $tang));
// echo file_put_contents(__DIR__ . '/cache/ci.json', $ci);
// $data = json_decode(file_get_contents(__DIR__ . '/cache/tang.json'), true);
// foreach(range(0, 4) as $t)
$t = 1;
foreach($change as $k => $v)
{
	$Content = str_replace($k, $v, file_get_contents(__DIR__ . '/cache/tang' . $t . '.json'));
	// unset($Content);
	// echo file_put_contents(__DIR__ . '/cache/tang' . $t . '.json', json_encode(array_slice($data, ($t * 10776), (($t + 1) * 10776)), 460));
}
echo file_put_contents(__DIR__ . '/cache/strtang' . $t . '.json', $Content);
// echo count($data);
/*
foreach(range(001, 900) as $k)
{
	$k = sprintf("%03d", $k);
	$data = json_decode(file_get_contents(__DIR__ . '/poetry/tang/' . $k . '.json'), true);
	$array = array_merge($array, $data);
	unset($data);
}
file_put_Contents(__DIR__ . '/cache/tang.json', json_encode($array, 460));
echo $k;
*/