<?php
header('content-type:application/json');

/* Start */
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */

$url = $_REQUEST['url'];
if(empty($url)){
    Switch($type){
        case 'text':
        need::send('请输入链接','text');
        break;
        default:
        need::send(array(
            'code'=>'-1',
            'text'=>'请输入链接'
        ));
        break;
    }
}
preg_match_all('/(http[s]?):\/\/([\w-.%#?\/\\\]+)/i',$url,$url);
$url = $url[1][0].'://'.$url[2][0];
//echo $url;exit;
if(preg_match('/(https:\/\/v\.ixigua\.com\/[0-9a-zA-Z]+)/',$url,$url_duan)){
    if($url[1]){ 
        $url = $url_duan[1];
        $url = need::teacher_curl($url,['loadurl'=>1]);
        preg_match('/video\/([0-9]+)\//',$url,$video_id);
        $video_id = $video_id[1];
        //echo $url;
    }
}else
//echo $url;exit;
if(preg_match('/video\/([0-9]+)\//',$url,$id)){ 
    $video_id = $id[1];
//    echo $video_id;
}else
if(preg_match('/https:\/\/www\.ixigua\.com\/([0-9]+)\?/',$url,$id)){ 
    $video_id = $id[1];
}
/*
echo 'https://www.ixigua.com/';
echo $video_id;
exit;
*/
$video = 'https://m.ixigua.com/video/'.$video_id.'?wid_try=1';
$data = need::teacher_curl($video,[
    'ua'=>'NeteaseMusic/8.0.50.210201163826(8000051);Dalvik/2.1.0 (Linux; U; Android 11; PCLM10 Build/RKQ1.200928.002)',
    'cookie'=>'Cookies'
]);
//echo need::teacher_curl('https://www.ixigua.com/'.$video_id);
//echo $data;
//exit;
//echo need::teacher_curl(need::teacher_curl('https://www.ixigua.com/'.$video_id,['loadurl'=>1]));exit;
preg_match('/aid: ([0-9]+),/',$data,$aid);
$aid = $aid[1];
//echo $aid;exit;
preg_match('/_SSR_DATA = ([\s\S]*?)<\/script>/',$data,$data);
$data = json_decode($data[1],true);
//print_r($data);exit;
$video_id = $data['data']['loadersData']['e64816860914ac67a8e574cb4a97d7fc_0']['_result']['video_id'];
//echo $video_id;
$title = $data['data']['loadersData']['e64816860914ac67a8e574cb4a97d7fc_0']['_result']['title'];
$url = 'https://ib.365yg.com/video/urls/v/1/toutiao/mp4/'.$video_id.'?r=9702509479332506&s=2553380017&aid='.$aid.'&logo_type=xigua_cw&vfrom=xgplayer&_=1631449656440&callback=';
$data = need::teacher_curl($url,[
    'ua'=>'NeteaseMusic/8.0.50.210201163826(8000051);Dalvik/2.1.0 (Linux; U; Android 11; PCLM10 Build/RKQ1.200928.002)',
    'refer'=>$video
]);
$data = json_decode($data,true);
print_r($data);exit;
$videourl = $data['data']['video_list']['video_3']['main_url']?$data['data']['video_list']['video_2']['main_url']:$data['data']['video_list']['video_1']['main_url'];
echo base64_decode($videourl);
//print_r( $videourl);
/*
class a32{
    var $n;
    function (){
        for($t = 0 ; $e = new array(256) ; $n = 0 ; $n != 256 ; ++$n && $t = $n)
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
    }
}*/
/*
function crc32(video_id) {
        var n = function() {
            for (var t = 0,
            e = new Array(256), n = 0; 256 != n; ++n) t = n,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            t = 1 & t ? -306674912 ^ t >>> 1 : t >>> 1,
            e[n] = t;
            return "undefined" != typeof Int32Array ? new Int32Array(e) : e
        } (),
        o = function(t) {
            for (var e, o, r = -1,
            i = 0,
            a = t.length; i < a;) e = t.charCodeAt(i++),
            e < 128 ? r = r >>> 8 ^ n[255 & (r ^ e)] : e < 2048 ? (r = r >>> 8 ^ n[255 & (r ^ (192 | e >> 6 & 31))], r = r >>> 8 ^ n[255 & (r ^ (128 | 63 & e))]) : e >= 55296 && e < 57344 ? (e = (1023 & e) + 64, o = 1023 & t.charCodeAt(i++), r = r >>> 8 ^ n[255 & (r ^ (240 | e >> 8 & 7))], r = r >>> 8 ^ n[255 & (r ^ (128 | e >> 2 & 63))], r = r >>> 8 ^ n[255 & (r ^ (128 | o >> 6 & 15 | (3 & e) << 4))], r = r >>> 8 ^ n[255 & (r ^ (128 | 63 & o))]) : (r = r >>> 8 ^ n[255 & (r ^ (224 | e >> 12 & 15))], r = r >>> 8 ^ n[255 & (r ^ (128 | e >> 6 & 63))], r = r >>> 8 ^ n[255 & (r ^ (128 | 63 & e))]);
            return r ^ -1
        },
        r = "/video/urls/v/1/toutiao/mp4/"+video_id + "?r=" + Math.random().toString(10).substring(2);
        "/" != r[0] && (r = "/" + r);
        var i = o(r) >>> 0;
        return ("https://ib.365yg.com"+r + "&s=" + i)
    }
*/
