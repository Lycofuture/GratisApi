<?php
function strim($str)
{
	return quotes(htmlspecialchars(trim($str)));
}
function get_curl($url,$post=0,$referer=1,$cookie=0,$header=0,$ua=0,$nobaody=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	if(stripos($url, "https://") !== false) {
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
	curl_setopt($ch, CURLOPT_SSLVERSION, 1);
	}
	$httpheader[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
	$httpheader[] = "Accept-Encoding:gzip,deflate";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.9";
	$httpheader[] = "Connection:close";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if($header){
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
	}
	if($cookie){
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if($referer){
		if($referer==1){
			curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_HOST']);
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
	}
	if($ua){
		curl_setopt($ch, CURLOPT_USERAGENT,$ua);
	}else{
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 6.0.1; OPPO R9s Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36');
	}
	if($nobaody){
		curl_setopt($ch, CURLOPT_NOBODY,1);
	}
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
	if (curl_errno($ch)) {
		return '[内部错误]'.curl_error($ch);//捕抓异常
	}else{
		return $ret;
	}
	curl_close($ch);
}

function real_ip(){
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
	$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
} elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
	$ip = $_SERVER['HTTP_X_REAL_IP'];
} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
	foreach ($matches[0] AS $xip) {
		if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
			$ip = $xip;
			break;
		}
	}
}
return $ip;
}
function get_ip_city($ip)
{
	$url = 'http://open.onebox.so.com/dataApi?type=ip&src=onebox&tpl=0&num=1&query=ip&url=ip&ip=';
	$city = get_curl($url . $ip);
	$city = json_decode($city, true);
	if ($city['isLocalIp']!=true) {
		$location = $city['0'] . " " . $city['1'] . " " . $city['2'] . " " . $city['3'] . " " . $city['4'] . " " . $city['5'];
	} else {
		$location = $city['0'].$city['4'];
	}
	if($location){
		return $location;
	}else{
		return false;
	}
}

function daddslashes($string, $force = 0, $strip = FALSE) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}

function getSubstr($str, $leftStr, $rightStr)
{
	$left = strpos($str, $leftStr);
	//echo '左边:'.$left;
	$right = strpos($str, $rightStr,$left);
	//echo '<br>右边:'.$right;
	if($left < 0 or $right < $left) return '';
	return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}

function strexists($string, $find) {
	return !(strpos($string, $find) === FALSE);
}
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$stringabc=$string;
	$ckey_length = 4;
	$key = md5($key ? $key : ENCRYPT_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		$code=$keyc.str_replace("=", '', base64_encode($result));
		$check=strpos($code,"/");
		if ($check) {
			return authcode($stringabc, 'ENCODE', SYS_KEY, 0);
		} else {
			return $code;
		}
	}
}

function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}

/****
	*十六进制转字符串函数
	*@pream string $hex='616263';
	*/ 
	function hexToStr($hex){   
		$str=""; 
		for($i=0;$i<strlen($hex)-1;$i+=2)
		$str.=chr(hexdec($hex[$i].$hex[$i+1]));
		return $str;
	} 


function checkIfActive($string) {
	$array=explode(',',$string);
	$php_self=substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'],'/')+1);
	$php_self=str_replace('.html','',$php_self);
	if (in_array($php_self,$array)){
		return 'current';
	}else
		return null;
}

function pdhttp() {
	if ($_SERVER['HTTPS'] != "on") {
		return "http";
	}else{
		return "https";
	}
}

function strip($str) {
	$strip = strip_tags($str);
	$strip = trim($strip);
	$strip = preg_replace('/\s(?=\s)/', '', $strip);
	//$strip = preg_replace('/[\n\r\t]/', '\n', $strip);
	return $strip;
}

function dic_DataRead($list, $name, $type = text) {
	clearstatcache();
	$fp_name = "dic/数据/" . $list . ".txt";
	$fp = fopen($fp_name, "r");
	flock($fp, LOCK_EX);
	$fp_open = fread($fp, filesize($fp_name));
	if ($type != "text") {
		return $fp_open;
	}else{
		$encode = base64_encode($name);
		$json = json_decode($fp_open, true);
		return $json[$encode];
	}
	flock($fp, LOCK_UN);
	fclose($fp);
}

function dic_DataWrite($list, $name, $msg) {
	clearstatcache();
	$encode = base64_encode($name);
 	$fp_name = "dic/数据/" . $list . ".txt";
	$dir = dirname($fp_name);
	if(!is_dir($dir)){
		mkdir($dir, 0777, true);
	}
	$read = dic_DataRead($list, $name, "json");
	if(!$read) {
		$json = array();
	}else{
		$json = json_decode($read, true);
	}
	$now_array = array($encode => $msg);
	$new_array = array_merge($json, $now_array);
	$new_json = json_encode($new_array);
	$fp = fopen($fp_name, "w");
	flock($fp, LOCK_EX);
	fwrite($fp, $new_json);
	flock($fp, LOCK_UN);
	fclose($fp);
}


?>