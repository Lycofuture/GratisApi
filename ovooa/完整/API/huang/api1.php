<?php

header('content-type: text/text');

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(99); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$type = @$_REQUEST["type"];

$day = @$_REQUEST["time"];

if(!$day){

$day = date('Y-n-j');

}else{

$day = $day;

}

$data = need::teacher_curl('https://3g.d5168.com/huangli/'.$day,[
    'refer'=>'https://3g.d5168.com/huangli',
    'Header'=>[
        'Host: 3g.d5168.com',
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
        'Referer: https://3g.d5168.com/huangli/2022-4-12',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
    ],
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36'
]);

if(!$data){

if($type == 'text'){

exit('获取出错请重新尝试');

}else{

exit(need::json(array('code'=>-1,'text'=>'获取出错请重新尝试')));

}

}

preg_match_all('/<em>农历 (.*?)<\/em>/',$data,$nong);

$nong = preg_replace('/[a-zA-Z]/','',$nong[1][0]);

$nong = preg_replace('/[[:punct:]]/i','',$nong);//获取农历

$nong = str_replace('小','(小) ',$nong);

$nong = str_replace('大','(大) ',$nong);

$yang = date('Y').'年'.date('m').'月'.date('d').'日 '.date_day(date('w'));

preg_match_all('/<span>宜([\s\S]*?)<div class="mod_form">/s',$data,$date);//匹配内容

preg_match_all('/<li>(.*?)<\/li>/',@$date[1][0],$li);

preg_match_all('/<dt>(.*?)<\/dt>/',@$date[1][0],$dt);

preg_match_all('/<dd>(.*?)<\/dd>/',@$date[1][0],$dd);

preg_match_all('/<div class="index_content_foot">(.*?)<li><a href="\/huangli\/"/s',$data,$text);

//exit($text[1][0]);

//$text = preg_replace('/<|>|\/|"|=|#|;|-|_|[0-9]+%|[0-9]+\.|:\(.*\)|[0-9]+:|	|:.?[0-9]+|:\(.*?\) :/s','',$text[1][0]);

$text = str_replace(':','：',@$text[1][0]);

$text = str_replace('</p>','\r',$text);

$text = preg_replace('/[a-zA-Z]/','',$text);

$text = preg_replace('/[[:punct:]]/i','',$text);

$text = str_replace('二十八','28',$text);

$text = str_replace('　','',$text);

$text = str_replace('黄色','Yellow',$text);

$text = str_replace('赌','贝者',$text);

$text = str_replace('毒','du',$text);

$text = str_replace('\\r\\n','\\r',$text);

if($type == 'text'){

exit(trim($text));

}else{

need::send(array('code'=>1,'text'=>trim($text)),'json');

}

//$date = $date[1][0];

/*if($tu=='1'){

}else{

if($type == 'text'){

echo '阳历：'.$yang."\r";
echo '农历：'.$nong."\r";
echo '宜：'.$li[1][0]."\r";
echo "忌：".$li[1][1]."\r";
echo $dt[1][0].'：'.$dd[1][0]."\r";
echo $dt[1][1].'：'.$dd[1][1];

exit;

}else{

exit(need::json(array('code'=>'1','text'=>"阳历：".$yang."\r农历：".$nong."\r宜：".$li[1][0]."\r忌：".$li[1][1]."\r".$dt[1][0].'：'.$dd[1][0]."\r".$dt[1][1].'：'.$dd[1][1])));

}

}

//echo $nong."\r".$yang;

*/
function date_day($num){

        $array = array('周日','周一','周二','周三','周四','周五','周六');
        
                return $array[$num];
                
}




