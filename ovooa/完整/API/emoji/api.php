<?php
Header('content-type: Application/json;');
require '../../need.php';
$Request = need::Request(); //获取所有get post请求;
$msg = isset($Request['msg']) ? $Request['msg'] : 'json'; //获取返回格式 默认json
echo hex2bin('u1f601');
echo PHP_EOL;
echo preg_match('/([\x{1F600}-\x{1F64F}])/iu', '😁', $emoji);
echo PHP_EOL;
echo bin2hex('😁');
echo PHP_EOL;
echo chr(ord('😁'));
echo PHP_EOL;
echo str_replace('\\x', '', $emoji[1]);
new emoji(array('msg'=>$msg));
class emoji{
	private $info = [];
	private $array = [];
	private $Message;
	private $path = __DIR__.'/cache';
	private $filecache;
	public function __construct(array $array)
	{
		foreach($array as $k=>$v)
		{
			$this->info[$k] = $v;
		}
		$this->dir($this->path);
		$this->getUnicode();
	}
	public function getUnicode()
	{
		$explode = explode('+', $this->info['msg']);
		print_r($explode);
		if(count($explode) < 2)
		{
			$this->evaluation(array('code'=>-1, 'text'=>'请输入正确的组合'));
			return $this;
		}
		$png = [];
		foreach($explode as $v)
		{
			$str = iconv('UTF-8', 'utf-32', $v);
			// echo $str;exit;
			$len = strlen($str);
			$string = '';
			
			for ($i = 0; $i < ($len - 1); $i = $i + 2){
				$c  = $str[$i];
				$c2 = $str[$i + 1];
				if (ord($c) > 0){
					$string .= '\u'.base_convert(ord($c), 10, 16).str_pad(base_convert(ord($c2), 10, 16), 2, 0, STR_PAD_LEFT);
				} else {
					$string .= '\u'.str_pad(base_convert(ord($c2), 10, 16), 4, 0, STR_PAD_LEFT);
				}
			}
			// echo $string;*/
			$png[] = $string;
			unset($string);
		}
		$png = join('_', $png);
		$this->filecache = $png.'.png';
		echo json_encode($explode, 320);
		exit;
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
			$imagick->setimageformat('png');
			$imagick->writeImages($this->filecache, true);
			$imagick->destroy();
		}
		$this->array = $array;
		$this->Message = $string;
		$this->result();
		return;
	}
}