<?php
class need{
	public static $info = [];
	/*
	* 获取域名
	*/
	public static function getHost()
	{
		return (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : false);
	}
	/*
	* emoji转换为UTF-32编码
	* @parameter $emoji -> String emoji表情
	*/
	public static function emoji2utf($emoji) {
		$hex = bin2hex(mb_convert_encoding($emoji, 'UTF-32', 'UTF-8'));
		return 'u'.substr($hex, 3);
	}
	/*
	* Skey或pskey进行转码
	*/
	public static function GTK($skey) {
		$len = strlen((String)$skey);
		$hash = 5381;
		for ($i = 0; $i < $len; $i++) {
			$hash += ($hash << 5 & 2147483647) + ord($skey[$i]) & 2147483647;
			$hash &= 2147483647;
		}
		return $hash & 2147483647;
	}
	/*
	* json格式化输出
	*/
	public static function json($arr) {
		header('Content-type: application/json; charset=utf-8;');
		return json_encode($arr,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
	/*
	* 输出
	*/
	public static function send($Msg, $Type = 'jsonp') {
		header('Content-Type:application/json; charset=utf-8;');
		if($Type == 'text') {
			echo $Msg;
			exit();
		}else if($Type == 'location') {
			header('location:'.$Msg);
			exit();
		}else if($Type == 'image') {
			header('Content-type:image/png;image/jpeg;image/gif;');
			//header('Content-type:image/jpeg');
			$curl = New need;
			echo $curl->teacher_curl($Msg);
			exit();
		}else if($Type == 'url') {
			echo $Msg;
			exit();
		}else if($Type == 'tion') {
			echo $Msg;
			exit();
		}else if($Type == 'jsonp') {
			echo stripslashes(json_encode($Msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
			exit();
		}else{
			echo json_encode($Msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			exit();
		}
	}
	/*
	* 获取访问者IP
	*/
	public static function userip() {
		$unknown = 'unknown';
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])&&$_SERVER['HTTP_X_FORWARDED_FOR']&&strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],$unknown)) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],$unknown)) {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	/*
	* qqvip的一种gtk加密，但是已经被腾讯弃用了
	*/
	public static function vipgtk($skey) {
		$salt=5381;
		$md5key='tencentQQVIP123443safde&!%^%1282';
		$hash=array();
		$hash[]=$salt<<5;
		for ($i=0; $i<strlen($skey); ++$i) {
			$acode=ord(substr($skey,$i,1));
			$hash[]=($salt<<5)+$acode;
			$salt=$acode;
		}
		$md5str=md5(join('',$hash).$md5key);
		return $md5str;
	}
	/*
	* 判断访问者是不是get
	*/
	public static function get_post() {
		if( $_SERVER['REQUEST_METHOD'] === 'GET') {
			 return true;
		}else{
			return false;
		}
	}
	/*
	* 获取毫秒时间戳
	*/
	public static function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}
	/*
	* 忘了
	*/
	public static function run_encode($msg) {
		$msg = idn_to_ascii($msg,IDNA_NONTRANSITIONAL_TO_ASCII,INTL_IDNA_VARIANT_UTS46);
		return $msg;
	}
	/*
	* 忘了
	*/
	public static function run_decode($msg) {
		$msg = idn_to_utf8($msg);
		return $msg;
	}
	/*
	* 16进制转码，不过没什么用
	*/
	public static function hex_encode($str) {
		$hex="";
		for($i=0;$i<strlen($str);$i++)
			$hex .= '\\u4E'.dechex(ord($str[$i]));
		$hex=$hex;
		return $hex;
	}
	/*
	* 16进制解码，也没什么用
	*/
	public static function hex_decode($hex) {
		$str="";
		for($i=0;$i<strlen($hex)-1;$i+=2)
			$str.=chr(hexdec($hex[$i].$hex[$i+1]));
		return $str;
	}
	/*
	* unicode 解码
	*/
	public static function decodeUnicode($str) {
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
			@create_function(
				'$matches',
				'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
			),
		$str);
	}
	/*
	* unicode转码
	*/
	public static function encodeUnicodes($str) {
		$decode = json_decode('{"text":"'.$str.'"}',true);
		if(!$decode) {
			return $str;
		}else{
			$encode = json_encode($decode);
			preg_match_all('/text":"(.*?)"/',$encode,$encode);
			$encode = str_replace('\\u4e','',$encode[1][0]);
			$encode = str_replace('\\u4E','',$encode);
			return $encode;
		}
	}
	/*
	* 转码加密
	*/
	public static function jiami($string) {
		$str = self::hex_encode($string);
		$str = self::decodeUnicode($str);
		return ($str);
	}
	/*
	* 转码解密
	*/
	public static function jiemi($string) {
		$str = self::encodeUnicodes($string);
		$str = self::hex_decode($str);
		return $str;
	}
	/*
	* 获取时间戳毫秒
	*/
	public static function time_sss() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}
	/*
	* 获取http状态码
	*/
	public static function http($url) {
		$ch = curl_init();
		$timeout = 3;
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_exec($ch);
		return $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
	}
	/*
	* 判断是不是qq或者群
	*/
	public static function is_num($num) {
		if(preg_match('/^[1-9][0-9]{4,11}$/', (String)$num)) {
			return true;
		}else{
			return false;
		}
	}
	/*
	* Cookie
	*/
	public static function Robot($dir,$key) {
		return self::cookie($key, true);
	}
	/*
	* Cookie
	*/
	public static function cookie($key,$Value = false) {
		return '获取Cookie的方法';
	}
	/*
	* 没用
	*/
	public static function emoji($text) {
		$array = array(
			'🐶'=>'狗',
			'🐱'=>'猫',
			'🐭'=>'鼠',
			'🐹'=>'仓鼠',
			'🐰'=>'兔',
			'🦊'=>'狐狸',
			'🐻'=>'熊',
			'🐼'=>'熊猫',
			'🐨'=>'考拉',
			'🐯'=>'虎',
			'🦁'=>'狮',
			'🐮'=>'牛',
			'🐷'=>'猪',
			'🐽'=>'猪鼻子',
			'🐸'=>'青蛙',
			'🐵'=>'猴',
			'🐔'=>'鸡',
			'🐕'=>'小狗',
			'🐂'=>'小牛',
			'🐴'=>'马',
			'🐎'=>'小马',
			'🐖'=>'小猪',
			'🦆'=>'鸭',
			'🐥'=>'小鸡',
			'🐓'=>'公鸡',
			'🦅'=>'鹰',
			'🦉'=>'猫头鹰',
			'🦇'=>'蝙蝠',
			'🐺'=>'狼',
			'🐗'=>'野猪',
			'🦄'=>'独角兽',
			'🐝'=>'蜜蜂',
			'🐛'=>'虫',
			'🦋'=>'蝴蝶',
			'🐌'=>'蜗牛',
			'🐉'=>'龙',
			'🐟'=>'鱼',
			'🦐'=>'虾',
			'🦞'=>'龙虾',
			'🌶️'=>'辣椒',
			'🦀'=>'螃蟹',
			'🦈'=>'鲨鱼',
			'🌿'=>'草',
			'🌸'=>'花',
			'🍉'=>'瓜',
			'💦'=>'汗',
			'☀️'=>'太阳',
			'🌤'=>'晴转多云',
			'⛅'=>'阴',
			'🌦️'=>'晴转雨',
			'🌧️'=>'小雨',
			'⛈️'=>'雷阵雨',
			'🌩️'=>'打雷',
			'🌧️'=>'大雨',
			'❄️'=>'雪花',
			'🌨️'=>'雪',
			'🌟'=>'闪光星星',
			'⚡'=>'电',
			'💧'=>'水滴',
			'☔'=>'雨伞',
			'🌈'=>'彩虹',
			'🌊'=>'海浪',
			'🌫️'=>'雾',
			'🌪️'=>'龙卷风',
			'☄️'=>'彗星',
			'🪐'=>'有环行星',
			'⭐'=>'星',
			'✨'=>'闪光',
			'👀'=>'看',
			'🌝'=>'微笑月亮',
			'🌞'=>'微笑太阳',
			'🌚'=>'微笑朔月',
			'🌙'=>'月亮',
			'🌛'=>'微笑上弦月',
			'🌜'=>'微笑下弦月',
			'🌕'=>'满月',
			'🌖'=>'亏凸月',
			'🌗'=>'下弦月',
			'🌘'=>'残月',
			'🌔'=>'盈凸月',
			'🌓'=>'上弦月',
			'🌒'=>'娥眉月',
			'🌑'=>'朔月',
			'🚗'=>'汽车',
			'🚌'=>'公交车',
			'🚞'=>'火车',
			'🚚'=>'货车',
			'✈️'=>'飞机',
			'🚕'=>'出租车',
			'🍜'=>'面',
			'🐦'=>'鸟',
			'🚓'=>'警车',
			'🚢'=>'船',
			'☃️'=>'雪人',
			'㊗️'=>'祝',
			'🈷️'=>'月',
			'👍🏻'=>'赞',
			'🍺'=>'啤酒',
			'🎁'=>'礼物',
			'🎆'=>'烟花',
			'🎉'=>'恭喜',
			'🎄'=>'圣诞',
			'🍎'=>'苹果',
			'🍐'=>'梨',
			'🍌'=>'香蕉',
			'🍇'=>'葡萄',
			'🍓'=>'草莓',
			'🍅'=>'西红柿',
			'🍊'=>'橘子',
			'🥚'=>'蛋',
			'🍚'=>'米饭',
			'🦴'=>'骨',
			'🥁'=>'鼓',
			'📖'=>'书',
			'🌲'=>'树',
			'🍋'=>'柠檬',
			'🍟'=>'薯条',
			'🍔'=>'汉堡',
			'🍠'=>'地瓜',
			'🥩'=>'肉',
			'🌹'=>'玫瑰',
			'❤️'=>'心',
			'🍳'=>'煎蛋',
			'✂️'=>'剪刀',
			'🍙'=>'饭团',
			'🦍'=>'猩猩',
			'❤'=>'心',
			'💩'=>'💩',
			'☂️'=>'伞',
			'💰'=>'钱',
			'💵'=>'美元',
			'👄'=>'嘴',
			'💄'=>'口红',
			'🍼'=>'奶瓶',
			'👍🏻'=>'赞',
			'🦟'=>'蚊子',
			'👻'=>'鬼',
			'🐢'=>'乌龟',
			'🐧'=>'企鹅',
			'🐍'=>'蛇',
			'🈲'=>'禁',
			'🔞'=>'十八禁',
			'🐁'=>'小白鼠',
			'✍🏻'=>'写',
			'👟'=>'鞋',
			'⭕'=>'圈',
			'🛠️'=>'工具',
			'🛣️'=>'公路',
			'🚥'=>'路灯',
			'🌀'=>'飓风',
			'👑'=>'皇冠',
			'🥒'=>'黄瓜',
			'🌼'=>'花',
			'💊'=>'药',
			'👨🏻'=>'男',
			'👩🏻'=>'女',
			'👴🏻'=>'爷',
			'👵🏻'=>'奶',
			'✌🏻'=>'耶',
			'🉐'=>'得',
			'㊙️'=>'秘',
			'👅'=>'舔',
			'🉑'=>'可',
			'🈚'=>'无',
			'💃🏻'=>'舞',
			'😭'=>'哭',
			'🙂'=>'微笑',
			'🧵'=>'线',
			'🤪'=>'滑稽',
			'😆'=>'笑',
			'😓'=>'汗',
			'👌🏻'=>'好',
			'🕰️'=>'钟',
			'🀄'=>'中',
			'🚿'=>'洗',
			'🈶'=>'有',
			'🆙'=>'升',
			'🍑'=>'桃',
			'🍵'=>'茶',
			'🍬'=>'糖',
			'🍭'=>'糖',
			'🈯'=>'指',
			'🌰'=>'栗子',
			'😁'=>'嘻',
			'😃'=>'哈',
			'🈳'=>'空',
			'😍'=>'色',
			'🥵'=>'热',
			'🥶'=>'冷',
			'🕳️'=>'洞',
			'👿'=>'恶魔',
			'👏🏻'=>'鼓掌',
			'🤮'=>'吐',
			'😏'=>'坏笑'
		);
		foreach($array as $k=>$v) {
			$text = str_replace($k,$v,$text);
		}
		return $text;
	}
	/*
	* curl
	*/
	public static function teacher_curl($url, $paras = array()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		if (@$paras['Header']) {
			$Header = $paras['Header'];
		} else {
			$Header[] = "Accept:*/*";
			$Header[] = "Accept-Encoding:gzip,deflate,sdch";
			$Header[] = "Accept-Language:zh-CN,zh;q=0.8";
			$Header[] = "Connection:close";
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $Header);
		if (@$paras['ctime']) { // 连接超时
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $paras['ctime']);
		} else {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		}
		if (@$paras['rtime']) { // 读取超时
			curl_setopt($ch, CURLOPT_TIMEOUT, $paras['rtime']);
		}
		if (@$paras['post']) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $paras['post']);
		}else{
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}
		if (@$paras['header']) {
			curl_setopt($ch, CURLOPT_HEADER, true);
		}
		if (@$paras['cookie']) {
			if(@$paras['Cookie']) {
				foreach(explode('; ', $paras['cookie']) as $v)
				{
					curl_setopt($ch, CURLOPT_COOKIE, $v);
				}
			} else {
				curl_setopt($ch, CURLOPT_COOKIE, $paras['cookie']);
			}
		}
		if (@$paras['refer']) {
			if ($paras['refer'] == 1) {
				curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
			} else {
				curl_setopt($ch, CURLOPT_REFERER, $paras['refer']);
			}
		}
		if (@$paras['ua']) {
			curl_setopt($ch, CURLOPT_USERAGENT, $paras['ua']);
		} else {
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
		}
		if (@$paras['nobody']) {
			curl_setopt($ch, CURLOPT_NOBODY, 1);
		}
		if(@$paras['resolve']) {
			curl_setopt($ch, CURLOPT_IPRESOLVE, 1);
		}
		if(@$paras['jump']) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		}
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		/*
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, "114.114.114.114"); //代理服务器地址
		//curl_setopt($ch, CURLOPT_PROXYPORT, 12635); //代理服务器端口
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		*/
		if (@$paras['GetCookie']) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			$result = curl_exec($ch);
			preg_match_all("/Set-Cookie: (.*?);/m", $result, $matches);
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($result, 0, $headerSize); //状态码
			$body = substr($result, $headerSize);
			$ret = [
				"Cookie" => $matches, "body" => $body, "header" => $header, 'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
			];
			curl_close($ch);
			return $ret;
		}
		$ret = curl_exec($ch);
		if(curl_errno($ch))
		{
			curl_close($ch);
			return false;
		}
		if (@$paras['loadurl']) {
			$Headers = curl_getinfo($ch);
			$ret = $Headers['redirect_url'];
		}
		self::$info = curl_getinfo($ch);
		curl_close($ch);
		return $ret;
	}
	/*
	* 随机获取一个IP
	*/
	public static function Rand_IP() {
		#第一种方法，直接生成
		$ip2id= round(rand(600000, 2550000) / 10000);
		$ip3id= round(rand(600000, 2550000) / 10000);
		$ip4id= round(rand(600000, 2550000) / 10000);
		#第二种方法，随机抽取
		$arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
		$randarr= mt_rand(0,count($arr_1)-1);
		$ip1id = $arr_1[$randarr];
		return $ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
	}
	/*
	* get访问获取数据
	*/
	public static function getResponseBody($url) {
		$ch = curl_init();
		#5秒超时
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000);
		#设置默认ua  这里经常测试，尽量用手机的ua,电脑的ua获取不到数据
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36');//'User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; vivo X9 Plus Build/LMY48Z) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36');
		#把随机ip添加进请求头 
		$httpheader = [];
		$httpheader[] = 'X-FORWARDED-FOR:'.self::Rand_IP();
		$httpheader[] = 'CLIENT-IP:'.self::Rand_IP();
		#请求头中添加cookie
		$httpheader[] = 'cookie:did=web_'.md5(time() . mt_rand(1,1000000)).'; didv='.time().'000;';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		#返回数据不直接输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		#设置请求地址
		curl_setopt($ch, CURLOPT_URL, $url);
		#关闭ssl验证
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		#设置默认referer
		curl_setopt($ch, CURLOPT_REFERER, 'https://www.moestack.com');
		#get方式请求
		curl_setopt($ch, CURLOPT_POST, false);
		$contents = curl_exec($ch);
		curl_close($ch);
		return $contents;
	}
	/*
	* get访问获取头部
	*/
	public static function getResponseHeader($url) {
		$ch  = curl_init($url);
		$httpheader = [];
		$httpheader[] = 'X-FORWARDED-FOR:'.self::Rand_IP();
		$httpheader[] = 'CLIENT-IP:'.self::Rand_IP();
		#请求头中添加cookie
		$httpheader[] = 'cookie:did=web_'.md5(time() . mt_rand(1,1000000)).'; didv='.time().'000;clientid=3; client_key=6589'.rand(1000, 9999);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$httpheader);
		#以下两句设置返回响应头不返回响应体
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		#返回数据不直接输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}
	/*
	* 获取get post请求，可以get+号
	*/
	public static function request() {
		$explode = explode('&', $_SERVER['QUERY_STRING']);
		// print_r($explode);
		$Request = [];
		foreach($explode as $value) {
			$explod = explode('=', $value, 2);
			$Request[@$explod[0]] = @$explod[1] ?: null;
			//print_r($explod);
			unset($explod);
		}
		foreach($Request as $k=>$v) {
			if(!empty($v)) {
				$fileType = mb_detect_encoding($v, array('UTF-8','GBK','LATIN1','BIG5','GB2312')) ;
				if($fileType != 'UTF-8') {
					$data = mb_convert_encoding($v, 'utf-8', $fileType);
				}else{
					$data = $v;
				}
			}else{
				$data = $v;
			}
			$array[$k] = urldecode((String) $data);
			unset($data, $fileType);
		}
		// print_r($array);
		return array_merge($array, $_POST);
	}
	/*
	* 读取文件夹内某些文件的绝对路径
	*/
	public static function read_all($dir, ...$type) {
		if (!is_dir($dir)) {
			return array();
		}
		$dir = preg_replace('/\/$/', '', $dir);
		$textarray = [];
		$handle = opendir($dir);
		if ($handle) {
			while (($fl = readdir($handle)) !== false) {
				$temp = iconv('utf-8', 'utf-8', $dir . DIRECTORY_SEPARATOR . $fl);
				//转换成utf-8格式
				//如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
				if (!(is_dir($temp) && $fl != '.' && $fl != '..')) {
					if ($fl != '.' && $fl != '..') {
						$suffix = substr(strrchr($fl, '.'), 1);
						if($type)
						{
							foreach($type as $v) {
								if ($suffix == $v) {
									$textarray[] = array("path" => $dir . DIRECTORY_SEPARATOR, "name" => $fl, 'file'=>$dir.DIRECTORY_SEPARATOR.$fl, 'suffix'=>$suffix);
								}
							}
						} else {
							$textarray[] = array("path" => $dir . DIRECTORY_SEPARATOR, "name" => $fl, 'file'=>$dir.DIRECTORY_SEPARATOR.$fl, 'suffix'=>$suffix);
						}
					}
				}
			}
		}
		return $textarray;
	}
	/*
	* 读取某些文件夹的所有子文件夹
	*/
	public static function read_all_dir($dir) {
		if(!is_dir($dir)) {
			return false;
		}
		$dir = preg_replace('/\/$/', '', $dir);
		$array = scandir($dir);
	   // print_r($array);
		foreach($array as $k=>$v) {
			$temp = iconv('utf-8', 'utf-8', $dir . DIRECTORY_SEPARATOR . $v);
			if(is_dir($temp) && $v != '.' && $v != '..') {
				$dirarray[] = ['name'=>$v, 'path'=>$temp];
			}
		}
		return $dirarray;
	}
	/*
	* 获取跳转
	*/
	public static function loadurl($url, $Array = []) {
		if(!isset($Array['loadurl']) || $Array['loadurl'] != 1) {
			$Array['loadurl'] = 1;
		}
		$Array['nobody'] = 1;
		$urls = self::teacher_curl($url, $Array);
		if(stristr($urls, '//')) {
			return self::loadurl($urls);
		}
		return $url;
	}
	/*
	* ASCII转utf8
	*/
	public static function ASCII_UTF8($string) {
		preg_match_all('/&#([0-9]+);/', $string, $int);
		if(empty($int[1])) {
			return $string;
		}
		foreach($int[1] as $k=>$v) {
			$string = str_replace('&#'.$v.';', chr($v), $string);
		}
		return $string;
	}
	/*
	* 一种加密
	*/
	public static function encrypt($string, $operation, $key='ovooa') {
		$key=md5($key);
		$key_length=strlen($key);
		$string = $operation == 'D' ? str_replace(' ', '+', $string) : $string;
		$string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
		$string_length=strlen($string);
		$rndkey=$box=array();
		$result='';
		for($i=0;$i<=255;$i++) {
			$rndkey[$i]=ord($key[$i%$key_length]);
			$box[$i]=$i;
		}
		for($j=$i=0;$i<256;$i++) {
			$j=($j+$box[$i]+$rndkey[$i])%256;
			$tmp=$box[$i];
				$box[$i]=$box[$j];
				$box[$j]=$tmp;
		}
		for($a=$j=$i=0;$i<$string_length;$i++) {
			$a=($a+1)%256;
			$j=($j+$box[$a])%256;
			$tmp=$box[$a];
			$box[$a]=$box[$j];
			$box[$j]=$tmp;
			$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
		}
		if($operation=='D') {
			if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)) {
				return substr($result,8);
			}else{
				return 'key错误';
			}
		}else{
			return str_replace('=','',base64_encode($result));
		}
	}
	/*
	* 去除空占位符
	*/
	public static function nate($String) {
		return str_replace(Array("\r", "\n", "\r\n", ' '), '', (String) $String);
	}
	public static function is_Skey($Skey) {
		if(strlen(str_replace(' ', '', $Skey)) == 10) {
			return true;
		}else{
			return false;
		}
	}
	/*
	* 判断是否是pskey
	*/
	public static function is_Pskey($Pskey) {
		if(strlen((String) $Pskey) == 44) {
			 //preg_match('/^.{38,46}$/', $Pskey)
			return true;
		}else{
			return false;
		}
	}
	/*
	* 判断是否是手机号
	*/
	public static function is_phone($number) {
		if(preg_match('/^1[1-9][0-9]{9,10}$/', $number))
		{
			return true;
		}else{
			return false;
		}
	}
	/*
	* 忘了
	*/
	public static function strtouni($str)
	{
		return preg_replace('/^"|"$/', '', Json_encode((string)$str));
	}
	/*
	* 忘了
	*/
	public static function unitostr($uni)
	{
		return preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($r) {
			return iconv('UCS-2BE', 'UTF-8', pack('H4', $r[1]));
		},
		$uni);
	}
	/*
	* mb库的split
	*/
	public static function mb_split($string, $split_length = 1, $encoding = null)
	{
		if (null !== $string && !\is_scalar($string) && !(\is_object($string) && \method_exists($string, '__toString'))) {
			trigger_error('mb_str_split(): expects parameter 1 to be string, '.\gettype($string).' given', E_USER_WARNING);
			return null;
		}
		if (null !== $split_length && !\is_bool($split_length) && !\is_numeric($split_length)) {
			trigger_error('mb_str_split(): expects parameter 2 to be int, '.\gettype($split_length).' given', E_USER_WARNING);
			return null;
		}
		$split_length = (int) $split_length;
		if (1 > $split_length) {
			trigger_error('mb_str_split(): The length of each segment must be greater than zero', E_USER_WARNING);
			return false;
		}
		if (null === $encoding) {
			$encoding = mb_internal_encoding();
		} else {
			$encoding = (string) $encoding;
		}
   
		if (! in_array($encoding, mb_list_encodings(), true)) {
			static $aliases;
			if ($aliases === null) {
				$aliases = [];
				foreach (mb_list_encodings() as $encoding) {
					$encoding_aliases = mb_encoding_aliases($encoding);
					if ($encoding_aliases) {
						foreach ($encoding_aliases as $alias) {
							$aliases[] = $alias;
						}
					}
				}
			}
			if (! in_array($encoding, $aliases, true)) {
				trigger_error('mb_str_split(): Unknown encoding "'.$encoding.'"', E_USER_WARNING);
				return null;
			}
		}
		$result = [];
		$length = mb_strlen($string, $encoding);
		for ($i = 0; $i < $length; $i += $split_length) {
				$result[] = mb_substr($string, $i, $split_length, $encoding);
		}
		return $result;
	}
	/*
	* 删除过期文件
	*/
	public static function delfile($dir, $time)
	{
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
	/*
	* 阿拉伯数字转大写数字，有bug
	*/
	public static function chinanum($num)
	{
		$char = array("零","一","二","三","四","五","六","七","八","九");
		$dw = array("","十","百","千","万","亿","兆");
		$retval = "";
		$proZero = false;
		for($i = 0;$i < strlen($num);$i++) {
			if($i > 0)
			{
				$temp = (int)(($num % pow (10,$i+1)) / pow (10,$i));
			}
			else {
				$temp = (int)($num % pow (10,1));
			}
			if($proZero == true && $temp == 0)
			{
				continue;
			}
			if($temp == 0) 
			{
				$proZero = true;
			} else {
				$proZero = false;
			}
			if($proZero)
			{
				if($retval == "")
				{
					continue;
				}
				$retval = $char[$temp].$retval;
			} else {
				$retval = $char[$temp].$dw[$i].$retval;
			}
		}
		if($retval == "一十")
		{
			$retval = "十";
		}
		$retval = str_replace('一十','十',$retval);
		return $retval;
	}
	/*
	* 判断是否是邮箱
	*/
	public static function is_email($email)
	{
		$pattern_test = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i"; 
		return preg_match($pattern_test, $email); 
	}
	/*
	* 忘了
	*/
	public static function getRandomHex($length)
	{
		if (function_exists('random_bytes')) {
			return bin2hex(random_bytes($length / 2));
		}
		if (function_exists('mcrypt_create_iv')) {
			return bin2hex(mcrypt_create_iv($length / 2, MCRYPT_DEV_URANDOM));
		}
		if (function_exists('openssl_random_pseudo_bytes')) {
			return bin2hex(openssl_random_pseudo_bytes($length / 2));
		}
	}
	/*
	* 转码，忘了转什么
	*/
	public static function bchexdec($hex)
	{
		$dec = 0;
		$len = strlen($hex);
		for ($i = 1; $i <= $len; $i++) {
			$dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
		}

		return $dec;
	}
	/*
	* 同上
	*/
	public static function bcdechex($dec)
	{
		$hex = '';
		do {
			$last = bcmod($dec, 16);
			$hex = dechex($last).$hex;
			$dec = bcdiv(bcsub($dec, $last), 16);
		} while ($dec > 0);

		return $hex;
	}
	/*
	* str转16进制
	*/
	public static function str2hex($string)
	{
		$hex = '';
		for ($i = 0; $i < strlen($string); $i++) {
			$ord = ord($string[$i]);
			$hexCode = dechex($ord);
			$hex .= substr('0'.$hexCode, -2);
		}

		return $hex;
	}
}