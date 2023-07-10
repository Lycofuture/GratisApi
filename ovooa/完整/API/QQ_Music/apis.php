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
$n = isset($request['n']) ? $request['n'] : false;
$type = isset($request['type']) ? $request['type'] : 'j';
new QQ_Music(['msg'=>$msg, 'songid'=>$songid, 'mid'=>$mid, 'page'=>$page, 'limit'=>$limit, 'tail'=>$tail, 'br'=>$br, 'n'=> $n, 'type'=>$type]);
class QQ_Music
{
	public $info = [];
	public $array = [];
	public $message, $data, $raw, $error;
	public $header = array(
				'Referer'		 => 'http://y.qq.com',
				'Cookie'		  => null,
				'User-Agent'	  => 'QQ%E9%9F%B3%E4%B9%90/54409 CFNetwork/901.1 Darwin/17.6.0 (x86_64)',
				'Accept'		  => '*/*',
				'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
				'Connection'	  => 'keep-alive',
				'Content-Type'	=> 'application/x-www-form-urlencoded',
	);
	public function __construct($array)
	{
		$this->info = $array;
		$this->parameterException();
	}
	public function parameterException()
	{
		$info = $this->info;
		if(isset($this->info['Cookie']))
		{
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
		$this->curl($api['url'], $api['body']);
		$this->data = $this->raw;
		// print_r($api);
		if (isset($api['decode'])) {
			$this->data = call_user_func_array(array($this, $api['decode']), array($this->data));
		}
		if (isset($api['format'])) {
			$this->data = $this->clean($this->data, $api['format']);
		}
		return $this->data;
	}
	public function curl($url, $payload = null, $headerOnly = 0)
	{
		$header = array_map(function ($k, $v) {
			return $k.': '.$v;
		}, array_keys($this->header), $this->header);
		// print_r($url);
		$curl = curl_init();
		if (!is_null($payload)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($payload) ? http_build_query($payload) : $payload);
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
		// curl_setopt($curl, CURLOPT_REFERER, $this->header['Referer']);
		// curl_setopt($curl, CURLOPT_COOKIE, $this->header['Cookie']);
		/*if ($this->proxy) {
			curl_setopt($curl, CURLOPT_PROXY, $this->proxy);
		}*/
		// print_r(curl_exec($curl));
		for ($i = 0; $i < 3; $i++) {
			$this->raw = curl_exec($curl);
			$this->error = curl_errno($curl);
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
		return $result;
	}
	/*
	* 通过名字获取列表以及音乐信息
	*/
	public function getName()
	{
		$api = array(
			'method' => 'GET',
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
		$data = $this->exec($api);
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
			return $this->result(['code'=>-3, 'text'=>'出现了意想不到的错误，请重试']);
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
			/*
			$type =array();
			foreach(range(0, 9) as $v)
			{
				foreach(range('0', '9') as $val)
				{
					$type[] = ['size_try', 960877, "Q{$v}{$val}0", 'ogg'];
				}
			}*/
			$type = array(
				// array('try_end', 102200, 'Q0M0', 'flac'),
				array('try_begin', 76592, 'Q0M0', 'ogg'),
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
			print_r($response);exit;
			$vkeys = $response['req_0']['data']['midurlinfo'];
			$index = isset($type[($this->info['br'] - 1)]) ? ($this->info['br'] - 1) : 4;
			// print_r($this->header);
			$vo = $type[$index];
			$url = null;
			if (isset($data['data'][0]['file'][$vo[0]]))
			{
				if (!empty($vkeys[$index]['vkey']))
				{
					$url = array(
						'url' => "http://y.qq.com/n/yqq/song/{$this->info['mid']}.html",
						'music'  => $response['req_0']['data']['sip'][0].$vkeys[$index]['purl'],
						'size' => $data['data'][0]['file'][$vo[0]],
						'br'   => $vo[1],
					);
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
				return $this->result(['code'=>-4, 'text'=>"1、Cookie可能失效了，建议使用自己的Cookie\n2、不支持这种音质\n3、母带不能听是因为没钱买超级会员。"]);
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