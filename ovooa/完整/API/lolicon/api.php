<?php
header('content-type:Application/json');
require ("../function.php"); // 引入函数文件
addApiAccess(148); // 调用统计函数
addAccess();//调用统计函数
require '../../need.php';
$type = $_REQUEST['type']?:$_REQUEST['Type'];
$r18 = $_REQUEST['r18']?:$_REQUEST['R18'];
$r18 = false;
class lolicon
{
	public function __construct($type = 'json',$r18 = false)
	{
		file:
		$file = file(__DIR__.'/cache/'.rand(0, 9).'.txt');
		$rand = array_rand($file, 1);
		if(!$file[$rand]) goto file;
		$Data = json_decode($file[$rand], true);
		// print_r($Data);exit;
		Switch($type){
			case 'text':
			$tags = '#'.join(' #', $Data['tags']);
			$url = $Data['urls'][0]?:$Data['urls']['original'];
			preg_match("/pixiv.re\/(.*)/", $url, $vurl);
			$url = str_replace("i.pixiv.re", "i.der.ink", $url).'?sign='.md5('MiKAYA/'.$vurl[1].time()).'&t='.time();
			$Data = '±img='.$url."±\n规格：".$Data['height'].'*'.$Data['width']."\n标题：".$Data['title']."\n标签：".$tags;
			need::send($Data,'text');
			break;
			case 'url':
			$url = isset($Data['urls'][0]) ? $Data['urls'][0] : $Data['urls']['original'];
			preg_match("/pixiv.re\/(.*)/", $url, $vurl);
			$url = str_replace("i.pixiv.re", "i.der.ink", $url).'?sign='.md5('MiKAYA/'.$vurl[1].time()).'&t='.time();
			need::send($url, 'text');
			break;
			default:
			$url = $Data['urls'][0]?:$Data['urls']['original'];
			preg_match("/pixiv.re\/(.*)/", $url, $vurl);
			$url = str_replace("i.pixiv.re", "i.der.ink", $url).'?sign='.md5('MiKAYA/'.$vurl[1].time()).'&t='.time();
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
	}
}
new lolicon($type, $r18);