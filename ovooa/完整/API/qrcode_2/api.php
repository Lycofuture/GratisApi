<?
use chillerlan\QRCode\{QRCode, QROptions};
require_once __DIR__.'/vendor/autoload.php';
require __DIR__.'/../../need.php';
/* Start */
require (__DIR__."/../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(174); // 调用统计函数*/
$data = 'http://ovooa.com';
$request = \need::request();
$msg = isset($request['msg']) ? $request['msg'] : '默认文本'; 
$version = isset($request['version']) ? $request['version'] : 7; 
$size = isset($request['size']) ? $request['size'] : 5; 
$ecc = isset($request['ecc']) ? $request['ecc'] : 3; 
$type = isset($request['type']) ? $request['type'] : 'json'; //返回格式
new qrcode_2(['msg'=>$msg, 'version'=>$version, 'size'=>$size, 'ecc'=>$ecc, 'type'=>$type]);
class qrcode_2
{
	private $info = [];
	public $array = [];
	public $message;
	public function __construct(array $array)
	{
		foreach($array as $k=>$v)
		{
			$this->info[$k] = $v;
		}
		$this->parametersexception();
	}
	public function parametersexception()
	{
		$info = $this->info;
		if(!$info['msg']) $info['msg'] = '默认文本';
		if(!$info['version'] || !$info['version'] || $info['version'] < 5 || $info['version'] > 15) $info['version'] = 7;
		if(!$info['size'] || !$info['size'] || $info['size'] < 1 || $info['size'] > 20) $info['size'] = 5;
		if(!$info['ecc'] || !$info['ecc'] || $info['ecc'] < 1 || $info['ecc'] > 3) $info['ecc'] = 3;
		$this->info = $info;
		$this->start();
		return true;
	}
	public function start()
	{
		return $this->result();
	}
	public function exec($array, $message = null)
	{
		$this->message = $message ? $message : $array['text'];
		$this->array = $array;
		return $this->result();
	}
	public function result()
	{
		$options = new QROptions([
			'version'=> $this->info['version'], //外形 默认7
			'outputType'=> QRCode::OUTPUT_IMAGICK, //模式，这是imagick
			'eccLevel'=>$this->info['ecc'], //绘制线条复杂等级
			'scale'=> $this->info['size'],//大小 n*53
			
		]);
		header('Content-type: image/png');
		echo (new QRCode($options))->render($this->info['msg']);
	}
}
// echo QRCode::ECC_L;exit;
/*
$options = new QROptions([
	'version'      => 20, //外形 默认7
	'outputType'   => QRCode::OUTPUT_IMAGICK, //模式，这是imagick
	'eccLevel'     => 3, //绘制线条复杂等级
	'scale'        => 10,//大小 n*53
	/*
	'moduleValues' => [
		// finder
		1536 => '#000000', // 方块3颜色
		6    => '#ffffff', // 方块2颜色
		// alignment
		2560 => '#000000',//定位方块1 3颜色
		10   => '#000000',//定位方块2颜色
		// timing
		3072 => '#000000',//链接方块的辅助线上面的小点点颜色
		12   => '#000000',//连接方块的那条辅助线颜色
		// format
		3584 => '#000000',//方块5颜色
		14   => '#000000',//方块6颜色
		// version
		4096 => false,//对焦方框辅助方块颜色 如果为空就是没有方块
		16   => false,//对焦方框辅助方块颜色2
		// data
		1024 => '#000000',//画的二维码颜色
		4    => '#ffffff',//大体背景
		// darkmodule
		512  => '#000000', //不知道什么玩意
		// separator
		8    => '#000000', //方框4颜色
		// quietzone
		18   => '#dddddd', //外圈颜色
	],
]);

header('Content-type: image/png');

echo (new QRCode($options))->render($data);*/