<?php
/* 搜索 添加 */
header("Content-type: Application/json;");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(76); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../curl.php');//引入curl文件
require ('../../need.php');//引入bkn文件
$qq=@$_REQUEST['qq'];
$skey=@$_REQUEST['skey'];
$group=@$_REQUEST['group'];
$gtk=need::GTK($skey);
$msg=@$_REQUEST['msg'];
$b=@$_REQUEST['b'];
$p = @$_REQUEST["p"]?:'1';
$ts = @$_REQUEST["ts"]?:'10';
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
$url="https://web.qun.qq.com/cgi-bin/media/search_music?t=0.6528915256057382&g_tk=".$gtk."&keyword=".urlencode($msg)."&page=".$p."&limit=".$ts."&gcode=".$group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$qq."&format=json&inCharset=utf-8&outCharset=utf-8";
$post="0";
$cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
$data=get_result($url,$post,$cookie);
//echo $data;exit;
$Array = json_decode(str_replace(array('�'),'',$data),true);
//print_r($Array);exit;
$song = $Array['result']['song_list'];
$count = count($song);
$array = [];
foreach($song as $v){
    $id = $v['songid'];//id
    $name = $v['name'];//歌名;
    $singer = '';
    foreach($v['singer_list'] as $va){
        $singer .= $va['name'].'，';
    }
    $singer = trim($singer,'，');//歌手
    $cover = $v['pic'];//封面图
    $time = $v['duration'];//时间
    $int = intval($time / 60);
    $second = ($time - ($int * 60));
    $Time = sprintf('%02d', $int) . ':' . sprintf('%02d', $second);
    $array[] = array('song'=>$name,'singer'=>$singer,'cover'=>$cover,'mid'=>$id,'time'=>$Time,'uint'=>$time);
}
//print_r($array);exit;
if(empty($count)){
    Switch($type){
        case 'text':
        need::send('未搜索到有关于'.$msg.'的歌曲','text');
        break;
        default:
        need::send(array('code'=>-10,'text'=>'未搜索到有关于'.$msg.'的歌曲'));
        break;
    }
}
if($b==null){
$retcode=$Array['retcode'];
if($retcode=="0"){
    Switch($type){
        case 'text':
        echo '——添加歌曲——';
        echo "\n";
        for ($x=0; $x < $count && $x<$ts; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            echo ($x+1)."：".$aa."-".$bb."\n";
        }
        
        need::send('提示：共搜索到'.$count.'首歌曲','text');
        break;
        default:
        $echo = [];
        for ($x=0; $x < $count && $x<$ts; $x++) {
            $aa = $array[$x]['song'];//歌名
            $bb = $array[$x]['singer'];//歌手
            $echo[] = $aa.'-'.$bb;
        }
        /*print_r($echo);
        echo json_encode($echo,320);*/
        need::send(array("code"=>1,"text"=>'获取成功','data'=>$echo,'array'=>$array,'count'=>$count),'json');//"提示：添加+以上序号即可"));
        break;
    }
}else{
    Switch($type){
        case 'text':
        need::send('搜索失败，请重试！','text');
        break;
        default:
        need::send(array("code"=>"-4","text"=>"搜索失败，请重试！"));
        break;
    }
}
}else
if($b>$ts||$b<1){
    Switch($type){
        case 'text';
        echo '——添加歌曲——';
        echo "\n";
        for ($x=0; $x < $count && $x<$ts; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            echo ($x+1)."：".$aa."-".$bb."\n";
        }
        
        need::send('提示：共搜索到'.$count.'首歌曲，请按以上序列号选择！','text');
        break;
        default:
        for ($x=0; $x < $count && $x<$ts; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $echo[] = $aa.'-'.$bb;
        }
        need::send(array("code"=>"-5","text"=>'请按序列号选择！','data'=>$echo,'array'=>$array,'count'=>$count));//"提示：添加+以上序号即可"));
        break;
    }
}else{
    $b=@$_REQUEST['b'];
    $b=($b-1);
    $songid=$array[$b]['mid'];//id
    if(!$songid){
        Switch($type){
            case 'text':
            need::send('添加失败！无法获取歌曲id！疑似“付费”请换首歌重试！','text');
            break;
            default:
            need::send(array("code"=>"-8","text"=>"添加失败！无法获取歌曲id！疑似“付费”请换首歌重试！"));
            break;
        }
    }
    $name=$array[$b]['song'];//歌名
    $list=$array[$b]['singer'];//歌手
    $cover=$array[$b]['cover'];//图片
    $duration=$array[$b]['uint'];//时间
    $time=$array[$b]['time'];//时间
    $url="https://web.qun.qq.com/cgi-bin/media/oper_music?t=0.21004511223164646&g_tk=".$gtk;
    $post='oper_type=1&song_list=[{"song_id":"'.$songid.'","name":"'.$name.'","sub_title":"","singer_list":["'.$list.'"],"cover":"'.$cover.'","duration":'.$duration.',"has_added":0}]&gcode='.$group.'&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin='.$qq.'&format=json&inCharset=utf-8&outCharset=utf-8';
    $cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
    $data=json_decode(get_result($url,$post,$cookie),true);;
    if(!$data){
        Switch($type){
            case 'text':
            need::send('未知错误！请换首歌重试！','text');
            break;
            default:
            need::send(array("code"=>"-9","text"=>"未知错误！请换首歌重试！"));
        }
    }
    $retcode=$data['retcode'];;
    if($retcode=="0"){
        Switch($type){
            case 'text':
            need::send("±img:{$cover}±已将歌曲[".$name."]加进歌单！",'text');
            break;
            default:
            need::send(array("code"=>"1","text"=>"已将歌曲[".$name."]加进歌单！",'data'=>array("cover"=>$cover,'song'=>$name,'singer'=>$list,'time'=>$time)));
            break;
        }
    }else
    if($retcode=="100001"){
        $b=@$_REQUEST['b'];
        $b=($b-1);
        $songid=$array[$b]['mid'];//id
        $name=$array[$b]['song'];//歌名
        $singer=$array[$b]['singer'];//歌手
        $cover=$array[$b]['cover'];//图片
        $duration=$array[$b]['uint'];//时间
        $post = 'oper_type=1&song_list=[{"song_id":"'.$songid.'","name":"'.$name.'","sub_title":"","singer_list":'.json_encode(explode('、',$singer),320).',"cover":"'.$cover.'","duration":'.$duration.',"spanName":"<span style=\"color: #00cafc\">'.$name.'</span>","spanTitle":"","spanSinger":"'.$singer.'","exactMatch":true}]&gcode='.$group.'&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin='.$qq.'&format=json&inCharset=utf-8&outCharset=utf-8';
        $url="https://web.qun.qq.com/cgi-bin/media/oper_music?t=0.21004511223164646&g_tk=".$gtk;
        $cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
        $data=json_decode(get_result($url,$post,$cookie),true);
        $retcode=$data['retcode'];
        if($retcode=="0"){
            Switch($type){
                case 'text':
                need::send("±img:{$cover}±已将歌曲[".$name."]加进歌单！",'text');
                break;
                default:
                need::send(array("code"=>"1","text"=>"已将歌曲[".$name."]加进歌单！","pic"=>$cover));
                break;
            }
        }else
        if($retcode=="100001"){
            Switch($type){
                case 'text':
                need::send('添加失败，请重试！','text');
                break;
                default:
                need::send(array("code"=>"-6","text"=>"添加失败，请重试!"));
                break;
            }
        }
    }else{
        Switch($type){
            case 'text':
            need::send('添加失败，请重试！','text');
            break;
            default:
            need::send(array("code"=>"-6","text"=>"添加失败，请重试!"));
            break;
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