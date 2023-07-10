<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(10); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$n = $_REQUEST['n'];
$msg = $_REQUEST['msg'];
$type = $_REQUEST['type'];
$tail = $_REQUEST['tail']?:'酷狗音乐';
$p = $_REQUEST['p']?:1;
$sc = $_REQUEST['sc']?:10;
if(!is_numEric($p) or $p < 1){
    $p = 1;
}
if(!is_numEric($sc) or $sc < 1){
    $sc = 10;
}
$url = 'https://songsearch.kugou.com/song_search_v2?keyword='.urlencode($msg).'&platform=WebFilter&pagesize='.$sc.'&page='.$p;//https://mobiles.kugou.com/api/v3/search/song?format=json&keyword=%E5%A4%A9%E5%90%8E&page=1&pagesize=30&showtype=1&callback=kgJSONP400753332
$data = json_decode(need::teacher_curl($url,[
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
    'refer'=>'',
    'cookie'=>'kg_mid=f63d4b35c78fe06cf2b615ad0b86606b; kg_dfid=33ZYYP2ugB621dnTPj0rH7Yg; kg_dfid_collect=d41d8cd98f00b204e9800998ecf8427e',
    'Header'=>[
        'Host: songsearch.kugou.com',
        'Connection: keep-alive',
        'Cache-Control: max-age=0',
        'Upgrade-Insecure-Requests: 1',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'dnt: 1',
        'X-Requested-With: mark.via',
        'Sec-Fetch-Site: none',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-User: ?1',
        'Sec-Fetch-Dest: document',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
    ]
]),true);
$data = $data['data']['lists'];
if(empty($data)){
    Switch($type){
        case 'text':
        need::send('未搜索到有关于'.$msg.'的歌曲信息','text');
        break;
        default:
        need::send(array('code'=>'-2','text'=>'未搜索到有关于'.$msg.'的歌曲信息'),'json');
        break;
    }
}
if(empty($n)){
    Switch($type){
        case 'text':
        foreach($data as $k=>$v){
            $name = $data[$k]['FileName'];
            echo ($k+1);
            echo '.';
            echo $name;
            echo "\n";
        }
        echo '提示：当前为第'.$p.'页';
        exit();
        break;
        default:
        foreach($data as $k=>$v){
            $array[] = $data[$k]['FileName'];
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>$array),'json');
        break;
    }
}
$n = ($n - 1);
//http://trackercdnbj.kugou.com/i/v2/?album_audio_id=101213203&behavior=play&module=&mtype=0&cmd=26&token=&album_id=7361841&userid=0&hash=93c9176f9de0ff5d7558410412ef534c&pid=2&vipType=65530&version=9108&area_code=1&appid=1005&mid=286974383886022203545511837994020015101&key=0026ec30c314553e799c6b092ebbaadf&pidversion=3001&with_res_tag=1
$url = 'http://m.kugou.com/app/i/getSongInfo.php?cmd=playInfo&hash='.$data[$n]['FileHash'];
$data = json_decode(need::teacher_curl($url,[
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
    'refer'=>'',
    'cookie'=>'kg_mid=f63d4b35c78fe06cf2b615ad0b86606b; kg_dfid=33ZYYP2ugB621dnTPj0rH7Yg; kg_dfid_collect=d41d8cd98f00b204e9800998ecf8427e; musicwo17=kugou',
    'Header'=>[
        'Host: m.kugou.com',
        'Connection: keep-alive',
        'Cache-Control: max-age=0',
        'Upgrade-Insecure-Requests: 1',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'dnt: 1',
        'X-Requested-With: mark.via',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
    ]
]),true);
//print_r($data);exit;
$url = $data['url'];
if(empty($url)){
    Switch($type){
        case 'text':
        need::send('获取失败，付费歌曲','text');
        break;
        default:
        need::send(array('code'=>'-3','text'=>'获取失败，付费歌曲'),'json');
        break;
    }
}
$image = str_replace('{size}','400',$data['album_img']);
$name = $data['fileName'];
$player = 'https://www.kugou.com/song/#hash='.$data['hash'].'&album_id='.$data['album_audio_id'];
$singer = $data['singerName'];
$song = $data['songName'];
Switch($type){
    case 'text':
    echo '±img=';
    echo $image;
    echo '±';
    echo "\n";
    echo '歌曲：';
    echo $name;
    echo "\n";
    echo '音乐链接：';
    echo $url;
    echo "\n";
    echo '播放链接：';
    echo $player;
    exit();
    break;
    case 'json':
    need::send('json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$song.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":205141,"ctime":1646802051,"desc":"'.$singer.'","jumpUrl":"'.$player.'","musicUrl":"'.$url.'","preview":"'.$image.'","sourceMsgId":"0","source_icon":"https:\/\/open.gtimg.cn\/open\/app_icon\/00\/20\/51\/41\/205141_100_m.png?t=1639645811","source_url":"","tag":"'.$tail.'","title":"'.$song.'","uin":2830877581}},"config":{"ctime":1646802051,"forward":true,"token":"b0407688307d8c9b10a6c0277a53f442","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":205141,\"uin\":2830877581}"}', 'text');
    //need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"酷狗音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$singer.'","jumpUrl":"'.$player.'","musicUrl":"'.$url.'","preview":"'.$image.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$song.'"}},"prompt":"[分享]'.$name.'","ver":"0.0.0.1","view":"music"}','text');
    break;
    case 'xml':
    need::send("card:3<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><msg serviceID=\"2\" templateID=\"1\" action=\"web\" brief=\"[分享]酷狗音乐\" sourceMsgId=\"0\" url=\"".str_replace('&','&amp;',$player)."\" flag=\"0\" adverSign=\"0\" multiMsgFlag=\"0\"><item layout=\"2\"><audio cover=\"".$image."\" src=\"".str_replace('&','&amp;',$url)."\" /><title>".$song."</title><summary>".$singer."</summary></item><source name=\"".$tail."\" icon=\"https://i.gtimg.cn/open/app_icon/00/20/51/41/205141_100_m.png?date=20170511&amp;_tcvassp_0_=750shp\" url=\"\" action=\"\" a_actionData=\"\" i_actionData=\"\" appid=\"205141\" /></msg>",'text');
    break;
    default:
    need::send(array('code'=>1,'text'=>'获取成功','data'=>array('songs'=>$name,'singer'=>$singer,'musicname'=>$song,'image'=>$image,'musicurl'=>$url,'player'=>$player)),'json');
    break;
}
