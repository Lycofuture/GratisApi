<?php
Header('Content-type: application/json;');

require '../need.php';
//https://yun.xingyaox.com/music.php?input=1441758494&filter=id&type=netease&page=1&url=music.ovooa.com
$Request = need::request();
$type = $Request['type'];
$filter = $Request['filter'];
$page = $Request['page'];
$url = $Request['url'];
$input = $Request['input'];
$Request = http_build_query($Request);

if($type == 'kugou'){
    new 酷狗音乐(['id'=>$input]);
    die();
}

need::send(need::teacher_curl('https://yun.xingyaox.com/music.php?'.$Request), 'text');



Switch($type){
    case 'netease':
    new 网抑云(Array('Name'=>$Msg, 'n'=>1, 'page'=>$page, 'num'=>$num, 'type'=>$type, 'id'=>$input));
    break;
    case 'kugou':
    break;
    case 'QQ':
    New QQ_Music_Ordinary(Array('Name'=>$Msg, 'n'=>1, 'page'=>$page, 'num'=>$num, 'id'=>$input, 'type'=>$type));
    break;
    case 'kuwo':
    break;
}


class 酷狗音乐{
    protected $info = [];
    protected $Array = [];
    protected $data;
    protected $id;
    public function __construct(Array $Array){
        foreach($Array as $k => $v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        //$this->GetName();
        $id = $this->info['id'];
        if($id){
            $this->Getid();
        }else{
            return false;
        }
    }
    public function Getid(){
        $id = $this->info['id'];
        $url = 'http://trackercdn.kugou.com/i/v2/?hash='.$id.'&key='.Md5($id.'kgcloudv2').'&pid=3&behavior=play&cmd=25&version=8990';
        //$data = json_decode(need::teacher_curl($url));
        $url = 'http://kugou.wamzw.cf/api/kugou.php';
        $data = json_decode(need::teacher_curl($url.'?r=play/getdata&hash='.$id), true);//*/
        $data = [
            'title'=>$data['songName'],
            'author'=>$data['author_name'],
            'songid'=>$data['hash'],
            'url'=>$data['url'],
            'pic'=>str_replace('{size}', '480', $data['imgUrl'])
        ];
        //need::send(Array('code'=>1, 'text'=>'获取成功', 'data'=>$data));
        need::send($data);
        return true;
    }
    public function GetName(){
                $api = array(
                'method' => 'POST',
                'url'    => 'http://media.store.kugou.com/v1/get_res_privilege',
                'body'   => json_encode(
                    array(
                    'relate'    => 1,
                    'userid'    => '0',
                    'vip'       => 0,
                    'appid'     => 1000,
                    'token'     => '',
                    'behavior'  => 'download',
                    'area_code' => '1',
                    'clientver' => '8990',
                    'resource'  => array(array(
                        'id'   => 0,
                        'type' => 'audio',
                        'hash' => '2CA0B75E368050248277AB1E796992DC',
                    )), )
                ),
            );
            $url = 'https://wwwapi.kugou.com/yy/index.php';//.$datas['FileHash'];
            $url = 'http://kugou.wamzw.cf/api/kugou.php';
            $data = json_decode(need::teacher_curl($url.'?r=play/getdata&hash=2CA0B75E368050248277AB1E796992DC'), true);;//*/
            print_r($data);exit;
            $Music = $data['url'];
        return;
    }
    public function returns(){
        $type = $this->info['type'];
        $data = $this->Array;
        $Msg = $this->Msg;
        if(!$data['data']['song']){
            Switch($type){
                case 'text':
                need::send($Msg, 'text');
                break;
                default:
                need::send($data, 'json');
                break;
            }
        }else{
            $Name = $data['data']['song'];//歌名
            $Url = $data['data']['Music'];//歌曲链接
            $Music = $data['data']['Music_Url'];//在线播放
            $Singer = $data['data']['singer'];//歌手
            $Cover = $data['data']['cover'];//封面图
            $tail = $this->info['tail'];
            Switch($type){
                case 'json':
                need::send('json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$Name.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":205141,"ctime":1646802051,"desc":"'.$Singer.'","jumpUrl":"'.$Music.'","musicUrl":"'.$Url.'","preview":"'.$Cover.'","sourceMsgId":"0","source_icon":"https:\/\/open.gtimg.cn\/open\/app_icon\/00\/20\/51\/41\/205141_100_m.png?t=1639645811","source_url":"","tag":"'.$tail.'","title":"'.$Name.'","uin":2830877581}},"config":{"ctime":1646802051,"forward":true,"token":"b0407688307d8c9b10a6c0277a53f442","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":205141,\"uin\":2830877581}"}', 'text');
                //need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"酷狗音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$Singer.'","jumpUrl":"'.$Music.'","musicUrl":"'.$Url.'","preview":"'.$Cover.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$Name.'"}},"prompt":"[分享]'.$Name.'","ver":"0.0.0.1","view":"music"}', 'text');
                break;
                case 'xml':
                echo "card:3<?xml version='1.0' encoding='UTF-8' standalone='yes' ?>";
                need::send('<msg serviceID="2" templateID="1" action="web" brief="[分享]'.str_replace('&','&amp;', $Name).'" sourceMsgId="0" url="'.str_replace('&','&amp;', $Music).'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.str_replace('&','&amp;', $Cover).'" src="'.str_replace('&','&amp;', $Url).'" /><title>'.str_replace('&','&amp;', $Name).'</title><summary>'.str_replace('&','&amp;', $Singer).'</summary></item><source name="'.$tail.'" icon="https://www.kugou.com/root/favicon.ico" action="app" a_actionData="" i_actionData="" appid="100497308" /></msg>', 'text');
                break;
                case 'text':
                need::send($Msg, 'text');
                break;
                default:
                need::send($data, 'json');
                break;
            }
        }
        return;
    }
}




class 网抑云{
    protected $info = [];
    public $Array = [];
    public $Msg;
    public $data;
    public function __construct(Array $Array){
        foreach($Array as $k=>$v){
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
        $id = $this->info['id'];
        if(empty($Name) && !$id){
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
        if($id){
            $this->解析歌曲($id);
        }
        $this->GetName();
        return;
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
            foreach($data as $k=>$v){
                $Name = $v['name'];
                $singer_Array = $v['ar'];
                foreach($singer_Array as $value){
                    $singer .= $value['name'].',';
                }
                $singer = trim($singer, ',');
                $Msg .= ($k+1).'.'.$Name . '—' . $singer."\n";
                $Array[] = Array('song' => $Name, 'singer' => explode(',', $singer),'singers'=>$singer);
                $text[] = $Name . '—' . $singer;
                unset($singer);
            }
            unset($this->Array, $this->Msg);
            $this->Msg = trim($Msg);
            $this->Array = Array('code'=>1, 'text'=>'歌曲列表获取成功', 'data'=>$Array, 'Msg'=>$text);
        }else{
            $data = $this->data;
            $n = ($n - 1);
            if(!$data[$n]){
                $this->info['n'] = 0;
                $this->Analysis();
                return;
            }else{
                $data = $data[$n];
                $Name = str_replace(Array('"', "'"), Array('\\"', "\\'"), $data['name']);//歌名
                $Cover = $data['al']['picUrl'];//封面图
                $song_id = $data['id'];//歌曲id
                $singer_Array = $data['ar'];
                foreach($singer_Array as $v){
                    $singer .= $v['name'].',';
                }
                $singer = trim($singer, ',');//歌手
                $url = 'http://music.163.com/song/media/outer/url?id='.$song_id;
                unset($this->Array, $this->Msg);
                $this->Msg = '±img='.$Cover."±\n歌曲：{$Name}\n歌手：{$singer}\n歌曲链接：{$url}";
                $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>['Id'=>$song_id, 'Music'=>$Name,'Cover'=>$Cover,'Singer_Array'=>explode(',', $singer),'Singer'=>$singer, 'Url'=>$url,'Music_Url'=>'https://music.163.com/#/song?id='.$song_id]);
            }
        }
        $this->returns($this->info['type']);
        return;
    }
    public function curl($url,$data,$m){
        $header=array(
            'user-Agent: Mozilla/5.0 (Linux; Android 6.0.1; OPPO A57 Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Accept: */*',
            'Referer: http://music.163.com/',
            'X-Requested-With: XMLHttpRequest',
            'Content-Type: application/x-www-form-urlencoded'
        );
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
    public function 解析歌曲($id){
        $url = 'http://music.163.com/api/song/detail/?id='.$id.'&ids=%5B'.$id.'%5D&csrf_token=';
        $data = json_decode(need::teacher_curl($url),true);
        $Music = [];
        $Music['music'] = $data['songs'][0]['name'];
        $Music['name'] = $data['songs'][0]['artists'][0]['name'];
        $Music['image'] = $data['songs'][0]['album']['picUrl'];
        $Music['musicurl'] = 'http://music.163.com/song/media/outer/url?id='.$id;
        $Music['id'] = $id;
        $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>$Music);
        $this->returns();
        return ;
    }
    public function returns($type = ''){
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
            $tail = $this->info['tail'];
            need::send($this->Array, 'json');
        }
        return;
    }
}

class QQ_Music_Ordinary{
    protected $info = [];
    protected $Msg;
    protected $Array = [];
    protected $data;
    protected $id;
    protected $header=[
		'Host: shc6.y.qq.com',
		'Connection: keep-alive',
		'Upgrade-Insecure-Requests: 1',
		'Sec-Fetch-Mode: navigate',
		'Sec-Fetch-User: ?1',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
		'dnt: 1',
		'X-Requested-With: mark.via',
		'Sec-Fetch-Site: none',
		'Sec-Fetch-Mode: navigate',
		'Sec-Fetch-User: ?1',
		'Sec-Fetch-Dest: document',
		'Accept-Encoding: gzip, deflate, br',
		'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
	];
    public function __construct(Array $Array){
        foreach($Array as $k => $v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        $Name = $this->info['Name'];
        $id = $this->info['id'];
        if(empty(need::nate($Name)) && !$id){
            unset($this->Array , $this->Msg);
            $this->Array = Array('code'=>-1, 'text'=>'请输入歌名');
            $this->Msg = '请输入歌名';
            $this->returns();
            return;
        }
        $num = $this->info['num'];
        if($num < 1 || !is_numEric($num)){
            $this->info['num'] = 10;
        }
        $page = $this->info['page'];
        if($page < 1 || !is_numEric($page)){
            $this->info['page'] = 1;
        }
        $n = $this->info['n'];
        if(empty($n) || !is_numEric($n) || $n < 1 || $n > $this->info['num']){
            $this->info['n'] = 0;
        }
        if($id){
            $this->id = $id;
            $this->labe();
            return;
        }
        $this->GetName();
    }
    public function GetName(){
        $Name = $this->info['Name'];
        $num = $this->info['num'];
        $page = $this->info['page'];
        $url = 'https://shc6.y.qq.com/soso/fcgi-bin/search_for_qq_cp?_='.time().'&g_tk='.need::GTK($Pskey).'&g_tk_new_20200303='.time().'&uin=2354452553&format=json&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&w='.urlencode($Name).'&zhidaqu=1&catZhida=1&t=0&flag=1&ie=utf-8&sem=1&aggr=0&perpage='.$this->info['num'].'&n='.$this->info['num'].'&p='.$this->info['page'].'&remoteplace=txt.mqq.all';
        $data = json_decode(need::teacher_curl($url, [
            'refer'=>'https://y.qq.com/',
            'Header'=>$this->header
        ]), true);
        $data = @$data["data"]["song"]["list"];
        //print_r($data);exit;
        if(empty($data)){
            unset($this->Array , $this->Msg);
            $this->Array = Array('code'=>-2, 'text'=>'歌曲列表获取失败');
            $this->Msg = '歌曲列表获取失败';
            $this->returns();
            return;
        }
        $this->data = $data;
        $this->Analysis();
        return;
    }
    public function Analysis(){
        $data = $this->data;
        $n = $this->info['n'];
        if($n == 0){
            foreach($data as $k=>$v){
                $pay = $v["pay"]["payplay"];
                if($pay == 0){
                    $pay = '[免费]';
                }else{
                    $pay = '[收费]';
                }
                $Name = $v['songname'];//歌名
                $singer_Array = $v['singer'];//歌手数据
                foreach($singer_Array as $value){
                    $singer .= $value['name'].',';
                }
                $singer = trim($singer, ',');//歌手
                $Msg .= ($k+1).'.'.$Name."—{$singer}{$pay}\n";
                $text[] = $Name."—{$singer}{$pay}";
                $Array[] = Array('song'=>$Name, 'singer'=>explode(',', $singer), 'singers'=>$singer,'pay'=>$pay=='[免费]'?false:true);
                unset($singer, $singer_Array, $Name, $pay, $value, $k, $v);
            }
            unset($this->Array , $this->Msg);
            $this->Array = Array('code'=>1, 'text'=>'歌曲列表获取成功', 'data'=>$Array, 'Msg'=>$text);
            $this->Msg = trim($Msg);
            $this->returns();
            return;
        }else{
            $n = ($n - 1);
            if(empty(need::nate($data[$n]))){
                $this->info['n'] = 0;
                $this->Analysis();
                return;
            }else
            if($data[$n]['pay']['payplay'] != 0){
                unset($this->Array , $this->Msg);
                $this->Array = Array('code'=>-3, 'text'=>'付费歌曲，请换首歌');
                $this->Msg = '付费歌曲，请换首歌';
                $this->returns();
                return;
            }else{
                $data = $data[$n];
                $mid = $data['songmid'];
                $Name = $data['songname'];//歌名
                $singer_Array = $data['singer'];
                foreach($singer_Array as $v){
                    $singer .= $v['name'].',';
                }
                $singer = trim($singer, ',');
                $singer_Array = explode(',', $singer);
                $Music = $this->curl($mid);//歌曲链接
                $Cover = 'http://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['albummid'].'.jpg';//图片
                unset($this->Array , $this->Msg);
                $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('Id'=>$mid, 'Music'=>$Name, 'Cover'=>$Cover, 'Singer_Array'=>$singer_Array, 'Singer'=>$singer, 'Url'=>$Music, 'Music_Url'=>'http://y.qq.com/n/yqq/#/'.$mid.'.html'));
                $this->Msg = "±img={$Cover}±\n歌曲：{$Name}\n演唱者：{$singer}\n歌曲链接：{$Music}";
                $this->id = $data['songid'];
                $this->Getlyric();
                $this->returns();
                return;
            }
        }
    }
    public function Getlyric(){
        $data = json_decode(str_replace(array('jsonp(',')'),'',need::teacher_curl('https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg?g_tk=5381&uin=0&format=json&jsonpCallback=jsonp&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&nobase64=1&musicid='.$this->id.'&songtype=0&_=1513437581324',[
            'refer'=>'https://c.y.qq.com/'
        ])),true);
        $lyric = need::ASCII_UTF8($data['lyric']);
        $this->lyric = $lyric;
        return;
    }
    public function label() {
        $url = 'https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=10170509&hosteuin=owok7evkow4koz**&songmid='.$this->id.'&type=0&appsongtype=1&_wv=1&source=qq&ADTAG=qfshare';
        $data = need::teacher_curl($url, ['refer'=>'https://i.y.qq.com/']);
        preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$data,$data_music);
		$data = json_decode($data_music[1], true);
		$return = [];
        $return['name'] = $data['songList'][0]['name'];
		$singer = $data['songList'][0]['singer'];
	    foreach($singer as $k=>$v){
		    $str .= $v['name']. ' ';
		}
	    $return['singer'] = trim($str);
	    $return['musicurl'] = $this->curl($this->id);
		$return['image'] = str_replace('150x150', '800x800', $data['metaData']['image']);
		$this->Array = ['code'=>1, 'text'=>'获取成功', 'data'=>$return];
		$this->returns();
		return $return;
	}
	
    protected function curl($mid){
        list($usec, $sec) = explode(" ", microtime());
        $msec = round($usec*1000);
        $post='{"comm":{"uin":"2354452553","authst":"123456789","mina":1,"appid":1109523715,"ct":29},"urlReq":{"module":"vkey.GetVkeyServer","method":"CgiGetVkey","param":{"guid":"'.$msec.'","songmid":["'.$mid.'"],"songtype":[0],"uin":"2354452553","loginflag":1,"platform":"23","h5to":"speed"}}}';
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,"https://u.y.qq.com/cgi-bin/musicu.fcg");
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl,CURLOPT_REFERER,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.30 Safari/537.36');
        curl_setopt($curl,CURLOPT_COOKIE,'p_skey=123456789; skey=123456789; uin=o2354452553; p_uin=o2354452553');
        curl_setopt($curl,CURLOPT_USERAGENT,"http://y.qq.com/portal/player.html");
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $result=curl_exec($curl);
        curl_close($curl);
        $data = json_decode($result,true);//格式化JSON
        $URL = $data["urlReq"]["data"]["midurlinfo"][0]["purl"]?:$data["urlReq"]["data"]["testfilewifi"];
        return 'http://dl.stream.qqmusic.qq.com/'.$URL;
    }
    public function returns(){
        $data = $this->Array;
        $data['data']['lyric'] = $this->lyric;
        need::send($data, 'json');
        return;
    }
}


