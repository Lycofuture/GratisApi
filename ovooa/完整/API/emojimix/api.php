<?
header('content-type: application/json;');
require __DIR__.'/../../need.php';
/* Start */

require (__DIR__."/../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(173); // 调用统计函数*/
$request = need::request();
// print_r($request);
$emoji1 = isset($request['emoji1']) ? $request['emoji1'] : false; //获取竖屏还是横屏或者视频
$emoji2 = isset($request['emoji2']) ? $request['emoji2'] : false; //分类
$type = isset($request['type']) ? $request['type'] : 'json'; //返回格式
new emojimix(['emoji1'=>$emoji1, 'emoji2'=>$emoji2, 'type'=>$type]);
class emojimix
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
		preg_match('/./u', $info['emoji1'], $emoji1);
		preg_match('/./u', $info['emoji2'], $emoji2);
		if(!$info['emoji1'] || !isset($emoji1[0]) || !isset($emoji2[0]) || strlen($emoji1[0]) < 4 || strlen($emoji2[0]) < 4) return $this->exec(['code'=>-1, 'text'=>'请输入正确的emoji']);
		$this->start();
		return true;
	}
	public function start()
	{
		$time = [20201001, 20211115, 20210218, 20220823, 20220815, 20220406, 20210831, 20210521, 20220203, 20220110, 20221101, 20201001];
		$cache = json_decode($this->get(), true);
		$error = json_decode($this->get(__DIR__.'/cache/error.json'), true);
		// print_r($error);
		$emoji1 = $this->info['emoji1'];
		$emoji2 = $this->info['emoji2'];
		$emoji1 = need::emoji2utf($emoji1);
		$emoji2 = need::emoji2utf($emoji2);
		if(isset($error[$emoji1.$emoji2]))
		{
			return $this->exec(['code'=>-2, 'text'=>'这两个emoji不支持合成']);
		} else if(isset($cache[$emoji1.$emoji2]))
		{
			return $this->exec(['code'=>1, 'text'=>'获取成功', 'data'=>['url'=>$cache[$emoji1.$emoji2]]], $cache[$emoji1.$emoji2]);
		} else {
			foreach($time as $v)
			{
				$url = "https://www.gstatic.com/android/keyboard/emojikitchen/{$v}/{$emoji1}/{$emoji1}_{$emoji2}.png";
				$url2 = "https://www.gstatic.com/android/keyboard/emojikitchen/{$v}/{$emoji2}/{$emoji2}_{$emoji1}.png";
				if(need::teacher_curl($url, ['nobody'=>true, 'GetCookie'=>true])['code'] == 200)
				{
					$cache[$emoji1.$emoji2] = $url;
					$cache[$emoji2.$emoji1] = $url;
					$this->set(__DIR__.'/cache/data.json', json_encode($cache, 460));
					return $this->exec(['code'=>1, 'text'=>'获取成功', 'data'=>['url'=>$url]], $url);
				} else if(need::teacher_curl($url2, ['nobody'=>true, 'GetCookie'=>true])['code'] == 200) {
					$cache[$emoji1.$emoji2] = $url2;
					$cache[$emoji2.$emoji1] = $url2;
					$this->set(__DIR__.'/cache/data.json', json_encode($cache, 460));
					return $this->exec(['code'=>1, 'text'=>'获取成功', 'data'=>['url'=>$url2]], $url2);
				}
			}
			$error[$emoji1.$emoji2] = false;
			$error[$emoji2.$emoji1] = false;
			$this->set(__DIR__.'/cache/error.json', json_encode($error, 460));
			return $this->exec(['code'=>-2, 'text'=>'这两个emoji不支持合成']);
		}
	}
	public function dir($path){
		/* 判断文件夹是否存在，不存在则创建 */
		$Array = explode('/', $path);
		$paths = '';
		unset($Array[(count($Array) -1)]);
		foreach($Array as $v){
			$paths .= $v.'/';
		}
		if(!is_dir($paths)){
			mkdir($paths, 0755, true);
		}
		return;
	}
	public function set($path, $string)
	{
		/* 储存文件 */
		$this->dir($path);
		if(file_put_Contents($path, $string))
		{
			return true;
		}
		return false;
	}
	public function get($get = __DIR__.'/cache/data.json')
	{
		if(file_exists($get))
		{
			return @file_get_contents($get);
		} else {
			return '{}';
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