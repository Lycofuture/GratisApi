<?php
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(166); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件
/* End */
$Request = need::Request();
$string = isset($Request['string']) ? $Request['string'] : false;
$format = isset($Request['format']) ? $Request['format'] : 'encode';
$type = isset($Request['type']) ? $Request['type'] : false;

class Punycode
{
	private $Message;
	private $array = array();
	private $info = array();
	public function __construct(array $array)
	{
		foreach($array as $k=>$v)
		{
			$this->info[$k] = $v;
		}
		$this->parameterException();
	}
	private function parameterException()
	{
		$info = $this->info;
		if(!$info['string'])
		{
			$this->evaluation(array('code'=>-1, 'text'=>'请输入需要编码的文本'));
			return false;
		}
		$array = array('encode', 'decode');
		$format = $info['format'];
		in_array($format, $array) ? $this->$format() : $this->encode();//$this->evaluation(array('code'=>-2, 'text'=>'调用了不存在的方法->'.$format));
		return false;
	}
	private function encode()
	{
		$string = $this->info['string'];
		$explode = explode('://', $string);
		$string = '';
		if(count($explode) > 1)
		{
			$host = $explode[0];
			$string = $host . '://' . idn_to_ascii(join('', array_slice($explode, 1)), IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
		} else {
			$string = idn_to_ascii(join('', $explode), IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
		}
		$this->evaluation(array('code'=>1, 'text'=>$string));
		return true;
	}
	private function decode()
	{
		$string = $this->info['string'];
		$explode = explode('://', $string);
		$string = '';
		if(count($explode) > 1)
		{
			$host = $explode[0];
			$string = $host . '://' . idn_to_utf8(join('', array_slice($explode, 1)), IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
		} else {
			$string = idn_to_utf8(join('', $explode), IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
		}
		$this->evaluation(array('code'=>1, 'text'=>$string));
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
}
echo new Punycode(array('type'=>$type, 'format'=>$format, 'string'=>$string));