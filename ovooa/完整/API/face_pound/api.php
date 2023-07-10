<?php
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(156); // 调用统计函数
addAccess();//调用统计函数
$Request = need::Request();
$url = isset($Request['url']) ? $Request['url'] : false;
$QQ = isset($Request['QQ']) ? $Request['QQ'] : false;
$type = isset($Request['type']) ? $Request['type'] : false;

New face_pound(array('QQ'=>$QQ, 'type'=>$type, 'url'=>$url));


class face_pound
{
	private $info = array();
	private $array = array();
	private $fileTime = 10;
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
		$this->dir(__DIR__.'/cache/');
		$this->parameterException();
	}
	private function parameterException()
	{
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
				$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/face_pound/cache/'.$info['QQ'].'.GIF'));
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
				$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/face_pound/cache/'.Md5($info['url']).'.GIF'));
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
		$width = $imagick->getimagewidth();
		$height = $imagick->getimageheight();
		// if($width<640)
		// {
			// $this->修改($imagick, 640, ($height+(640-$width)));
			// $height = $imagick->getimageheight();
			// $width = 640;
		// }
		// if($height < 640)
		// {
			// $this->修改($imagick, ($width+(640-$height)), 640);
		// }
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
			$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}else{
			$imagick = New imagick($file);
			$width = $imagick->Getimagewidth();//获取宽
			$height = $imagick->Getimageheight();//获取高
			$image = New imagick();//创建一个空白图层
			$image->Newimage($width, $height, 'none');//设置宽高为头像宽高，颜色为空色
			$image->setimageformat('png');//设置图像格式为png
			$image->setimagematte(true);//激活遮罩通道
			$draw = New imagickdraw();//创建一个空白文本图层
			$draw->setfillcolor('#ffffff');//设置颜色
			$draw->circle($width/2, $height/2, $width/2, $height);//填充圆形
			$image->drawimage($draw);//将文本图层合并
			$imagick->setimageformat('png');//设置图片格式为png
			$imagick->setimagematte(true);//激活遮罩通道
			$imagick->cropimage($width, $height, 0, 0);//提取图像区域
			$imagick->compositeimage($image, Imagick::COMPOSITE_DSTIN, 0, 0);//合并图层
			$imagick->writeimage($file);//写出文件
			$imagick->destroy();//关闭图片
			$image->destroy();//关闭图片
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
			$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}else{
			$bg = [
				"0.png"=>[
					'位置'=>[
						230,
						130
					],
					'大小'=>[
						150,
						60
					]
				],
				"1.png"=>[
					'位置'=>[
						235,
						135
					],
					'大小'=>[
						135,
						60
					]
				],
				"2.png"=>[
					'位置'=>[
						185,
						150
					],
					'大小'=>[
						110,
						130
					]
				],
				"3.png"=>[
					'位置'=>[
						185,
						150
					],
					'大小'=>[
						110,
						130
					]
				],
				"4.png"=>[
					'位置'=>[
						188,
						145
					],
					'大小'=>[
						115,
						110
					]
				],
				"5.png"=>[
					'位置'=>[
						195,
						144
					],
					'大小'=>[
						115,
						100
					]
				],
				"6.png"=>[
					'位置'=>[
						220,
						140
					],
					'大小'=>[
						130,
						70
					]
				],
				"7.png"=>[
					'位置'=>[
						224,
						140
					],
					'大小'=>[
						130,
						60
					]
				]
			];
			$this->dir(__DIR__.'/cache/');
			$array = array();
			$GIF = New imagick();//创建空白图层
			//$GIF->Newimage(235, 196, 'none');
			$GIF->setformat('GIF');//设置为GIF
			foreach($bg as $k=>$v)
			{
				// $v = $bg['7.png'];
				$image = New imagick();//创建空白图层
				$image->Newimage(500, 400, 'white');//设置图片大小颜色
				$image->setimageformat('png');//设置图片格式为png
				// $image->setimagematte(true);//激活遮罩通道
				if(isset($v['位置'][0]) && isset($v['大小'][0]))
				{
					$imagick = New imagick($file);//创建头像图层
					$imagick->setimageformat('png');//设置为png
					$y = $v['位置'][0];//拼接位置：上下
					$x = $v['位置'][1];//拼接位置：左右
					$width = $v['大小'][0];
					$height = $v['大小'][1];
					// $k = 3;
					$this->修改($imagick, $width, $height);
					$image->compositeimage($imagick, Imagick::COMPOSITE_ATOP, $x, $y);//合并头像以及背景
					$imagick->destroy();//关闭头像
					unset($imagick);
				}
				$imagick = New imagick('./pound/'.$k);//创建首层图
				$imagick->setimageformat('png');//设置png
				$imagick->setimagematte(true);//激活遮罩通道
				$image->compositeimage($imagick, Imagick::COMPOSITE_ATOP, 0, 0);//合并图层
				$imagick->destroy();//关闭图片
				$image->setimageformat('GIF');//设置图片格式GIF
				$GIF->addimage($image);//将拼接好的图片放到GIF图层中
				$GIF->setImageDelay(6);//设置GIF播放速度
				$image->destroy();//关闭图层
				unset($imagick, $image, $v);
				 // break;
			}
			$GIF->writeImages($this->filecache, true);//储存图片
			// Header('Content-type: image/GIF');
			// echo $GIF->getImageBlob();
			// unlink($this->filecache);
			// unlink($this->filename);
			$GIF->destroy();//关闭GIF
			// exit;
			$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/face_pound/cache/'.$this->file));
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