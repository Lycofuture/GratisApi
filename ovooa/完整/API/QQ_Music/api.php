<?php
Header('Content-type: application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(117); // 调用统计函数
addAccess();//调用统计函数*/
require '../../need.php';//调用所需其他函数
/* End */
$request = need::request();
$msg = isset($request['msg']) ? $request['msg'] : false;
$songid = isset($request['songid']) ? $request['songid'] : false;
$mid = isset($request['mid']) ? $request['mid'] : false;
$page = isset($request['page']) ? $request['page'] : false;
$limit = isset($request['limit']) ? $request['limit'] : false;
$tail = isset($request['tail']) ? $request['tail'] : false;
$br = isset($request['br']) ? $request['br'] : 4;
$Skey = isset($request['Skey']) ? $request['Skey'] : false;
$Cookie = isset($request['Cookie']) ? $request['Cookie'] : false;
$uin = isset($request['uin']) ? $request['uin'] : false;
$n = isset($request['n']) ? $request['n'] : false;
$type = isset($request['type']) ? $request['type'] : 'j';
new QQ_Music(['msg'=>$msg, 'songid'=>$songid, 'mid'=>$mid, 'page'=>$page, 'limit'=>$limit, 'tail'=>$tail, 'br'=>$br, 'n'=> $n, 'type'=>$type, 'Skey'=>$Skey, 'uin'=>$uin, 'Cookie'=>$Cookie]);
class QQ_Music
{
	public $info = [];
	public $array = [];
	public $message, $data, $raw, $error, $curlinfo;
	public $header = array(
				'Referer'		 => 'https://y.qq.com/',
				'Cookie'		  => null,
				'User-Agent'	  => 'QQ%E9%9F%B3%E4%B9%90/54409 CFNetwork/901.1 Darwin/17.6.0 (x86_64)',
				'Accept'		  => '*/*',
				'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
				'Connection'	  => 'keep-alive',
				'Content-Type'	=> 'application/x-www-form-urlencoded',
	);
	public $guid = 1559616839293;
	public function __construct($array)
	{
		$this->info = $array;
		$this->parameterException();
	}
	public function parameterException()
	{
		$info = $this->info;
		if(isset($this->info['uin']) && need::is_num($this->info['uin']) && isset($this->info['Skey']) && need::is_Skey($this->info['Skey']))
		{
			$this->header['Cookie'] = "uin=o{$this->info['uin']}; skey={$this->info['Skey']}";
			// print_r($this->header);exit;
		} else if(isset($this->info['Cookie']) && $this->info['Cookie']) {
			$this->header['Cookie'] = $this->info['Cookie'];
		} else {
			$this->header['Cookie'] = need::cookie('Skey');
		}
		if(!$info['msg'])
		{
			if((!$info['songid'] || !is_numeric($info['songid'])) && !$info['mid']) return $this->result(['code'=>-1, 'text'=>'请输入需要搜索的歌名或Id']);
			if($info['songid'] && is_numeric($info['songid']))
			{
				return $this->songid();
			}
			if($info['mid']) return $this->getMusic();
			return $this->result(['code'=>-1, 'text'=>'请输入需要搜索的歌名或Id']);
		}
		if(!$info['br'] || !is_numeric($info['br'])) $info['br'] = 4;
		if(!$info['n'] || !is_numeric($info['n']) || $info['n'] < 1) $info['n'] = 0;
		if(!$info['page'] || !is_numeric($info['page']) || $info['page'] < 1) $info['page'] = 1;
		if(!$info['limit'] || !is_numeric($info['limit']) || $info['limit'] < 1 || $info['limit'] > 50) $info['limit'] = 10;
		$this->info = $info;
		return $this->getName();
	}
	public function exec($api)
	{
		if (isset($api['encode'])) {
			$api = call_user_func_array(array($this, $api['encode']), array($api));
		}
		if ($api['method'] == 'GET') {
			if (isset($api['body'])) {
				$api['url'] .= '?'.http_build_query($api['body']);
				$api['body'] = null;
			}
		}
		if (isset($api['headers']) && $api['headers'])
		{
			$this->curl($api['url'], $api['body'], 0, (isset($api['post']) ? $api['post'] : false), $api['headers']);
		} else {
			$this->curl($api['url'], $api['body'], 0, (isset($api['post']) ? $api['post'] : false));
		}
		$this->data = $this->raw;
		// echo 'exec';
		// print_r($this->curl($api['url'], $api['body'], 0, (isset($api['post']) ? $api['post'] : false), $api['headers']));
		// print_r($api);
		if (isset($api['decode'])) {
			$this->data = call_user_func_array(array($this, $api['decode']), array($this->data));
		}
		if (isset($api['format'])) {
			$this->data = $this->clean($this->data, $api['format']);
		}
		return $this->data;
	}
	public function curl($url, $payload = null, $headerOnly = 0, $post = false, $headers = null)
	{
		$headers = $headers ? $headers : $this->header;
		$header = array_map(function ($k, $v) {
			return $k.': '.$v;
		}, array_keys($headers), $headers);
		// print_r($header);
		$curl = curl_init();
		if (!is_null($payload)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			if($post === false)
			{
				curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($payload) ? http_build_query($payload) : $payload);
			} else {
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload,320));
			}
			// $header[] = 'Content-Length: '.strlen((is_array($payload) ? http_build_query($payload) : $payload));
		}
		curl_setopt($curl, CURLOPT_HEADER, $headerOnly);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
		curl_setopt($curl, CURLOPT_IPRESOLVE, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if(isset($headers['Referer']) && $headers['Referer'])
		{
			// echo 123;
			curl_setopt($curl, CURLOPT_REFERER, $headers['Referer']);
		}
		curl_setopt($curl, CURLOPT_COOKIE, $this->header['Cookie']);
		/*if ($this->proxy) {
			curl_setopt($curl, CURLOPT_PROXY, $this->proxy);
		}*/
		// print_r(curl_exec($curl));
		for ($i = 0; $i < 3; $i++) {
			$this->raw = curl_exec($curl);
			$this->error = curl_errno($curl);
			$this->curlinfo = curl_getinfo($curl);
			if (!$this->error) {
				break;
			}
		}
		// print_r(($this));
		curl_close($curl);
		return $this;
	}
	public function pickup($array, $rule)
	{
		$t = explode('.', $rule);
		foreach ($t as $vo) {
			if (!isset($array[$vo])) {
				return array();
			}
			$array = $array[$vo];
		}

		return $array;
	}
	public function clean($raw, $rule)
	{
		$raw = json_decode($raw, true);
		// print_r($raw);
		if (!empty($rule)) {
			$raw = $this->pickup($raw, $rule);
		}
		if (!isset($raw[0]) && count($raw)) {
			$raw = array($raw);
		}
		$result = array_map(array($this, 'format'), $raw);
		return ($result);
	}
	protected function format($data)
	{
		if (isset($data['musicData'])) {
			$data = $data['musicData'];
		}
		// print_r($data);exit;
		/*
		$result = array(
			'songid'	   => $data['songid'],
			'song'	 => $data['songname'],
			'singers'   => array(),
			'album'	=> trim($data['albumname']),
			'mid'   => $data['songmid'],
			'picture' => 'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['albummid'].'.jpg?max_age=2592000'
			// 'source'   => 'tencent',
		);
		foreach ($data['singer'] as $vo) {
			$result['singers'][] = $vo['name'];
		}
		$result['singer'] = join('、', $result['singers']);
		*/
		
		$result = array(
			'songid'=>$data['id'],
			'song'=>$data['name'],
			'singers'=>array(),
			'album'=>$data['album']['name'],
			'mid'=>$data['mid'],
			'picture'=>'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['album']['mid'].'.jpg?max_age=2592000'
		);
		foreach ($data['singer'] as $vo) {
			$result['singers'][] = $vo['name'];
		}
		$result['singer'] = join('、', $result['singers']);
		
		return $result;
	}
	/*
	* 通过名字获取列表以及音乐信息
	*/
	public function getName()
	{
	/*
		$api = array(
			'method' => 'POST',
			'url' => 'https://shc6.y.qq.com/soso/fcgi-bin/search_for_qq_cp',//https://c.y.qq.com/soso/fcgi-bin/client_search_cp',
			'body' => array(
				'format' => 'json',
				'p' => isset($this->info['page']) ? $this->info['page'] : 1,
				'n' => isset($this->info['limit']) ? $this->info['limit'] : 10,
				'w' => $this->info['msg'],
				'aggr' => 1,
				'lossless' => 1,
				'cr' => 1,
				'new_json' => 1,
			),
			'format' => 'data.song.list',
		);
		*/
		$guid = $this->guid;
		/*
		for($i = 0 ; $i < $this->info['page'] ; $i++)
		{
		*/
		
			$api = [
				'method'=>'POST',
				'url'=>'https://u.y.qq.com/cgi-bin/musicu.fcg?_webcgikey=DoSearchForQQMusicDesktop&_='.need::time_sss(),
				'body'=>[
					"comm"=>[
						"g_tk"=>1617175180,
						"uin"=>2354452553,
						"format"=>"json",
						"inCharset"=>"utf-8",
						"outCharset"=>"utf-8",
						"notice"=>0,
						"platform"=>"h5",
						"needNewCode"=>1,
						"ct"=>23,
						"cv"=>0
					],
					"req_0"=>[
						"method"=>"DoSearchForQQMusicDesktop",
						"module"=>"music.search.SearchCgiService",
						"param"=>[
							"remoteplace"=>"txt.mqq.all",
							"searchid"=>$guid,
							"search_type"=>0,
							"query"=> (String) $this->info['msg'],
							"page_num"=>(int) isset($this->info['page']) ? $this->info['page'] : 1,
							"num_per_page"=> (int) isset($this->info['limit']) ? $this->info['limit'] : 10
						]
					]
				],
				'post'=>true,
				'format'=>'req_0.data.body.song.list',
				'headers'=>[
					'Origin'=>'https://i.y.qq.com',
					'Host'=>'u.y.qq.com',
					'User-Agent'=>'Mozilla/5.0 (Linux; Android 13; 22127RK46C Build/TKQ1.220905.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.97 Mobile Safari/537.36',
					'Accept'		  => 'application/json',
					'Accept-Language' => 'zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
					'Connection'	  => 'keep-alive',
					'Cookie'=>$this->header['Cookie'],
					'Content-Type'	=> 'application/x-www-form-urlencoded',
					'X-Requested-With: mark.via',
					'Sec-Fetch-Site: same-site',
					'Sec-Fetch-Mode: cors',
					'Sec-Fetch-Dest: empty',
					'Referer: https://i.y.qq.com/',
					'Accept-Encoding: gzip, deflate'
				]
			];
			// print_r($this->info);
			// print_r($api);exit;
		/*
		$api = array(
			'method' => 'GET',
			'url' => 'https://c.y.qq.com/soso/fcgi-bin/search_for_qq_cp',
			'body' => array(
				'format' => 'json',
				'p' => isset($this->info['page']) ? $this->info['page'] : 1,
				'n' => isset($this->info['limit']) ? $this->info['limit'] : 10,
				'w' => $this->info['msg'],
				'platform' => 'h5'
			),
			// 'format' => 'data.song.list',
			'headers'=>[
				'Host'=>'c.y.qq.com',
				'Connection'=>'keep-alive',
				'Cache-Control'=>'max-age=0',
				'Upgrade-Insecure-Requests'=>'1',
				'User-Agent'=>'Mozilla/5.0 (Linux; Android 13; 22127RK46C Build/TKQ1.220905.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.97 Mobile Safari/537.36',
				'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
				'dnt'=>'1',
				'X-Requested-With'=>'mark.via',
				'Sec-Fetch-Site'=>'none',
				'Sec-Fetch-Mode'=>'navigate',
				'Sec-Fetch-User'=>'?1',
				'Sec-Fetch-Dest'=>'document',
				'Referer'=>'https://c.y.qq.com/soso/fcgi-bin/search_for_qq_cp?g_tk=5381&uin=0&format=jsonp&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&w=%E4%BD%A0%E5%A5%BD%E4%B8%8D%E5%A5%BD&zhidaqu=1&catZhida=1&t=0&flag=1&ie=utf-8&sem=1&aggr=0&perpage=20&n=20&p=1&remoteplace=txt.mqq.all&_=1520833663464',
				'Accept-Encoding'=>'gzip, deflate',
				'Accept-Language'=>'zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
			]
		);*/
		// print_r($api);
		// $api['headers']['Content-Length'] = strlen(json_encode($api['body'], 320));
		
		$data = ($this->exec($api));
		/*
		if(isset($data['req_0'])) $data = $data['req_0'];
		// print_r($data['data']['meta']);
		if(isset($data['data']) && $data['data'])
		{
			if(isset($data['data']['meta']['next_page_start']['searchid']) && $data['data']['meta']['next_page_start']['searchid'])
			{
				$this->guid = $data['data']['meta']['next_page_start']['searchid'];
				continue;
			} else {
				$data = $data['data']['body']['song']['list'];
				break;
			}
		} else {
			break;
		}
	}*/
		// print_r($data);exit;
		// print_r($api['url'].http_build_query($api['body']));exit;
		if($this->info['n'] < 1)
		{
			$message = [];
			foreach($data as $k=>$v)
			{
				$message[] = ($k + 1).'、'.$v['song'] . '  —  '. $v['singer'];
			}
			return $this->result(['code'=>1, 'text'=>'请选择歌曲', 'data'=>$data], join("\n", $message));
		} else {
			$n = ($this->info['n'] - 1);
			if(isset($data[$n]))
			{
				// print_r($this->header);
				// print_r($data);exit;
				$this->info['mid'] = $data[$n]['mid'];
				$this->info['songid'] = $data[$n]['songid'];
				$this->array = $data[$n];
				return $this->getMusic();
			} else {
				$this->info['n'] = 0;
				return $this->getName();
			}
		}
	}
	public function songid()
	{
		$api = [
			'method'=>'GET',
			'url'=>'https://i.y.qq.com/v8/playsong.html',
			'body'=>[
				'platform'=>11,
				'appshare'=>'android_qq',
				'appversion'=>'10170509',
				'hosteuin'=>'owok7evkow4koz**',
				'songid'=>$this->info['songid'],
				'type'=>'0',
				'appsongtype'=>'1',
				'_wv'=>'1',
				'source'=>'qq',
				'ADTAG'=>'qfshare'
			]
		];
		$data = $this->exec($api);
		preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$data, $Music);
		if($Music[1])
		{
			$data = json_decode($Music[1], true);
			if(isset($data['songList'][0]['mid']) && $data['songList'][0]['mid'])
			{
				$this->info['mid'] = $data['songList'][0]['mid'];
				$this->array = $data['songList'][0];
				// print_r($data);
				return $this->getMusic();
			} else {
				return $this->result(['code'=>-2, 'text'=>'获取失败，这首歌可能不行，换首试试？']);
			}
		} else {
			return $this->result(['code'=>-2, 'text'=>'获取失败，这首歌可能不行，换首试试？']);
		}
	}
	public function getMusic()
	{
		$Id = $this->info['mid'];
		if(!$Id)
		{
			if(!$this->info['songid'])
			{
				return $this->result(['code'=>-3, 'text'=>'出现了意想不到的错误，请重试']);
			} else {
				return $this->songid();
			}
		} else {
			$api = array(
				'method' => 'GET',
				'url'	=> 'https://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg',
				'body'   => array(
					'songmid'  => $Id,
					'platform' => 'yqq',
					'format'   => 'json',
				)
			);
			$guid = uniqid();
			$data = json_decode($this->exec($api), true);
			// print_r($data);
			$cover = 'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.@$data['data'][0]['album']['mid'].'.jpg?max_age=2592000';
			$type = array(
				// array('try_end', 102200, 'Q0M0', 'flac'),
				// array('try_begin', 76592, 'QN00', 'ogg'),
				array('size_try', 960887, 'AI00', 'flac'),
				// array('size_hires', 0, 'AI00', 'flac'),
				array('size_flac', 999, 'F000', 'flac'),
				array('size_320mp3', 320, 'M800', 'mp3'),
				array('size_192aac', 192, 'C600', 'm4a'),
				array('size_128mp3', 128, 'M500', 'mp3'),
				array('size_96aac', 96, 'C400', 'm4a'),
				array('size_48aac', 48, 'C200', 'm4a'),
				array('size_24aac', 24, 'C100', 'm4a')
			);
			$uin = need::cookie('Robot', true);
			$payload = array(
				'req_0' => array(
					'module' => 'vkey.GetVkeyServer',
					'method' => 'CgiGetVkey',
					'param'  => array(
						'guid'	  => (string) $guid,
						'songmid'   => array(),
						'filename'  => array(),
						'songtype'  => array(),
						'uin'	   => $uin,
						'loginflag' => 1,
						'platform'  => '20',
					),
				),
			);
			foreach ($type as $vo) {
				$payload['req_0']['param']['songmid'][] = $data['data'][0]['mid'];
				$payload['req_0']['param']['filename'][] = $vo[2].$data['data'][0]['file']['media_mid'].'.'.$vo[3];
				$payload['req_0']['param']['songtype'][] = $data['data'][0]['type'];
			}
			$api = array(
				'method' => 'GET',
				'url'	=> 'https://u.y.qq.com/cgi-bin/musicu.fcg',
				'body'   => array(
					'format'	  => 'json',
					'platform'	=> 'yqq.json',
					'needNewCode' => 0,
					'data'		=> json_encode($payload),
				),
			);
			$response = json_decode($this->exec($api), true);
			// print_r($payload);
			// print_r($response);
			$vkeys = isset($response['req_0']['data']['midurlinfo']) ? $response['req_0']['data']['midurlinfo'] : null;
			$index = isset($type[($this->info['br'] - 1)]) ? ($this->info['br'] - 1) : 4;
			foreach(range($index, 7) as $i)
			{
				$vo = $type[$i];
				$url = null;
				if (isset($data['data'][0]['file'][$vo[0]]))
				{
					if (!empty($vkeys[$i]['vkey']))
					{
						$url = array(
							'url' => "http://y.qq.com/n/yqq/song/{$this->info['mid']}.html",
							'music'  => $response['req_0']['data']['sip'][0].$vkeys[$i]['purl'],
							'size' => $data['data'][0]['file'][$vo[0]],
							'br'   => $vo[1],
						);
						break;
					}
				}
			}
			if($url)
			{
				if(isset($this->array['vs']))
				{
					$singer = [];
					foreach($this->array['singer'] as $v)
					{
						$singer[] = $v['name'];
					}
					$result = [
						'code'=>1,
						'text'=>'获取成功',
						'data'=>[
							'song'=>$this->array['name'],
							'mid'=>$this->info['mid'],
							'songid'=>$this->info['songid'],
							'album'	=> trim($this->array['album']['name']),
							'url'=>"http://y.qq.com/n/yqq/song/{$this->info['mid']}.html",
							'picture'=>$cover,
							'singer'=>join('、', $singer),
							'singers'=>$singer,
							'music'=>$url['music'],
							'size'=>$url['size'],
							'br'=>$url['br']
						]
					];
				} else {
					$singer = [];
					foreach($data['data'][0]['singer'] as $v)
					{
						$singer[] = $v['name'];
					}
					$result = [
						'code'=>1,
						'text'=>'获取成功',
						'data'=>($this->array && $url) ? array_merge($this->array, $url) : [
							'songid'	   => $data['data'][0]['id'],
							'song'	 => $data['data'][0]['name'],
							'singers'   => $singer,
							'singer'   => join('、', $singer),
							'album'	=> trim($data['data'][0]['album']['name']),
							'mid'   => $data['data'][0]['mid'],
							'picture' => 'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['data'][0]['album']['mid'].'.jpg?max_age=2592000',
							'music'=>$url['music'],
							'size'=>$url['size'],
							'br'=>$url['br']
						]
					];
				}
				
				$message = "±img={$cover}±\n歌曲名：{$result['data']['song']}\n歌手：{$result['data']['singer']}\n播放链接：{$result['data']['music']}";
				return $this->result($result, $message);
			} else {
			/*
				if(isset($type[$this->info['br']]) && $type[$this->info['br']])
				{
					$this->info['br'] = ($this->info['br'] + 1);
					return $this->getMusic();
				} else {
				*/
					return $this->result(['code'=>-4, 'text'=>"1、Cookie可能失效了，建议使用自己的Cookie\n2、不支持这种音质\n3、母带不能听是因为没钱买超级会员。"]);
				// }
			}
		}
	}
	public function result($array, $message = null)
	{
		if(!$message) $message = $array['text'];
		switch($this->info['type'])
		{
			case 'text':
				need::send($message, 'text');
			break;
			case 'json':
				if(isset($array['data']['music']) && $array['data']['music'])
				{
					need::send('json:{"app":"com.tencent.qzone.structmsg","desc":"QQ音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$array['data']['song'].'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"ctime":1646799816,"desc":"'.$array['data']['singer'].'","jumpUrl":"https://y.qq.com/n/ryqq/songDetail/'.$this->info['mid'].'","musicUrl":"'.$array['data']['music'].'","preview":"'.$array['data']['picture'].'","sourceMsgId":"0","source_icon":"https://p.qpic.cn/qqconnect/0/app_100497308_1626060999/100?max-age=2592000&t=0","source_url":"http://ovooa.com/","tag":"'.$this->info['tail'].'","title":"'.$array['data']['song'].'","uin":2354452553}},"config":{"ctime":'.Time().',"forward":true,"token":"549b5afa08722eace91fdf1334a0a8c3","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":2354452553}"}', 'text');
				} else {
					need::send($array, 'json');
				}
			break;
			default:
				need::send($array, 'json');
			break;
		}
	}
}