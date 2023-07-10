<?PHP
header('content-type: application/json');
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(161); // 调用统计函数*/
require '../../need.php';
$Request = need::Request();//获取所有请求参数
$type = isset($Request['type']) ? $Request['type'] : 'json';
$phone = isset($Request['phone']) ? $Request['phone'] : '';
$password = isset($Request['password']) ? $Request['password'] : '';
$key = isset($Request['key']) ? $Request['key'] : '';
$num = isset($Request['num']) ? $Request['num'] : 20;
// $page = isset($Request['page']) ? $Request['page'] : 1;//竟然没有翻页 差评
$start = isset($Request['start']) ? $Request['start'] : 0;
$format = isset($Request['format']) ? $Request['format'] : 'login';

new huluxia_login(array('type'=>$type, 'phone'=>$phone, 'password'=>$password, 'key'=>$key, 'num'=>$num, 'start'=>$start, 'format'=>$format));
class huluxia_login
{
	private $url = 'http://floor.huluxia.com/account/login/ANDROID/4.0?platform=2&gkey=000000&app_version=4.0.0.7&versioncode=20141436&market_id=floor_tencent&_key=&device_code=%5Bw%5D02%3A00%3A00%3A00%3A00%3A00%5Bd%5Dcc2e3f81-1946-4b18-9d24-9ab402012cb9';
	private $info = array();
	private $array = array();
	private $Message;
	private $ua = 'okhttp/3.8.1';
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
		if(isset($info['phone']) && !need::is_phone($info['phone']) && $info['format'] == 'login')
		{
			$this->evaluation(array('code'=>-1, 'text'=>'请输入正确的手机号'));
			return;
		}
		if(isset($info['password']) && !need::nate($info['password']) && $info['format'] == 'login')
		{
			$this->evaluation(array('code'=>-2, 'text'=>'请输入正确的密码'));
			return;
		}
		/*
		if(isset($info['page']) && (!is_numEric($info['page']) || $info['page'] < 1))
		{
			$this->info['page'] = mt_rand(1, 300);
		}
		*/
		if(isset($info['start']) && !is_numEric($info['start']))
		{
			$this->info['start'] = 0;
		}
		/*
		if(isset($info['key']) && $info['format'] != 'login' && strlen($info['key']) != 112 && !need::is_phone($info['phone']) && !$info['password'])
		{
			$this->evaluation(array('code'=>-6, 'text'=>'请输入正确的key'));
			return;
		}
		//竟然不需要key 差评
		*/
		if(isset($info['num']) && (!is_numEric($info['num']) || $info['num'] < 5 || $info['num'] > 100))
		{
			$this->info['num'] = 20;
		}
		$format = $info['format'];
		if(is_callable(array($this, $format)))
		{
			$array = array('login', 'summer', 'highlight', 'register');
			if(in_array($format, $array, true) !== true)
			{
				$this->evaluation(array('code'=>-404, 'text'=>'不存在的format'));
				return;
			}
			$this->$format();
			return;
		}else{
			$this->evaluation(array('code'=>-404, 'text'=>'不存在的format'));
			return;
		}
		return;
	}
	private function login()
	{
		$post = Array('account'=>$this->info['phone'], 'login_type'=>2, 'password'=>Md5((string)$this->info['password']));//, 'device_code'=>'[w]02:00:00:00:00:00[d]afc7ba4c-2185-4e8f-9bb2-4b52fe88e5d8');
		$data = json_decode(need::teacher_curl($this->url, [
			'ua'=>$this->ua,
			'post'=>http_build_query($post)
		]), true);
		$code = $data['code']?:$data['status'];
		Switch($code)
		{
			case 1101:
			case 1102:
			$array = array('code'=>-3, 'text'=>$data['msg']);
			$text = $array['text'];
			break;
			case 102:
			$array = array('code'=>-4, 'text'=>$data['msg']);
			$text = $array['text'];
			break;
			case 101:
			$array = array('code'=>-5, 'text'=>$data['msg']);
			$text = $array['text'];
			break;
			case 1:
			$array = array('code'=>1, 'text'=>'获取成功', 'data'=>array('id'=>$data['user']['userID'], 'nick'=>$data['user']['nick'], 'key'=>$data['_key'], 'session'=>$data['session_key']));
			$text = 'key：'.$data['_key']."\nsession：".$data['session_key'];
			break;
		}
		$this->evaluation($array, $text);
		return;
	}
	private function getloginkey($phone, $password)
	{
		$post = Array('account'=>$phone, 'login_type'=>2, 'password'=>Md5((string)$password));//, 'device_code'=>'[w]02:00:00:00:00:00[d]afc7ba4c-2185-4e8f-9bb2-4b52fe88e5d8');
		$data = json_decode(need::teacher_curl($this->url, [
			'ua'=>$this->ua,
			'post'=>http_build_query($post)
		]), true);
		$code = $data['code']?:$data['status'];
		Switch($code)
		{
			case 1101:
			case 1102:
			$array = array('code'=>-3, 'text'=>$data['msg']);
			$text = $array['text'];
			break;
			case 102:
			$array = array('code'=>-4, 'text'=>$data['msg']);
			$text = $array['text'];
			break;
			case 101:
			$array = array('code'=>-5, 'text'=>$data['msg']);
			$text = $array['text'];
			break;
			case 1:
			$array = array('code'=>1, 'text'=>'获取成功', 'data'=>array('id'=>$data['user']['userID'], 'nick'=>$data['user']['nick'], 'key'=>$data['_key'], 'session'=>$data['session_key']));
			$text = 'key：'.$data['_key']."\nsession：".$data['session_key'];
			break;
		}
		return isset($array['data']['key']) ? $array['data']['key'] : false;
	}
	private function summer()
	{
		$info = $this->info;
		// $page = $info['page'];
		$num = $info['num'];
		$key = $info['key'];
		$start = $info['start'];
		/*
		if(strlen($info['key']) != 112 && need::is_phone($info['phone']) && $info['password'])
		{
			$key = $this->getloginkey($info['phone'], $info['password']);
			if($key === false)
			{
				$this->evaluation(array('code'=>-6, 'text'=>'请输入正确的key'));
				return;
			}
		}
		//竟然不需要key 差评
		*/
		$url = 'http://floor.huluxia.com/post/list/ANDROID/2.1?platform=2&gkey=000000&app_version=4.1.1.8.2&versioncode=344&market_id=tool_web&_key='.$key.'&device_code=%5Bd%5D5731263f-ce15-4605-ad0b-15073480574f&phone_brand_type=OP&start='.$start.'&count='.$num.'&cat_id=56&tag_id=0&sort_by=0';
		$data = json_decode(need::teacher_curl($url, [
			'ua'=>$this->ua
		]), true);
		$status = isset($data['status']) ? $data['status'] : 0;
		$data = isset($data['posts']) ? $data['posts'] : false;
		$starttimes = end($data)['activeTime'];
		if($status == 1 && $data)
		{
			$Message = '';
			foreach($data as $k=>$v)
			{
				if(strstr($v['title'], '举牌') || strstr($v['detail'], '举牌')) continue;
				$image = $v['images'];
				$array = array('id'=>$v['user']['userID'], 'nick'=>$v['user']['nick']);//, 'image'=>$image));
				if($image)
				{
					$array['image'] = $image;
					$array = json_encode($array);
					$file = $this->get(__DIR__.'/cache/data.json');
					if($file){
						if(!stristr($file, $array)){
							$this->put(__DIR__.'/cache/data.json', $array."\n");
						}
					}else{
						$this->put(__DIR__.'/cache/data.json', $array."\n");
					}
				}
				$Message .= '●'.$v['title']."\n•".$v['detail']."\n";
				if(is_array($image))
				{
					foreach($image as $val){
						$Message .= '·'.$val."\n";
					}
				}
				$Message .= '@'.$v['user']['nick'].'('.$v['user']['userID'].")\n\n";
				unset($file, $image, $array);
			}
			$rand = array_rand($data);
			$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>$data), trim($Message));
			return;
		}else{
			$this->evaluation(array('code'=>-7, 'text'=>'获取失败'));
			return;
		}
		return;
	}
	private function register()
	{
		$info = $this->info;
		// $page = $info['page'];
		$num = $info['num'];
		$key = $info['key'];
		$start = $info['start'];
		if(strlen($info['key']) != 112 && need::is_phone($info['phone']) && $info['password'])
		{
			$key = $this->getloginkey($info['phone'], $info['password']);
			if($key === false)
			{
				$this->evaluation(array('code'=>-6, 'text'=>'请输入正确的key'));
				return;
			}
		}else if(strlen($info['key']) != 112){
			$this->evaluation(array('code'=>-6, 'text'=>'请输入正确的key'));
			return;
		}
		$data = json_decode(need::teacher_curl('http://floor.huluxia.com/category/list/ANDROID/2.0?platform=2&gkey=000000&app_version=4.1.1.8.2&versioncode=344&market_id=tool_web&_key='.$key.'&device_code=%5Bd%5D5731263f-ce15-4605-ad0b-15073480574f&phone_brand_type=OP&is_hidden=1'), true);
		$data = array_slice($data['categories'], 3);
		$tags = array();
		$fail_tags = array();
		$i = 0;
		$f = 0;
		foreach($data as $k=>$v)
		{
			$catid = $v['categoryID'];
			$Status = json_decode(need::teacher_curl('http://floor.huluxia.com/user/signin/ANDROID/4.1.8?platform=2&gkey=000000&app_version=4.2.1.6.1&versioncode=367&market_id=tool_web&_key=' . $key . '&device_code=%5Bd%5D9a9a0a26-7279-4a01-bef8-672a2a33a7fe&phone_brand_type=MI&time=' . need::time_sss() . '&cat_id=' . $catid, [
				'Header'=>[
					'Connection: close',
					'Content-length: 37',
					'Host: floor.huluxia.com',
					'Accept-Encoding: gzip',
					'User-Agent: okhttp/3.8.1'
				],
				'ua'=>'okhttp/3.8.1',
				'post'=>'sign=BA23A3A3FF8A911119D9ECCC3640464B'
			]), true);
			// print_r($Status);exit;
			if($Status['status'] == 1)
			{
				$tags[] = $v['title'];
				$i++;
			} else {
				$f++;
				$fail_tags[] = $v['title'];
			}
			unset($Status);
		}
		$i > 0 ? $this->evaluation(array('code'=>1, 'text'=>'签到成功', 'data'=>array('tags'=>$tags, 'count'=>$i, 'fail_tags'=>$fail_tags, 'fail_count'=>$f))) : $this->evaluation(array('code'=>-8, 'text'=>'签到失败', 'data'=>$Status));
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
	private function get($path)
	{
		$this->dir($path);
		if(file_exists($path))
		{
			return file_get_contents($path);
		}else{
			return false;
		}
	}
	private function put($path, $string)
	{
		/* 储存文件 */
		$this->dir($path);
		if(file_put_Contents($path, $string, FILE_APPEND))
		{
			return true;
		}
		return false;
	}
	private function delete()
	{
		/* 删除可能存在的变量 */
		unset($this->array, $this->Message);
		return true;
	}
	private function evaluation(array $array, $string = false)
	{
		/* 统一回复 */
		$this->delete();
		$this->array = $array;
		$this->Message = $string ? $string : $array['text'];
		$this->result();
		return;
	}
	private function highlight()
	{
		Header('content-type: text/html; charset=utf-8');
		echo (highlight_file(__FILE__));
		return;
	}
	private function result()
	{
		/*发送 */
		Switch($this->info['type'])
		{
			case 'text':
			need::send($this->Message, 'text');
			break;
			default:
			need::send($this->array, 'json');
			break;
		}
		return;
	}
}
