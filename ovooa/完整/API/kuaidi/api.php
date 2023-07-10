<?
header('Content-type: application/json');
require __DIR__.'/../../need.php';
/* Start */
require (__DIR__."/../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(175); // 调用统计函数*/
$request = need::request();
// print_r($request);
$id = isset($request['id']) ? $request['id'] : false; //快递单号
$type = isset($request['type']) ? $request['type'] : 'json'; //返回格式
new kuaidi(['id'=>$id, 'type'=>$type]);
class kuaidi
{
	private $info = [];
	public $array = [];
	public $message;
	public $Cookie, $token;
	public function __construct(array $array)
	{
		$this->info = $array;
		$this->parametersexception();
	}
	public function parametersexception()
	{
		$info = $this->info;
		if(!$info['id']) return $this->result(['code'=>-1, 'text'=>'请输入需要查询的快递单号']);
		if(!preg_match('/^[a-z0-9]+$/im', $info['id'])) return $this->result(['code'=>-1, 'text'=>'请输入需要查询的快递单号']);
		$this->getCookie();
		$this->start();
		return true;
	}
	public function start()
	{
		$queryUrl = 'https://m.kuaidi100.com/query';
		$rand = lcg_value();
		$type = $this->getType();
		if(!$type)
		{
			return $this->result(['code'=>-2, 'text'=>'暂不支持该快递']);
		} else {
			$post = 'postid='.$this->info['id'].'&id=1&valicode=&temp='.$rand.'&type='.$type.'&phone=&token='.$this->token.'&platform=MWWW';
			$query = json_decode(need::teacher_curl($queryUrl, [
				'post'=>$post,
				'cookie'=>$this->Cookie,
				'Header'=>[
					'Host: m.kuaidi100.com',
					'Connection: keep-alive',
					'Content-Length: '.strlen($post),
					'Accept: application/json, text/javascript, */*; q=0.01',
					'X-Requested-With: XMLHttpRequest',
					'User-Agent: Mozilla/5.0 (Linux; Android 7.1.2; OPPO R11t Build/N6F26Q; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/81.0.4044.117 Mobile Safari/537.36',
					'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
					'Origin: https://m.kuaidi100.com',
					'Sec-Fetch-Site: same-origin',
					'Sec-Fetch-Mode: cors',
					'Sec-Fetch-Dest: empty',
					'Referer: https://m.kuaidi100.com/result.jsp?nu=JD0092947910127',
					'Accept-Encoding: gzip, deflate',
					'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
				],
				'ua'=>'Mozilla/5.0 (Linux; Android 7.1.2; OPPO R11t Build/N6F26Q; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/81.0.4044.117 Mobile Safari/537.36',
				'refer'=>'https://m.kuaidi100.com/result.jsp?nu='.$this->info['id']
			]), true);
			// print_r($query);
			if($query['message'] == 'ok' && isset($query['data'][0]) && $query['data'][0])
			{
				$message = [];
				foreach($query['data'] as $v)
				{
					$message[] = '时间：'.$v['time'].'-'. $v['ftime'];
					$message[] = '事件：'.$v['context'];
					if (isset($v['local'])) $message[] = "地点：{$v['local']}\n";
				}
				return $this->result([
					'code'=>1,
					'text'=>'获取成功',
					'data'=>$query['data']
				], trim(join("\n", $message)));
			} else {
				return $this->result(['code'=>-3, 'text'=>$query['message']]);//'未查询到有关于'.$this->info['id'].'的快递信息']);
			}
		}
	}
	public function getCookie()
	{
		$url = 'https://m.kuaidi100.com/result.jsp?nu='.$this->info['id'];
		$Cookie = join('; ', need::teacher_curl($url, ['GetCookie'=>true])['Cookie'][1]);
		preg_match('/token=(.+);/i', $Cookie, $token);
		$this->Cookie = $Cookie;
		$this->token = $token[1];
		return $Cookie;
	}
	public function getType()
	{
		$AutoUrl = 'https://m.kuaidi100.com/apicenter/kdquerytools.do?method=autoComNum&text='.$this->info['id'];
		$Auto = json_decode(need::teacher_curl($AutoUrl, [
			'post'=>'token=&platfrom=MWWW',
			'cookie'=>$this->Cookie
		]), true);
		$type = false;
		if(isset($Auto['auto'][0]) && $Auto['auto'][0])
		{
			$type = $Auto['auto'][0]['comCode'];
		}
		return $type;
	}
	public function result($array, $message = null)
	{
		$message = $message ? $message : $array['text'];
		$array = $array;
		$info = $this->info;
		$type = isset($info['type']) ? $info['type'] : 'json';
		Switch($type)
		{
			case 'text':
				need::send($message, 'text');
			break;
			default:
				need::send($array, 'json');
			break;
		}
		// print_r($this->array);
		return true;
	}
}