<?php
// header('Content-Type:application/json');
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(134); // 调用统计函数*/
/*
$QQ = @$_REQUEST['QQ'];
$url = @$_REQUEST['url'];
$rand = @$_REQUEST['format'];
$Type = @$_REQUEST['type'];
*/


require '../../need.php';
$Request = need::Request();
$QQ = @$Request['QQ'];
$url = @$Request['url'];
$type = @$Request['type'];
New head(array('QQ'=>$QQ, 'type'=>$type, 'url'=>$url));


class head
{
	private $info = array();
	private $array = array();
	private $fileTime = -1;
	private $filename;
	private $filecache;
	private $file;
	private $Message;
	private $image;
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
		$this->dir(__DIR__.'/cache/');
		$info = $this->info;
		if(isset($info['QQ']))
		{
			$this->filename = __DIR__.'/cache/'.$info['QQ'].'.png';
			$this->filecache = __DIR__.'/cache/'.$info['QQ'].'_p_.png';
			$this->file = $info['QQ'].'_p_.png';
			if(!need::is_num($info['QQ']))
			{
				$this->exec(array('code'=>-1, 'text'=>'请输入正确的账号'));
				return;
			}
			if(file_exists(__DIR__.'/cache/'.$info['QQ'].'_p_.png') && $this->is_image(__DIR__.'/cache/'.$info['QQ'].'_p_.png'))
			{
				$this->exec(array('code'=>1, 'text'=>'http://ovooa.com/API/head/cache/'.$info['QQ'].'_p_.png'));
				return;
			}
		}else
		if(isset($info['url']))
		{
			$this->filename = __DIR__.'/cache/'.Md5($info['url']).'.png';
			$this->filecache = __DIR__.'/cache/'.Md5($info['url']).'_p_.png';
			$this->file = Md5($info['url']).'_p_.png';
			if(!$info['url'])
			{
				$this->exec(array('code'=>-3, 'text'=>'请输入正确的参数'));
				return;
			}
			if(file_exists(__DIR__.'/cache/'.Md5($info['url']).'_p_.png') && $this->is_image(__DIR__.'/cache/'.Md5($info['url']).'_p_.png'))
			{
				$this->exec(array('code'=>1, 'text'=>'http://ovooa.com/API/head/cache/'.Md5($info['url']).'_p_.png'));
				return;
			}
		}else{
			$Md5 = Md5(uniqid().mt_rand());
			$this->filename = __DIR__.'/cache/'.$Md5.'.png';
			$this->filecache = __DIR__.'/cache/'.$Md5.'_p_.png';
			$this->file = $Md5.'_p_.png';
			$this->exec(array('code'=>-4, 'text'=>'请输入正确的参数'));
			return;
		}
		$this->GetHeader();
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
	private function GetHeader()
	{
		/* 获取头像 */
		$QQ = $this->info['QQ'];
		if($QQ)
		{
			$url = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=5';
		}else{
			$url = $this->info['url'];
		}
		$string = need::teacher_Curl($url);
		if(!$string)
		{
			$this->exec(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}
		if($this->put($this->filename, $string))
		{
			$this->图像处理($this->filename);
			return;
		}
		$this->exec(array('code'=>-2, 'text'=>'获取失败'));
		return;
	}
	private function 图像处理($file)
	{
		if($this->is_image($file) === false)
		{
			unset($file);
			$this->exec(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}
		$imagick = new imagick($file);
		if($imagick->getImageFormat() == 'GIF')
		{
			$image = $imagick->coalesceImages();
			foreach($image as $k=>$v)
			{
				$this->put($file, $v);
				break;
			}
			$imagick->destroy();
		}
		$imagick = new imagick($file);
		$this->修改($imagick, 640, 640);
		$imagick->writeimages($file, true);
		$imagick->destroy();
		$this->裁剪圆形($file);
		return;
	}
	private function is_image($file)
	{
		try{
			$imagick = new imagick($file);
		} catch (\Exception $e) {
			return false;
		}
		$imagick->destroy();
		return true;
	}
	private function 裁剪圆形($file)
	{
		/* 裁剪头像为圆形 */
		if(!file_exists($file))
		{
			$this->exec(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}else{
			$this->开始合成($file);
			return true;
		}
	}
	private function 开始合成($file)
	{
		/* 开始合成 */
		if(!file_exists($file))
		{
			/* 头像图不存在则抛出 */
			$this->exec(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}else{
			$bg = $read = need::read_all('./head','jpg','png','jpeg');//原图
			// print_r($read);exit;
			$array = array();
			$GIF = New imagick($file);//创建空白图层
			$GIF->setformat('png');//设置为GIF
			$rand = array_rand($read, 1);
			$image = new imagick(__DIR__.'/head/'.$read[$rand]['name']);
			$image->setformat('png');
			$this->修改($image, 640, 640);
			$image->setimagematte(true);//激活遮罩通道
			$GIF->compositeimage($image, Imagick::COMPOSITE_ATOP, 0, 0);//合并头像以及背景
			$image->destroy();//关闭图层
			$GIF->writeImages($this->filecache, true);//储存图片
			$GIF->destroy();//关闭GIF
			$this->exec(array('code'=>1, 'text'=>'http://ovooa.com/API/head/cache/'.$this->file));
			return;
		}
	}
	private function 修改(imagick $imagick, $width, $height)
	{
		return $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
	}
	private function delete()
	{
		unset($this->array, $this->Message);
		return true;
	}
	private function exec(array $array, $string = false)
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
			$imagick->setimageformat('png');
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
			Header('Content-type: image/png');
			echo file_Get_Contents($this->filecache);
			break;
		}
		return;
	}
	public function __destruct()
	{
		/* 魔术方法 类运行结束时触发 */
		if($this->array['code'] != 1)
		{
			unlink($this->filecache);
		}
		if(file_exists($this->filename))
		{
			unlink($this->filename);
		}
		$this->delfile(__DIR__.'/cache/', $this->fileTime);
	}
}