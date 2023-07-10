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
$Redis->select(11);//选择数据库0-15

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
$ip_array = ["103.41.65.209","43.249.207.211","221.192.179.0","27.221.79.214","221.192.179.246","8.131.94.70","49.235.124.117","43.250.201.1","61.242.130.161","101.206.110.224","27.151.28.66","127.0.0.1"];
$time = (20);//拉黑时间 秒
$Runtime = time(); //获取时间戳
$keyouttime = 1; //key过期时间
if(!in_array($ip, $ip_array))
{
	if(!$Redis->keys($ip.'\+*')){
		$key = $ip .'+'. $Runtime;
	} else {
		$key = $Redis->keys($ip.'\+*')[0];
	}
	if($Redis->ttl($key) == -2)
	{
		$key = $ip .'+'. $Runtime;
	}
	$Redis->expire($key, $keyouttime);
	$qps = $Redis->incr($key); //使用一次加1
	$qpsout = 5; //过期时间内访问次数
	if($qpsout < $qps || $Redis->ttl($ip) > 0 && !in_array($ip, $ip_array))
	{
	// 访问者触发拉黑
		$Redis->setex($ip, $time, $Runtime); //此为一直拉黑直到访问者停下访问 $time 秒
		$Redis->close();
		header('HTTP/1.0 514');
		header('Content-Type:application/json');
		$type = isset($_REQUEST['type']) ? @$_REQUEST['type'] : 'json';
		Switch($type)
		{
			case 'text':
			exit('触发QPS限制，请勿频繁请求本站! 看到此提示请等待'.$time.'秒后再次访问。');
			break;
			default:
			exit(json_encode(array('code' => 514, 'text' => '触发QPS限制，请勿频繁请求本站! 看到此提示请等待'.$time.'秒后再次访问。'), 460));//达到QPS限制，这里的操作可以自行修改
			break;
		}
	}
}
$keys = $Redis->keys('*\+*');//获取所有携带时间戳的key
foreach($keys as $v)
{
	$explode = explode('+', $v); //将key分割为数组
	if(($Runtime - end($explode)) > $keyouttime)
	{
		// 判断非访问者时间大于过期时间
		$Redis->del($v); //删除
	}
}
$Redis->close();

