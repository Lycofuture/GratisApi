<?php

header('content-type: text/text');

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$msg = $_GET["msg"];

$type = $_GET["type"];

if($msg==''){

if($type == 'text'){

exit('缺少致命参数');

}else{

exit(need::json(array('code'=>'1','text'=>'缺少致命参数')));

}

}


$date = need::teacher_curl('http://nlp.xiaoi.com/robot/webrobot?&callback=__webrobot_processMsg&data=%7B%22sessionId%22%3A%22fdd8288edf874891800027eb47d972fd%22%2C%22robotId%22%3A%22webbot%22%2C%22userId%22%3A%22a17406e718694d399e4f4c5ef89945ae%22%2C%22body%22%3A%7B%22content%22%3A%22'.urlencode($msg).'%22%7D%2C%22type%22%3A%22txt%22%7D&ts='.need::time_sss(),[
'refer'=>'http://nlp.xiaoi.com/robot/webrobot?&callback=&data=%7B%22sessionId%22%3A%22fdd8288edf874891800027eb47d972fd%22%2C%22robotId%22%3A%22webbot%22%2C%22userId%22%3A%22a17406e718694d399e4f4c5ef89945ae%22%2C%22body%22%3A%7B%22content%22%3A%22%E5%93%88%E5%93%88%E5%93%88%22%7D%2C%22type%22%3A%22txt%22%7D&ts=']);

//exit($date);


preg_match_all('/","emoticons":(.*?)emoticons/',$date,$text);

preg_match_all('/"content":"(.*?)","/',$text[1][0],$data);

$text = str_replace('\r\n','',$data[1][0]);

$text = str_replace('\\t','',$text);

$text = preg_replace('/u003[a-z]|link|\[|\]|"|url/u','',$text);

$text = str_replace('\\','',$text);



if($type == 'text'){

exit($text);

}else{

exit(need::json(array('code'=>'1','text'=>$text)));

}

