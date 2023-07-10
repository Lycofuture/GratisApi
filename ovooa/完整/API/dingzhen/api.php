<?php
/* 这是一个Api demo */
Header('Content-type: Application/Json; charset=utf-8');
require '../../need.php';

$Request = need::Request(); //获取所有get post请求;
$type = isset($Request['type']) ? $Request['type'] : 'json'; //获取返回格式 默认json
echo new dingzhen(array('type'=>$type));
class dingzhen
{
	/* Api类 
	* 更好的维护
	*/
	private $info = array(); //储存请求数据
	private $array = array(); //储存返回数据
	private $Message; //储存返回文本
	private $dir = __DIR__ . '/'; //本文件所在文件夹
	private $class = __CLASS__; //本类类名
	public function __construct(array $array)
	{
		/* php构造函数 类初始化时自动运行 */
		foreach($array as $k=>$v)
		{
			/* for循环获取参数并赋值到类中使用 */
			$this->info[$k] = $v;
		}
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
	private function run() : bool 
	{
		$file_all = need::read_all(__DIR__.'/cache', 'jpg', 'jpeg', 'png', 'gif');
		$rand = array_rand($file_all, 1);
		$file = $file_all[$rand];
		$this->result($file);
		return true;
	}
	private function result($file) : bool
	{
		$info = $this->info;
		// print_r($file);exit;
		header('content-type: image/'.$file['suffix']);
		echo @file_get_contents($file['file']);
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