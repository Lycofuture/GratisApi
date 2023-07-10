<?
header('Content-type: application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(16); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../need.php');//引入bkn文件
$request = need::request();
$msg = isset($request['msg']) ? $request['msg'] : null;
$limit = isset($request['limit']) ? $request['limit'] : null;
$type = isset($request['type']) ? $request['type'] : null;
$n = isset($request['n']) ? $request['n'] : null;
$format = isset($request['format']) ? $request['format'] : 'lrc';

new kggc(['msg'=>$msg, 'limit'=>$limit, 'type'=>$type, 'n'=>$n, 'format'=>$format]);

class kggc
{
	public function __construct(public $info)
	{
		$this->parameterException();
	}
	private function parameterException()
	{
		$info = $this->info;
		if(!isset($info['msg']) || !$info['msg']) return $this->result(['code'=>-1, 'text'=>'请输入需要搜索的名字']);
		if(!isset($info['page']) || !is_numeric($info['page']) || $info['page'] < 1) $info['page'] = 1;
		$format = ['lrc', 'krc', 'ksc'];
		if(!in_array($info['format'], $format)) $info['format'] = 'lrc';
		$this->info = $info;
		return $this->start();
	}
	private function start()
	{
		$info = $this->info;
		$msg = ($info['msg']);
		$format = $info['format'];
		$fmt = ($format == 'krc' || $format == 'ksc' ? 'krc' : 'lrc');
		$n = is_numeric($info['n']) ? $info['n'] - 1 : -1;
		$api = array(
			'method' => 'GET',
			'url'	=> 'http://mobilecdn.kugou.com/api/v3/search/song',
			'body'   => array(
				'api_ver'   => 1,
				'area_code' => 1,
				'correct'   => 1,
				'pagesize'  => isset($info['limit']) ? $info['limit'] : 30,
				'plat'	  => 2,
				'tag'	   => 1,
				'sver'	  => 5,
				'showtype'  => 10,
				'page'	  => isset($info['page']) ? $info['page'] : 1,
				'keyword'   => $msg,
				'version'   => 8990,
			),
		);
		$data = $this->exec($api);
		if($data)
		{
			if(isset($data['data']['info']) && $data['data']['info'])
			{
				$data = $data['data']['info'];
				if($n < 0 || !isset($data[$n]) || !$data[$n])
				{
					$message = null;
					$array = [];
					foreach($data as $k=>$v)
					{
						$name = $v['filename'];
						$singer = $v['singername'];
						$hash = $v['hash'];
						$array[] = ['name'=>$name, 'singer'=>$singer, 'music'=>$v['songname'], 'hash'=>$hash];
						$message .= ($k + 1) ."、{$name}\n";
					}
					$message = trim($message);
				} else {
					$data = $data[$n];
					$hash = $data['hash'];
					$api = array(
						'method' => 'GET',
						'url'	=> 'http://krcs.kugou.com/search',
						'body'   => array(
							'keyword'  => '%20-%20',
							'ver'	  => 1,
							'hash'	 => $hash,
							'client'   => 'mobi',
							'man'	  => 'yes',
						)
					);
					$data = $this->exec($api);
					if(isset($data['candidates']) && $data['candidates'])
					{
						$data = $data['candidates'];
						$api = array(
							'method' => 'GET',
							'url'	=> 'http://lyrics.kugou.com/download',
							'body'   => array(
								'charset'   => 'utf8',
								'accesskey' => $data[0]['accesskey'],
								'id'		=> $data[0]['id'],
								'client'	=> 'mobi',
								'fmt'	   => $fmt,
								'ver'	   => 1,
							)
						);
						$data = $this->exec($api);
						if(isset($data['content']) && $data['content'])
						{
							$content = str_replace("\r\n", "\n", base64_decode($data['content']));
							$content = trim(($format == 'krc' ? $this->krc($content) : ($format == 'ksc' ? $this->krc2ksc($this->krc($content)) : $content)));
							// echo $content;
							return $this->result(['code'=>1, 'text'=>'获取成功', 'data'=>[
								'content'=>$content,
								'base64'=>base64_encode($content)
							]], $content);
						} else {
							return $this->result(['code'=>-3, 'text'=>(isset($data['info']) ? $data['info'] : '未知错误')]);
						}
					} else {
						return $this->result(['code'=>-2, 'text'=>'获取失败：'.$data['errormsg']]);
					}
				}
			} else {
				return $this->result(['code'=>-4, 'text'=>'获取失败，该歌曲不存在。']);
			}
		} else {
			return $this->result(['code'=>-5, 'text'=>'未知错误，您可以重试看看。']);
		}
	}
	private function exec($api)
	{
		if(isset($api['method']) && $api['method'] == 'GET')
		{
			$api['url'] = $api['url'] . '?' . http_build_query($api['body']);
			$api['body'] = null;
		} else {
			if(!isset($api['json']) || $api['json'] !== true)
			{
				$api['body'] = http_build_query($api['body']);
			}
		}
		$data = need::teacher_curl($api['url'], [
			'post'=>$api['body']
		]);
		if($result = json_decode($data, true))
		{
			return $result;
		} else {
			return $data;
		}
	}
	private function krc($Content)
	{
		$enKey = array(64, 71, 97, 119, 94, 50, 116, 71, 81, 54, 49, 45, 206, 210, 110, 105);
		$krc_content = substr($Content, 4);
		$len = strlen($Content);
		$krc_compress = '';
		for ($k = 0; $k < $len; $k++){
			$krc_compress .= chr(ord($krc_content[$k]) ^ $enKey[$k % 16]);
		}
		return str_replace("\r\n", "\n", gzuncompress($krc_compress));
	}
	private function krc2ksc($krc) {
		$ksc = '';
		$krc_lines = explode("\n", $krc);
		$yes = false;
		foreach($krc_lines as $v)
		{
			if(preg_match('/\[([0-9]+),([0-9]+)\]/', $v, $outtime))
			{
				preg_match_all('/<([0-9]+),([0-9]+),([0-9]+)>([^<\n]*)/u', $v, $out);
				$start = date('i:s', intval(($outtime[1] - $outtime[1] % 1000)) / 1000);
				$outtime[2] = ($outtime[1] + $outtime[2]);
				$stop = date('i:s', intval(($outtime[2] - $outtime[2] % 1000)) / 1000);
				$ksc_time = $msg = [];
				$ksc .= "Music.Ovooa.Com.Ksc('";
				$ksc .= $start;
				$ksc .= '.';
				$ksc .= $outtime[1] % 1000;
				$ksc .= "','";
				$ksc .= $stop;
				$ksc .= '.';
				$ksc .= $outtime[2] % 1000;
				$ksc .= "','";
				foreach($out[2] as $k=>$val)
				{
					/*$out1 = $val; //词开始时间
					$out2 = $out[2][$k]; //词使用时间
					$out3 = $out[3][$k]; //词不知道什么时间
					*/
					$msg[] = $out[4][$k]; //词本体
					$ksc_time[] = $val;
				}
				$ksc .= join('', $msg)."', '";
				$ksc .= join(',', $ksc_time)."');\n";
				// print_r($out);exit;
			}
		}
		return trim(($ksc));
	}
	public function result($array, $message = null)
	{
		$message = $message ? $message : $array['text'];
		return match($this->info['type']) {
			'text' => need::send($message, 'text'),
			default => need::send($array, 'json')
		};
	}
}



