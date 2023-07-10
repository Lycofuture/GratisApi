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

$speed = @$_GET["speed"]?:"1";

$sound = @$_GET["sound"]?:"1";

$type = @$_GET["type"];

if($speed > 9 || $speed < 1){

$speed = "1";

}else

if($sound >9 || $sound < 1){

$sound = '1';

}

if($sound == "1"){

$sound = "0";

}else

if($sound == "2"){

$sound = "1";

}else

if($sound == "3"){

$sound = "2";

}else

if($sound == "4"){

$sound = "3";

}else

if($sound == "5"){

$sound = "4";

}else

if($sound == "6"){

$sound = "5";

}else

if($sound == "7"){

$sound = "6";

}else{

$sound = "1";

}


$data = need::teacher_curl('https://fanyi.sogou.com/reventondc/synthesis?text='.$msg.'&speed='.$speed.'&speaker='.$sound.'&lang=zh-CHS&from=girl');


$rand=need::getMillisecond();

$shuju=$rand.".".$h;

$handle = fopen($shuju, 'w') or die('Cannot open file: '.$shuju);

fwrite($handle, $data);

if($type == 'text'){

echo "http://".$_SERVER['HTTP_HOST']."/API/sgyy/".$rand.".".$h."";

}else{

echo need::json(array("code"=>"1","url"=>"http://".$_SERVER['HTTP_HOST']."/API/sgyy/".$rand.".".$h.""));

}


fastcgi_finish_request();//先返回上面的内容
time_sleep_until(time()+30);//延迟30秒后执行下面的命令
unlink("".$rand.".".$h);

