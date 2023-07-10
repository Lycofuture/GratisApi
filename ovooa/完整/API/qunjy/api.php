<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(78); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件
/* End */
$qq=@$_REQUEST['qq'];
$skey=@$_REQUEST['skey'];
$group=@$_REQUEST['group'];
$type = @$_REQUEST['type'];
$num = @$_REQUEST['num']?:5;
$tail = @$_REQUEST['tail']?:'————';
$bkn=need::GTK($skey);
if(!need::is_num($qq)){
    if($type == 'text'){
        die('不好意思，账号不得为空呢');
    }else{
        die(need::json(array('code'=>-4,'text'=>'不好意思，账号不得为空呢')));
    }
}
if(!need::is_num($group)){
    if($type == 'text'){
        die('不好意思，群号不得为空呢');
    }else{
        die(need::json(array('code'=>-5,'text'=>'不好意思，群号不得为空呢')));
    }
}
if(empty($skey)){
    if($type == 'text'){
        die('不好意思，Skey不得为空呢');
    }else{
        die(need::json(array('code'=>-6,'text'=>'不好意思，Skey不得为空呢')));
    }
}
if(!is_numeric($num) or $num < 1){
    $num = 5;
}
$url=need::teacher_curl("https://qinfo.clt.qq.com/cgi-bin/qun_info/get_group_shutup",[
    'cookie'=>'uin=o'.$qq.'; skey='.$skey.'',
    'post'=>[
        'gc'=>$group,
        'bkn'=>$bkn
    ]
]);
$data=str_replace(array('&nbsp;','&quot;'),' ',$url);
//$data=str_replace('&quot;',' ',$data);
$json=json_decode($data,true);
//print_r($json);
$ec=$json["ec"];
if($ec=='7'){
    if($type == 'text'){
        need::send('好像不是管理员呢…','text');
    }else{
        need::send(array("code"=>"-1","text"=>"好像不是群管理员呢…"));
    }
}
else
if($ec=='4'){
    if($type == 'text'){
        die('Skey好像失效了耶');
    }else{
        need::send(array("code"=>"-2","text"=>"Skey好像失效了耶"));
    }
}else
if($ec == '0'){
    $data = $json['shutup_list'];
    if(empty($data)){
        if($type == 'text'){
            die('大家都很乖没有被禁言呢');
        }else{
            need::send(array("code"=>"1","text"=>"大家都很乖没有被禁言呢"));
        }
    }else{
        for($i = 0 ; $i < $num && $i < count($data); $i ++){
            $Time = ceil($data[$i]['t'] / 60);//时间
            
            $Msg .= ($i+1).'.昵称：'.$data[$i]['nick']."\n账号：".$data[$i]['uin']."\n禁言时长：".$Time."分钟\n";
            $Array[] = ['nick'=>$data[$i]['nick'], 'uin'=>$data[$i]['uin'], 'Time'=>$Time];
        }
        Switch($type){
            case 'text':
            need::send(trim($Msg), 'text');
            break;
            default:
            need::send(Array('code'=>1, 'text'=>'获取成功', 'data'=>$Array), 'json');
            break;
        }
        /*
        if($type == 'text'){
            for ($x=0; $x < ($result-1) && $x < $num ; $x++) {
                $t=ceil($nute[1][$x]/60);
                $nick=need::decodeUnicode($nute[2][$x]);
                $uin=$nute[3][$x];
                echo "昵称:".$nick."\n账号：".$uin."\n禁言时长：".$t."分钟\n{$tail}\n";
            }
//    $rep = ($result-1);
            $Nick=need::decodeUnicode(end($nute[2]));
            $uina = end($nute[3]);
            $T = ceil(end($nute[1]) / 60);
            need::send("昵称：".$Nick."\n账号：".$uina."\n禁言时长：".$T."分钟",'text');
        }else{
            for ($x=0; $x < $result && $x < $num ; $x++) {
                $t=ceil($nute[1][$x]/60);
                $nick=need::decodeUnicode($nute[2][$x]);
                $uin=$nute[3][$x];
                $echo [] = array("nick"=>$nick,"uin"=>$uin,"Time"=>$t);
            }
            need::send(array("code"=>"1","text"=>'获取成功','data'=>$echo));
        }*/
    }
}else{
    if($type == 'text'){
        die('查询群禁言失败');
    }else{
        need::send(array("code"=>"-3","text"=>"查询群禁言失败"));
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
function replace_unicode_escape_sequence($match) {
return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}

