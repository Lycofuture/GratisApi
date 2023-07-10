<?php
Header('Content-type: Application/json; charset=utf-8');
require '../../need.php';
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(168); // 调用统计函数
$Request = need::Request();
$type = isset($Request['type']) ? $Request['type'] : 'json'; //获取返回格式 默认json
$url = isset($Request['url']) ? $Request['url'] : '';

new icp(['url'=>$url, 'type'=>$type]);

class icp
{
	private $info = [];
	private $array = [];
	private $Message;
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
	private function parameterException()
	{
		/* 判断参数是否正常 */
		if(!stristr($this->info['url'], 'http'))
		{
			$this->info['url'] = 'http://'.$this->info['url'];
		}
		$host = parse_url($this->info['url'], PHP_URL_HOST);
		if(!$host)
		{
			$this->evaluation(array('code'=>-1, 'text'=>'请输入正确的域名'));
			return;
		}
		$this->run(); //调用方法
		return true;
	}
	private function Run()
	{
		$scheme = @parse_url($this->info['url'], PHP_URL_SCHEME);
		$host = @parse_url($this->info['url'], PHP_URL_HOST);
		$url = $scheme.'://'.$host;
		$data = json_decode(trim(preg_replace('/([a-z]+):/i', '"$1":', preg_replace('/jQuery\((.*)\)/', '$1', need::teacher_curl('https://micp.chinaz.com/Handle/AjaxHandler.ashx?action=GetPermit&callback=jQuery&query='.$host.'&type=host&_=1672673081420')))));
		// print_r($data);
		if(!isset($data->Typ))
		{
			$this->evaluation(array('code'=>-2, 'text'=>'备案数据获取失败'));
			return;
		}
		$String = '';
		$array = array(
			'unit'=>$data->ComName, 
			'property'=>$data->Typ, 
			'record'=>$data->Permit, 
			'hostname'=>$data->WebName, 
		);
		$String = "主办单位：{$data->ComName}\n单位性质：{$data->Typ}\n网站名字：{$data->WebName}\n网站备案号：{$data->Permit}";
		$String = trim($String, PHP_EOL);
		$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>$array), $String);
		return;
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
	private function result()
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
}




