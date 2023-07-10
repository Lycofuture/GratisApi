<?php
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(132); // 调用统计函数
addAccess();//调用统计函数*/
$Request = need::Request();
$QQ = @$Request['QQ'];
$type = @$Request['type'];
New chi(array('QQ'=>$QQ, 'type'=>$type));


class chi
{
	private $info = array();
	private $array = array();
	private $fileTime = 10;
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
		$info = $this->info;
		if(isset($info['QQ']))
		{
			if(!need::is_num($info['QQ']))
			{
				$this->evaluation(array('code'=>-1, 'text'=>'请输入正确的账号'));
				return;
			}
		}
		if(file_exists(__DIR__.'/cache/'.$info['QQ'].'.GIF') && $this->is_image(__DIR__.'/cache/'.$info['QQ'].'.GIF'))
		{
			/* 如果有缓存直接输出 */
			$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/chi/cache/'.$this->info['QQ'].'.GIF'));
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
		$url = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=5';
		$string = need::teacher_Curl($url);
		if(!$string)
		{
			$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
			return;
		}
		if($this->put(__DIR__.'/'.$QQ.'.png', $string))
		{
			$this->裁剪圆形(__DIR__.'/'.$QQ.'.png');
			return;
		}
		$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
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
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"1.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"2.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"3.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"4.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"5.png"=>[
					'位置'=>[
						165,
						9
					],
					'大小'=>[
						105,
						105
					]
				],
				"6.png"=>[
					'位置'=>[
						154,
						14
					],
					'大小'=>[
						105,
						105
					]
				],
				"7.png"=>[
					'位置'=>[
						150,
						19
					],
					'大小'=>[
						105,
						105
					]
				],
				"8.png"=>[
					'位置'=>[
						147,
						20
					],
					'大小'=>[
						105,
						105
					]
				],
				"9.png"=>[
					'位置'=>[
						141,
						25
					],
					'大小'=>[
						105,
						105
					]
				],
				"10.png"=>[
					'位置'=>[
						137,
						32
					],
					'大小'=>[
						105,
						105
					]
				],
				"11.png"=>[
					'位置'=>[
						133,
						36
					],
					'大小'=>[
						105,
						105
					]
				],
				"12.png"=>[
					'位置'=>[
						128,
						44
					],
					'大小'=>[
						105,
						105
					]
				],
				"13.png"=>[
					'位置'=>[
						127,
						67
					],
					'大小'=>[
						105,
						105
					]
				],
				"14.png"=>[
					'位置'=>[
						130,
						90
					],
					'大小'=>[
						105,
						105
					]
				],
				"15.png"=>[
					'位置'=>[
						120,
						-29
					],
					'大小'=>[
						105,
						105
					]
				],
				"16.png"=>[
					'位置'=>[
						214,
						-25
					],
					'大小'=>[
						105,
						105
					]
				],
				"17.png"=>[
					'位置'=>[
						208,
						-20
					],
					'大小'=>[
						105,
						105
					]
				],
				"18.png"=>[
					'位置'=>[
						195,
						-17
					],
					'大小'=>[
						105,
						105
					]
				],
				"19.png"=>[
					'位置'=>[
						187,
						-13
					],
					'大小'=>[
						105,
						105
					]
				],
				"20.png"=>[
					'位置'=>[
						177,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"21.png"=>[
					'位置'=>[
						182,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"22.png"=>[
					'位置'=>[
						182,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"23.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"24.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"25.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"26.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"27.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"28.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"29.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				],
				"30.png"=>[
					'位置'=>[
						180,
						-7
					],
					'大小'=>[
						105,
						105
					]
				]
				// "31.png"=>[
					// '位置'=>[
						// -7,
						// 180
					// ],
					// '大小'=>[
						// 105,
						// 105
					// ]
				// ]
			];
			$this->dir(__DIR__.'/cache/');
			$array = array();
			$GIF = New imagick();//创建空白图层
			//$GIF->Newimage(235, 196, 'none');
			$GIF->setformat('GIF');//设置为GIF
			foreach($bg as $k=>$v)
			{
				// $v = $bg['5.png'];
				$image = New imagick();//创建空白图层
				$image->Newimage(250, 250, 'white');//设置图片大小颜色
				$image->setimageformat('png');//设置图片格式为png
				// $image->setimagematte(true);//激活遮罩通道
				if(isset($v['位置'][0]) && isset($v['大小'][0]))
				{
					$imagick = New imagick($file);//创建头像图层
					$imagick->setimageformat('png');//设置为png
					$y = $v['位置'][0];//拼接位置：左右
					$x = $v['位置'][1];//拼接位置：上下
					$width = $v['大小'][0];
					$height = $v['大小'][1];
					// $k = 3;
					$this->修改($imagick, $width, $height);
					$image->compositeimage($imagick, Imagick::COMPOSITE_ATOP, $x, $y);//合并头像以及背景
					$imagick->destroy();//关闭头像
					unset($imagick);
				}
				$imagick = New imagick('./chi/'.$k);//创建首层图
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
			$GIF->writeImages('./cache/'.$this->info['QQ'].'.GIF', true);//储存图片
			// Header('Content-type: image/GIF');
			// echo file_get_contents('./cache/'.$this->info['QQ'].'.GIF');
			// unlink('./cache/'.$this->info['QQ'].'.GIF');
			// unlink('./'.$this->info['QQ'].'.png');
			$GIF->destroy();//关闭GIF
			// exit;
			$this->evaluation(array('code'=>1, 'text'=>'http://ovooa.com/API/chi/cache/'.$this->info['QQ'].'.GIF'));
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
			$imagick->writeImages('./cache/'.$this->info['QQ'].'.GIF', true);
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
			echo file_Get_Contents('./cache/'.$this->info['QQ'].'.GIF');
			break;
		}
		return;
	}
	public function __destruct()
	{
		/* 魔术方法 类运行结束时触发 */
		if($this->array['code'] != 1)
		{
			@unlink(__DIR__.'./cache/'.$this->info['QQ'].'.GIF');
		}
		@unlink(__DIR__.'/'.$this->info['QQ'].'.png');
		$this->delfile(__DIR__.'/cache/', $this->fileTime);
	}
}