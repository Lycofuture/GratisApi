<?php


function getip_user() {
return $_SERVER["HTTP_X_FORWARDED_FOR"];
}
/*

function need::json($code,$name,$msg=false) {
	if(is_array($name)) {
		//是数组
		$foundation=array(
		'code'=>$code,
		);
		$array=array_merge($foundation,$name);
	} else {
		//不是数组
		$array=array(
		'code'=>$code,
		$name=>$msg,
		);
	}
	return json_encode($array,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

*/
function Music_curl($url,$data=0,$header_array=0,$referer=0,$time=30,$code=0,$body=false,$Encoding=false,$ip=false) {
if($header_array==0){
$header_array=[];
}
$ip=$ip?$ip:getip_user();
		$header=[
		'CLIENT-IP: '.$ip,
		'X-FORWARDED-FOR: '.$ip,
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.106 Safari/537.36'
		];
	$header=array_merge($header_array,$header);
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
	if($data) {
		if(is_array($data)) {
			if($data["CURLFile"]==false) {
				$data=http_build_query($data);
			}
		}
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
	}
	if($referer) {
		curl_setopt($curl,CURLOPT_REFERER,$referer);
	}
	if($body) {
		curl_setopt($curl, CURLOPT_NOBODY,true);
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
		$code_int['exec']=$Encoding?characet(substr($return,$code_code["header_size"])):substr($return,$code_code["header_size"]);
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


function line($string, $length, $end, $once = false) {
	$array = array();
	$strlen = mb_strlen($string);
	while($strlen) {
		$array[] = mb_substr($string, 0, $length, "utf-8");
		if($once)
		return $array[0] . $end;
		$string = mb_substr($string, $length, $strlen, "utf-8");
		$strlen = mb_strlen($string);
	}
	return implode($end, $array);
}

function curl_post($url, $data) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		if (is_array($data)) {
			$data = http_build_query($data);
			$data = str_replace('+', '%20', $data);
		}
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$tmpInfo = curl_exec($curl);
		curl_close($curl);
		return $tmpInfo;
	}