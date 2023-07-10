<?php
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(68); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$msg = urlencode($_REQUEST["msg"]);
//echo $msg;exit;
$h = @$_REQUEST["h"]?:"mp3";
$speed = @$_REQUEST["speed"]?:"5";
$sound = @$_REQUEST["sound"]?:13;
$type = @$_REQUEST["type"];
$format = @$_REQUEST['format']?:'ZH';
if($format == 'jp'){
    $data = json_decode(need::teacher_curl('https://fanyi.baidu.com/basetrans',[
        'post'=>[
            'query'=>'你好你好',
            'from'=>'zh',
            'to'=>'jp',
            'token'=>'130eba4e6f1846adb1703807ba6264bf',
            'sign'=>'47194.285547'
        ],
        'refer'=>'https://fanyi.baidu.com/?aldtype=38319&tpltype=sigma',
        'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
        'cookie'=>'BAIDUID=4E57A3A4FB7E5AE7EF4652399C19BB10:FG=1; delPer=0; BIDUPSID=4E57A3A4FB7E5AE7EF4652399C19BB10; BDRCVFR[5GQZCjFg8mf]=mk3SLVN4HKm; __yjs_duid=1_15d26c40eeaaba756b8b9481f85174ff1628558464580; rsv_i=6c02S12b7hNTcB2ZewTf3HEM1wFikkhSYIcKmi0syT%2BoRtZVeOsOJya%2FlwDOSXdCpGPRTWHYZABz3HeWCy9ADKSzTaHhu1w; BDRCVFR[vFswLGFo7sC]=mk3SLVN4HKm; BDUSS=JiY0gyWVRXbHowSHZrc29kODJIfmJLYzBTb2tKM1lScm1KTFduRWJqNVI3VUZoRUFBQUFBJCQAAAAAAAAAAAEAAAAO4OVfwr27or-ow8trbQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFFgGmFRYBphMU; BDUSS_BFESS=JiY0gyWVRXbHowSHZrc29kODJIfmJLYzBTb2tKM1lScm1KTFduRWJqNVI3VUZoRUFBQUFBJCQAAAAAAAAAAAEAAAAO4OVfwr27or-ow8trbQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFFgGmFRYBphMU; IMG_WH=360_682; H_WISE_SIDS=107315_110085_127969_164326_175755_176120_176399_176677_177008_177409_177897_178327_178530_178639_179345_179455_179486_180114_180276_180314_180407_181135_181216_181485_181585_181589_181610_181712_181862_181941_182000_182002_182100_182141_182178_182194_182247_182530_182775_182860_183111_183327_183431_183507_183535_183548_183568_183584_183867_183944_183954_184010_184146_184163_184275_184286_184456_184551_184576_184722_184789_184794_184809_184826_184849_184895_184919; BDPASSGATE=IlPT2AEptyoA_yiU4VKG3kIN8efjWrKBAh7GSEph36OtfCaWmhH3BrVMSEnHN-a81iTM-Y3fo_-5kV8PQlxmicwdggYTpW-H7CG-zNSF5hHNTaNgzbIZCb4jQUZqqvzxkgl-zuEHQ49zBCstpBTbpuoLivKl73JQb4r56kCygKrl_oGm2X8My88bOXVfYZ04Stu594rXnEpKLDm4YtqtT9PecSIMR7QEy0ufls9S2gzcmCUw3uPxWsRSI9rkI9pfG3bfGtuAzMS8ByUwvodiVEITokSY7qjxVE-Z; SE_LAUNCH=5%3A1629680296; BA_HECTOR=0k0g8k8h2k002124kj1gi5sl811; BDORZ=AE84CDB3A529C0F8A2B9DCDD1D18B695; ab_sr=1.0.1_ZWI5NDQ1MTMzZTk4OGZmODQxODY4NjM5Y2Y3NDliOGZkMDQyNjRhZDg4NTA1MDczYWJmMjQ3ZDFlZTJjNTAzZWIyN2M1NWEwYzVkYTJiNmEyZmY5NjhlZTA1ZDlhNzJjMTE3ZTJiNTY0ODBkODNlZWY5NTJkN2FmNjNjYTkwYTUxMTA4MjliYjVjNDE0NGViNWQ5MDFmNmUxNWI2MDQ2OWM1YjI5OGQ4YmU0NzUzZWM5MDI0YzEzMDBjNmM3MzUz'
    ]),true);
    print_r($data);exit;
}
/*百度语言合成
音色说明：
基础音库:度小宇=1，度小美=0，度逍遥（基础）=3，度丫丫=4
精品音库:度逍遥（精品）=5003，度小鹿=5118，度博文=106，度小童=110，度小萌=111，度米朵=103，度小娇=5
臻品音库:度逍遥=4003，度博文=4106，度小贤=4115，度小鹿=4119，度灵儿=4105，度小乔=4117，度小雯=4100，度米朵=4103
[1~4基础音库]
1=度小宇(成熟男声)
2=度小美(成熟女声)
3=度逍遥(磁性男声)
4=度丫丫(可爱女童)
[5~11精品音库]
5=度逍遥(磁性男声)
6=度小鹿(甜美女声)
7=度博文(情感男声)
8=度小童(活泼男童)
9=度小萌(可爱女童)
10=度米朵(可爱女童)
11=度小娇(情感女声)
[12~19臻品音库]
12=度逍遥(磁性男声)
13=度博文(情感男声)
14=度小贤(情感男声)
15=度小鹿(甜美女声)
16=度灵儿(清澈女声)
17=度小乔(情感女声)
18=度小雯(成熟女声)
19=度米朵(可爱女童)*/
if($speed > 9 || $speed < 1){
    $speed = "5";
}
if($sound == "0"){
    $sound = "0";
}else
if($sound == "1"){
    $sound = "1";
}else
if($sound == "2"){
    $sound = "2";
}else
if($sound == "3"){
    $sound = "3";
}else
if($sound == "4"){
    $sound = "4";
}else
if($sound == "5"){
    $sound = "5";
}else
if($sound == "6"){
    $sound = "103";
}else
if($sound == "7"){
    $sound = "106";
}else
if($sound == "8"){
    $sound = "1115";
}else
if($sound == "9"){
    $sound = "5117";
}else
if($sound == 10){
    $sound = 4119;
}elseif($sound == 11){
    $sound = 4115;
}else
if($sound == 12){
    $sound = 4117;
}else
if($sound == 13){
    $sound = 4100;
}else
if($sound == 14){
    $sound = 4103;
}else
if($sound == 15){
    $sound = 4105;
}/*else
if($sound == 16){
    $sound = 4015;
}*/else{
    $sound = "103";
}
if(!$msg){
    echo need::json(array("code"=>"-1","text"=>"抱歉，msg参数不存在！此为必填项。"));exit;
}
$url="https://ss0.baidu.com/6KAZsjip0QIZ8tyhnq/text2audio?&cuid=dict&lan={$format}&ctp=9&per=".$sound."&pdt=30&vol=9&spd=".$speed."&tex=".$msg."/";
//die($url);
$str = need::teacher_curl($url);
$rand = need::getMillisecond();
$path = $rand.".".$h;
file_put_Contents(__DIR__.'/Cache/'.$path, $str);
Switch($type){
    case 'text':
    echo "http://".$_SERVER['HTTP_HOST']."/API/yuyin/Cache/".$rand.".".$h."";
    break;
    case 'audio':
    //$file = @file_get_contents($shuju);
    header('content-type:audio/'.$h);
    echo $str;
    break;
    default:
    echo need::json(array("code"=>"1","url"=>"http://".$_SERVER['HTTP_HOST']."/API/yuyin/Cache/".$rand.".".$h.""));
}
fastcgi_finish_request();//先返回上面的内容
time_sleep_until(time()+30);//延迟30秒后执行下面的命令
unlink(__DIR__.'/Cache/'.$rand.".".$h);

function get_curl($url,$post=0,$referer=1,$cookie=0,$header=0,$ua=0,$nobaody=0,$json=0)
{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$httpheader[] = "Accept:application/json";
		$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
		$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
		$httpheader[] = "Connection:close";
		if($json){
			$httpheader[] = "Content-Type:application/json; charset=utf-8";
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
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
				curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
			}else{
				curl_setopt($ch, CURLOPT_REFERER, $referer);
			}
		}
		if($ua){
			curl_setopt($ch, CURLOPT_USERAGENT,$ua);
		}else{
			curl_setopt($ch, CURLOPT_USERAGENT,'Dalvik/2.1.0 (Linux; U; Android 9; 16s Build/PKQ1.190202.001)');
		}
		if($nobaody){
			curl_setopt($ch, CURLOPT_NOBODY,1);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		//$ret=mb_convert_encoding($ret, "UTF-8", "UTF-8");
		return $ret;
}

?>