<?php
header("Content-type: text/html; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(88); // 调用统计函数
addAccess();//调用统计函数
/* End */
require ('../../curl.php');//引入curl文件
require ('../../need.php');//引入bkn文件
$type = @$_REQUEST["type"];
$rand = mt_rand(1,4693);
if(!is_file('./image/'.$rand.'.jpg')){
    $String = need::teacher_curl('https://cdn.jsdelivr.net/gh/ssrss/img/'.$rand.'.jpg', [
        'Header'=>[
            'Host: cdn.jsdelivr.net',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'dnt: 1',
            'X-Requested-With: mark.via',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-User: ?1',
            'Sec-Fetch-Dest: document',
            'Referer: https://cdn.jsdelivr.net/gh/ssrss/img/4693.jpg',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
        ],
        'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
        'refer'=>'https://cdn.jsdelivr.net/gh/ssrss/img/'.rand(1,4639).'.jpg'
    ]);
    file_put_Contents('./image/'.$rand.'.jpg',$String);
}

if($type == "text"){
    need::send('https://cdn.jsdelivr.net/gh/ssrss/img/'.$rand.'.jpg','text');
}else
if($type == 'image'){
    $class = New need;
    $class->send('http://ovooa.com/API/dmt/image/'.rand(1,3000).'.jpg', 'image');
}else
if($type == 'location'){
    need::send('https://cdn.jsdelivr.net/gh/ssrss/img/'.$rand.'.jpg', 'location');
}else{
    need::send(array("code"=>1,"text"=>"https://cdn.jsdelivr.net/gh/ssrss/img/".$rand.".jpg"));
}

