<?php
header("Content-type: text/html; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(76); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$qq=@$_REQUEST['qq'];
$skey=@$_REQUEST['skey'];
$group=@$_REQUEST['group'];
$gtk=need::GTK($skey);
$p=@$_REQUEST['p']?:"1";
$ts=@$_REQUEST['ts']?:"10";
$type = @$_REQUEST['type'];
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
if(!is_numeric($p) || !is_numeric($ts)){
    $p = 1;
    $ts = 10;
//exit(need::json(array("code"=>"-9","text"=>"请填写数字页数或者数字条数")));
}
$url="https://web.qun.qq.com/qunmusic/listener?uin=".$group."&uinType=1&_wwv=128&_wv=2";
$cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
$data=get_result($url,$cookie);
//echo $data;
$result=@preg_match_all('/__INITIAL_STATE__=([\s\S]*?)<\/script>/',$data,$nute);
$array = json_decode($nute[1][0],true);
//print_r($array);
$Count = count($array['memberList']);
//echo $Count;
if($Count == $ts || $ts == "1"){
    $pa = intval($Count/$ts);
}else{
    $pa = intval($Count/$ts+1);
}//计算总页数
if($p > $pa || $p < 1){
    Switch($type){
        case 'text':
        need::send('页数不存在！','text');
        break;
        default:
        need::send(array("code"=>"-8","text"=>"页数不存在！"));
        break;
    }
}
$pb = intval($p-1);
$pb = intval($pb*$ts);
$pc = intval($p*$ts-1);
if(empty($Count)){
    Switch($type){
        case 'text':
        need::send('当前并没有开启一起听歌！','text');
        break;
        default:
        need::send(array("code"=>"-4","text"=>"当前并没有开启听歌！"));
        break;
    }
}else{
    Switch($type){
        case 'text':
        echo '——听歌人数——';
        echo "\n";
        for ($x = $pb ; $x < $Count && $x <= $pc ; $x++) {
            echo ($x + 1);
            echo '.';
            echo $array['memberList'][$x]['nick'];
            echo '(';
            echo $array['memberList'][$x]['uin'];
            echo ')';
            echo "\n";
        }
        echo '第'.$p.'/'.$pa.'页';
        echo "\n";
        need::send('提示：共有'.$Count.'人在听歌','text');
        break;
        default:
        for ($x = $pb ; $x < $Count && $x <= $pc ; $x++) {
            $array_r[] = $array['memberList'][$x]['nick'].'('.$array['memberList'][$x]['uin'].')';
        }
        need::send(array("code"=>"1","text"=>"获取成功",'data'=>$array_r,'Count'=>$Count));
        break;
    }
}

function get_result($url,$cookie)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
$header = array(
'User-Agent: Mozilla/5.0 (Linux; Android 6.0.1; vivo Y55L Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 MQQBrowser/6.2 TBS/045132 Mobile Safari/537.36 V1_AND_SQ_8.3.0_1362_YYB_D QQ/8.3.0.4480 NetType/WIFI WebP/0.3.0 Pixel/720 StatusBarHeight/49 SimpleUISwitch/0 QQTheme/1000',
);
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
curl_setopt($ch, CURLOPT_HEADER, 0); 
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