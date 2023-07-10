<?
header('Content-type: application/json; charset=utf-8');
require_once __DIR__ . '/../../need.php';
$request = need::request();
$type = isset($request['type']) ? $request['type'] : 'json';
$type = isset($request['user']) ? $request['user'] : '';
new douyin_user(['user'=>'王者荣耀', 'type'=>$type]);

class douyin_user
{
	public function __construct(public $Info)
	{
		$this->parametersException();
	}
	public function parametersException()
	{
		if(!$this->Info['user']) return $this->result(['code'=>-1, 'text'=>'请输入需要搜索的用户']);
		$this->user();
	}
	public function user()
	{
		$Info = $this->Info;
		$user = urlencode($Info['user']);
		$url = "https://www.douyin.com/aweme/v1/web/discover/search/?aid=6383&keyword={$user}&count=8&X-Bogus=1-1";
		$data = json_decode(need::teacher_curl($url, [
			'Header'=>[
				'Host: www.douyin.com',
				'Connection: keep-alive',
				'accept: application/json, text/plain, */*',
				'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
				'X-Requested-With: mark.via',
				'Sec-Fetch-Site: same-origin',
				'Sec-Fetch-Mode: cors',
				'Sec-Fetch-Dest: empty',
				"Referer: https://www.douyin.com/search/{$user}?source=switch_tab&type=user",
				'Accept-Encoding: gzip, deflate',
				'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
				'Cookie: passport_csrf_token=1'
			],
			'cookie'=>'passport_csrf_token=1',
			'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
		]));
		$array = array();
		$message = null;
		if(isset($data->user_list) && $data->user_list)
		{
			foreach($data->user_list as $k=>$v)
			{
				$v = $v->user_info;
				$array[] = [
					'uid' => $v->uid,
					'short_id' => $v->short_id,
					'unique_id'=>$v->unique_id,
					'sec_uid'=>$v->sec_uid,
					'name'=>$v->nickname,
					'signature'=>$v->signature,
					'head'=>str_replace('100x100', "{$v->avatar_thumb->width}x{$v->avatar_thumb->height}", $v->avatar_thumb->url_list[0]),
					'follower_count'=>$v->follower_count,
					'total_favorited'=>$v->total_favorited,
					'user_tags'=>$v->user_tags,
					'custom_verify'=>$v->custom_verify
				];
				if(isset($v->enterprise_verify_reason)) $array[$k]['enterprise_verify_reason'] = $v->enterprise_verify_reason;
			}
			return $this->result(['code'=>1, 'text'=>'获取成功', 'data'=>$array]);
		}
		print_r($data);
	}
	public function result($array, $message = null)
	{
		$message = $message ? $message : $array['text'];
		switch($this->Info['type'])
		{
			case 'text':
				need::send($message, 'text');
			break;
			default:
				need::send($array, 'json');
			break;
		}
	}
}