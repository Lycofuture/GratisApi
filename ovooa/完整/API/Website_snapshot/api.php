<?php

/* 这是一个Api demo  文件需要755权限*/
Header('Content-type: Application/Json; charset=utf-8');
require '../../need.php';

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(169); // 调用统计函数*/
$Request = need::Request(); //获取所有get post请求;
$url = isset($Request['url']) ? $Request['url'] : false; //获取返回格式 默认json
$width = isset($Request['w']) ? $Request['w'] : 1366; //获取宽度
$height = isset($Request['h']) ? $Request['h'] : 768; //获取高度
$type = isset($Request['type']) ? $Request['type'] : 'image'; //获取返回格式 默认json
new Website_snapshot(array('url'=>$url, 'width'=>$width, 'height'=>$height, 'type'=>$type));
class Website_snapshot
{
	/* Api类 
	* 守护更好的API
	*/
	private $info = array(); //储存请求数据
	private $array = array(); //储存返回数据
	private $Message; //储存返回文本
	private $dir = __DIR__ . '/'; //本文件所在文件夹
	private $class = __CLASS__; //本类类名
	private $file = 'http://ovooa.com/API/Website_snapshot/cache/';
	private $filetime = 1;
	private $error = 0;
	public function __construct(array $array)
	{
		/* php构造函数 类初始化时自动运行 */
		foreach($array as $k=>$v)
		{
			/* for循环获取参数并赋值到类中使用 */
			$this->info[$k] = $v;
		}
		$this->dir(__DIR__.'/cache/');
		$this->parameterException(); //调用类中方法
	}
	private function parameterException() :  bool
	{
		/* 判断参数是否正常 */
		$url = (preg_match('/^http[s]*:\/\//', ($this->info['url'])) ? $this->info['url'] : 'http://'.$this->info['url']);
		if(!$this->is_url($url))
		{
			$this->evaluation(array('code'=>-1, 'text'=>'请输入正确的网址'));
			return false;
		}
		// $url = (preg_match('/^http/i', $url) ? $url : 'http://'.$url);
		// print_r($url);exit;
		// $this->info['url'] = $url;
		$this->info['url'] = $url;
		if($this->info['width'] < 50 || $this->info['height'] <50)
		{
			$this->info['width'] = 1366;
			$this->info['height'] = 768;
		}
		if(file_exists(__DIR__.'/cache/'.md5($url).'.png'))
		{
			$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>array('url'=>$this->file.md5($this->info['url']).'.png')), $this->file.md5($this->info['url']).'.png');
			return true;
		}
		$this->run(); //调用方法
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
			$imagick->writeImages(__DIR__.'/cache/'.md5($this->info['url']).'.png', true);
			$imagick->destroy();
		}
		$this->delete(); //调用删除方法
		$this->array = $array; //赋值
		$this->Message = $string; //赋值
		$this->result(); //输出内容 
		return true;
	}
	private function run() : bool 
	{
		$this->error = ($this->error + 1);
		exec('PATH=$PATH:/www/wwwroot/default/phantomjs/bin && export PATH && phantomjs '.__DIR__.'/1.js '.$this->info['url'].' '.__DIR__.'/cache/'.md5($this->info['url']).'.png '.$this->info['width'].' '.$this->info['height'].' 2>&1', $data);
		// exec('export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/www/server/panel/pyenv/lib/python3.7:/www/server/panel/pyenv/bin:/root/bin:/www/server/panel/pyenv/lib/python3.7:/www/server/panel/pyenv/bin:/www/wwwroot/defalut/phantomjs/bin:/www/server/panel/pyenv/lib/python3.7:/www/server/panel/pyenv/bin:/www/wwwroot/default/phantomjs/bin && ls 2>&1', $data);
		// echo __DIR__.'/1.js '.$this->info['url'].' '.__DIR__.'/cache/'.md5($this->info['url']).'.png '.$this->info['width'].' '.$this->info['height'];
		// print_r($data);exit;
		$data = end($data);
		if($this->error > 5)
		{
			$this->evaluation(array('code'=>-3, 'text'=>'别开玩笑了老哥~'));
			return false;
		} else if($data == 'success') {
			if(file_exists(__DIR__.'/cache/'.md5($this->info['url']).'.png'))
			{
				$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>array('url'=>$this->file.md5($this->info['url']).'.png')), $this->file.md5($this->info['url']).'.png');
				return true;
			} else {
				$this->evaluation(array('code'=>-2, 'text'=>'获取失败'));
				return false;
			}
		} else {
			$this->evaluation(array('code'=>-4, 'text'=>'出现了未知错误：'.$data));
			return false;
		}
		return true;
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
	private function result() : bool
	{
		$info = $this->info;
		$type = isset($info['type']) ? $info['type'] : 'json';
		Switch($type)
		{
			case 'text':
			need::send($this->Message, 'text');
			break;
			case 'json':
			need::send($this->array, 'json');
			break;
			default:
			Header('content-type: image/png;');
			echo file_get_contents(__DIR__.'/cache/'.md5($this->info['url']).'.png');
			break;
		}
		return true;
	}
	public function is_url($url)
	{
		if(preg_match('~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~i', $url, $url))
		{
			if(!$url[1] || !$url[2] || !$url[3] || !$url[4]) return false;
			if(isset($url[5]) && $url[5])
			{
				if(preg_match('/^\//', $url[5])) return true;
			}
			return true;
		}
		return false;
	}
	public function __destruct()
	{
		/* php析构函数 类运行结束时调用 */
		if($this->array['code'] != 1 && file_exists(__DIR__.'/cache/'.md5($this->info['url']).'.png'))
		{
			unlink(__DIR__.'/cache/'.md5($this->info['url']).'.png');
		}
		$this->delfile(__DIR__.'/cache/', $this->filetime);
	}

}
