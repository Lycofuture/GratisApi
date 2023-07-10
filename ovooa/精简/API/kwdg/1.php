<?php
header("Content-type: application/json; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(12); // 调用统计函数
require '../../need.php';
require '../../curl.php';
/* End */
$a = @$_REQUEST["msg"];
$b = @$_REQUEST["n"];
$p = @$_REQUEST["p"]?:"0";
if($p){
	$p= ($p-1);
}

$type = @$_REQUEST["type"];
$h=@$_REQUEST["h"]?:"\n";
$sc=@$_REQUEST["sc"]?:'15';
$format = @$_REQUEST['format']?:'mp3';
$br = @$_REQUEST['br']?:320;
$tail = @$_REQUEST['tail']?:'酷我音乐';
if(!preg_match('/aac|mp3|/',$forma)){
	$format = 'aac';
}
$data = curl("http://search.kuwo.cn/r.s?client=kt&pn=".$p."&rn={$sc}&uid=1179647890&ver=kwplayer_ar_9.2.7.1&vipver=1&show_copyright_off=1&newver=1&correct=1&ft=music&cluster=0&strategy=2012&encoding=utf8&rformat=json&vermerge=1&mobi=1&issubtitle=1&all=".urlencode($a),"GET",0,0);//用已经封装好的curl进行GET请求

$result = preg_match_all('/"FARTIST":"(.*?)","FORMAT":"(.*?)","FSONGNAME":"(.*?)","KMARK":"(.*?)","MINFO":"(.*?)","MUSICRID":"MUSIC_(.*?)","MVFLAG":"(.*?)","MVPIC":"(.*?)","MVQUALITY":"(.*?)","NAME":"(.*?)","NEW":"(.*?)","ONLINE":"(.*?)","PAY":"(.*?)","PROVIDER":"(.*?)","SONGNAME":"(.*?)"/',$data,$v);//正则匹配需要的东西
//print_r($v[6]);exit;
if($result== 0){
echo '搜索不到与'.@$_REQUEST['msg'].'的相关歌曲，请稍后重试或换个关键词试试。';
}else{
	if($b== null){
		for( $i = 0 ; $i < $result && $i < $sc ; $i ++ ){
		$ga=urldecode($v[15][$i]);//获取名称
		$gb=urldecode($v[1][$i]);//歌手
		echo ($i+1).'：'.$ga.'——'.$gb.''.$h.'';
	}
		need::send('提示：当前为第'.($p + 1).'页'.$h,'text');//您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';
	}else{
//
		$i=($b-1);
		$ga=$v[15][$i];//获取名称
		$gb=$v[1][$i];//歌手
		$id=$v[6][$i];//rid
		if(!$id){
//die ('列表中暂无序号为『'.$b.'』的相关内容，请输入存在的序号进行搜索。');
			for( $i = 0 ; $i < $result && $i < $sc ; $i ++ ){
				$ga=urldecode($v[15][$i]);//获取名称
				$gb=urldecode($v[1][$i]);//歌手
				echo ($i+1).'：'.$ga.'——'.$gb.''.$h.'';
			}
			need::send('提示：当前为第'.($p + 1).'页'.$h,'text');//您可以发送：下一页'.$h.'您可以发送：上一页'.$h.'但是请您按以上序列号选择';
		}
		$t = curl("http://artistpicserver.kuwo.cn/pic.web?user=867401041651110&android_id=f243cc2225eac3c9&prod=kwplayer_ar_9.2.8.1&corp=kuwo&source=kwplayer_ar_9.2.8.1_qq.apk&type=rid_pic&pictype=url&content=list&size=320&rid=".$id,"GET",0,0);//http://mobile.kuwo.cn/mpage/html5/songinfoandlrc?mid=40900571
		/*
		$l = need::teacher_curl('http://kuwo.cn/api/v1/www/music/playUrl?mid='.$id.'&type=music&httpsStatus=1&br='.$br.'&format='.$format,[
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'refer'=>'http://m.kuwo.cn/newh5app/play_detail/28879676',
			'cookie'=>'kw_token=AHCJ6IC4L1A',
			'Header'=>[
				'csrf: AHCJ6IC4L1A'
			]
		]);//http://m.kuwo.cn/newh5app/api/mobile/v1/music/src/*/
		$li = need::teacher_curl('http://antiserver.kuwo.cn/anti.s?type=convert_url&rid='.$id.'&format='.$format.'&response=url&br='.$br,[
			'ua'=>'Dalvik/2.1.0 (Linux; U; Android 7.1.2; 22041216C Build/NZH54D)',
			'refer'=>'http://m.kuwo.cn/newh5app/play_detail/28879676',
			'Header'=>[
				'Host: antiserver.kuwo.cn',
			],
			'GetCookie'=>true
		]);
		print_r($li);exit;
		preg_match_all('/"url":"(.*?)"/',$l,$l);
		$l=$l[1][0]?:$li;//播放链接
		if(empty($l)){
			Switch($type){
				case 'text':
				need::send('播放链接获取失败','text');
				break;
				default:
				need::send(array('code'=>-3,'text'=>'播放链接获取失败'));
				break;
			}
		}else{
			Switch($type){
				case 'json':
				need::send('json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$ga.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100243533,"ctime":1646801556,"desc":"'.$gb.'","jumpUrl":"http://m.kuwo.cn/newh5app/play_detail/'.$id.'","musicUrl":"'.$l.'","preview":"'.$t.'","sourceMsgId":"0","source_icon":"https:\/\/p.qpic.cn\/qqconnect\/0\/app_100243533_1636374695\/100?max-age=2592000&t=0","source_url":"","tag":"'.$tail.'","title":"'.$ga.'","uin":2830877581}},"config":{"ctime":1646801556,"forward":true,"token":"d2c58a67f09729df48dbce319ea4a10b","type":"normal"},"text":"","extraApps":[],"sourceAd":"","extra":"{\"app_type\":1,\"appid\":100243533,\"uin\":2830877581}"}', 'text');
				//need::send('json:{"app":"com.tencent.structmsg","desc":"酷我音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$ga.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100243533,"ctime":1646800750,"desc":"'.$gb.'","jumpUrl":"http://m.kuwo.cn/newh5app/play_detail/'.$id.'","musicUrl":"'.$l.'","preview":"'.$t.'","sourceMsgId":"0","source_icon":"https:\/\/p.qpic.cn\/qqconnect\/0\/app_100243533_1636374695\/100?max-age=2592000&t=0","source_url":"","tag":"'.$tail.'","title":"'.$ga.'","uin":2830877581}},"config":{"ctime":1646800750,"forward":true,"token":"5cf4cb467d3aa8aa43ab97adaab2a87d","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100243533,\"uin\":2830877581}"}', 'text');
				//need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"QQ音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gb.'","jumpUrl":"http://m.kuwo.cn/newh5app/play_detail/'.$id.'","musicUrl":"'.$l.'","preview":"'.$t.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$ga.'"}},"prompt":"[分享]'.$ga.'","ver":"0.0.0.1","view":"music"}','text');
				break;
				case 'X6':
				need::send('json:{"app":"com.tencent.structmsg","desc":"酷我音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$ga.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gb.'","jumpUrl":"http://m.kuwo.cn/newh5app/play_detail/'.$id.'","musicUrl":"'.$l.'","preview":"'.$t.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$ga.'"}},"config":{"autosize":true,"forward":true,"type":"normal"},"text":"","sourceAd":"","extra":""}','text');
				break;
				case 'xml':
				$g=str_replace('&','&amp;',$gb);
				need::send('card:3<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]酷我音乐" sourceMsgId="0" url="'.$l.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$t.'" src="'.$l.'" /><title>'.$ga.'</title><summary>'.$g.'</summary></item><source name="酷我音乐" icon="http://pp.myapp.com/ma_icon/0/icon_11771_1593680980/96" action="app" a_actionData="com.netease.cloudmusic" i_actionData="tencent100495085://" appid="100243533" /></msg>','text');
				break;
				case 'text':
				need::send( '图片：'.$t.''.$h.'歌名：'.$ga.''.$h.'歌手：'.$gb.''.$h.'播放链接：'.$l.'','text');
				break;
				default:
				need::send(array('code'=>1,'data'=>array('desc'=>'酷我音乐','singer'=>$gb,'image'=>$t,'musicname'=>$ga,'musicurl'=>$l)));
				break;
			}
		}
	}
}

