<?php
header('content-type:Application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(1); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
//require ('../../Core/Database/connect.php');
/* End */
$url = @$_GET["url"];
//echo need::json(parse_url('lkaa.top/API/yiyan/api.php'));
//exit();
if(!$url){
    exit(need::json(array('code'=>-1,'text'=>'请输入网址')));
}
/*
if(need::http($url)!='200'){
    exit(need::json(array('code'=>-2,'text'=>'请输入正确的网址')));
}*/
if(stristr($url,'http')){
    $url = str_replace(Array('http','https','://','/'),'',$url);
}
$data = need::teacher_curl('https://www.baidu.com/s?wd=site%3A'.urlencode($url).'&rsv_spt=1&rsv_iqid=0xa26d87bc001eb19e&issp=1&f=8&rsv_bp=1&rsv_idx=2&ie=utf-8&tn=baiduhome_pg&rsv_enter=1&rsv_dl=tb&rsv_sug3=6&rsv_sug1=3&rsv_sug7=101&rsv_sug2=0&rsv_btype=i&inputT=2648&rsv_sug4=4058',[
    'ua'=>'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.96 Safari/537.36'
]);
preg_match('/相关结果数约(.*?)个/',$data,$num);
$Array['Baidu'] = $num[1]?:0;
$data = need::teacher_curl('https://www.sogou.com/web?query=site%3A'.urlencode($url).'&_asf=www.sogou.com&_ast=&w=01019900&p=40040100&ie=utf8&from=index-nologin&s_from=index&sut=2464&sst0=1637336886098&lkt=0%2C0%2C0&sugsuv=0064057274A20391604718C85BB3A759&sugtime=1637336886099',[
    'ua'=>'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.96 Safari/537.36'
]);

if(preg_match('/http/',$url)){
    $url = $url;
}else{
    $url_http = need::teacher_curl($url,[
        'loadurl'=>1
    ]);
    if(!$url_http){
        $url = 'http://'.$url;
    }else{
        $url = $url_http;
    }
}
preg_match('/已为您找到约(.*?)条相关结果/',$data,$num);
$Array['Sougou'] = $num[1]?:0;

//print_r($num);exit;
echo need::json(array('code'=>1,'url'=>$url,'data'=>$Array));
exit;
