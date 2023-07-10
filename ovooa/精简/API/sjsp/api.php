<?php 
Header('content-type: application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(44); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件

/* End */

$name = @$_REQUEST["msg"];
$type = @$_REQUEST["type"];

new rand_video(['msg'=>$name, 'type'=>$type]);

class rand_video
{
	public $id = array(
		"网红" => "5930e061e7bce72ce01371ae",
		"明星" => "5930e046e7bce72ce013719c",
		"热舞" => "5930e081e7bce72ce01371c8",
		"风景" => "5930e16ee7bce72ce013725f",
		"游戏" => "5930e009e7bce72ce0137170",
		"动物" => "5930e22ee7bce72ce01372f3",
		'动漫'=>'5930e065e7bce72ce01371b1'
	);
	public $skip = array(
		"网红" => 1000,
		"明星" => 850,
		"热舞" => 470,
		"风景" => 800,
		"游戏" => 1100,
		"动物" => 300,
		'动漫'=> 2800
	);
	public $info = [];
	public $array = [];
	public $message;
	public function __construct($array)
	{
		foreach($array as $k=>$v) $this->info[$k] = $v;
		$this->parameterException();
	}
	public function parameterException()
	{
		if(!isset($this->info['msg']) || !$this->info['msg'] || !isset($this->id[$this->info['msg']]))
		{
			$this->info['msg'] = '动漫';
		}
		return $this->start();
	}
	public function start()
	{
		$skip = mt_rand(0, $this->skip[$this->info['msg']]);
		$url = 'https://service.videowp.adesk.com/v1/videowp/category/'.$this->id[$this->info['msg']].'?limit=30&skip='.$skip.'&adult=false&first=0&order=hot';
		// echo $url;
		$data = json_decode(need::teacher_curl($url, [
			'Header'=>[
				'Accept-Language'=>'zh-CN,zh;q=0.8',
    			'User-Agent'=>'132,tencent',
    			'Session-Id'=>' ',
    			'Host'=>'service.videowp.adesk.com',
    			'Connection'=>'Keep-Alive',
    			'Accept-Encoding'=>'gzip'
			],
			'ua'=>'132,tencent'
		]), true);
		if(!$data || !$data['res']['videowp'])
		{
			return $this->exec(['code'=>-1, 'text'=>'获取失败，未知错误']);
		} else {
			$data = $data['res']['videowp'];
			$rand = array_rand($data, 1);
			$data = $data[$rand];
			// print_r($data);
			$tag = (isset($data['tag']) && $data['tag'] ?'#'.join(' #', explode('	', $data['tag'])) : '#'.$data['name']);
			$url = $data['video'];
			$cover = $data['img'];
			return $this->exec(['code'=>1, 'text'=>'获取成功', 'data'=>['img'=>$cover, 'mold'=>$tag, 'url'=>$url]], "±img={$cover}±\n{$tag}\n{$url}");
		}
	}
	public function exec($array, $message = null)
	{
		$message = !$message ? $array['text'] : $message;
		$this->array = $array;
		$this->message = $message;
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
		return true;
	}
}

?>
