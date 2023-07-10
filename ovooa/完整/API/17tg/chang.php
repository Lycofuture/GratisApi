<?php
/* 切换 */
header("Content-type: text/html; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(76); // 调用统计函数
/* End */
require ('../../curl.php');//引入curl文件
require ('../../need.php');//引入bkn文件
$qq=@$_REQUEST['qq'];
$skey=@$_REQUEST['skey'];
$group=@$_REQUEST['group'];
$gtk=need::GTK($skey);
$msg=@$_REQUEST['msg'];
$b=@$_REQUEST['b'];
$type = @$_REQUEST['type'];
$ts = @$_REQUEST['ts']?:10;
$p = @$_REQUEST['p']?:1;
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
$url="https://web.qun.qq.com/cgi-bin/media/get_music_list?t=0.3637193874264979&g_tk=".$gtk."&gcode=".$group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$qq."&format=json&inCharset=utf-8&outCharset=utf-8";
$post="0";
$cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
$data=@get_result($url,$post,$cookie);
$Array = json_decode($data,true);
$data = $Array['result']['song_list'];
if (empty($data)){
    Switch($type){
        case 'text':
        need::send('未知错误！','text');
        break;
        default:
        need::send(array("code"=>"-4","text"=>"未知错误！"));
        break;
    }
}
foreach($data as $v){
    $song = $v['song'];
    $name = $song['bytes_name'];//歌名
    $singer = '';
    foreach($song['rpt_bytes_singer'] as $v){
        $singer .= $v.'，';
    }
    $singer = trim($singer,'，');
    $time = $song['uint32_duration'];//播放时间
    $int = intval($time / 60);
    $second = ($time - ($int *60));
    $Time = sprintf('%02d', $int) .':'. sprintf('%02d', $second);//时间
    $cover = $song['bytes_cover'];//图
    $id = $song['str_song_id'];//id
    $array[] = array('song'=>$name,'singer'=>$singer,'cover'=>$cover,'time'=>$Time,'mid'=>$id,'uint'=>$time);
}
$count = count($array);
if($count == "0"){
    Switch($type){
        case 'text':
        need::send('播放列表空空如也','text');
        break;
        default:
        need::send(array("code"=>"1","text"=>"播放列表空空如也哦！"));
        break;
    }
}
if($count == $ts || $ts == "1"){
    $pa = intval($count/$ts);
}else{
    $pa = intval($count/$ts+1);
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
if($b==null){
    Switch($type){
        case 'text':
        echo '——歌曲列表——';
        echo "\n";
        for ($x = $pb ; $x < $count && $x <= $pc ; $x++){
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $Time = $array[$x]['time'];//时间
            echo ($x+1);
            echo '.';
            echo $aa;
            echo '-';
            echo $bb;
            echo '-'.$Time;
            echo "\n";
        }
        echo '第'.$p.'/'.$pa.'页';
        echo "\n";
        need::send( '提示：共有'.$count.'首歌','text');
        break;
        default:
        for ($x = $pb ; $x < $result && $x <= $pc ; $x++){
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $array[] = $aa.'-'.$bb;
        }
        need::send(array("code"=>"1","text"=>'获取成功','data'=>$array,'array'=>$array,'count'=>$count));
        break;
    }
}else
if($b>$count||$b<1){
    Switch($type){
        case 'text':
        echo '——歌曲列表——';
        echo "\n";
        for ($x = $pb ; $x < $count && $x <= $pc ; $x++){
      //  for ($x=0; $x < $result && $x < $ts; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $Time = $array[$x]['time'];//时间
            echo ($x+1);
            echo '.';
            echo $aa;
            echo '-';
            echo $bb;
            echo '-'.$Time;
            echo "\n";
        }
        echo '第'.$p.'/'.$pa.'页';
        echo "\n";
        need::send( '提示：共有'.$count.'首歌','text');
        break;
        default:
        for ($x = $pb ; $x < $result && $x <= $pc ; $x++){
      //  for ($x=0; $x < $result ; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $array[] = $aa.'-'.$bb;
        }
        need::send(array("code"=>"1","text"=>'获取成功','data'=>$array,'array'=>$array,'count'=>$count));
        break;
    }
}else{
    $b=($b-1);
    $song = $array[$b]['song'];
    $singer = $array[$b]['singer'];
    $cover = $array[$b]['cover'];
    $time = $array[$b]['time'];
    $songid = $array[$b]['mid'];
    $url="https://web.qun.qq.com/cgi-bin/media/play_next_song?t=0.8287074011709832&g_tk=".$gtk."&song_id=".$songid."&gcode=".$group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$qq."&format=json&inCharset=utf-8&outCharset=utf-8";
    $post='0';
    $cookie='uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$skey;
    $data = json_decode(get_result($url,$post,$cookie),true);
    $retcode=$data['retcode'];
    if($retcode=="0"){
        Switch($type){
            case 'text':
            need::send("已切换歌曲[".$song."]",'text');
            break;
            default:
            need::send(array("code"=>"1","text"=>"已切换歌曲[".$song."]",'data'=>Array('cover'=>$cover,'music'=>$song,'singer'=>$singer,'time'=>$time)));
            break;
        }
    }else{
        Switch($type){
            case 'text':
            need::send('切换失败，请重试','text');
            break;
            default:
            need::send(array("code"=>"-6","text"=>"切换失败，请重试!"));
        }
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