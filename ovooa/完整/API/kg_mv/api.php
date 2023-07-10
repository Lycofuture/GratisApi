<?
header('content-type: application/json;');
/* Start */
require (__DIR__."/../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(65); // 调用统计函数
require __DIR__.'/../../need.php';
$request = need::request();
// print_r($request);
$msg = isset($request['msg']) ? $request['msg'] : false;
$n = isset($request['n']) ? $request['n'] : false;
$page = isset($request['page']) ? $request['page'] : 1;
$limit = isset($request['limit']) ? $request['limit'] : 10;
$type = isset($request['type']) ? $request['type'] : 'json';
new kg_mv(['msg'=>$msg, 'n'=>$n, 'page'=>$page, 'limit'=>$limit, 'type'=>$type]);
class kg_mv
{
	private $info = [];
	public $array = [];
	public $message;
	public function __construct(array $array)
	{
		foreach($array as $k=>$v)
		{
			$this->info[$k] = $v;
		}
		$this->parametersexception();
	}
	public function parametersexception()
	{
		$info = $this->info;
		if(!isset($info['msg']) || !$info['msg']) return $this->exec(['code'=>-1, 'text'=>'请输入需要搜索的名字']);
		if(isset($info['limit']))
		{
			if(!is_numeric($info['limit']) || $info['limit'] < 1 || $info['limit'] > 30) $info['limit'] = 10;

		}
		if(isset($info['n']))
		{
			if(!is_numeric($info['n']) || $info['n'] < 1 || $info['n'] > $info['limit']) $info['n'] = 0;
		}
		if(isset($info['page']))
		{
			if(!is_numeric($info['page']) || $info['page'] < 1) $info['page'] = 1;
		}
		$this->info = $info;
		$this->start();
		return true;
	}
	public function start()
	{
		$msg = $this->info['msg'];
		$page = $this->info['page'];
		$limit = $this->info['limit'];
		$n = $this->info['n'];
		if(!$n)
		{
			$url = 'http://mvsearch.kugou.com/mv_search?page='.$page.'&pagesize='.$limit.'&userid=-1&clientver=&platform=WebFilter&tag=em&filter=10&iscorrection=1&privilege_filter=0&keyword='.urlencode($msg);
			$data = json_decode(strip_tags(need::teacher_curl($url)), true);
			if(isset($data['data']['lists']) && ($data = $data['data']['lists']))
			{
				// print_r($data);exit;
				$array = [];
				$string = null;
				foreach($data as $k=>$v)
				{
					$array[] = ['name'=>$v['MvName'], 'singer'=>$v['SingerName'], 'cover'=>'http://imge.kugou.com/mvhdpic//'.substr($v['Pic'], 0, 8).'/'.$v['Pic']];
					$string .= ($k+1).'、'.$v['MvName'] . ' · '.$v['SingerName']."\n";
				}
				return $this->exec(['code'=>1, 'text'=>'获取成功', 'data'=>$array], $string);
			} else {
				return $this->exec(['code'=>-2, 'text'=>'获取失败，可能是接口坏了，或者换个名字搜。']);
			}
		} else {
			$url = 'http://mvsearch.kugou.com/mv_search?page='.$page.'&pagesize='.$limit.'&userid=-1&clientver=&platform=WebFilter&tag=em&filter=10&iscorrection=1&privilege_filter=0&keyword='.urlencode($msg);
			$data = json_decode(strip_tags(need::teacher_curl($url)), true);
			if(isset($data['data']['lists']) && ($data = $data['data']['lists']))
			{
				$n = ($n - 1);
				if(!isset($data[$n]) || !$data[$n])
				{
					$this->info['n'] = 0;
					return $this->start();
				} else {
					$data = $data[$n];
					$hash = $data['MvHash'];
					$url = 'http://m.kugou.com/app/i/mv.php?cmd=100&hash='.$hash.'&ismp3=1&ext=mp4';
					$data = json_decode(need::teacher_curl($url, [
						'refer'=>'https://www.kugou.com/webkugouplayer/?isopen=0&chl=yueku_index',
						'ua'=>'Mozilla/5.0 (Linux; Android 9; 16s Build/PKQ1.190202.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 MQQBrowser/6.2 TBS/045140 Mobile Safari/537.36 V1_AND_SQ_8.3.5_1392_YYB_D QQ/8.3.5.4555 NetType/WIFI WebP/0.3.0 Pixel/1080 StatusBarHeight/72 SimpleUISwitch/0 QQTheme/1000'
					]), true);
					if(isset($data['error']) && $data['error'])
					{
						return $this->exec(['code'=>-3, 'text'=>$data['error']]);
					} else {
																// print_r($data);exit;
						$mv = (isset($data['mvdata']['sq']['downurl']) ? $data['mvdata']['sq']['downurl'] : (isset($data['mvdata']['le']['downurl']) ? $data['mvdata']['le']['downurl'] : (isset($data['mvdata']['rq']['downurl']) ? $data['mvdata']['rq']['downurl'] : false)));
						$cover = str_replace('{size}', '', $data['mvicon']);
						$name = $data['songname'];
						$singer = $data['singer'];
						$_singer = [];
						foreach($data['authors'] as $s)
						{
							$_singer[] = $s['author_name'];
						}
						$string = "±img={$cover}±\nMV名字：{$name}\n作者：{$singer}\n播放：{$mv}";
						$array = ['code'=>1, 'text'=>'获取成功', 'data'=>['name'=>$name, 'singer'=>$singer, '_singer'=>$_singer, 'cover'=>$cover, 'url'=>$mv]];
						return $this->exec($array, $string);
					}
				}
			}
		}
	}
	public function exec($array, $message = null)
	{
		$this->message = $message ? $message : $array['text'];
		$this->array = $array;
		return $this->result();
	}
	public function result()
	{
		$info = $this->info;
		$type = isset($info['type']) ? $info['type'] : 'json';
		Switch($type)
		{
			case 'text':
			need::send($this->message, 'text');
			break;
			default:
			need::send($this->array, 'json');
			break;
		}
		// print_r($this->array);
		return true;
	}
}