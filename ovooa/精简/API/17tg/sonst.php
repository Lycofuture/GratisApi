<?php
/* 歌曲列表 删除歌曲 */
//header("Content-type: Application/json;");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(76); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../curl.php');//引进封装好的curl文件
require '../../need.php';//引用封装好的函数文件
/* End */
$qq=@$_REQUEST['qq'];
$skey=@$_REQUEST['skey'];
$group=@$_REQUEST['group'];
$gtk=need::GTK($skey);
$msg=@$_REQUEST['msg'];
$b=@$_REQUEST['b'];
$p = @$_REQUEST["p"]?:"1";
$ts = @$_REQUEST["ts"]?:"10";
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
}
$url="https://web.qun.qq.com/cgi-bin/media/get_music_list?t=0.3637193874264979&g_tk=".$gtk."&gcode=".$group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$qq."&format=json&inCharset=utf-8&outCharset=utf-8";
$post="0";
$cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
$data=get_result($url,$post,$cookie);
$Array = json_decode($data,true);
$data = $Array['result']['song_list'];
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
    unset($id,$Time,$cover,$second,$time,$singer,$name,$song);
}
//print_r($array);exit;
$count = count($array);
if ($count == "0"){
    Switch($type){
        case 'text':
        need::send('暂时还没有歌曲呢！快来添加一首吧~','text');
        break;
        default:
        need::send(array("code"=>"-4","text"=>"暂时还没有歌曲呢！快来添加一首吧~"));
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
//print_r($array);
    if(empty($data)){
        Switch($type){
            case 'text':
            need::send('歌曲列表空空如也','text');
            break;
            default:
            need::send(array('code'=>-4,'text'=>'歌曲列表空空如也~快来添加一手自己喜欢的吧~'));
            break;
        }
    }
    Switch($type){
        case 'text':
        echo '——歌曲列表——';
        echo "\n";
        for ($x = $pb ; $x < $count && $x <= $pc ; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $Time = $array[$x]['time'];//时间
            //$bb=str_replace('","','/',$bb);
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
        for ($x = $pb ; $x < $count && $x <= $pc ; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $Time = $array[$x]['time'];//时间
            $echo[] = $aa.'-'.$bb.'-'.$Time;
        }
        //need::send( $array);
        //need::send(array('code'=>1,'text'=>'获取成功','data'=>$echo,'page'=>$p),'json');
        $array = array_slice($array,$pb,$ts);
        //print_r($array);exit;
        need::send(array("code"=>1,"text"=>'获取成功','data'=>$echo,'array'=>$array,'page'=>$p,'max_page'=>$pa));
        break;
    }
}else
if($b>$count||$b<1){
    Switch($type){
        case 'text':
        echo '——歌曲列表——';
        echo "\n";
        for ($x = $pb ; $x < $count && $x <= $pc ; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $Time = $array[$x]['time'];//时间
            //$bb=str_replace('","','/',$bb);
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
        need::send( '提示：请按照以上序列号选择！','text');
        break;
        default:
        for ($x = $pb ; $x < $count && $x <= $pc ; $x++) {
            $aa=$array[$x]['song'];//歌名
            $bb=$array[$x]['singer'];//歌手
            $bb=str_replace('","','/',$bb);
            $echo[] = $aa.'-'.$bb;
        }
        $array = array_slice($array,$pb,$ts);
        need::send(array("code"=>"1","text"=>'获取成功','data'=>$echo,'array'=>$array,'page'=>$p,'max_page'=>$pa));
    }
}else{
    $b=@$_REQUEST['b'];
    $b=($b-1);
    $aa=$array[$b]['song'];//$nute[1][$b];//歌名
    $bb=$array[$b]['singer'];//$nute[2][$b];//歌手
    $cc=$array[$b]['mid'];//$nute[4][$b];//ID
    $dd=$array[$b]['uint'];//$nute[5][$b];
    $ee=$array[$b]['cover'];//$nute[6][$b];//封面
    $url="https://web.qun.qq.com/cgi-bin/media/oper_music?t=0.9837291536011012&g_tk=".$gtk;
    $post='oper_type=2&song_list=[{"song_id":"'.$cc.'","name":"'.$aa.'","sub_title":"","singer_list":["'.$bb.'"],"cover":"'.$ee.'","duration":'.$dd.',"current":0,"is_invalid":0,"can_delete":1}]&gcode='.$group.'&qua=V1_AND_SQ_8.4.1_1442_YYB_D&uin='.$qq.'&format=json&inCharset=utf-8&outCharset=utf-8';
    $cookie='uin=o'.$qq.'; skey='.$skey.'; p_uin=o'.$qq.'';
    $data=get_result($url,$post,$cookie);
    preg_match_all("/retcode\":(.*?)}/",$data,$retcode);
    //echo $data;
    $retcode=$retcode[1][0];
    if($retcode=="0"){
        Switch($type){
            case 'text':
            need::send("已将歌曲[".$aa."]从歌单删除!",'text');
            break;
            default:
            need::send(array("code"=>"1","text"=>"已将歌曲[".$aa."]从歌单删除!",'data'=>Array('music'=>$aa,'cover'=>$ee)));
            break;
        }
    }else{
        Switch($type){
            case 'text':
            need::send('删除失败，请重试!','text');
            break;
            default:
            need::send(array("code"=>"-6","text"=>"删除失败，请重试!"));
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
?>