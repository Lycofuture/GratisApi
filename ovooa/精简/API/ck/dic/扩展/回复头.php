<?php

function dic_head(){

echo '±img=http://q2.qlogo.cn/headimg_dl?dst_uin=%QQ%&spec=140±';

echo "〔昵称〕：\$群昵称 %群号% %QQ%\$\r";

echo "〔账号〕：%QQ%\r";

}

function dic_tail(){

$file = @file_get_contents('./dic/缓存/小尾巴.php');

if(preg_match('/http(.*?)/',$file,$url)){

$text = file_get_content($url);

}else{

$text = $file;

}

return $text;

}
