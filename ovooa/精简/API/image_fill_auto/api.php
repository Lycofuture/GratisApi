<?php
Header('content-type: image/png');
require_once(__DIR__.'/../../need.php');

require ("../function.php"); // 引入函数文件
addApiAccess(143); // 调用统计函数
addAccess();//调用统计函数*/
$Request = need::Request();
$msg = (isset($Request['msg']) ? $Request['msg'] : false) ?: (isset($Request['Msg']) ? $Request['Msg'] : false);
$line = isset($Request['line']) ? $Request['line'] : false;
$width = isset($Request['width']) ? $Request['width'] : 200;
$size = isset($Request['size']) ? $Request['size'] : 20;
new image_fill_auto(array('msg'=>$msg, 'width'=>$width, 'size'=>$size, 'line'=>$line));

class image_fill_auto
{
	private $info = array();
	private $array = array();
	private $Message;
	private $data;
	private $EdgeDistance = 20;
	public function __construct(array $array)
	{
		foreach($array as $k=>$v)
		{
			$this->info[$k] = $v;
		}
		$this->ParameterException();
	}
	private function ParameterException()
	{
		$info = $this->info;
		if(!$info['msg'])
		{
			$this->evaluation('请输入内容');
			return false;
		}
		if($info['size'] < 1 || !is_numEric($info['size']) || $info['size'] > 100)
		{
			$this->info['size'] = 20;
		}
		if($info['width'] < 1 || !is_numEric($info['width']) || $info['width'] > 8000)
		{
			$this->info['width'] = 200;
		}
		if(isset($info['line']) && $info['line'] && mb_strstr($info['msg'], $info['line']) !== false)
		{
			$this->StartImageLine();
			return true;
		} else {
			$this->StartImage();
			return true;
		}
		$this->evaluation('未知错误');
		return false;
	}
	/*
	* 合成图片
	*/
	private function StartImage()
	{
		$info = $this->info;
		$msg = $info['msg'];
		$width = $info['width'];
		$FontSize = $info['size'];
		$string = $this->AutoLineText($msg, $width, $FontSize);
		$Imagick = new Imagick();
		$Draw = new ImagickDraw();
		$Draw->setFont(__DIR__.'/../1.ttf');
		$Draw->setFontSize($FontSize);
		$Draw->setFillColor(new ImagickPixel('rgb(0, 0, 0)'));
		$Draw->Annotation($this->EdgeDistance,  ($this->EdgeDistance + $info['size']), $string);
		$TextInfo = $Imagick->queryFontMetrics($Draw, $string);
		// print_r($TextInfo);exit;
		$Imagick->newImage(($TextInfo['textWidth'] + $this->EdgeDistance * 2), ($TextInfo['textHeight'] + $this->EdgeDistance * 2), 'white');
		$Imagick->DrawImage($Draw);
		$Imagick->setformat('png');
		
		echo $Imagick;
		
		$Imagick->destroy();
		return true;
	}
	private function StartImageLine()
	{
		$info = $this->info;
		$msg = explode($info['line'], $info['msg']);
		$width = $info['width'];
		$FontSize = $info['size'];
		$string = '';
		foreach($msg as $v)
		{
			$string .= $this->AutoLineText($v, $width, $FontSize) . "\n";
		}
		$string = trim($string);
		$Imagick = new Imagick();
		$Draw = new ImagickDraw();
		$Draw->setFont(__DIR__.'/../1.ttf');
		$Draw->setFontSize($FontSize);
		$Draw->setFillColor(new ImagickPixel('rgb(0, 0, 0)'));
		$Draw->Annotation($this->EdgeDistance, ($this->EdgeDistance + $info['size']), $string);
		$TextInfo = $Imagick->queryFontMetrics($Draw, $string);
		// print_r($TextInfo);exit;
		$Imagick->newImage(($TextInfo['textWidth'] + $this->EdgeDistance * 2), ($TextInfo['textHeight'] + $this->EdgeDistance * 2), 'white');
		$Imagick->DrawImage($Draw);
		$Imagick->setformat('png');
		
		echo $Imagick;
		
		$Imagick->destroy();
		return true;
	}
	
	
	private function AutoLineText($Message, $width = 200, $FontSize = 50)
	{
		if(!is_numEric($width) || $width < 1) $width = 200;
		$array = mb_str_split($Message);
		$string = '';
		$str = '';
		$StrWidth = 0;
		$Imagick = new Imagick();
		$Draw = new ImagickDraw();
		$Draw->SetFontSize($FontSize);
		$Draw->SetFont(__DIR__.'/../1.ttf');
		foreach($array as $v)
		{
			$string .= $v;
			$Void = $Imagick->queryFontMetrics($Draw, $string);
			if($width < $Void['textWidth'])
			{
				$str .= "\n".$v;
				$string = $v;
			} else {
				$str .= $v;
			}
			unSet($Void);
		}
		return $str;
	}
	private function evaluation($Message)
	{
		$Message = $this->AutoLineText($Message, $this->info['width'], $this->info['size']);
		$imagick = New imagick();
		$draw = New imagickdraw();
		$draw->setfillcolor(New ImagickPixel('black'));
		$draw->setfontsize($this->info['size']);
		$draw->setfont('../1.ttf');
		$me = $imagick->queryFontMetrics($draw, $Message);
		$draw->Annotation($this->EdgeDistance, ($this->EdgeDistance + $this->info['size']), $Message);
		$imagick->newImage(($me['textWidth'] + ($this->EdgeDistance * 2)), ($me['textHeight'] + $this->EdgeDistance * 2), 'white');
		$imagick->drawimage($draw);
		$imagick->setimageformat('png');
		echo $imagick->getImageBlob();
		$imagick->destroy();
		return;
	}
}