<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(73); // 调用统计函数
/* End */
addAccess();//调用统计函数
require ('../../curl.php');//引入curl文件

require ('../../need.php');//引入bkn文件

require ('curl.php');

$type=$_REQUEST["type"]?$_REQUEST["type"]:"qun.qq.com";

if($_REQUEST["type"]=="all"||strstr($_REQUEST["type"],",")){

$type="qzone.qq.com";

}

$appid=array(

"qzone.qq.com"=>"549000912",//QQ空间

"qun.qq.com"=>"715030901",//QQ群官网

"vip.qq.com"=>"8000201",//QQ会员

"connect.qq.com"=>"716027613",//QQ互联

"docs.qq.com"=>"1600000931",//腾讯文档

"id.qq.com"=>"1006102",//QQ安全中心

"tenpay.com"=>"716027609"//腾讯支付

//https://ssl.ptlogin2.qq.com/ptqrshow?appid=716027609&e=2&l=M&s=3&d=72&v=4&t=0.4847652946394696&daid=383&pt_3rd_aid=101502376

//"www.qq.com"=>"1101774620",//QQ安全中心
);

$daid=array(

"qun.qq.com"=>"73",

"qzone.qq.com"=>"5",

"vip.qq.com"=>"18",

"connect.qq.com"=>"377",

"docs.qq.com"=>"536",

"id.qq.com"=>"1",

"tenpay.com"=>"383"

//"www.qq.com"=>"381",

);

$url=array(

"qun.qq.com"=>"https%3A%2F%2Fqun.qq.com%2F",

"qzone.qq.com"=>"https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone",

"vip.qq.com"=>"https%3A%2F%2Fvip.qq.com%2Floginsuccess.html",

"connect.qq.com"=>"https%3A%2F%2Fconnect.qq.com%2Flogin_success.html",

"docs.qq.com"=>"https%3A%2F%2Fdocs.qq.com%2Ftim%2Fdocs%2Fcomponents%2FBindQQ.html%3Ftype%3Dlogin",

"id.qq.com"=>"https%3A%2F%2Fid.qq.com%2Findex.html",

"tenpay.com"=>"https%3A%2F%2Fgraph.qq.com%2Foauth2.0%2Flogin_jump"

//"www.qq.com"=>"http%3A%2F%2Fconnect.qq.com",

);

$time=time();

if(!$_REQUEST["qrsig"]) {

$data_msg=curl_curl('https://ssl.ptlogin2.qq.com/ptqrshow?appid='.$appid[$type].'&e=2&l=M&s=3&d=72&v=4&t=0.5409099'.$time.mt_rand().'&daid='.$daid[$type].'&pt_3rd_aid=0',0,0,0,0,1);

	preg_match('/qrsig=(.*?);/',$data_msg['header'],$data_qrsig);
	
	if($data_qrsig[1]) {
	
		file_put_contents("img/".$time.".jpg",$data_msg['exec']);
		
				
																		$array=array(
																		"qrsig"=>$data_qrsig[1],
																		"url"=>"http://".$_SERVER['HTTP_HOST']."/API/Skey/img/".$time.".jpg",
																		"base64_picture"=>base64_encode($data_msg['exec'])
																		);
		echo need::json(array('code'=>'1','data'=>$array));
		if (function_exists("fastcgi_finish_request")) {
			//用于验证二维码状态，防止二维码超时过快
			fastcgi_finish_request();
			$haeder=[
															'Cookie: qrsig='.$data_qrsig[1].';',
															];
			for ($i=1;$i<15;$i++) {
				sleep(5);
				if ($i >= 14){
		unlink("img/".$time.".jpg");
		}else{
				$data_msg=curl_curl('https://ssl.ptlogin2.qq.com/ptqrlogin?u1='.$url[$type].'&ptqrtoken='.getqrtoken($data_qrsig[1]).'&ptredirect=1&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-'.$time.'0000&js_ver=20010217&js_type=1&login_sig='.$data_qrsig[1].'&aid='.$appid[$type].'&daid='.$daid[$type].'&ptdrvs=&',0,$haeder,0,0,1);
				preg_match("/ptuiCB\('(.*?)'\)/",$data_msg['exec'],$data_data);
				$data_data_code=explode("','",str_replace("', '","','",$data_data[1]));
				if($data_data_code[0]==66) {
				} else {
					break;
				}
			}
		}}
		exit();
	} else {
		echo need::json(array("code"=>"-1","text"=>"二维码申请失败！"));
		exit();
	}
}
if($_REQUEST["type"]=="all"){
all($appid,$daid,$url);
exit();
}else if(strstr($_REQUEST["type"],",")){
$appid_part=explode(",",$_REQUEST["type"]);
foreach($appid_part as $v){
if($appid[$v]){
$appid_Self[$v]=$appid[$v];
}
}
all($appid_Self,$daid,$url);
exit();
}
$haeder=[
						'Cookie: qrsig='.$_REQUEST["qrsig"].';',
						];
$data_msg=curl_curl('https://ssl.ptlogin2.qq.com/ptqrlogin?u1='.$url[$type].'&ptqrtoken='.getqrtoken($_REQUEST["qrsig"]).'&ptredirect=1&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-'.$time.'0000&js_ver=20010217&js_type=1&login_sig='.$_REQUEST["qrsig"].'&aid='.$appid[$type].'&daid='.$daid[$type].'&ptdrvs=&',0,$haeder,0,0,1);
print_r($data_msg);
preg_match("/ptuiCB\('(.*?)'\)/",$data_msg['exec'],$data_data);
$data_data_code=explode("','",str_replace("', '","','",$data_data[1]));

if($data_data_code[0]==65) {
	echo need::json(array("code"=>"-2","text"=>"二维码已失效！"));
	@unlink("img/".$time.".jpg");
	exit();
} else if($data_data_code[0]==66) {
	echo need::json(array("code"=>"-3","text"=>"二维码未失效！"));
	exit();
} else if($data_data_code[0]==67) {
	echo need::json(array("code"=>"-4","text"=>"正在验证二维码！"));
	exit();
} else if($data_data_code[0]==68) {
	echo need::json(array("code"=>"-5","text"=>"操作者拒绝登录！"));
		@unlink("img/".$time.".jpg");
	exit();
} else if($data_data_code[0]==0) {
	$data_msg_one=curl_curl($data_data_code[2],0,0,0,0,1);
	preg_match('/Set-Cookie: skey=(.*);/iU',$data_msg['header'],$skey);
	if(!$skey[1]) {
	print_r($data_msg_one);
		echo need::json(array("code"=>"-6","text"=>"cookie获取失败！"));
		@unlink("img/".$time.".jpg");
		exit();
	}
	//Array ( [exec] => [code] => 200 [content_type] => text/html;Charset=utf-8 [header] => HTTP/1.1 302 Found Date: Fri, 23 Jul 2021 15:48:33 GMT Content-Length: 0 Connection: keep-alive Cache-Control: no-cache, no-store, must-revalidate Expires: -1 Location: https://graph.qq.com/oauth2.0/login_jump Pragma: no-cache Server: Tencent Login Server/2.0.0 Strict-Transport-Security: max-age=31536000 Set-Cookie: pt_login_type=3;Path=/;Domain=graph.qq.com;Secure;HttpOnly;SameSite=None; HTTP/1.1 200 OK Date: Fri, 23 Jul 2021 15:48:35 GMT Content-Type: text/html;Charset=utf-8 Content-Length: 225 Connection: keep-alive Server: QZHTTP-2.38.20 Content-Encoding: gzip Cache-control: no-cache )
	preg_match('/uin=o(\d+);/',$data_msg['header'],$uin);
	preg_match("/p_skey=(.*?);/",$data_msg_one['header'],$p_skey);
	preg_match("/pt4_token=(.*?);/",$data_msg_one['header'],$pt4token);
	$array=array(
								'uin'=>getuin($uin[1]),
								'name'=>$data_data_code[5],
								'gtk'=>getBkn($skey[1]),
								'skey'=>$skey[1],
								'p_skey'=>$p_skey[1],
								'pt4_token'=>$pt4token[1]
								);
	echo need::json(array("code"=>"1","data"=>$array));
//file_put_contents("../".getuin($uin[1]).".txt",need::json(1000,null,null,$array));
} else {
	echo need::json(array("code"=>"-7","text"=>"未知的错误，错误码:".$data_data_code[0]));
	@unlink("img/".$time.".jpg");exit();
}
function all($appid,$daid,$url){
$haeder=[
'Cookie: qrsig='.$_REQUEST["qrsig"].';',
];
foreach($appid as $k=>$v){
$Callback='https://ssl.ptlogin2.qq.com/ptqrlogin?u1='.$url[$k].'&ptqrtoken='.getqrtoken($_REQUEST["qrsig"]).'&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-' . time() . '0000&js_ver=10194&js_type=1&login_sig='.$_REQUEST["qrsig"].'&pt_uistyle=40&aid=549000912&daid='.$daid[$k].'&';
$data_msg=curl_curl($Callback,0,$haeder,0,0,1);
preg_match("/ptuiCB\('(.*?)'\)/",$data_msg['exec'],$data_data);
$data_data_code=explode("','",str_replace("', '","','",$data_data[1]));
if($data_data_code[0]==65) {
	echo need::json(array("code"=>"-2","text"=>"二维码已失效！"));
	@unlink("img/".$time.".jpg");
	exit();
} else if($data_data_code[0]==66) {
	echo need::json(array("code"=>"-3","text"=>"二维码未失效！"));
	exit();
} else if($data_data_code[0]==67) {
	echo need::json(array("code"=>"-4","text"=>"正在验证二维码！"));
	exit();
} else if($data_data_code[0]==68) {
	echo need::json(array("code"=>"-5","text"=>"操作者拒绝登录！"));
	@unlink("img/".$time.".jpg");
	exit();
} else if($data_data_code[0]==0) {
	$data_msg_one=curl_curl($data_data_code[2],0,0,0,0,1);
	preg_match('/Set-Cookie: skey=(.*);/iU',$data_msg['header'],$skey);
	if(!$skey[1]) {
		exit();
	}
	preg_match('/uin=o(\d+);/',$data_msg['header'],$uin);
	preg_match("/p_skey=(.*?);/",$data_msg_one['header'],$p_skey);
	preg_match("/pt4_token=(.*?);/",$data_msg_one['header'],$pt4token);
$array["data"]["uin"]=getuin($uin[1]);
$array["data"]["name"]=$data_data_code[5];
$array["data"]["gtk"]=getBkn($skey[1]);
$array["data"]["skey"]=$skey[1];
$array["data"]["p_skey"][$k]=$p_skey[1];
$array["data"]["pt4token"][$k]=$pt4token[1];
$data_msg="";
$data_data_code="";
$data_msg_one="";
$skey="";
$uin="";
$p_skey="";
$pt4token="";
}
/*
$haeder=[
'Cookie: qrsig='.$_REQUEST["qrsig"].';',
];
$data_msg=curl_curl('https://ssl.ptlogin2.qq.com/ptqrlogin?u1='.$url[$type].'&ptqrtoken='.getqrtoken($_REQUEST["qrsig"]).'&ptredirect=1&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-'.$time.'0000&js_ver=20010217&js_type=1&login_sig='.$_REQUEST["qrsig"].'&aid='.$appid[$type].'&daid='.$daid[$type].'&ptdrvs=&',0,$haeder,0,0,1);
preg_match("/ptuiCB\('(.*?)'\)/",$data_msg['exec'],$data_data);
$data_data_code=explode("','",str_replace("', '","','",$data_data[1]));
*/

}
if($array["data"]["skey"]){
echo need::json(array('code'=>'1',$array));
@unlink("img/".$time.".jpg");exit;
}else{
	echo need::json(array("code"=>"-7","text"=>"未知的错误，错误码:".$data_data_code[0]));
	@unlink("img/".$time.".jpg");exit();
}
}




function getuin($uin){
        for($i = 0; $i < strlen($uin); $i++){
			if($uin[$i]=='o'||$uin[$i]=='0')continue;
			else break;
        }
        return substr($uin,$i);
    }

function getqrtoken($qrsig) {
	$len=strlen($qrsig);
	$hash=0;
	for ($i=0;$i<$len;$i++) {
		$hash+=(($hash<<5)&2147483647)+ord($qrsig[$i])&2147483647;
		$hash&=2147483647;
	}
	return $hash&2147483647;
}