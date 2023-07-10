<?
header('content-type: application/json;');
require __DIR__.'/../../need.php';
/* Start */
require (__DIR__."/../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(172); // 调用统计函数
$request = need::request();
// print_r($request);
$screen = isset($request['screen']) ? $request['screen'] : false; //获取竖屏还是横屏或者视频
$n = isset($request['n']) ? $request['n'] : false; //选择
$format = isset($request['format']) ? $request['format'] : false; //分类
$page = isset($request['page']) ? $request['page'] : 1; //页码
$limit = isset($request['limit']) ? $request['limit'] : 24; //每页数量
$type = isset($request['type']) ? $request['type'] : 'json'; //返回格式
new loveanimer(['screen'=>$screen, 'n'=>$n, 'format'=>$format, 'page'=>$page, 'limit'=>$limit, 'type'=>$type]);
class loveanimer
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
		if($info['screen'] == 1)
		{
			$info['screen'] = 'la'; //横屏
		} else if($info['screen'] == 2) {
			$info['screen'] = 'video'; //视频
		} else {
			$info['screen'] = 've'; //竖屏
		}
		if(isset($info['limit']))
		{
			if(!is_numeric($info['limit']) || $info['limit'] < 1 || $info['limit'] > 50) $info['limit'] = 24;

		}
		/*
		if(isset($info['n']))
		{
			if(!is_numeric($info['n']) || $info['n'] < 1 || $info['n'] > $info['limit']) $info['n'] = 0;
		}*/
		if(isset($info['page']))
		{
			if(!is_numeric($info['page']) || $info['page'] < 1) $info['page'] = 1;
		}
		$this->info = $info;
		$this->start();
		return true;
	}
	public function start()
	{
		$n = $this->info['n'];
		$url = $this->getUrl();
		$array = [];
		$data = json_decode(need::teacher_curl($url), true);
		if($data && (isset($data['object_list']) || isset($data['res'])))
		{
			if(isset($data['object_list']) && $data['object_list'])
			{
				// 壁纸
				foreach($data['object_list'] as $v)
				{
					$Image = $v['path'];
					$width = $v['width'];
					$height = $v['height'];
					$size = $v['size'];
					$tag = array_filter(explode(' ', $v['tag']));
					$tag = (count($tag) > 0 ? '#'.join(' #', $tag) : '#默认');
					$array[] = array(
						'width'=>$width,
						'height'=>$height,
						'size'=>$size,
						'url'=>$Image,
						'tag'=>$tag
					);
				}
				if (isset($data['object_list'][$n]))
				{
					$v =$data['object_list'][$n];
				} else {
					$v = $data['object_list'][array_rand($data['object_list'], 1)];
				}
				$Image = $v['path'];
				$width = $v['width'];
				$height = $v['height'];
				$size = $v['size'];
				$tag = array_filter(explode(' ', $v['tag']));
				$tag = ($tag ? '#'.join(' #', $tag) : '#默认');
				$message = "±img={$Image}±\n规格：{$width}*{$height}\n大小：{$size}\n标签：{$tag}";
				return $this->exec([
					'code'=>1,
					'text'=>'获取成功',
					'data'=>$array
				], $message);
    		} else if(isset($data['res']['videowp']) && $data['res']['videowp']) {
				foreach($data['res']['videowp'] as $v)
				{
					$video = $v['video'];
					$cover = $v['img'];
					$width = $v['width'];
					$height = $v['height'];
					$size = $v['bit_rate'];
					$tag = array_filter(explode("\t", $v['tag']));
					$tag = ($tag ? '#'.join(' #', $tag) : '#默认');
					$array[] = array(
						'width'=>$width,
						'height'=>$height,
						'size'=>$size,
						'cover'=>$cover,
						'url'=>$video,
						'tag'=>$tag
					);
				}
				if (isset($data['res']['videowp'][$n]))
				{
					$v =$data['res']['videowp'][$n];
				} else {
					$v = $data['res']['videowp'][array_rand($data['res']['videowp'], 1)];
				}
				$video = $v['video'];
				$cover = $v['img'];
				$width = $v['width'];
				$height = $v['height'];
				$size = $v['size'];
				$tag = array_filter(explode("\t", $v['tag']));
				$tag = ($tag ? '#'.join(' #', $tag) : '#默认');
				$message = "±img={$cover}±\n规格：{$width}*{$height}\n大小：{$size}\n标签：{$tag}播放链接：{$video}";
			return $this->exec([
					'code'=>1,
					'text'=>'获取成功',
					'data'=>$array
				], $message);
			} else {
				return $this->exec(['code'=>-2, 'text'=>'获取失败']);
			}
		} else {
			return $this->exec(['code'=>-3, 'text'=>'获取失败']);
		}
	}
	public function getUrl()
	{
		$screen = $this->info['screen'];
		$page = $this->info['page'];
		$limit = $this->info['limit'];
		$format = $this->info['format'];
		$Url = null;
		
		switch($screen)
		{
			case 'la':
			case 've':
				//横屏 竖屏
				switch($format)
				{
					case '2':
					case 2:
						//动漫壁纸
						$id = "4e4d610cdf714d2966000003";
					break;
					case '3':
					case 3:
						//风景壁纸
						$id = "4e4d610cdf714d2966000002";
					break;
					case '4':
					case 4:
						//游戏壁纸
						$id = "4e4d610cdf714d2966000007";
					break;
					case '5':
					case 5:
						//明星壁纸
						$id = "5109e05248d5b9368bb559dc";
					break;
					case '6':
					case 6:
						//机械壁纸
						$id = "4e4d610cdf714d2966000005";
					break;
					case '7':
					case 7:
						//动物壁纸
						$id = "4e4d610cdf714d2966000001";
					break;
					case '8':
					case 8:
						//文字壁纸
						$id = "5109e04e48d5b9364ae9ac45";
					break;
					case '9':
					case 9:
						//城市壁纸
						$id = "4fb47a305ba1c60ca5000223";
					break;
					case '10':
					case 10:
						//视觉壁纸
						$id = "4fb479f75ba1c65561000027";
					break;
					case '11':
					case 11:
						//物语壁纸
						$id = "4fb47a465ba1c65561000028";
					break;
					case '12':
					case 12:
						//情感壁纸
						$id = "4ef0a35c0569795756000000";
					break;
					case '13':
					case 13:
						//设计壁纸
						$id = "4fb47a195ba1c60ca5000222";
					break;
					case '14':
					case 14:
						//男人壁纸
						$id = "4e4d610cdf714d2966000006";
					break;
					default:
						//美女壁纸
						$id = "4e4d610cdf714d2966000000";
					break;
				}
				$Url = "https://www.loveanimer.com/comic/photo/list?page={$page}&pageSize={$limit}&cid={$id}&type={$screen}&order=hot&v=63";
			break;
			case 'video':
				switch($format) {
					case '2':
					case 2:
						//网红
						$id = '5930e061e7bce72ce01371ae';
					break;
					case '3':
					case 3:
						//游戏
						$id = '5930e009e7bce72ce0137170';
					break;
					case '4':
					case 4:
						//热门
						$id = '5930df00e7bce72c3860daa2';
					break;
					case '5':
					case 5:
						//风景
						$id = '5930e16ee7bce72ce013725f';
					break;
					case '6':
					case 6:
						//其他
						$id = '5930e165e7bce72ce0137257';
					break;
					case '7':
					case 7:
						//热舞
						$id = '5930e081e7bce72ce01371c8';
					break;
					case '8':
					case 8:
						//明星
						$id = '5930e046e7bce72ce013719c';
					break;
					case '9':
					case 9:
						//影视
						$id = '5930e039e7bce72ce0137190';
					break;
					case '10':
					case 10:
						//动物
						$id = '5930e22ee7bce72ce01372f3';
					break;
					default:
						//动漫
						$id = '5930e065e7bce72ce01371b1';
					break;
				}
				$Url = "https://www.loveanimer.com/comic/video/list/category/hot?cid={$id}&page={$page}&pageSize={$limit}&v=63";
			break;
		}
		if($Url) return $Url;
		return $this->exec(['code'=>-1, 'text'=>'出现了意想不到的错误，请联系网站管理员']);
	}
	public function exec($array, $message = null)
	{
		$this->message = $message ? $message : $array['text'];
		$this->array = $array;
		return $this->result();
	}
	public function result()
	{
		$info = $this->info;
		$type = isset($info['type']) ? $info['type'] : 'json';
		Switch($type)
		{
			case 'text':
				need::send($this->message, 'text');
			break;
			case 'url':
				if(isset($this->array['data'][0])) need::send($this->array['data'][array_rand($this->array['data'], 1)]['url'], 'text');
				$this->info['type'] = 'text';
				$this->result();
			default:
				need::send($this->array, 'json');
			break;
		}
		// print_r($this->array);
		return true;
	}
}