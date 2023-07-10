<?php
header('content-type:Application/json');
require ("../function.php"); // 引入函数文件
addApiAccess(148); // 调用统计函数
addAccess();//调用统计函数
require '../../need.php';
// print_r($_REQUEST);
$Request = need::request();
$type = isset($Request['type']) ? $Request['type'] : false;
$r18 = false;
class Pximg{
    public function __construct($type = 'json', $r18 = false){
        if(!$r18){
            $file = file('setu.json');
        }else{
            $file = file('setu_r18.json');
        }
        $rand = array_rand($file,1);
        $Data = json_decode($file[$rand],true);
        Switch($type){
            case 'text':
            $Data = $Data['data'][0];
            foreach($Data['tags'] as $v){
                $tags .= $v.',';
            }
            $tags = trim($tags,',');
            $url = $Data['urls'][0]?:$Data['urls']['original'];
            preg_match("/pixiv.re\/(.*)/", $url, $vurl);
            $url = str_replace("i.pixiv.re", "i.piccache.top", $url).'?sign='.md5('MiKAYA/'.$vurl[1].time()).'&t='.time();
            $Data = '±img='.$url."±\n规格：".$Data['height'].'*'.$Data['width']."\n标题：".$Data['title']."\n标签：".$tags;
            need::send($Data,'text');
            break;
            case 'url':
            $Data = $Data['data'][0];
            $url = $Data['urls'][0]?:$Data['urls']['original'];
            preg_match("/pixiv.re\/(.*)/", $url, $vurl);
            $url = str_replace("i.pixiv.re", "i.piccache.top", $url).'?sign='.md5('MiKAYA/'.$vurl[1].time()).'&t='.time();
            need::send($url, 'text');
            break;
            default:
            $Data = $Data['data'][0];
            $url = isset($Data['urls'][0])? $Data['urls'][0] : $Data['urls']['original'];
            preg_match("/pixiv.re\/(.*)/", $url, $vurl);
            $url = str_replace("i.pixiv.re", "i.piccache.top", $url).'?sign='.md5('MiKAYA/'.$vurl[1].time()).'&t='.time();
            if ($Data['urls']['original']) {
            $Data['urls']['original'] = $url;
            } else {
            $Data['urls'][0] = $url;
            }
            unset($Data['p'],$Data['r18'],$Data['uploadDate']);
            $Data = array('code'=>1,'text'=>'获取成功','data'=>$Data);
            need::send($Data);
            break;
        }
        return true;
    }
}
new Pximg($type, $r18);