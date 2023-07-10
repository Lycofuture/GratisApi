<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(73); // 调用统计函数
/* End */
require ('../../curl.php');//引入curl文件
require ('../../need.php');//引入bkn文件
require ('curl.php');
$type=@$_REQUEST["type"]?@$_REQUEST["type"]:"qun.qq.com";
if(@$_REQUEST["type"]=="all"||strstr(@$_REQUEST["type"],",")){
	$type="qzone.qq.com";
}
need::delfile(__DIR__.'/img/', 1);
$appid=array(
	"qzone.qq.com"=>"549000912",//QQ空间
	"qun.qq.com"=>"715030901",//QQ群官网
	"vip.qq.com"=>"8000201",//QQ会员
	"connect.qq.com"=>"716027613",//QQ互联
	"docs.qq.com"=>"1600000931",//腾讯文档
	"id.qq.com"=>"1006102",//QQ安全中心
	"qq.com"=>"716027609",//腾讯
);

$daid=array(
	"qun.qq.com"=>"73",
	"qzone.qq.com"=>"5",
	"vip.qq.com"=>"18",
	"connect.qq.com"=>"377",
	"docs.qq.com"=>"536",
	"id.qq.com"=>"1",
	"qq.com"=>"383"
);

$url=array(
	"qun.qq.com"=>"https%3A%2F%2Fqun.qq.com%2F",
	"qzone.qq.com"=>"https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone",
	"vip.qq.com"=>"https%3A%2F%2Fvip.qq.com%2Floginsuccess.html",
	"connect.qq.com"=>"https%3A%2F%2Fconnect.qq.com%2Flogin_success.html",
	"docs.qq.com"=>"https%3A%2F%2Fdocs.qq.com%2Ftim%2Fdocs%2Fcomponents%2FBindQQ.html%3Ftype%3Dlogin",
	"id.qq.com"=>"https%3A%2F%2Fid.qq.com%2Findex.html",
	"qq.com"=>"https%3A%2F%2Fgraph.qq.com%2Foauth2.0%2Flogin_jump"
);

//$time=time();

if(!@$_REQUEST["qrsig"]) {
	$data_msg=curl_curl('https://ssl.ptlogin2.qq.com/ptqrshow?appid='.$appid[$type].'&e=2&l=M&s=3&d=72&v=4&t=0.6643828'.$time.mt_rand().'&daid='.$daid[$type].'&pt_3rd_aid=0',0,0,0,0,1);
	// $data_msg = curl_curl('https://xui.ptlogin2.qq.com/ssl/ptqrshow?s=8&e=0&appid='.$appid[$type].'&type=0&t=0.6032158'.mt_Rand().'&daid='.$daid[$type].'&pt_3rd_aid=100497308', 0, 0, 0, 0, 1);
	
	preg_match('/qrsig=(.*?);/',$data_msg['header'],$data_qrsig);
	if($data_qrsig[1]) {
		$time = md5($data_qrsig[1]);
		// print_r($data_msg);exit;
		file_put_contents(__DIR__.'/img/'.$time.".png", $data_msg['exec']);
		$array=array(
			"qrsig"=>$data_qrsig[1],
			"url"=>$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/API/Skey/img/".$time.".png",
			"base64_picture"=>base64_encode($data_msg['exec'])
		);
		echo need::json(array('code'=>1,'data'=>$array));
		if (function_exists("fastcgi_finish_request")) {
			//用于验证二维码状态，防止二维码超时过快
			fastcgi_finish_request();
			$haeder=[
				'Cookie: qrsig='.$data_qrsig[1].';',
			];
			for ($i=1;$i<15;$i++) {
				sleep(5);
				if ($i >= 14){
		unlink(__DIR__.'./img/'.$time.".png");
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
		echo need::json(array("code"=>-1,"text"=>"二维码申请失败！"));
		exit();
	}
}
if(@$_REQUEST["type"]=="all"){
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
	'Cookie: qrsig='.@$_REQUEST["qrsig"].';'
];
$time = md5(@$_REQUEST['qrsig']);
$data_msg=curl_curl('https://ssl.ptlogin2.qq.com/ptqrlogin?u1='.$url[$type].'&ptqrtoken='.getqrtoken(@$_REQUEST["qrsig"]).'&ptredirect=1&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-'.$time.'0000&js_ver=20010217&js_type=1&login_sig='.@$_REQUEST["qrsig"].'&aid='.$appid[$type].'&daid='.$daid[$type].'&ptdrvs=&',0,$haeder,0,0,1);
preg_match("/ptuiCB\('(.*?)'\)/",$data_msg['exec'],$data_data);
$data_data_code=explode("','",str_replace("', '","','",(String) @$data_data[1]));
@unlink('./img/'.$time.'.png');
if($data_data_code[0]==65) {
	echo need::json(array("code"=>-2,"text"=>"二维码已失效！"));
	@unlink(__DIR__.'./img/'.$time.".png");
	exit();
} else if($data_data_code[0]==66) {
	echo need::json(array("code"=>-3,"text"=>"二维码未失效！"));
	exit();
} else if($data_data_code[0]==67) {
	echo need::json(array("code"=>-4,"text"=>"正在验证二维码！"));
	exit();
} else if($data_data_code[0]==68) {
	echo need::json(array("code"=>-5,"text"=>"操作者拒绝登录！"));
		@unlink(__DIR__.'./img/'.$time.".png");
	exit();
} else if($data_data_code[0]==0) {
	$data_msg_one=curl_curl($data_data_code[2],0,0,0,0,1);
	preg_match('/Set-Cookie: skey=(.*);/iU',$data_msg['header'],$skey);
	if($type == 'qq.com') {
		preg_match('/uin=o(\d+);/',$data_msg['header'],$uin);
		preg_match("/p_skey=(.*?);/",$data_msg_one['header'],$p_skey);
		preg_match("/pt4_token=(.*?);/",$data_msg_one['header'],$pt4token);
		$array=array(
			'uin'=>getuin($uin[1]),
			'name'=>$data_data_code[5],
//								'gtk'=>getBkn($skey[1]),
//								'skey'=>$skey[1],
			'p_skey'=>$p_skey[1],
			'pt4_token'=>$pt4token[1]
		);
	
		if($pt4token[1]){
	
			echo need::json(array('code'=>1,'text'=>'获取成功','data'=>$array));
			exit();
		}
		
	}else
	
	if(!$skey[1]) {
		echo need::json(array("code"=>-6,"text"=>"cookie获取失败！"));
		@unlink(__DIR__.'./img/'.$time.".png");
		exit();
	}
	preg_match('/uin=o(\d+);/',$data_msg['header'],$uin);
	preg_match("/p_skey=(.*?);/",$data_msg_one['header'],$p_skey);
	preg_match("/pt4_token=(.*?);/",$data_msg_one['header'],$pt4token);
	$array=array(
		'uin'=>getuin($uin[1]),
		'name'=>$data_data_code[5],
		'gtk'=>getBkn($skey[1]),
		'skey'=>$skey[1],
		'p_skey'=>$p_skey[1],
		'pt4_token'=>$pt4token[1],
		'IP'=>need::userip(),
		'ua'=>$_SERVER['HTTP_USER_AGENT']
	);
	echo need::json(array("code"=>1,"data"=>$array));exit();
//file_put_contents("../".getuin($uin[1]).".txt",need::json(1000,null,null,$array));
} else {
	echo need::json(array("code"=>-7,"text"=>"未知的错误，错误码:".$data_data_code[0]));
	@unlink(__DIR__.'./img/'.$time.".png");exit();
}
function all($appid,$daid,$url){
$haeder=[
'Cookie: qrsig='.@$_REQUEST["qrsig"].';',
];
foreach($appid as $k=>$v){
$Callback='https://ssl.ptlogin2.qq.com/ptqrlogin?u1='.$url[$k].'&ptqrtoken='.getqrtoken(@$_REQUEST["qrsig"]).'&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-' . time() . '0000&js_ver=10194&js_type=1&login_sig='.@$_REQUEST["qrsig"].'&pt_uistyle=40&aid=549000912&daid='.$daid[$k].'&';
$data_msg=curl_curl($Callback,0,$haeder,0,0,1);
preg_match("/ptuiCB\('(.*?)'\)/",$data_msg['exec'],$data_data);
$data_data_code=explode("','",str_replace("', '","','",$data_data[1]));
if($data_data_code[0]==65) {
	echo need::json(array("code"=>-2,"text"=>"二维码已失效！"));
	@unlink(__DIR__.'./img/'.$time.".png");
	exit();
} else if($data_data_code[0]==66) {
	echo need::json(array("code"=>-3,"text"=>"二维码未失效！"));
	exit();
} else if($data_data_code[0]==67) {
	echo need::json(array("code"=>-4,"text"=>"正在验证二维码！"));
	exit();
} else if($data_data_code[0]==68) {
	echo need::json(array("code"=>-5,"text"=>"操作者拒绝登录！"));
	@unlink(__DIR__.'./img/'.$time.".png");
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

}
if($array["data"]["skey"]){
echo need::json(array('code'=>1,$array));
@unlink(__DIR__.'./img/'.$time.".png");exit;
}else{
	echo need::json(array("code"=>-7,"text"=>"未知的错误，错误码:".$data_data_code[0]));
	@unlink(__DIR__.'./img/'.$time.".png");exit();
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