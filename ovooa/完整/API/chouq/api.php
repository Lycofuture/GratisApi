<?php
header("Content-type: text/json");
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(101); // 调用统计函数
require '../../curl.php';
require '../../need.php';
/* End */
$type = @$_REQUEST["type"];
$format = @$_REQUEST['format'];
/*
$s=rand(1,100);
$qian = @file_get_contents('./cache/qian/'.$s.'.txt');
if ($qian){
	$array = explode('\n', $qian);
	$array[] = $s;
	 // print_r($array);
    if($type == 'text'){
        echo "±img=http://ovooa.com/API/chouq/cache/image/".$s.".gif±\n";
        need::send($qian,'text');
    }else{
        $Array = explode('\n',$qian);
        //print_r($Array);exit;
        echo need::json(array('code'=>1,'data'=>array('title'=>@$Array[1],'text'=>$qian,'image'=>'http://ovooa.com/API/chouq/cache/image/'.$s.'.gif')));
        exit();
    }
}else{
    echo "未知错误";
    exit;
}*/

New chouq(array('format'=>$format, 'type'=>$type));

class chouq
{
	private $info = array();
	private $array = [];
	private $Message;
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
		if($info['format'])
		{
			if(!is_numEric($info['format']) || $info['format'] < 1 || $info['format'] > 100)
			{
				// $this->evaluation(array('code'=>-1, 'text'=>'解签不存在'));
				$this->Getinfo(mt_rand(1, 100));
				return;
			}
			$this->Getinfo($info['format']);
			return;
		}
		$this->Getinfo(mt_rand(1, 100));
		return;
	}
	private function Getinfo($format)
	{
		$file = './cache/qian/'.$format.'.txt';
		$image = 'http://ovooa.com/API/chouq/cache/image/'.$format.'.gif';
		$data = explode('\n', file_Get_Contents($file));
		$array = array('code'=>1, 'text'=>'获取成功', 'data'=>array('format'=>$format, 'draw'=>$data[0], 'annotate'=>$data[1], 'explain'=>$data[2], 'details'=>$data[3], 'source'=>$data[4], 'image'=>$image));
		$this->evaluation($array, '±img='.$image."±\n".$data[1]."\n".$data[2]);
		return;
	}
	private function delete()
	{
		unset($this->array, $this->Message);
		return true;
	}
	private function evaluation(array $array, $string = false)
	{
		$string = $string ? $string : $array['text'];
		$this->delete();
		$this->Message = $string;
		$this->array = $array;
		$this->result();
		return;
	}
	private function result()
	{
		Switch($this->info['type'])
		{
			case 'text':
			need::send($this->Message, 'text');
			break;
			default:
			need::send($this->array, 'json');
			break;
		}
		return;
	}
}
