<?php
/*----------------------------
1、报错机制
------------------------------*/
ini_set('display_errors', 0);

if (version_compare(PHP_VERSION, '5.3', '>=')){
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
}else{
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
}
/*-----------------------
2、时区设置，运行时长设置
-------------------------*/
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
   set_magic_quotes_runtime(0);
} else {
   ini_set('magic_quotes_runtime', 0);
}

if (version_compare(PHP_VERSION, '5.1', '>=')) date_default_timezone_set('PRC'); 

/*-----------------------
3、UI平台判断
-------------------------*/
$cde_is_mobile=false;
if(is_mobile()) $cde_is_mobile=true;

define('CDEISMOBILE',$cde_is_mobile);

define('DD','/');
define('APPPATH', str_replace('inc/common.php', '', str_replace('\\', '/', __FILE__)));
define('INCPATH',APPPATH.'inc'.DD);
$cde_cfg['rooturl']="http://pp.cdecms.com/qm/";
$viewdir=!$cde_is_mobile? 'web' : 'wap';
define('VPATH',$viewdir.DD);
$cde_cfg['viewurl']=$cde_cfg['rooturl'].$viewdir;
$cde_charset="UTF-8";
function is_mobile()
{
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
		return TRUE;
    }
	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA'])){
		return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;
	}
	if (isset($_SERVER['HTTP_USER_AGENT']))
	{
		$agent = trim($_SERVER['HTTP_USER_AGENT']);
	}
	$mobiles = array('mobile','mobileexplorer','huawei','oppo','nokia','sony','motorola','ericsson','mot-','samsung','htc','sgh','lg',
	'sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','ipad','blackberry','meizu','android','openweb',
     'netfront','symbian','ucweb','windows ce','windows phone','webos','palm','operamini','opera mini','operamobi','opera mobi','openwave','nexusone','j2me','wireless','fennec','wosbrowser','mqqbrowser','juc','cldc','midp','up.link','up.browser','smartphone','cellphone','wap'
            );
	foreach($mobiles as $key){
		if (FALSE !== (stripos($agent, $key)))
		{
			return TRUE;
		}
	}
	if (isset ($_SERVER['HTTP_ACCEPT'])){ // 协议法，因为有可能不准确，放到最后判断
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
		$accept=$_SERVER['HTTP_ACCEPT'];
		if ((strpos($accept, 'vnd.wap.wml') !== FALSE) && (strpos($accept, 'text/html') === FALSE || 
		(strpos($accept, 'vnd.wap.wml') < strpos($accept, 'text/html')))){
                return TRUE;
            }
        }

	return FALSE;
}
function setoptions($start,$end,$selected,$str=''){
	$option='';
	for($i=$start;$i<=$end;$i++){
		$option.='<option value="'.$i.'"';
		if($i==$selected) $option.=" selected";
		$option.='>'.$i.$str.'</option>';
	}
	return $option;
}
function cn_substr($string,$s,$e=0){
	global $cde_charset;
	$char=strtolower($cde_charset);
	if(strpos($char,'utf')!==false) $cn=3;
	elseif(strpos($char,'gb')!==false) $cn=2;
	if($s<0){
		$str=substr($string,$s*$cn);
	}else{
		if($e>0) $str=substr($string,$s,$e*$cn);
		else $str=substr($string,$s);
	}
	return $str;
}
?>