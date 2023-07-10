<?php
/* 这是一个Api demo */
Header('Content-type: Application/Json; charset=utf-8');
require '../../need.php';

$Request = need::Request(); //获取所有get post请求;
$type = isset($Request['type']) ? $Request['type'] : 'json'; //获取返回格式 默认json
class cosplay
{
	/* Api类 
	* 更好的维护
	*/
	private $info = array(); //储存请求数据
	private $array = array(); //储存返回数据
	private $Message; //储存返回文本
	private $dir = __DIR__ . '/'; //本文件所在文件夹
	private $class = __CLASS__; //本类类名
	public $d = ['cosplay0.txt', 'cosplay1.txt', 'cosplay2.txt', 'cosplay3.txt', 'cosplay4.txt', 'cosplay5.txt', 'cosplay6.txt', 'cosplay7.txt', 'coscache1.json', 'coscache2.json', 'coscache3.json', 'coscache4.json', 'coscache5.json', 'coscache6.json', 'coscache7.json', 'coscache8.json', 'coscache9.json', 'coscache10.json', 'coscache11.json', 'coscache12.json', 'coscache13.json'];
	// public $d = [7];
	private $rand ;
	public function __construct(array $array)
	{
		/* php构造函数 类初始化时自动运行 */
		foreach($array as $k=>$v)
		{
			/* for循环获取参数并赋值到类中使用 */
			$this->info[$k] = $v;
		}
		$this->rand = $this->d[array_rand($this->d)];
		$this->parameterException(); //调用类中方法
	}
	private function parameterException() :  bool
	{
		/* 判断参数是否正常 */
		$this->run(); //调用方法
		return true;
	}
	private function delete() : bool
	{
		/* 删除已有参数 */
		unset($this->array, $this->Message);
		return true;
	}
	private function evaluation(array $array, $string = false) : bool
	{
		/* 输出数据 */
		$string = $string ? $string : $array['text']; //判断$string是否存在
		$this->delete(); //调用删除方法
		$this->array = $array; //赋值
		$this->Message = $string; //赋值
		$this->result(); //输出内容 
		return true;
	}
	private function run() : bool 
	{
		$data = file(__DIR__.'/data/' . $this->rand . '');
		$Rand = array_rand($data, 1);
		$data = json_decode($data[$Rand], True);
		$array = [];
		foreach($data['images'] as $v)
		{
			$array[] = isset($v['path']) ? $v['path'] : $v;
		}
		$dataarray = array(
			'title'=>$data['title'],
			'images'=>$array,
			'cosplay_file_id'=>array_search($this->rand, $this->d)
		);
		// print_r($array);
		$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>$dataarray), $array[array_rand($array, 1)]);
		return true;
	}
	private function result() : bool
	{
		$info = $this->info;
		$type = isset($info['type']) ? $info['type'] : 'json';
		Switch($type)
		{
			case 'text':
			need::send($this->Message, 'text');
			break;
			default:
			need::send($this->array, 'json');
			break;
		}
		return true;
	}
	public function __toString() : string
	{
		/* php魔术函数 直接echo类时调用 */
		return json_encode($this, JSON_FORCE_OBJECT);
	}
	public function __destruct()
	{
		/* php析构函数 类运行结束时调用 */
	}
}
new cosplay(array('type'=>'json'));
