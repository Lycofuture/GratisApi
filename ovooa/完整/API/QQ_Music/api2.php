<?php
header ('Content-Type:Application/Json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(117); // 调用统计函数
addAccess();//调用统计函数*/
require '../../need.php';//调用所需其他函数
/* End */

@$Request = need::request();
/*
$query = http_build_query(@$Request);
echo need::teacher_curl('http://lkaa.top/daili/QQ_Music/api.php?'.$query);
exit;
*/
$id = @$Request['id'];
$Msg = @$Request['Msg']?:@$Request['msg']?:@$Request['Name']?:@$Request['name'];
$url = @$Request['url'];
$n = @$Request['n'];
$page = @$Request['page']?:@$Request['p'];
if($page < 1 || empty($page) || !is_numEric($page)){
    $page = 1;
}
$num = @$Request['num'];
if($num < 1 || empty($num) || !is_numEric($num)){
    $num = 10;
}
$Skey = @$Request['Skey'];
$Pskey = @$Request['Pskey'];
$Uin = @$Request['Uin'];
$type = @$Request['type'];
$tail = @$Request['tail']?:'QQ音乐';
if(empty($Skey) || empty($Pskey) || !need::is_num($Uin)){
    $cookie = need::cookie('y.qq.com');
}else{
    $cookie = 'uin=o'.$Uin.'; p_uin=o'.$Uin.'; skey='.$Skey.'; p_skey='.$Pskey;
}
$Music = New QQ_Music(array('id'=>$id, 'Name'=>$Msg, 'url'=>$url, 'n'=>$n, 'page'=>$page, 'num'=>$num,'cookie'=>$cookie,'tail'=>$tail,'type'=>$type));
if($cookie == false){
    $Music->array['code'] = -8;
    $Music->array['text'] = '登录状态已失效';
    $Music->data = '登录状态已失效';
    $Music->returns($type);
}
if($id){
    $Music->Getid();
}else 
if($url){
    $Music->Geturl();
}else
if($Msg){
    $Music->GetName();
    $Music->Getmusic();
}else{
    $Music->array['code'] = -1;
    $Music->array['text'] = '请填写歌曲ID/歌名/链接';
    $Music->data = '请填写歌曲ID/歌名/链接';
}
$Music->returns($type);

class QQ_Music{
    protected $info = [];
    public $array = [];
    public $data;
    public $getName;
    public $header=[
    	'Host: shc6.y.qq.com',
		'Connection: keep-alive',
		'Pragma: no-cache',
		'Cache-Control: no-cache',
		'Upgrade-Insecure-Requests: 1',
		'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
		'dnt: 1',
		'X-Requested-With: mark.via',
		'Sec-Fetch-Site: none',
		'Sec-Fetch-Mode: navigate',
		'Sec-Fetch-User: ?1',
		'Sec-Fetch-Dest: document',
		'Referer: https://i.y.qq.com/n2/m/index.html',
		'Accept-Encoding: gzip, deflate',
		'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
	];
	public $ua = 'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36';
	public $refer = 'https://i.y.qq.com/n2/m/index.html';
    public function __construct(array $array){
        foreach($array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->need = New need;
        if(is_numEric($array['id']) && strlen($array['id']) >= 6){
            $this->info['songid'] = $array['id'];
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        if($this->need->cookie('Skey') == false){
            unset($this->array, $this->data);
            $this->array['code'] = -8;
            $this->array['text'] = '登录状态已失效';
            $this->data = '登录状态已失效';
            return;
        }
    }
    public function Getid(){
        $id = $this->info['id'];
        if(($id == 0 && !empty($this->info['songid'])) || ($id == $this->info['songid'])){
            $url = $this->need->loadurl('https://i.y.qq.com/v8/playsong.html?ADTAG=ryqq.index.songDetail&songmid=&songid='.$this->info['songid'].'&songtype=0&shareuin=');
            $data = $this->need->teacher_curl($url,[
                'cookie'=>$this->info['cookie'],
                'Header'=>$this->header,
                'ua'=>$this->ua,
                'refer'=>$this->refer
            ]);
            preg_match('/__ssrFirstPageData__ =([\s\S]*?)<\/script>/', $data, $data);
            $data = json_decode($data[1], true);
            //print_r($data['songList'][0]['mid']);
            if($data['songList'][0]['mid']){
                $id = $data['songList'][0]['mid'];
                //$this->Getid();
            }else{
                unset($this->array, $this->data);
                $this->array['code'] = -6;
                $this->array['text'] = '歌曲ID获取失败';
                $this->data = '歌曲ID获取失败';
                return ;
            }
        }else
        if($id == 0){
            unset($this->array, $this->data);
            $this->array['code'] = -6;
            $this->array['text'] = '歌曲ID获取失败';
            $this->data = '歌曲ID获取失败';
            return ;
        }
        if(empty($id)){
            unset($this->array, $this->data);
            $this->array['code'] = -1;
            $this->array['text'] = '请填写歌曲ID';
            $this->data = '请填写歌曲ID';
            return ;
        }
        $url = 'https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=10170509&hosteuin=owok7evkow4koz**&songmid='.$id.'&type=0&appsongtype=1&_wv=1&source=qq&ADTAG=qfshare';
        $data = $this->need->teacher_curl($url, [
            'cookie'=>$this->info['cookie'],
            'Header'=>$this->header,
            'ua'=>$this->ua,
            'refer'=>$this->refer
        ]);
        preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$data,$data_music);
		$data_music = json_decode($data_music[1], true);
		$songurl = $data_music['songList'][0]['url'];
		$pay = $data_music['songList'][0]['pay']['price_album'];
		if(empty($songurl) && $pay){
		    unset($this->array, $this->data);
		    $this->array['code'] = -5;
		    $this->array['text'] = '该歌曲为付费歌曲';
		    $this->data = '该歌曲为付费歌曲';
		    return '';
		}
		if(!$songurl){
		    unset($this->array, $this->data);
		    $this->array['code'] = -4;
		    $this->array['text'] = '歌曲获取失败';
		    $this->data = '歌曲获取失败';
		    return;
		}else{
		    unset($this->array, $this->data);
		    $this->music = $data_music;
		    $this->array['code'] = 1;
		    $this->array['text'] = '获取成功';
		    $this->array['data'] = array('song'=>$this->label('name'),'Mid'=>$id, 'songid'=>$this->label('songid'), 'url'=>'http://y.qq.com/n/yqq/song/'.$this->info['id'].'.html', 'picture'=>$this->label('image'),'singer'=>$this->label('singer'),'music'=>$songurl);//, 'lyric'=>$this->Getlyric());
		    $this->data = '±img='.$this->label('image')."±\n歌曲：".$this->label('name')."\n歌手：".$this->label('singer')."\n播放链接：".$songurl;
		    return;
		}
    }
    public function GetName(){
        $Name = $this->info['Name'];
        if(empty($Name)){
            unset($this->array, $this->data);
            $this->array['code'] = -1;
            $this->array['text'] = '请填写歌曲ID';
            $this->data = '请填写歌曲ID';
            return;
        }
        preg_match('/p_skey=(.*?);/',$this->info['cookie'],$Pskey);
        $Pskey = $Pskey[1];
        $url = 'https://shc6.y.qq.com/soso/fcgi-bin/search_for_qq_cp?_='.time().'&g_tk='.$this->need->GTK($Pskey).'&g_tk_new_20200303='.time().'&uin=2354452553&format=json&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&w='.urlencode($Name).'&zhidaqu=1&catZhida=1&t=0&flag=1&ie=utf-8&sem=1&aggr=0&perpage='.$this->info['num'].'&n='.$this->info['num'].'&p='.$this->info['page'].'&remoteplace=txt.mqq.all';
        $data = Json_decode($this->need->teacher_curl($url, [
            'cookie'=>$this->info['cookie'],
            'Header'=>$this->header,
            'ua'=>$this->ua,
            'refer'=>$this->refer,
            //'ctime'=>10,
            'rtime'=>10,
            'GetCookie'=>false
        ]), true);
        if(!$data['data']['song']['totalnum']){
            unset($this->array, $this->data);
            $this->array['code'] = -7;
            $this->array['text'] = 'QQ音乐把我拉黑了╮( •́ω•̀ )╭明天再试试吧ฅ';
            $this->data = 'QQ音乐把我拉黑了╮( •́ω•̀ )╭明天再试试吧ฅ';
            return ;
        }
        $data=$data["data"]["song"]["list"];
        if(empty(need::nate($data)) || is_null($data)){
            $this->array['code'] = -2;
            $this->array['text'] = '搜索失败，没有搜到有关于'.$Name.'的歌曲';
            $this->data = '搜索失败，没有搜到有关于'.$Name.'的歌曲';
            return ;
        }else{
        	$Array = [];
            foreach($data as $k=>$v) {
			    $singer_end=end($v["singer"]);
			    foreach($v["singer"] as $vv) {
			    	if($vv ==$singer_end) {
					    $singer.= $vv["name"];
			    	} else {
					    $singer.= $vv["name"]." / ";
			    	}
			    }
			    $singer = preg_replace('/\/$/', '', $singer);
			    $array[] = $v["songname"]."-".$singer.($v["pay"]["payplay"]?"【付费】":"").($v["alertid"]?"":"【无版权】");
			    $Msg .= ($k + 1).'.'.$v["songname"]."-".$singer.($v["pay"]["payplay"]?"【付费】":"").($v["alertid"]?"":"【无版权】")."\n";
			    $Array[] = Array('song'=>$v['songname'], 'singer'=>explode('/', $singer), 'singers'=>$singer,'pay'=>$v["pay"]["payplay"]?true:false);
			    unset($singer);
			}
			unset($this->array, $this->data);
			$this->array['code'] = 1;
            $this->array['text'] = '获取成功';
            $this->array['data'] = $Array;
            $this->array['Msg'] = $array;
            $this->data = $Msg;
            $this->getName = $data;
            // print_r($data);exit;
            //$this->info['id'] = $data[$this->n['songmid']];
            return $data;
        }
    }
    public function Geturl(){
        preg_match("/\/([\w]+)\.html/",$this->info['url'],$return);
		if(is_null($return[1]) || $return[1]=="playsong") {
			preg_match("/songmid=(.*?)&/",$this->info['url'],$return);
			if(is_null($return[1])) {
			    preg_match('/u?__(.*)/',$this->info['url'], $return);
			    if($return[1]){
			        $this->info['url'] = $this->need->loadurl($this->info['url']);
			        $this->Geturl();
			        return;
			    }else{
			    	unset($this->array, $this->data);
			    	$this->array['code'] = -1;
                    $this->array['text'] = '请填写正确的歌曲链接';
                    $this->data = '请填写正确的歌曲链接';
				    return;
				}
			}
		}
		$this->info['id'] = $return[1];
		$this->Getid();
		//解析歌曲
    }
    public function Getmusic(){
        $n = $this->info['n'];
        if(!is_numEric($n) || $n < 1 || empty($n)){
            return;
        }
        if($n > $this->info['num']){
            $this->info['n'] = 0;
            $this->GetName();
            return;
        }
        $data_json = $this->getName;
		$list = $data_json[($n-1)];
		/*
		if(is_null($list)){
		    for($i = 0 ; $i < 1 ; $i++){
		        $data_json = $this->GetName();
		        $list = $data_json[($n-1)];
		        if(!is_null($list)) {
			        break;
			    }
			}
		}
		*/
		if(is_null($list)) {
			unset($this->array, $this->data);
			$this->array['code'] = -3;
            $this->array['text'] = '歌曲获取失败，可能是选择不存在';
            $this->data = '歌曲获取失败，可能是选择不存在';
			return;
		}
		$this->info['songid'] = $list['songid'];
		$this->info['id'] = $list["songmid"];
		//print_r($list);exit;
		$music = $this->Getid();
		
		//解析歌曲
    }
    public function label($name) {
		$data = $this->music;
		// print_r($data);
		Switch($name){
		    case 'name':
		    $return = $data['songList'][0]['name'];
		    break;
		    case 'singer':
		    $singer = $data['songList'][0]['singer'];
		    foreach($singer as $k=>$v){
		        $str .= $v['name']. '、';
		    }
		    $return = preg_replace('/、$/', '', $str);
		    break;
		    case 'image':
		    $return = str_replace('150x150', '800x800', $data['metaData']['image']);
		    break;
		    case 'songid':
		    $return = $data['songList'][0]['id'];
		    break;
		    default:
		    $return = false;
		    break;
		}
		return $return;
	}
	public function Getlyric(){
	    $data = json_decode(str_replace(array('jsonp(',')'),'',need::teacher_curl('https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg?g_tk=5381&uin=0&format=json&jsonpCallback=jsonp&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&nobase64=1&musicid='.$this->info['songid'].'&songtype=0&_=1513437581324',[
            'refer'=>'https://c.y.qq.com/'
        ])),true);
        $lyric = need::ASCII_UTF8($data['lyric']);
        $this->lyric = $lyric;
        return $lyric;
    }
    public function 备用(){
        $info = $this->info;
        if($info['type'] == 'text' || $info['type'] == 'xml'){
            $data = need::teacher_curl('http://d.ovooa.com/QQ_Music.api?'.http_build_query($info));
            unset($this->array, $this->data);
            $this->data = $data;
            return;
        }
        $data = json_decode(need::teacher_curl('http://d.ovooa.com/QQ_Music.api?'.http_build_query($info)), true);
            unset($this->array, $this->data);
            foreach($data as $k=>$v){
                $this->array[$k] = $v;
            }
            $this->data = $data['text']?:$data['Msg'];
            return;
    }
	public function returns($Type){
	    if(!empty($this->array['data']['music'])){
	        $array = $this->array['data'];
	        Switch($Type){
	            case 'text':
	            $this->need->send($this->data, 'text');
	            break;
	            case 'xml':
	            $url = str_replace('&', '&amp;', $array['url']);
	            $music = str_replace('&', '&amp;',$array['music']);
	            $cover = str_replace('&', '&amp;',$array['picture']);
	            $singer = str_replace('&', '&amp;',$array['singer']);
	            $song = str_replace('&', '&amp;',$array['song']);
	            $tail = str_replace('&', '&amp;',$this->info['tail']);
	            $this->need->send('card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]'.$song.'" sourceMsgId="0" url="'.$url.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$cover.'" src="'.$music.'" /><title>'.$song.'</title><summary>'.$singer.'</summary></item><source name="'.$tail.'" icon="https://i.gtimg.cn/open/app_icon/01/07/98/56/1101079856_100_m.png?date=20200331&amp;_tcvassp_0_=750shp&amp;_tcvassp_0_1765997760=750shp" action="app" a_actionData="" i_actionData="" appid="100497308" /></msg>','text');
	            break;
	            case 'json':
	            $url = $array['url'];
	            $music = $array['music'];
	            $cover = $array['picture'];
	            $singer = $array['singer'];
	            $song = $array['song'];
	            $tail = $this->info['tail'];
	            $this->need->send('json:{"app":"com.tencent.structmsg","desc":"QQ音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$song.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"ctime":1646799816,"desc":"'.$singer.'","jumpUrl":"https://y.qq.com/n/ryqq/songDetail/'.$this->info['id'].'","musicUrl":"'.$music.'","preview":"'.$cover.'","sourceMsgId":"0","source_icon":"https://p.qpic.cn/qqconnect/0/app_100497308_1626060999/100?max-age=2592000&t=0","source_url":"http://ovooa.com/","tag":"'.$tail.'","title":"'.$song.'","uin":2354452553}},"config":{"ctime":'.Time().',"forward":true,"token":"549b5afa08722eace91fdf1334a0a8c3","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":2354452553}"}', 'text');
	            //$this->need->send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"QQ音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$singer.'","jumpUrl":"'.$url.'","musicUrl":"'.$music.'","preview":"'.$cover.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$song.'"}},"prompt":"[分享]'.$song.'","ver":"0.0.0.1","view":"music"}','text');
	            break;
	            case 'lyric':
	            need::send($this->Getlyric(), 'text');
	            break;
	            default:
	            $this->need->send($this->array, 'json');
	            break;
	        }
	        return;
	    }
	    Switch($Type){
	        case 'text':
	        $this->need->send($this->data, 'text');
	        break;
	        default:
	        $this->need->send($this->array, 'json');
	        break;
	    }
	    return;
	}
}