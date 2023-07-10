<?php
/**
 * @author 教书先生
 * @link https://blog.oioweb.cn
 * @date 2020年11月12日18:00:30
 * @msg PHPCurl封装的方法
 */

 $ip = Rand_IP();

function teacher_curl($url, $paras = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if (@$paras['Header']) {
        $Header = $paras['Header'];
    } else {
        $Header[] = "Accept:*/*";
        $Header[] = "Accept-Encoding:gzip,deflate,sdch";
        $Header[] = "Accept-Language:zh-CN,zh;q=0.8";
        $Header[] = "Connection:close";
        $Header[] = 'X-FORWARDED-FOR:'.Rand_IP();
        $Header[] = 'CLIENT-IP:'.Rand_IP();
        $Header[] = 'REMOTE_ADDR:'.Rand_IP();
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $Header);
    if (@$paras['ctime']) { // 连接超时
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $paras['ctime']);
    } else {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    }
    if (@$paras['rtime']) { // 读取超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $paras['rtime']);
    }
    if (@$paras['post']) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paras['post']);
    }
    if (@$paras['header']) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    if (@$paras['cookie']) {
        curl_setopt($ch, CURLOPT_COOKIE, $paras['cookie']);
    }
    if (@$paras['refer']) {
        if ($paras['refer'] == 1) {
            curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $paras['refer']);
        }
    }
    if (@$paras['ua']) {
        curl_setopt($ch, CURLOPT_USERAGENT, $paras['ua']);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
    }
    if (@$paras['nobody']) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (@$paras['GetCookie']) {
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        preg_match_all("/Set-Cookie: (.*?);/m", $result, $matches);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($result, 0, $headerSize); //状态码
        $body = substr($result, $headerSize);
        $ret = [
            "Cookie" => $matches, "body" => $body, "header" => $header, 'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
        ];
        curl_close($ch);
        return $ret;
    }
    $ret = curl_exec($ch);
    if (@$paras['loadurl']) {
        $Headers = curl_getinfo($ch);
        $ret = $Headers['redirect_url'];
    }
    curl_close($ch);
    return $ret;
}



function Rand_IP(){
	#第一种方法，直接生成
    $ip2id= round(rand(600000, 2550000) / 10000);
    $ip3id= round(rand(600000, 2550000) / 10000);
    $ip4id= round(rand(600000, 2550000) / 10000);
	#第二种方法，随机抽取
    $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
    $randarr= mt_rand(0,count($arr_1)-1);
    $ip1id = $arr_1[$randarr];
    return $ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
}
#获取重定向请求头
function getResponseHeader($url) {
    $ch  = curl_init($url);
    $httpheader = [];
    $httpheader[] = 'X-FORWARDED-FOR:'.Rand_IP();
    $httpheader[] = 'CLIENT-IP:'.Rand_IP();
    #请求头中添加cookie
    $httpheader[] = 'cookie:did=web_'.md5(time() . mt_rand(1,1000000)).'; didv='.time().'000;clientid=3; client_key=6589'.rand(1000, 9999);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$httpheader);
    #以下两句设置返回响应头不返回响应体
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    #返回数据不直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}
#获取响应体
function getResponseBody($url) {
    $ch = curl_init();
    #5秒超时
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000);
    #设置默认ua  这里经常测试，尽量用手机的ua,电脑的ua获取不到数据
    curl_setopt($ch, CURLOPT_USERAGENT,'User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; vivo X9 Plus Build/LMY48Z) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36');
    #把随机ip添加进请求头 
    $httpheader = [];
    $httpheader[] = 'X-FORWARDED-FOR:'.Rand_IP();
    $httpheader[] = 'CLIENT-IP:'.Rand_IP();
    #请求头中添加cookie
    $httpheader[] = 'cookie:did=web_'.md5(time() . mt_rand(1,1000000)).'; didv='.time().'000;';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    #返回数据不直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    #设置请求地址
    curl_setopt($ch, CURLOPT_URL, $url);
    #关闭ssl验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    #设置默认referer
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.moestack.com');
    #get方式请求
    curl_setopt($ch, CURLOPT_POST, false);
    $contents = curl_exec($ch);
    curl_close($ch);
    return $contents;
}


