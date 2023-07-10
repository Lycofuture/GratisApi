<?php
//require '../../curl.php';
class music {
    //$pages;
	public function lists() {
		$data_json=$this->Inquire();
		$list=$data_json["data"]["song"]["list"];
		if(is_null($list[0])) {
			$this->code='error';
			return;
		}
		foreach($list as $k=>$v) {
			$singer_end=end($v["singer"]);
			foreach($v["singer"] as $vv) {
				if($vv===$singer_end) {
					$singer.=$vv["name"];
				} else {
					$singer.=$vv["name"]." / ";
				}
			}
			$array[]=$v["songname"]."-".$singer.($v["pay"]["payplay"]?"【付费】":"").($v["alertid"]?"":"【无版权】");
			$singer="";
		}
		$array = ['code'=>1,'text'=>'查询成功','data'=>$array];
		$this->code='ok';
		$this->return=$array;
	}
	public function choose() {
	    /* 解析id */
		$data_json=$this->Inquire();
		$list=$data_json["data"]["song"]["list"][$this->id-1];
		if(is_null($list)){
		    for($i = 0 ; $i < 5 ; $i++){
		        $data_json=$this->Inquire();
		        $list=$data_json["data"]["song"]["list"][$this->id-1];
		        if(!is_null($list)) {
			        //$this->code='id';
			        //return;
			        break;
			    }
			}
		}
		if(is_null($list)) {
			$this->code = 'id';
			return;
		}
		$this->songid = $list['songid'];
		$this->mid = $list["songmid"];
		$music=$this->analyze();
		//解析歌曲
	}
	public function check() {
		preg_match("/\/([\w]+).html/",$this->url,$return);
		if(is_null($return[1])||$return[1]=="playsong") {
			preg_match("/songmid=([\w]+)#/",$this->url,$return);
			if(is_null($return[1])) {
				$this->code = 'mid';
				return;
			}
		}
		$this->mid=$return[1];
		$this->analyze(true);
		//解析歌曲
	}
	public function Inquire($stop=false) {
		$num = $_REQUEST['sc']?:$_REQUEST['num']?:10;
		if($num < 1 || !is_numEric($num)){
		    $num = 10;
		}
		$header=[
		'User-Agent: Mozilla/5.0 (Linux; Android 10; HLK-AL00 Build/HONORHLK-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/78.0.3904.108 Safari/537.36 V1_AND_SQ_8.4.5_1468_YYB_D QQ/8.4.5.4745 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/51 SimpleUISwitch/0 QQTheme/1000 InMagicWin/0',
		'referer: https://y.qq.com/m/mqq/music/search.html?_wv=3&ADTAG=mqq&_wvNb=ffffff&_wvNt=000000',
		];
		$data_msg=Music_curl("https://shc6.y.qq.com/soso/fcgi-bin/search_for_qq_cp?_=".time()."&g_tk=&g_tk_new_20200303=".time()."&uin=2354452553&format=json&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&w=".urlencode($this->song)."&zhidaqu=1&catZhida=1&t=0&flag=1&ie=utf-8&sem=1&aggr=0&perpage=".$num."&n=".$num."&p=".$this->pages."&remoteplace=txt.mqq.all",0,$header);
		if(empty($data_msg)){
		    if($stop){
		        return;
		    }
		    return $this->Inquire(true);
		}
		return json_decode($data_msg,true);
	}
	public function analyze($anew=false) {
	    $skey=$_REQUEST["Skey"]?:need::Robot('../../','Skey');
	    $qq=$_REQUEST["uin"]?:need::Robot('../../','Robot');
	    $Pskey = $_REQUEST['Pskey']?:need::Robot('../../','y.qq.com');
		$header=[
		'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.120 MQQBrowser/6.2 TBS/045714 Mobile Safari/537.36 V1_AND_SQ_8.3.9_340_TIM_D QQ/3.4.0.3018 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/0 QQTheme/1015712',
		'Host: i.y.qq.com',
		'Connection: keep-alive',
		'Upgrade-Insecure-Requests: 1',
		'Sec-Fetch-Mode: navigate',
		'Sec-Fetch-User: ?1',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
		'X-Requested-With: com.tencent.tim',
		'Sec-Fetch-Site: none',
		'Accept-Encoding: gzip, deflate, br',
		'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
		'Cookie: uin=o'.$qq.'; p_uin=o'.$qq.'; p_skey='.$Pskey.'; skey='.$Skey.''//uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$skey.'; p_skey='.$Pskey.';'
		];
		$data = Music_curl('https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=10170509&hosteuin=owok7evkow4koz**&songmid='.$this->mid.'&type=0&appsongtype=1&_wv=1&source=qq&ADTAG=qfshare',0,$header);//Music_curl("https://i.y.qq.com/v8/playsong.html?_wv=14113&geneid=&uin=".$_REQUEST['uin']."&ADTAG=mqq_music&songmid=".$this->mid."&playindex=0",0,$header);
		preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$data,$data_music);
		$data_music = json_decode($data_music[1],true);
		$songurl = $data_music['songList'][0]['url'];
		$pay = $data_music['songList'][0]['pay']['price_album'];
		//echo $pay;
		//print_r($data_music);
		if(empty($songurl) && $pay){
		    $this->code = 'pay';
		    return '';
		}
		if(!$songurl){
			if($anew){
				if($this->num>=3){
					$this->analyze();
				}else{
					$this->analyze();
				}
				$this->num+=1;
				//解析歌曲
				return;
			}else{
			    $cookie = need::cookie('y.qq.com');
			    $header=[
			        'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.120 MQQBrowser/6.2 TBS/045714 Mobile Safari/537.36 V1_AND_SQ_8.3.9_340_TIM_D QQ/3.4.0.3018 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/0 QQTheme/1015712',
			        'Host: i.y.qq.com',
			        'Connection: keep-alive',
			        'Upgrade-Insecure-Requests: 1',
			        'Sec-Fetch-Mode: navigate',
			        'Sec-Fetch-User: ?1',
			        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
			        'X-Requested-With: com.tencent.tim',
			        'Sec-Fetch-Site: none',
			        'Accept-Encoding: gzip, deflate, br',
			        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
			        'Cookie: '.$cookie
		        ];
			    $data = Music_curl('https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=10170509&hosteuin=owok7evkow4koz**&songmid='.$this->mid.'&type=0&appsongtype=1&_wv=1&source=qq&ADTAG=qfshare',0,$header);
			    preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$data,$data_music);
			    $data_music = json_decode($data_music[1],true);
			    $songurl = $data_music['songList'][0]['url'];
			    $pay = $data_music['songList'][0]['pay']['price_album'];
			    if(empty($songurl) && $pay){
			        $this->code = 'pay';
			        return '';
			    }
			    if(!$songurl){
			    	if($anew){
			    		if($this->num>=3){
			    			$this->analyze();
			    		}else{
			    			$this->analyze();
			    		}
			    		$this->num+=1;
			    		//解析歌曲
			    		return;
			    	}else{
					    $this->code='null';
					    return;
					}
				}
			}
		}
		$this->data=$data_music;
		$array=[
		'code'=>1,
		'text'=>'获取成功',
		'data'=>[
		'songid'=>$this->songid,
		"url"=>"http://y.qq.com/n/yqq/song/".$this->mid.".html",
		"singer"=>$this->label('singer'),
		"song"=>$this->label('name'),
		"picture"=>''.$this->label('image'),
		"music"=>$songurl,
		'mid'=>$this->mid
		]];
		$this->code='ok';
		$this->return=$array;
		//缓存两小时
	}
	public function label($name) {
		$data = $this->data;
		Switch($name){
		    case 'name':
		    $return = $data['songList'][0]['name'];
		    break;
		    case 'singer':
		    $singer = $data['songList'][0]['singer'];
		    foreach($singer as $k=>$v){
		        $str .= $v['name']. ' ';
		    }
		    $return = trim($str);
		    break;
		    case 'image':
		    $return = $data['metaData']['image'];
		}
		return $return;
	}
}