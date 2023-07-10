<?php
require '../../need.php';
date_default_timezone_set('PRC');
require ("../function.php"); // 引入函数文件
addApiAccess(164); // 调用统计函数
addAccess();//调用统计函数*/
$Request = need::Request();
$format = isset($Request['format']) ? $Request['format'] : false;
$type = isset($Request['type']) ? $Request['type'] : false;

New face_time(array('type'=>$type, 'format'=>$format));


class face_time
{
	private $info = array();
	private $array = array();
	private $fileTime = 1;
	private $file;
	private $filecache;
	private $Message;
	private $string;
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
		$time = time() + 35;
		$hour = need::ChinaNum(date('G', $time)) ?: '零';
		$hour == '二' ? $hour = '两' : '';
		$minute = need::ChinaNum(date('i', $time));
		// echo $minute,date('i'),';',date('i', $time),';',$time;exit;
		if(!empty($info['format']))
		{
			$string = join("\n", mb_str_split($hour.'點'.$minute.'分了！'));
			// echo mb_strlen($string);exit;
			$this->string = $string;
			$this->filecache = __DIR__ . '/cache/data/'.Md5($string).'.jpg';
			$this->file = 'cache/data/'.Md5($string).'.jpg';
			$this->get_time_specific();
			return;
		} else {
			$string = join("\n\n", mb_str_split($hour.'點了！'));
			$this->string = $string;
			$this->filecache = __DIR__ . '/cache/time/'.Md5($string).'.jpg';
			$this->file = 'cache/time/'.Md5($string).'.jpg';
			$this->get_time_specific();
			return;
		}
		return;
	}
	private function dir($path){
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
	private function put($path, $string)
	{
		/* 储存文件 */
		$this->dir($path);
		if(file_put_Contents($path, $string))
		{
			return true;
		}
		return false;
	}
	private function get_time_specific()
	{
		/* 获取具体时间 */
		$file = $this->filecache;
		$this->dir($file);
		if(file_exists($file))
		{
			$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/face_time/'.$this->file));
			return;
		} else {
			$image = new imagick(__DIR__.'/time/0.jpg');
			$image->setformat('jpeg');
			$draw = new imagickdraw();
			$draw->setfillcolor('black');
			$size = mb_strlen($this->string) < 7 ? 22 : 27;// mb_strlen($this->string) != 6 ? (mb_strlen($this->string) > 6 ? 27 : 22) : 22;
			// $size == 27 ? '' : $this->string = str_replace("\n\n", "\n", $this->string);
			// echo $size ; exit;
			$draw->setfontsize($size);
			$draw->setfont('../1.ttf');
			$height = Intval(700 / mb_strlen($this->string));
			// echo $height;exit;
			$image->annotateImage($draw, 438, $height, 0, $this->string);
			$image->writeimages($this->filecache, true);
			$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/face_time/'.$this->file));
			return;
		}
		return;
	}
	private function get_time()
	{
	
	}
	private function delete()
	{
		unset($this->array, $this->Message);
		return true;
	}
	private function evaluation(array $array, $string = false)
	{
		$this->delete();
		$string = $string ? $string : $array['text'];
		if($array['code'] != 1)
		{
			$imagick = New imagick();
			$draw = New imagickdraw();
			$draw->setfillcolor(New ImagickPixel('black'));
			$draw->setfontsize(50);
			$draw->setfont('../1.ttf');
			$me = $imagick->queryFontMetrics($draw, $string);
			$draw->annotation(0, 50, $string);
			$imagick->Newimage($me['textWidth'], $me['textHeight'], 'white');
			$imagick->drawimage($draw);
			$imagick->setimageformat('GIF');
			$imagick->writeImages($this->filecache, true);
			$imagick->destroy();
		}
		$this->array = $array;
		$this->Message = $string;
		$this->result();
		return;
	}
	private function delfile($dir, $time){
		if(is_dir($dir)) {
			if($dh=opendir($dir)) {
				while (false !== ($file = readdir($dh))) {
					// $count = strstr($file,'duodu-')||strstr($file,'dduo-')||strstr($file,'duod-');
					if($file!='.' && $file!='..') {
						$fullpath=$dir.'/'.$file;
						if(!is_dir($fullpath)) {
							$filedate=filemtime($fullpath);
							$minutes=round((time()-$filedate)/60);
							if($minutes>$time) unlink($fullpath);
							//删除文件
						}
					}
				}
			}
		}
		closedir($dh);
		return true;
	}
	private function result()
	{
		Switch($this->info['type'])
		{
			case 'text':
			echo ($this->Message);
			break;
			case 'json':
			echo need::json($this->array);
			break;
			default:
			Header('Content-type: image/jpeg');
			echo file_Get_Contents($this->filecache);
			break;
		}
		return;
	}
	public function __destruct()
	{
		$this->delfile(__DIR__.'/cache/data/', 1440);
	}
}