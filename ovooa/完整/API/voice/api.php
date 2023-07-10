<?
header('Content-type: application/json; charset=utf-8');
require_once __DIR__ . '/../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(178); // 调用统计函数
addAccess();//调用统计函数
$request = need::request();
$format = isset($request['format']) ? $request['format'] : 'sound';
$rand = isset($request['rand']) ? $request['rand'] : 'false';
$page = isset($request['page']) ? $request['page'] : 1;
$limit = isset($request['limit']) ? $request['limit'] : 10;
$id = isset($request['id']) ? $request['id'] : null;
$n = isset($request['n']) ? $request['n'] : null;
$type = isset($request['type']) ? $request['type'] : false;

new voice(['format'=>$format, 'rand'=>$rand, 'page'=>$page, 'limit'=>$limit, 'id'=>$id, 'n'=>$n, 'type'=>$type]);
class voice
{
	public $dir, $message;
	public function __construct(public $info)
	{
		$this->dir = need::read_all_dir(__DIR__. '/cache/');
		$this->parametersException();
	}
	public function parametersException()
	{
		$info = $this->info;
		if(!in_array($info['format'], ['list', 'sound', 'info']))
		{
			$info['format'] = 'sound';
		}
		if(!isset($this->info['page']) || !$info['page'] || !is_numEric($info['page']) || $info['page'] < 1)
		{
			$info['page'] = 1;
		}
		if(!isset($this->info['limit']) || !$info['limit'] || !is_numEric($info['limit']) || $info['limit'] < 1)
		{
			$info['limit'] = 10;
		}
		if(isset($info['n']) && $info['n'])
		{
			if(!is_numEric($info['n']) || $info['n'] < 1)
			{
				$info['n'] = null;
			}
		}
		$this->info = $info;
		$format = $this->info['format'];
		return $this->$format();
	}
	public function sound()
	{
		if(!isset($this->info['id']) || !$this->info['id'])
		{
			$dir = $this->dir[array_rand($this->dir)];
		} else {
			$dir = isset($this->dir[$this->info['id']]) ? $this->dir[$this->info['id']] : $this->dir[array_rand($this->dir)];
		}
		if(is_dir($dir['path']))
		{
			$data = need::read_all($dir['path']);
			if(isset ($this->info['n']) && isset ($data[$this->info['n']]))
			{
				$name = $data[$this->info['n']]['name'];
			} else {
				if(isset($this->info['rand']) && $this->info['rand'] == 'true')
				{
					$name = $data[array_rand($data)]['name'];
				} else {
					$page = $this->info['page'];
					$limit = $this->info['limit'];
					$start = (($page - 1) * $limit);
					// $end = ($page * $limit);
					$data = array_slice($data, $start, $limit);
					$array = ['code'=>1, 'text'=>'获取成功'];
					$message = [];
					foreach($data as $k=>$v)
					{
						$array['data'][] = $v['name'];
						$message[] = ($k + 1) . "、{$v['name']}";
					}
					return $this->result($array, join("\n", $message));
				}
			}
			$dir = $dir['name'];
			$sound = "http://ovooa.com/API/voice/cache/${dir}/${name}";
			return $this->result(['code'=>1, 'text'=>'获取成功', 'data'=>['url'=>$sound]], $sound);
		} else {
			return $this->result(['code'=>-1, 'text'=>'获取失败，请联系后台']);
		}
	}
	public function list()
	{
		$page = $this->info['page'];
		$limit = $this->info['limit'];
		$start = (($page - 1) * $limit);
		// $end = ($page * $limit);
		$data = array_slice($this->dir, $start, $limit);
		$array = ['code'=>1, 'text'=>'获取成功'];
		$message = [];
		foreach($data as $k=>$v)
		{
			$array['data'][] = $v['name'];
			$message[] = ($k + 1) . "、{$v['name']}";
		}
		// print_r($message);exit;
		return $this->result($array, join("\n", $message));
	}
	public function result($array, $message = null)
	{
		$message = (!$message && isset($array['text']) ? $array['text'] : $message);
		switch($this->info['type'])
		{
			case 'text':
				need::send($message, 'text');
			break;
			case 'voice':
				if(isset($array['data']['url']))
				{
					header('Content-type: audio/'.end(explode('.', $array['data']['url'])));
					echo need::teacher_curl($array['data']['url']);
					exit();
				} else {
					need::send($message, 'text');
				}
			break;
			default:
				need::send($array, 'json');
			break;
		}
	}
}