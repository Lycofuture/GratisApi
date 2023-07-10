<?php
header("content-type:application/json");
/* Start */

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(9); // 调用统计函数*/
require '../../need.php';
/* End */
$request = need::request();
$Msg = @$request["msg"]?:@$request['Msg'];
$n = @$request["n"];
$type = @$request["type"];
$page = @$request["p"]?:@$request['page']?:1;//翻页，默认第一页
$num = @$request["sc"]?:@$request['num']?:10;//输出条数，默认10条
$tail = @$request['tail']?:'网易云音乐';
$br = isset($request['br']) ? $request['br'] : 1;
New Music_163(Array('Name'=>$Msg, 'n'=>$n, 'page'=>$page, 'num'=>$num, 'type'=>$type, 'tail'=>$tail, 'br'=>$br));

class Music_163{
	protected $info = [];
	public $Array = [];
	public $Msg;
	public $data;
	public $header = array(
			'user-Agent: Mozilla/5.0 (Linux; Android 6.0.1; OPPO A57 Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
			'Accept: */*',
			'Referer: http://music.163.com/',
			'X-Requested-With: XMLHttpRequest',
			'Content-Type: application/x-www-form-urlencoded'
		);
	public function __construct($array){
		// echo http_build_query($array);
		echo need::teacher_curl('http://backup.api.lkaa.top/netease.php', [
			'post'=>http_build_query($array)
		]);
		exit();
		foreach($array as $k=>$v){
			$this->info[$k] = $v;
		}
		$this->ParameterException();
	}
	public function ParameterException(){
		$num = $this->info['num'];
		if($num < 1 || !is_numEric($num)){
			$this->info['num'] = 10;
		}
		$page = $this->info['page'];
		if($page < 1 || !is_numEric($page)){
			$this->info['page'] = 1;
		}
		$Name = need::nate($this->info['Name']);
		if(empty($Name)){
			unset($this->Array, $this->Msg);
			$this->Array = Array('code'=>-1, 'text'=>'请输入歌名');
			$this->Msg = '请输入歌名';
			$this->returns($this->info['type']);
			return;
		}else{
			$this->info['Name'] = urldecode($Name);
		}
		$n = $this->info['n'];
		if(!empty($n)){
			if($n < 1 || !is_numEric($n) || $n > $this->info['num']){
				$this->info['n'] = 0;
			}
		}else{
			$this->info['n'] = 0;
		}
		$this->br();
		$this->GetName();
		return;
	}
	public function br()
	{
		if(isset($this->info['br']))
		{
			return $this->info['br'] = match($this->info['br']) {
				1, '1' => 320000,
				2, '2' => 192000,
				3, '3' => 128000,
				'4', 4 => 885154,
				default => 320000
			};
		} else {
			return $this->info['br'] = 320000;
		}
	}
	public function GetName(){
		$Name = $this->info['Name'];
		$page = $this->info['page'];
		$num = $this->info['num'];
		$Array = $this->encode_netease_data([
			'method' => 'POST',
			'url' => 'http://music.163.com/api/cloudsearch/pc',
			'params' => [
				's' => $Name,
				'type' => 1,
				'offset' => (($page -1)*$num),//(($p * 10) - 10),
				'limit' => $num
			]
		]);
		$post = http_build_query($Array);
		$url = 'http://music.163.com/api/linux/forward';
		// $url = 'http://music.163.com/api/search/get/web?csrf_token=hlpretag=&hlposttag=&s=%E5%AD%A4%E5%8B%87%E8%80%85&type=1&offset=0&total=true&limit=2';
		// echo need::teacher_curl($url);
		// exit;
		$data = json_decode($this->curl($url, $post, null), true);
		$code = $data['code'];//状态码 没啥用
		$count = $data['result']['songCount'];//歌曲数量
		$data = $data['result']['songs'];//歌曲列表
		if($count < 1 || empty($data)){
			unset($this->Array, $this->Msg);
			$this->Array = Array('code'=>-2, 'text'=>'搜索失败，没有找到有关于'.$Name.'的歌曲');
			$this->Msg = '搜索失败，没有找到有关于'.$Name.'的歌曲';
			$this->returns($this->info['type']);
			return ;
		}
		$this->data = $data;
		$this->Analysis();
		return ;
	}
	public function Analysis(){
		$n = $this->info['n'];
		if($n == 0){
			$data = $this->data;
			$Msg = null;
			$Array = $text = [];
			foreach($data as $k=>$v){
				$singer = [];
				$Name = $v['name'];
				$singer_Array = $v['ar'];
				foreach($singer_Array as $value){
					$singer[] = $value['name'];
				}
				$singers = join(', ', $singer);
				$Msg .= ($k+1).'.'.$Name . '—' . $singers."\n";
				$Array[] = Array('song' => $Name, 'singer' => $singer, 'singers'=>$singer);
				$text[] = $Name . '—' . $singers;
			}
			unset($this->Array, $this->Msg);
			$this->Msg = trim($Msg);
			$this->Array = Array('code'=>1, 'text'=>'歌曲列表获取成功', 'data'=>$Array, 'Msg'=>$text);
		}else{
			$data = $this->data;
			$n = ($n - 1);
			if(!isset($data[$n])){
				$this->info['n'] = 0;
				$this->Analysis();
				return;
			}else{
				$data = $data[$n];
				$singer = null;
				$Name = str_replace(Array('"', "'"), Array('\\"', "\\'"), $data['name']);//歌名
				$Cover = $data['al']['picUrl'];//封面图
				$song_id = $data['id'];//歌曲id
				$singer_Array = $data['ar'];
				foreach($singer_Array as $v){
					$singer .= $v['name'].',';
				}
				$singer = trim($singer, ',');//歌手
				
				$api = $this->netease_AESCBC(array(
					'method' => 'POST',
					'url'	=> 'http://music.163.com/api/song/enhance/player/url',
					'body'   => array(
						'ids' => array($song_id),
						'br'  => $this->info['br'],
					)
				));
				$this->header[] = 'Cookie: MUSIC_U=086138c6dd0abe279c79faae59b8ec92d923009ef0845b56db03aa41d38fd8bc993166e004087dd31b40e32efa3219778aa80f00292137a6e25a09c308aa478bf77e48bf7c1db4ecd4dbf082a8813684'; //'Cookie: MUSIC_U='.$api['body']['encSecKey'];
				$data = json_decode($this->curl($api['url'], http_build_query($api['body']), null), true);
				// print_r($data);exit;
				if (isset($data['data'][0]['uf']['url'])) {
					$data['data'][0]['url'] = $data['data'][0]['uf']['url'];
				}
				$url = isset($data['data'][0]['url']) && $data['data'][0]['url'] ? $data['data'][0]['url'] : 'http://music.163.com/song/media/outer/url?id='.$song_id;
				if(str_contains($url, 'authSecret'))
				{
					$url = preg_replace('/(=[0-9a-z]{30})[0-9a-z]+$/', '$1', $url);
				}
				unset($this->Array, $this->Msg);
				$this->Msg = '±img='.$Cover."±\n歌曲：{$Name}\n歌手：{$singer}\n歌曲链接：{$url}";
				$this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>['Id'=>$song_id, 'Music'=>$Name,'Cover'=>$Cover,'Singer_Array'=>explode(',', $singer),'Singer'=>$singer, 'Url'=>$url,'Music_Url'=>'https://music.163.com/#/song?id='.$song_id]);
			}
		}
		$this->returns($this->info['type']);
		return;
	}
	public function curl($url, $data, $m){
		$header = $this->header;
		//设置请求头
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
		if($data==null){
			
		}else{
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_HEADER, 0);//设置返回头
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec($curl); 
		if (curl_errno($curl)) {
			echo 'Errno'.curl_error($curl);
		}
		curl_close($curl); 
		return $result; 
	}
	public function encode_netease_data($data){
		$_key = '7246674226682325323F5E6544673A51';
		$data = json_encode($data);
		if (function_exists('openssl_encrypt')) {
			$data = openssl_encrypt($data, 'aes-128-ecb', pack('H*', $_key));
		}else{
			$_pad = 16 - (strlen($data) % 16);
			$data = base64_encode(mcrypt_encrypt(
				MCRYPT_RIJNDAEL_128,
				hex2bin($_key),
				$data.str_repeat(chr($_pad), $_pad),
				MCRYPT_MODE_ECB
				)
			);
		}
		$data = strtoupper(bin2hex(base64_decode($data)));
		return ['eparams' => $data];
	}
	public function returns($type){
		if(empty($this->Array['data']['Music'])){
			Switch($type){
				case 'text':
				need::send($this->Msg, 'text');
				break;
				default:
				need::send($this->Array, 'json');
				break;
			}
		}else{
			$data = $this->Array['data'];
			$Name = $data['Music'];
			$Singer = $data['Singer'];
			$Music = $data['Url'];
			$Cover = $data['Cover'];
			$Music_Url = $data['Music_Url'];
			$Url = need::teacher_curl($Music, ['loadurl'=>true]);
			$tail = $this->info['tail'];
			Switch($type){
				case 'text':
				need::send($this->Msg, 'text');
				break;
				case 'xml':
				echo "card:3<?xml version='1.0' encoding='UTF-8' standalone='yes' ?>";
				need::send('<msg serviceID="2" templateID="1" action="web" brief="[分享]'.str_replace('&','&amp;',$Name).'" sourceMsgId="0" url="'.$Music_Url.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$Cover.'" src="'.$Music.'" /><title>'.str_replace('&','&amp;',$Name).'</title><summary>'.str_replace('&','&amp;',$Singer).'</summary></item><source name="'.$tail.'" icon="http://p3.music.126.net/F4LudfJWGfPRHe8tAArJ1A==/109951163421193595.png" url="" action="app" a_actionData="" i_actionData="" appid="100495085\" /></msg>', 'text');
				break;
				case 'json':
				need::send('json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$Name.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100495085,"ctime":1646799816,"desc":"'.$Singer.'","jumpUrl":"'.$Music_Url.'","musicUrl":"'.$Music.'","preview":"'.$Cover.'","sourceMsgId":"0","source_icon":"https://i.gtimg.cn/open/app_icon/00/49/50/85/100495085_100_m.png","source_url":"","tag":"'.$tail.'","title":"'.$Name.'","uin":2354452553}},"config":{"ctime":'.Time().',"forward":true,"token":"549b5afa08722eace91fdf1334a0a8c3","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100495085,\"uin\":2354452553}"}', 'text');
				break;
				default:
				$this->Array['data']['dataUrl'] = $Url;
				need::send($this->Array, 'json');
				break;
			}
		}
		return;
	}
	private function netease_AESCBC($api)
	{
		$modulus = '157794750267131502212476817800345498121872783333389747424011531025366277535262539913701806290766479189477533597854989606803194253978660329941980786072432806427833685472618792592200595694346872951301770580765135349259590167490536138082469680638514416594216629258349130257685001248172188325316586707301643237607';
		$pubkey = '65537';
		$nonce = '0CoJUm6Qyw8W8jud';
		$vi = '0102030405060708';

		if (extension_loaded('bcmath')) {
			$skey = need::getRandomHex(16);
		} else {
			$skey = 'B3v3kH4vRPWRJFfH';
		}

		$body = json_encode($api['body']);

		if (function_exists('openssl_encrypt')) {
			$body = openssl_encrypt($body, 'aes-128-cbc', $nonce, false, $vi);
			$body = openssl_encrypt($body, 'aes-128-cbc', $skey, false, $vi);
		} else {
			$pad = 16 - (strlen($body) % 16);
			$body = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $nonce, $body.str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $vi));
			$pad = 16 - (strlen($body) % 16);
			$body = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $skey, $body.str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $vi));
		}

		if (extension_loaded('bcmath')) {
			$skey = strrev(utf8_encode($skey));
			$skey = need::bchexdec(need::str2hex($skey));
			$skey = bcpowmod($skey, $pubkey, $modulus);
			$skey = need::bcdechex($skey);
			$skey = str_pad($skey, 256, '0', STR_PAD_LEFT);
		} else {
			$skey = '85302b818aea19b68db899c25dac229412d9bba9b3fcfe4f714dc016bc1686fc446a08844b1f8327fd9cb623cc189be00c5a365ac835e93d4858ee66f43fdc59e32aaed3ef24f0675d70172ef688d376a4807228c55583fe5bac647d10ecef15220feef61477c28cae8406f6f9896ed329d6db9f88757e31848a6c2ce2f94308';
		}
		// echo $skey;
		$api['url'] = str_replace('/api/', '/weapi/', $api['url']);
		$api['body'] = array(
			'params'	=> $body,
			'encSecKey' => $skey,
		);

		return $api;
	}
}
