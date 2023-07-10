<?php
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(129); // 调用统计函数
addAccess();//调用统计函数*/
$Request = need::Request();
$url = isset($Request['url']) ? $Request['url'] : false;
$QQ = isset($Request['QQ']) ? $Request['QQ'] : false;
$type = isset($Request['type']) ? $Request['type'] : false;

New xian(array('QQ'=>$QQ, 'type'=>$type, 'url'=>$url));


class xian
{
	private $info = array();
	private $array = array();
	private $fileTime = 10;
	private $filename;
	private $filecache;
	private $file;
	private $Message;
	private $image;
	private $Delay = [];
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
		if(isset($info['QQ']) && $info['QQ'])
		{
			$this->filename = __DIR__.'/cache/'.$info['QQ'].'.png';
			$this->filecache = __DIR__.'/cache/'.$info['QQ'].'.GIF';
			$this->file = $info['QQ'].'.GIF';
			if(!need::is_num($info['QQ']))
			{
				$this->evaluation(array('code'=>-1, 'text'=>'请输入正确的账号'));
				return;
			}
			if(file_exists(__DIR__.'/cache/'.$info['QQ'].'.GIF') && $this->is_image(__DIR__.'/cache/'.$info['QQ'].'.GIF'))
			{
				$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/xian/cache/'.$info['QQ'].'.GIF'));
				return;
			}
		}else
		if(isset($info['url']) && $info['url'])
		{
			$this->filename = __DIR__.'/cache/'.Md5($info['url']).'.png';
			$this->filecache = __DIR__.'/cache/'.Md5($info['url']).'.GIF';
			$this->file = Md5($info['url']).'.GIF';
			if(!$info['url'])
			{
				$this->evaluation(array('code'=>-3, 'text'=>'请输入正确的参数'));
				return;
			}
			if(file_exists(__DIR__.'/cache/'.Md5($info['url']).'.GIF') && $this->is_image(__DIR__.'/cache/'.Md5($info['url']).'.GIF'))
			{
				$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/xian/cache/'.Md5($info['url']).'.GIF'));
				return;
			}
		}else{
			$Md5 = Md5(uniqid().mt_rand());
			$this->filename = __DIR__.'/cache/'.$Md5.'.png';
			$this->filecache = __DIR__.'/cache/'.$Md5.'.GIF';
			$this->file = $Md5.'.GIF';
			$this->evaluation(array('code'=>-4, 'text'=>'请输入正确的参数'));
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
			$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}
		if($this->put($this->filename, $string))
		{
			$this->图像处理($this->filename);
			return;
		}
		$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
		return;
	}
	private function 图像处理($file)
	{
		if($this->is_image($file) === false)
		{
			unset($file);
			$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}
		$imagick = new imagick($file);
		$files = [];
		if($imagick->getImageFormat() == 'GIF')
		{
			$image = $imagick->coalesceImages();
			foreach($image as $k=>$v)
			{
				$name = md5($file. $k);
				$files[] = (__DIR__ . '/cache/' . $name);
				$this->Delay[] = $v->getImageDelay();
				$this->put(__DIR__ . '/cache/' . $name, $v);
				// break;
			}
			$imagick->destroy();
		} else {
			$files[] = $file;
		}
		$this->开始合成($files);
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
	private function 开始合成($file)
	{
		/* 开始合成 */
		
		if(!is_array($file) || !$file)
		{
			/* 头像图不存在则抛出 */
			return $this->evaluation(array('code'=>-2, 'text'=>'获取失败2'));
		}else{
			$GIF = New imagick();//创建空白图层
			$GIF->setformat('GIF');//设置为GIF
			foreach($file as $key=>$file)
			{
				if(file_exists($file))
				{
					$Image = new imagick($file);
					$Image->setformat('gif');
					$Image->charcoalImage(0.7, 0.5);//设置为黑白色
					$GIF->addImage($Image);
					if($this->Delay)
					{
						$GIF->setImageDelay($this->Delay[$key]);
					} else {
						$GIF->setImageDelay(6);
					}
					$Image->destroy();
				} else {
					return $this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
				}
			}
			$GIF->writeImages($this->filecache, true);
			$GIF->destroy();//关闭GIF
			// exit;
			$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/xian/cache/'.$this->file));
			return;
		}
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
			Header('Content-type: image/GIF');
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