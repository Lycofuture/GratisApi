<?php
Header('Content-type: application/json; charset=utf-8');
require '../../need.php';
$type = isset($Request['type']) ? $Request['type'] : 'json';
$num = isset($Request['num']) ? $Request['num'] : '1';

New nowKuai(array('type'=>$type, 'num'=>$num));

class nowKuai
{
	private $array = array();
	private $Message;
	private $info = array();
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
		$this->getApp();
		return;
	}
	private function getApp()
	{
		$url = 'https://www.kuaishou.com/graphql';
		$post = '{"operationName":"brilliantTypeDataQuery","variables":{"hotChannelId":"00","page":"brilliant"},"query":"fragment photoContent on PhotoEntity {\n  id\n  duration\n  caption\n  likeCount\n  viewCount\n  realLikeCount\n  coverUrl\n  photoUrl\n  photoH265Url\n  manifest\n  manifestH265\n  videoResource\n  coverUrls {\n    url\n    __typename\n  }\n  timestamp\n  expTag\n  animatedCoverUrl\n  distance\n  videoRatio\n  liked\n  stereoType\n  profileUserTopPhoto\n  musicBlocked\n  __typename\n}\n\nfragment feedContent on Feed {\n  type\n  author {\n    id\n    name\n    headerUrl\n    following\n    headerUrls {\n      url\n      __typename\n    }\n    __typename\n  }\n  photo {\n    ...photoContent\n    __typename\n  }\n  canAddComment\n  llsid\n  status\n  currentPcursor\n  __typename\n}\n\nfragment photoResult on PhotoResult {\n  result\n  llsid\n  expTag\n  serverExpTag\n  pcursor\n  feeds {\n    ...feedContent\n    __typename\n  }\n  webPageArea\n  __typename\n}\n\nquery brilliantTypeDataQuery($pcursor: String, $hotChannelId: String, $page: String, $webPageArea: String) {\n  brilliantTypeData(pcursor: $pcursor, hotChannelId: $hotChannelId, page: $page, webPageArea: $webPageArea) {\n    ...photoResult\n    __typename\n  }\n}\n"}';
		$Header = [
			'Host: www.kuaishou.com',
			'Connection: keep-alive',
			'Content-Length: '.strlen($post),
			'accept: */*',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
			'content-type: application/json',
			'Origin: https://www.kuaishou.com',
			'X-Requested-With: mark.via',
			'Sec-Fetch-Site: same-origin',
			'Sec-Fetch-Mode: cors',
			'Sec-Fetch-Dest: empty',
			'Referer: https://www.kuaishou.com/brilliant',
			'Accept-Encoding: gzip, deflate',
			'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
		];
		$data = json_decode(need::teacher_Curl($url, [
			'Header'=>$Header,
			'refer'=>'https://www.kuaishou.com/brilliant',
			'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
			'cookie'=>$this->Cookie(),
			'post'=>$post
		]), true);
		// print_r($data);
		$data = isset($data['data']) ? $data['data'] : false;
		$data = isset($data['brilliantTypeData']) ? (isset($data['brilliantTypeData']['feeds']) ? $data['brilliantTypeData']['feeds'] : false) : false;
		if(!$data)
		{
			$this->evaluation(array('code'=>-1, 'text'=>'获取失败'));
			return;
		}
		$array = array();
		foreach($data as $v)
		{
			$array[] = array('author'=>$v['author'], 'data'=>array_slice($v['photo'], 0, 9));
		}
		if(!$array){
			$this->evaluation(array('code'=>-1, 'text'=>'获取失败'));
			return;
		}else{
			$data = $array[0]['data'];
			$cover = $data['coverUrl'];
			$title = $data['caption'];
			$like = $data['likeCount'];
			$video = $data['photoUrl'];
			$BGM = $data['photoH265Url'];
			// print_r($array);
			$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>$array), "±img={$cover}±\n标题：{$title}\n点赞数量：{$like}\n视频：{$video}\nHD：{$BGM}");
			return;
		}
		// print_r($array);
	}
	private function Cookie()
	{
		return 'did=web_'.md5(time() . mt_rand(1,1000000)).'; didv='.time().'000;clientid=3; client_key=6589'.rand(1000, 9999);
	}
	private function delete()
	{
		unset($this->array, $this->Message, $this->data);
		return true;
	}
	private function evaluation(array $array, $string = false)
	{
		$this->delete();
		$this->array = $array;
		$this->Message = $string ? $string : $array['text'];
		$this->result();
		return;
	}
	private function result()
	{
		Switch($this->info['type'])
		{
			case 'text':
			need::send($this->Message, 'text');
			break;
			default:
			need::send($this->array);
			break;
		}
		return;
	}
}