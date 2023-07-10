<?php
/* *
 * 类名：DicNotify
 * 功能：网络词库校验处理类
 * 详细：处理词库验证访问权限
 * 版权：小鑫
 * Q Q：1569097443
 * 版本：V1.0
 */

class AlidicNotify {

	var $config;
	function __construct($config){
		$this->config = $config;
		$this->http_verify_url = $this->config['apiurl'].'api.php?';
	}
	function AlidicNotify($config) {
		$this->__construct($config);
	}

	function getSign($param, $key)
	{
		$signPars = "";
		reset($param);
		foreach ($param as $k => $v) {
			if ("sign" != $k && "sign_type" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}
			if ("msgtext" == $k && "" == $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars = substr($signPars,0,count($signPars)-2);
		if(get_magic_quotes_gpc()){$signPars = stripslashes($signPars);}
		$signPars .= $key;
		$sign = strtoupper(md5($signPars));
		return $sign;
	}

	function verifyReturn(){
	if(empty($_GET["sign"]))exit("参数错误 -8");
		if(empty($_GET)) {
			return false;
		} else {
			$key = $this->config['key'];
			$isSign = $this->getSign($_GET, $key);
			$responseTxt = 'true';
			if (preg_match("/true$/i",$responseTxt) && $isSign == $_GET["sign"]) {
				return true;
			} else {
				return false;
			}
		}
	}

function logResult($word='') {
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}
}
?>
