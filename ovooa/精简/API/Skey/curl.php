<?php
header("Content-type: text/html; charset=utf-8");
function curl_curl($url,$data=0,$header_array=0,$referer=0,$time=30,$code=0,$cookie=0) {
	if($header_array==0) {
		$header=array(
		    "CLIENT-IP: ".getip_user(),
		    "X-FORWARDED-FOR: ".getip_user(),
		    'User-Agent: Mozilla/5.0 (Linux; Android 9; V1901A Build/P00610; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/68.0.3440.91 Mobile Safari/537.36',
		    "Connection: keep-alive",
		    "Accept-Language: zh-CN,zh;q=0.8",
		    "Accept-Encoding: gzip,deflate,sdch",
		    "Accept: */*"
		    
		);
	} else {
		$header=array(
		    "CLIENT-IP: ".getip_user(),
		    "X-FORWARDED-FOR: ".getip_user(),
		    'User-Agent: Mozilla/5.0 (Linux; Android 9; V1901A Build/P00610; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/68.0.3440.91 Mobile Safari/537.36',
		    "Connection: keep-alive",
		    "Accept-Language: zh-CN,zh;q=0.8",
		    "Accept-Encoding: gzip,deflate,sdch",
		    "Accept: */*"
//		    'Cookie: '.$cookie
		);
		$header=array_merge($header_array,$header);
	}
//print_r($header);
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
	if($data) {
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
	}
	if($referer) {
		curl_setopt($curl,CURLOPT_REFERER,$referer);
	}
	curl_setopt($curl,CURLOPT_TIMEOUT,$time);
	curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl,CURLOPT_ENCODING,'gzip,deflate');
if($code) {
		curl_setopt($curl, CURLOPT_HEADER, 1);
		$return=curl_exec($curl);
		$code_code=curl_getinfo($curl);
		curl_close($curl);
		$code_int['exec']=substr($return,$code_code["header_size"]);
		$code_int['code']=$code_code["http_code"];
		$code_int['content_type']=$code_code["content_type"];
		$code_int['header']=substr($return,0,$code_code["header_size"]);
		return $code_int;
	} else {
		$return=curl_exec($curl);
		curl_close($curl);
		return $return;
	}
}
function ip() {
	$ip_long = array(
					array('607649792', '608174079'),
					array('1038614528', '1039007743'),
					array('1783627776', '1784676351'),
					array('2035023872', '2035154943'),
					array('2078801920', '2079064063'),
					array('-1950089216', '-1948778497'),
					array('-1425539072', '-1425014785'),
					array('-1236271104', '-1235419137'),
					array('-770113536', '-768606209'),
					array('-569376768', '-564133889'),
					);
	$rand_key=mt_rand(0,9);
	return $ip=long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
}
function Get_BKN($skey) {
	$len=strlen($skey);
	$hash=5381;
	for ($i=0;$i<$len;$i++) {
		$hash+=((($hash<<5) & 0x7fffffff)+ord($skey[$i])) & 0x7fffffff;
		$hash&=0x7fffffff;
	}
	return $hash & 0x7fffffff;
}
function getBkn($skey) {
	$hash = 5381;
	for ($i = 0, $len = strlen($skey); $i < $len; ++$i) {
		$hash +=($hash << 5) + charCodeAt($skey, $i);
	}
	return $hash & 2147483647;
}
function charCodeAt($str, $index) {
	$char = mb_substr($str, $index, 1, 'UTF-8');
	$value = null;
	if (mb_check_encoding($char, 'UTF-8')) {
		$ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
		$value = hexdec(bin2hex($ret));
	}
	return $value;
}
function host($url) {
	$url = strtolower($url);
	$hosts = parse_url($url);
	$host = $hosts['host'];
	$data = explode('.', $host);
	$n = count($data);
	$preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
	if(($n > 2) && preg_match($preg,$host)) {
		$host = $data[$n-3].'.'.$data[$n-2].'.'.$data[$n-1];
	} else {
		$host = $data[$n-2].'.'.$data[$n-1];
	}
	return $host;
}
function url($url) {
	$url=parse_url($url);
	if($url["port"]) {
		if($url["host"]) {
			$url=$url["host"].":".$url["port"];
		} else {
			$url=$url["path"].":".$url["port"];
		}
	} else {
		if($url["host"]) {
			$url=$url["host"];
		} else {
			$url=$url["path"];
		}
	}
	return $url;
}
function getip() {
	$unknown = 'unknown';
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])&&$_SERVER['HTTP_X_FORWARDED_FOR']&&strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],$unknown)) {
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	} else if(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],$unknown)) {
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
function GTK($skey) {
	$len = strlen($skey);
	$hash = 5381;
	for ($i = 0; $i < $len; $i++) {
		$hash += ($hash << 5 & 2147483647) + ord($skey[$i]) & 2147483647;
		$hash &= 2147483647;
	}
	return $hash & 2147483647;
}
function replace_unicode_escape_sequence($match) {
	return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}
function tongji() {
}
function getip_user() {
	if(empty($_SERVER["HTTP_CLIENT_IP"]) == false) {
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	} else if(empty($_SERVER["HTTP_X_FORWARDED_FOR"]) == false) {
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} else if(empty($_SERVER["REMOTE_ADDR"]) == false) {
		$cip = $_SERVER["REMOTE_ADDR"];
	} else {
		$cip = "";
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = isset($cips[0]) ? $cips[0] : "";
	unset($cips);
	return $cip;
}

?>