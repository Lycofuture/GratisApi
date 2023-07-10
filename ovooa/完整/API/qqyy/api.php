<?php


/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$msg = urlencode((String)@$_GET["msg"]);

$h = @$_GET["h"]?:"mp3";

$speed = @$_GET["speed"]?:"5";

$sound = @$_GET["sound"]?:"6";

$type = @$_GET["type"];

$data = need::teacher_curl('https://textts.qq.com/cgi-bin/tts',[
'post'=>'{"appid":"201908021016","uin":2354452553,"sendUin":202347537,"text":"哈哈哈","textmd5":"0D1B08C34858921BC7C662B228ACB7BA","seq":2,"clientVersion":"AND_537065007_8.4.1","net":4}',
'cookie'=>'uin=2354452553;skey=M7lMGwkdHR',
'ua'=>'Dalvik/2.1.0 (Linux; U; Android 10; PCLM10 Build/QKQ1.191021.002)'
]);

$rand=need::getMillisecond();

$shuju=$rand.".".$h;

$handle = fopen($shuju, 'w') or die('Cannot open file: '.$shuju);

fwrite($handle, $data);

if($type == 'text'){

echo "http://".$_SERVER['HTTP_HOST']."/API/qqyy/".$rand.".".$h."";

}else{

echo need::json(array("code"=>"1","url"=>"http://".$_SERVER['HTTP_HOST']."/API/qqyy/".$rand.".".$h.""));

}



fastcgi_finish_request();//先返回上面的内容
time_sleep_until(time()+30);//延迟30秒后执行下面的命令
unlink("".$rand.".".$h);

