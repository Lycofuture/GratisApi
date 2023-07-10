<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(37); // 调用统计函数
/* End */

require ('../../curl.php');//引入curl文件

require ('../../need.php');//引入bkn文件

error_reporting(0);//防止不致命错误报错影响使用。
$post='{"req_body":{"uniqueID":"","scene":0,"pageType":1,"feedID":"71GuqVGym1KcoFUXR","collectionID":"","hostStatus":0,"SharerID":"1554942914732599","dataSource":1,"playStatus":1},"req_header":{"personId":"","authInfo":{"refreshToken":"","accessToken":"","sessionKey":"","authType":0,"thrAppId":"","uid":""},"channelId":1}}';
$header=array("Cookie:eas_sid=iaKsbfEhiVuB29MK3Gf9arCDrP","Accept:*/*",'User-Agent:Mozilla/5.0(Linux;Android6.0.1;OPPO R9s Plus Build/MMB29M;wv)AppleWebKit/537.36(KHTML,like Gecko)Version/4.0Chrome/55.0.2883.91Mobile Safari/537.36',"Connection:Keep-Alive","Charset:UTF-8","Accept-Encoding:gzip","Content-Type:application/json;charset=UTF-8","Host:api.weishi.qq.com","Content-Length:316");
$data=@get("https://api.weishi.qq.com/trpc.weishi.weishi_h5_proxy.weishi_h5_proxy/H5GetPlayPageNew?t=0.2785974940414975&g_tk=",$post,$header);

if(!$data){

exit(need::json(array("code"=>"-1","text"=>"获取失败请重试")));

}

$json = json_decode($data, true);
$s=count($json["rsp_body"]["recommendfeeds"]);
$s=rand(0,$s);

$bt=$json["rsp_body"]["recommendfeeds"][$s]["wording"]?:$json["rsp_body"]["recommendfeeds"][$s]["feed_desc_withat"];

echo need::json(array("code"=>"1","data"=>array("name"=>$bt,"author"=>$json["rsp_body"]["recommendfeeds"][$s]["poster"]["nick"],"img"=>$json["rsp_body"]["recommendfeeds"][$s]["images"][0]["url"],"url"=>$json["rsp_body"]["recommendfeeds"][$s]["video_url"])));


function get($url,$data=0,$header_array=0,$referer=0,$time=30,$code=0) {
	if($header_array==0) {
		$header=array("CLIENT-IP: ".ip(),"X-FORWARDED-FOR: ".ip(),'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.106 Safari/537.36');
	} else {
		$header=array("CLIENT-IP: ".ip(),"X-FORWARDED-FOR: ".ip(),'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.106 Safari/537.36');
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

function replace_unicode_escape_sequence($match){
  return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');}
?>