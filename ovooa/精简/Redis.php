<?php
// Header('Content-type: Application/json');
$host = '127.0.0.1';//Redis ip,一般127.0.0.1即可
$port = 6379;//Redis端口
$auth = '123456789';//Redis密码 如果有就填 没有就空着
$Redis = new Redis();
$Redis->connect($host, $port);
try{
	$Redis->auth($auth);
}catch (\Exception $e){

}
$Redis->select(5);//选择数据库0-15

/* 获取访客真实IP */
$ip = false;
if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
	$ip = $_SERVER["HTTP_CLIENT_IP"];
}
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
	if ($ip) {
		array_unshift($ips, $ip);
		$ip = FALSE;
	}
	for ($i = 0; $i < count($ips); $i++) {
		if (!mb_eregi("^(10|172.16|192.168|0)", $ips[$i])) {
			$ip = $ips[$i];
			break;
		}
	}
}
if (!$ip) {
	$ip = $_SERVER['REMOTE_ADDR'];
}
/* 获取访客真实IP */
$key = $ip . '+' . time();//key
// $Redis->set($key, time());
$Redis->expire($key, 1);//设置key的过期时间为1秒，也就是判断1秒内访问次数
$qps = $Redis->incr($key);//使key的值增加1
$gettime = 10; //拉黑时间 秒
$Runtime = (time() + $gettime);
$settime = $Redis->get($ip);
// print_r($Redis->keys('*'));
if ($qps > 5 || $settime > time()) {//这里的5代表超过5qps时限制,可自行修改
	$Redis->set($ip, $Runtime); //这里是写入拉黑时间 默认$gettime秒可以修改
	$Redis->expire($ip, $gettime);
	$Redis->close();
	header('HTTP/1.0 514');
	header('Content-Type:application/json');
	$type = isset($_REQUEST['type']) ? @$_REQUEST['type'] : 'json';
	Switch($type)
	{
		case 'text':
		exit('触发QPS限制，请勿频繁请求本站!');
		break;
		default:
		exit(json_encode(array('code' => 514, 'text' => '触发QPS限制，请勿频繁请求本站!'), 460));//达到QPS限制，这里的操作可以自行修改
		break;
	}
}
//删除无用名单
if(($settime - time()) > $gettime)
{
	$Redis->del($ip);
}
$keys = $Redis->keys('*\+*');
// print_r($keys);
//删除过期产品
foreach($keys as $v)
{
	$explode = explode('+', $v);
	// print_r($explode);
	if((end($explode) < time() && $ip != $explode[0]) )
	{
		$Redis->del($v);
	}
}
$Redis->close();