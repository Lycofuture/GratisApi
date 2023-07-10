<?php
header("Content-type: text/html; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(76); // 调用统计函数
/* End */
addAccess();//调用统计函数
require ('../../curl.php');//引入curl文件
require ('../../need.php');//引入bkn文件
$qq=@$_REQUEST['qq'];
$skey=@$_REQUEST['skey'];
$group=@$_REQUEST['group'];
$pskey = @$_REQUEST["pskey"];
$gtk=need::GTK($skey);
if(!need::is_num($qq)){
    Switch($type){
        case 'text':
        need::send('账号错误！','text');
        break;
        default:
        need::send(Array("code"=>"-1","text"=>"QQ号不能为空！"));
        break;
    }
}
if(!need::is_num($group)){
    Switch($type){
        case 'text':
        need::send('群号错误！','text');
        break;
        default:
        need::send(array("code"=>"-2","text"=>"群号不能为空"));
        break;
    }
}
if(empty($skey)){
    Switch($type){
        case 'text':
        need::send('Skey不能为空！','text');
        break;
        default:
        need::send(array("code"=>"-3","text"=>"Skey不能为空！"));
        break;
    }
}
$url="https://web.qun.qq.com/cgi-bin/media/set_media_state?t=0.85959781718529&g_tk=".$gtk."&state=0&gcode=".$group."&qua=V1_AND_SQ_8.4.1_1442_YYB_D&uin=".$qq."&format=json&inCharset=utf-8&outCharset=utf-8";
$post="0";
$cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
$data=get_result($url,$post,$cookie);
@preg_match_all('/retcode":(.*?)}/',$data,$retcode);
$retcode=$retcode[1][0];
if($retcode=="0"){
    Switch($type){
        case 'text':
        need::send('已关闭一起听歌！','text');
        break;
        default:
        need::send(array("code"=>"1","text"=>"已关闭一起听歌!"));
        break;
    }
}else
if($retcode=="100061"){
    Switch($type){
        case 'text':
        need::send('未开启一起听歌','text');
        break;
        default:
        need::send(array("code"=>"-4","text"=>"已关闭，请勿重试!"));
        break;
    }
}else
if($retcode=="100051"){
    Switch($type){
        case 'text':
        need::send('关闭失败，权限不足！','text');
        break;
        default:
        need::send(array("code"=>"-5","text"=>"关闭失败，权限不足！"));
        break;
    }
}else
if($retcode=="100000"){
    Switch($type){
        case 'text':
        need::send('关闭失败，Skey已过期！','text');
        break;
        default:
        need::send(array("code"=>"-6","text"=>"关闭失败，SKEY已失效!"));
        break;
    }
}else{
    Switch($type){
        case 'text':
        need::send('关闭失败，未知错误！','text');
        break;
        default:
        need::send(array("code"=>"-7","text"=>"关闭失败，未知错误!"));
        break;
    }
}

function get_result($url,$post,$cookie)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
$header = array();
//curl_setopt($ch,CURLOPT_POST,true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); //设置等待时间
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($ch);
curl_close($ch);
return $content;
}
/*
function need::GTK($skey){
$len = strlen($skey);
$hash = 5381;
for ($i = 0; $i < $len; $i++) {
$hash += ($hash << 5 & 2147483647) + ord($skey[$i]) & 2147483647;
$hash &= 2147483647;
}
return $hash & 2147483647;
}*/
?>