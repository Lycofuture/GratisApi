<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(10); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件
/* End */
$n = @$_REQUEST['n'];
$msg = @$_REQUEST['msg'];
$type = @$_REQUEST['type'];
$tail = @$_REQUEST['tail']?:'酷狗音乐';
$p = @$_REQUEST['p']?:1;
$num = @$_REQUEST['sc']?:10;
$br = @$_REQUEST['br']?:320;

new 酷狗音乐(['name'=>$msg, 'num'=>$num, 'page'=>$p, 'tail'=>$tail, 'n'=>$n, 'type'=>$type, 'br'=>$br]);
class 酷狗音乐{
	protected $info = [];
	protected $Msg;
	protected $Array = [];
	protected $data;
	protected $id;
	protected $img;
	public function __construct(Array $Array){
		echo (need::teacher_curl('http://backup.api.lkaa.top/kgdg.php', [
			'post'=>http_build_query($Array)
		]));
		exit();
		foreach($Array as $k => $v){
			$this->info[$k] = $v;
		}
		$this->ParameterException();
	}
	protected function ParameterException(){
		$Name = $this->info['name'];
		if(empty(need::nate($Name))){
			unset($this->Array , $this->Msg);
			$this->Array = Array('code'=>-1, 'text'=>'请输入歌名');
			$this->Msg = '请输入歌名';
			$this->returns();
			return;
		}
		$num = $this->info['num'];
		if($num < 1 || !is_numEric($num)){
			$this->info['num'] = 10;
		}
		$page = $this->info['page'];
		if($page < 1 || !is_numEric($page)){
			$this->info['page'] = 1;
		}
		$n = $this->info['n'];
		if(empty($n) || !is_numEric($n) || $n < 1 || $n > $this->info['num']){
			$this->info['n'] = 0;
		}
		$this->GetName();
	}
	public function GetName(){
		$name = urlencode($this->info['name']);
		$page = $this->info['page'];
		$num = $this->info['num'];
		$n = $this->info['n'];
		$rand = Md5(mt_rand());
		//echo $name;
		$url = 'https://songsearch.kugou.com/song_search_v2?keyword='.($name).'&platform=WebFilter&pagesize='.$num.'&showtype=1&page='.$page;
		// echo $url;
		$data = json_decode(need::teacher_curl($url, [
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'refer'=>$url,
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
		]), true);
		if($data['data']['sectag_info']){
			$url = 'http://mobilecdn.kugou.com/api/v3/search/song?api_ver=1&area_code=1&correct=1&pagesize='.$num.'&plat=2&tag=1&sver=5&showtype=10&page='.$page.'&keyword='.$name.'&version=8990';
			$data = json_decode(need::teacher_curl($url), true);
		}
		$data = isset($data['data']['lists']) ? $data['data']['lists'] : $data['data']['info'];
		// echo file_get_Contents($url);
		// print_r($data);exit;
		if(!$data){
			unset($this->Array , $this->Msg);
			$this->Array = ['code'=>-2, 'text'=>'未搜索到相关歌曲'];
			$this->Msg = '未搜索到相关歌曲';
			$this->returns();
			return;
		}
		if(!$n || !$data[($n - 1)]){
			$Array = [];
			foreach($data as $k=>$v){
				$name = isset($v['SongName']) ? $v['SongName'] : $v['songname'];
				$Singer = isset($v['SingerName']) ? $v['SingerName'] : $v['singername'];
				// print_r($v); exit;
				$Msg .= ($k + 1) . '.' . $name. '-' . $Singer."\n";
				$Array[] = ['name'=>$name, 'singer'=>$Singer, '_singer'=>explode('、', $Singer)];
			}
			unset($this->Array , $this->Msg);
			$this->Msg = trim($Msg);
			$this->Array = ['code'=>1, 'text'=>'获取成功', 'data'=>$Array];
			$this->returns();
			return;
		}else{
			$datas = $data[($n - 1)];
			//echo $Music;exit;
			if(!isset($data['url'])){
				$albumID = isset($datas['AlbumID']) ? $datas['AlbumID'] : $datas['album_id'];
				$Time = need::time_sss();
				unset($url, $data);
				$data = json_decode(need::teacher_curl('http://media.store.kugou.com/v1/get_res_privilege', [
					'post'=>'{"relate":1,"userid":"0","vip":0,"appid":1000,"token":"","behavior":"download","area_code":"1","clientver":"8990","resource":[{"id":0,"type":"audio","hash":"'.(isset($datas['FileHash']) ? $datas['FileHash'] : $datas['hash']).'"}]}',
					'Header'=>["User-Agent: IPhone-8990-searchSong","UNI-UserAgent: iOS11.4-Phone8990-1009-0-WiFi"]
				]), true);
				// print_r($data);exit;
				$br = isset($this->info['br']) ? $this->info['br'] : 320;
				foreach ($data['data'][0]['relate_goods'] as $vo) {
					if ($vo['info']['bitrate'] <= $br && $vo['info']['bitrate'] > 0) {
						$api = array(
							'method' => 'GET',
							'url'	=> 'http://trackercdn.kugou.com/i/v2/',
							'body'   => array(
								'hash'	 => $vo['hash'],
								'key'	  => md5($vo['hash'].'kgcloudv2'),
								'pid'	  => 3,
								'behavior' => 'play',
								'cmd'	  => '25',
								'version'  => 8990,
							),
							'Header'=>["User-Agent: IPhone-8990-searchSong","UNI-UserAgent: iOS11.4-Phone8990-1009-0-WiFi"]
						);
						$this->img = str_replace('{size}', 480, $vo['info']['image']);
						$url = $api['url'].'?'.http_build_query($api['body']);
						break;
					}
				}
				if($url){
					$data = json_decode(need::teacher_curl($url, [
						'Header'=>["User-Agent: IPhone-8990-searchSong","UNI-UserAgent: iOS11.4-Phone8990-1009-0-WiFi"]
					]), true);
				}else{
					//print_r($data);exit;
					$url = 'https://wwwapi.kugou.com/yy/index.php?r=play/getdata&hash='.$datas['FileHash'].'&mid='.Md5($Time).'&dfid=&appid=1014&platid=4&album_id='.$albumID.'&_='.$Time;
					$data = json_decode((str_replace(['jQuery19106392526654175634_1649080820565(', ');'], '', need::teacher_curl($url))), true);
				}
				/*
				echo $url;
				print_r($data);exit;
				//*/
				$code = $data['status'];
				if($code == 0){
					unset($this->Array , $this->Msg);
					$this->Msg = trim('被拉黑了，明天再试试');
					$this->Array = ['code'=>-4, 'text'=>'被拉黑了，明天再试试'];
					$this->returns();
				}else
				if($code != 1){
					unset($this->Array , $this->Msg);
					$this->Msg = trim('获取失败，该歌曲可能为付费歌曲');
					$this->Array = ['code'=>-3, 'text'=>'获取失败，该歌曲可能为付费歌曲'];
					$this->returns();
					return;
				}else{
					unset($this->Array , $this->Msg);
					array_key_exists('data', $data) ? $data = $data['data'] : $data = $data;
					//echo in_array('data', $data);
					//print_r($data);exit;
					$image = isset($data['img']) ? $data['img'] : $this->img;
					$Singer = (isset($data['author_name']) ? $data['author_name'] : (isset($datas['SingerName']) ? $datas['SingerName'] : @$datas['singername'])) ?: null;
					$song = isset($datas['SongName']) ? $datas['SongName'] : @$datas['songname'];
					$song = urldecode((String)$song);
					$Music = (isset($data['play_url']) ? $data['play_url'] : (isset($data['url'][0]) ? $data['url'][0] : @$data['url'][1]));
					$albumid = (isset($data['albumid']) ? $data['albumid'] : (isset($data['album_id']) ? $data['album_id'] : @ $albumID));
					// print_r($data);
					$Music_URL = 'https://www.kugou.com/song/#hash='. (isset($data['hash']) ? $data['hash'] : @$datas['FileHash']) .'&album_id='.$albumid;
					$this->Msg = '±img='.$image.'±'."\n歌名：{$song}\n歌手：{$Singer}\n歌曲链接：{$Music}";
					$this->Array = ['code'=>1, 'text'=>'获取成功', 'data'=>['song'=>$song, 'singer'=>$Singer, 'url'=>$Music, 'cover'=>$image, 'Music_Url'=>$Music_URL]];
					$this->returns();
					return;
				}
			}
			unset($this->Array , $this->Msg);
			$Music = $data['url'];
			$image = str_replace('{size}','480',$data['album_img']);
			$Singer = $data['singerName'];
			$song = $data['songName'];
			$Music_URL = 'https://www.kugou.com/song/#hash='.$data['hash'].'&album_id='.$data['albumid'];
			$this->Msg = '±img='.$image.'±'."\n歌名：{$song}\n歌手：{$Singer}\n歌曲链接：{$Music}";
			$this->Array = ['code'=>1, 'text'=>'获取成功', 'data'=>['song'=>$song, 'singer'=>$Singer, 'url'=>$Music, 'cover'=>$image, 'Music_Url'=>$Music_URL]];
			$this->returns();
			return;
		}
		return;
	}
	protected function url($url){
		Switch($url['method']){
			case 'GET':
			$url = $url['url'].'?'.http_build_query($url['body']);
			return need::teacher_curl($url, [
				'Header'=>isset($url['Header']) ? $url['Header'] : false
			]);
			break;
			default:
			return need::teacher_curl($url['url'], [
				'post'=>json_encode($url['body']),
				'Header'=>isset($url['Header']) ? $url['Header'] : false
			]);
		}
	}
	public function returns(){
		$type = $this->info['type'];
		$data = $this->Array;
		//print_r($data);
		$Msg = $this->Msg;
		if(!$data['data']['song']){
			Switch($type){
				case 'text':
				need::send($Msg, 'text');
				break;
				default:
				need::send($data, 'json');
				break;
			}
		}else{
			$Name = $data['data']['song'];//歌名
			$Url = $data['data']['url'];//歌曲链接
			$Music = $data['data']['Music_Url'];//在线播放
			$Singer = $data['data']['singer'];//歌手
			$Cover = $data['data']['cover'];//封面图
			$tail = $this->info['tail'];
			Switch($type){
				case 'json':
				need::send('json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$Name.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":205141,"ctime":1646802051,"desc":"'.$Singer.'","jumpUrl":"'.$Music.'","musicUrl":"'.$Url.'","preview":"'.$Cover.'","sourceMsgId":"0","source_icon":"https:\/\/open.gtimg.cn\/open\/app_icon\/00\/20\/51\/41\/205141_100_m.png?t=1639645811","source_url":"","tag":"'.$tail.'","title":"'.$Name.'","uin":2830877581}},"config":{"ctime":1646802051,"forward":true,"token":"b0407688307d8c9b10a6c0277a53f442","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":205141,\"uin\":2830877581}"}', 'text');
				//need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"酷狗音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$Singer.'","jumpUrl":"'.$Music.'","musicUrl":"'.$Url.'","preview":"'.$Cover.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$Name.'"}},"prompt":"[分享]'.$Name.'","ver":"0.0.0.1","view":"music"}', 'text');
				break;
				case 'xml':
				echo "card:3<?xml version='1.0' encoding='UTF-8' standalone='yes' ?>";
				need::send('<msg serviceID="2" templateID="1" action="web" brief="[分享]'.str_replace('&','&amp;', $Name).'" sourceMsgId="0" url="'.str_replace('&','&amp;', $Music).'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.str_replace('&','&amp;', $Cover).'" src="'.str_replace('&','&amp;', $Url).'" /><title>'.str_replace('&','&amp;', $Name).'</title><summary>'.str_replace('&','&amp;', $Singer).'</summary></item><source name="'.$tail.'" icon="https://www.kugou.com/root/favicon.ico" action="app" a_actionData="" i_actionData="" appid="100497308" /></msg>', 'text');
				break;
				case 'text':
				need::send($Msg, 'text');
				break;
				default:
				need::send($data, 'json');
				break;
			}
		}
		return;
	}
}
