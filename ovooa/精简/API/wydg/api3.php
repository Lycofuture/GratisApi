<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(9); // 调用统计函数
require '../../need.php';
/* End */


header("content-type:application/json");
$msg = $_REQUEST["msg"];
$xg = $_REQUEST["n"];
$type = $_REQUEST["type"];
$p = $_REQUEST["p"]?:"1";//翻页，默认第一页
$s = $_REQUEST["sc"]?:"10";//输出条数，默认10条
$tail = $_REQUEST['tail']?:'网易云音乐';
if(empty($msg)){
    Switch($type){
        case 'text':
        die("歌名呢，被你次掉啦？");
        break;
        default:
        need::send(array('code'=>'-1','text'=>'歌名呢，被你次掉啦？'));
        break;
    }
}
$aa=encode_netease_data([
    'method'  => 'POST',
    'url'  => 'http://music.163.com/api/cloudsearch/pc',
    'params'  => [
        's'=> urlencode($msg),
        'type'=> 1,
        'offset' => (($p -1)*$s),//(($p * 10) - 10),
        'limit'  => $s
    ]
]);
$url="http://music.163.com/api/linux/forward";
$post="eparams=".$aa["eparams"];
$str=curl_post_https($url,$post,null);
$json = json_decode($str, true);
$xa=$json["code"];//判断有无歌曲
$bb=$json["result"]["songs"];//解析数组
//print_r($bb);exit;
$cc=$bb["album"]["picUrl"];//图
$d=$json["result"];
$e=$d["songCount"];
if(empty($e)){
    Switch($type){
        case 'text':
        need::send("找不到关于[$msg]的歌",'text');
        break;
        default:
        need::send(array('code'=>'-2','text'=>'未搜索到有关于'.$msg.'的歌'));
        break;
    }
}
if($xg== null || $xg > count($bb)){
    Switch($type){
        case 'text':
        for( $i = 0 ; $i < count($bb) && $i < $s ; $i ++ ){
            unset($us);
            $aa = $json["result"]["songs"][$i];
            $u = $aa["ar"];
            $a = $aa["name"];//名
//$u=$u["name"];//手
            $m=$aa["al"]["picUrl"];//图
//print_r($e);
            foreach($u as $o) {
                $us .= $o["name"].',';//手
            }
            $us = trim($us, ',');
            echo ($i+1).".".$a."-".$us."\n";
        }
        need::send("提示：当前为第".$p."页",'text');
        break;
        default:
        for( $i = 0 ; $i < count($bb) && $i < $s ; $i ++ ){
            unset($us);
            $aa = $json["result"]["songs"][$i];
            $u = $aa["ar"];
            $a = $aa["name"];//名
//$u=$u["name"];//手
            $m = $aa["al"]["picUrl"];//图
//print_r($e);
            foreach($u as $o) {
                $us .= $o["name"].',';//手
            }
            $us = trim($us, ',');
            $array[] = $a."-".$us;
        }
        need::send(array('code'=>1,'text'=>'歌曲列表获取成功！','data'=>$array));
        break;
    }
}else{
    $i = ($xg-1);
    $aa = $json["result"]["songs"][$i];
    //$aa = $bb[$i];
    //print_r($aa);exit;
    $a = str_replace('"','',$aa["name"]);//名
    $m = $aa["al"]["picUrl"];//图
    $d = $aa["id"];//id
    $u = $aa["ar"];
    $bo = $aa["al"]["name"];
    foreach($u as $o) {
        $us .= $o["name"].',';//手
    }
    $us = trim($us, ',');
    if($d==null){
        Switch($type){
            case 'text':
            need::send("对不起哦，列表中没有序号为『".$xg."』的歌曲",'text');
            break;
            default:
            need::send(array('code'=>'-3','text'=>'对不起哦，列表中没有序号为『'.$xg.'』的歌曲'));
            break;
        }
    }else{
        $gm = str_replace(array('"',"'"),'',$a);
        $gs = $us;
        $ga = $a;
        $img = $m;
        $gurl = "http://music.163.com/song/media/outer/url?id=$d";
        Switch($type){
            case 'text':
            echo "±img=".$img;
            echo "±歌曲：".$ga;
            echo "\r歌手：".$gs;
            need::send("\r播放链接：".$gurl,'text');
            break;
            case 'xml':
            $gm=str_replace('&','&amp;',$gm);
            $gs=str_replace('&','&amp;',$gs);
            $ga=str_replace('&','&amp;',$ga);
            $img=str_replace('&','&amp;',$img);
            echo"card:3<?xml version='1.0' encoding='UTF-8' standalone='yes' ?>";
            echo'<msg serviceID="2" templateID="1" action="web" brief="[分享]'.str_replace('&','&amp;',$gm).'" sourceMsgId="0" url="https://music.163.com/m/song?id='.$d.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$img.'" src="'.$gurl.'" /><title>'.str_replace('&','&amp;',$gm).'</title><summary>'.str_replace('&','&amp;',$gs).'</summary></item><source name="网易云音乐" icon="http://p3.music.126.net/F4LudfJWGfPRHe8tAArJ1A==/109951163421193595.png" url="" action="app" a_actionData="" i_actionData="" appid="1101079856\" /></msg>';
            exit();
            break;
            case 'json':
            $img=str_replace('/','\/',$img);
            $gurl=str_replace('/','\/',$gurl);
            echo 'json:{"app":"com.tencent.structmsg","desc":"com.tencent.structmsg","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$gm.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100495085,"ctime":1638937670,"desc":"'.$gs.'","jumpUrl":"https:\/\/y.music.163.com\/m\/song?id='.$d.'&uct=GEUDoDVt5aKu9Y%2BqYfWl8Q%3D%3D&app_version=8.6.31","musicUrl":"'.$gurl.'","preview":"'.$img.'","sourceMsgId":"0","source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$gm.'","uin":2354452553}},"config":{"forward":1,"autosize":1,"type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100495085,\"uin\":2354452553}"}';
            exit();
            break;
            case 'X6':
            echo 'json:{"app":"com.tencent.structmsg","desc":"网易云音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$gm.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gs.'","jumpUrl":"https://music.163.com/#/song?id='.$d.'","musicUrl":"'.$gurl.'","preview":"'.$img.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$gm.'"}},"config":{"autosize":true,"forward":true,"type":"normal"},"text":"","sourceAd":"","extra":""}';
            break;
            default:
            $array = ['code'=>1,'text'=>'获取成功','data'=>['Music'=>$gm,'Cover'=>$img,'Singer'=>$gs,'Url'=>$gurl,'Music_Url'=>'https://music.163.com/#/song?id='.$d]];
//未填输出提示
            need::send($array);
            break;
        }
    }
}

function curl_post_https($url,$data,$m){
    $header=array(
        'user-Agent: Mozilla/5.0 (Linux; Android 6.0.1; OPPO A57 Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
        'Accept: */*',
        'Referer: http://music.163.com/',
        'X-Requested-With: XMLHttpRequest',
        'Content-Type: application/x-www-form-urlencoded'
    );
//设置请求头
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
    if($data==null){
    }else{
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_HEADER, 0);//设置返回头
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    $result = curl_exec($curl); 
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);
    }
    curl_close($curl); 
    return $result; 
}
// 加密网易云音乐 api 参数 APP接口
function encode_netease_data($data){
    $_key = '7246674226682325323F5E6544673A51';
    $data = json_encode($data);
    if (function_exists('openssl_encrypt')) {
        $data = openssl_encrypt($data, 'aes-128-ecb', pack('H*', $_key));
    }else{
        $_pad = 16 - (strlen($data) % 16);
        $data = base64_encode(mcrypt_encrypt(
            MCRYPT_RIJNDAEL_128,
            hex2bin($_key),
            $data.str_repeat(chr($_pad), $_pad),
            MCRYPT_MODE_ECB
            )
        );
    }
    $data = strtoupper(bin2hex(base64_decode($data)));
    return ['eparams' => $data];
}