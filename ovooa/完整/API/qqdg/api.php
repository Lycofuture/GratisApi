<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(11); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件
/* End */
//require("curlid.php");
@$Request = need::request();
/*
$query = http_build_query(@$Request);
echo need::teacher_curl('http://so.lkaa.top/qqdg/api.php?'.$query);
exit;
*/
$Msg = @$Request['msg']?:@$Request['Msg'];
$n = @$Request['n'];
$type =@$Request["type"];
$page = @$Request["p"]?:@$Request['page']?:'1';
$num=@$Request["sc"]?:@$Request['num']?:'15';
$tail = @$Request['tail']?:'来自QQ音乐';
New QQ_Music_Ordinary(Array('Name'=>$Msg, 'n'=>$n, 'page'=>$page, 'num'=>$num, 'tail'=>$tail, 'type'=>$type));

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
        if(!(($Name))){
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
        if(!($n) || !is_numEric($n) || $n < 1 || $n > $this->info['num']){
            $this->info['n'] = 0;
        }
        $this->GetName();
    }
    public function GetName(){
        $Name = $this->info['Name'];
        $num = $this->info['num'];
        $page = $this->info['page'];
        $url = 'https://shc6.y.qq.com/soso/fcgi-bin/search_for_qq_cp?_='.time().'&g_tk=&g_tk_new_20200303='.time().'&uin=2354452553&format=json&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&w='.urlencode($Name).'&zhidaqu=1&catZhida=1&t=0&flag=1&ie=utf-8&sem=1&aggr=0&perpage='.$this->info['num'].'&n='.$this->info['num'].'&p='.$this->info['page'].'&remoteplace=txt.mqq.all';
        $data = json_decode(need::teacher_curl($url, [
            'refer'=>'https://y.qq.com/',
            'Header'=>$this->header
        ]), true);
        $data = @$data["data"]["song"]["list"];
        // print_r($data);exit;
        if(!($data)){
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
        // print_r($data);exit;
        $n = $this->info['n'];
        if($n == 0){
            $Msg = null;
            $text = $Array = $singers = [];
            foreach($data as $k=>$v){
                $pay = $v["pay"]["payplay"];
                if($pay == 0){
                    $pay = '[免费]';
                }else{
                    $pay = '[收费]';
                }
                $Name = $v['songname'];//歌名
                foreach($v['singer'] as $val)
                {
                	$singers[] = $val['name'];
                }
                $singer = join(', ', $singers);//歌手
                $Msg .= ($k+1).'.'.$Name."—{$singer}{$pay}\n";
                $text[] = $Name."—{$singer}{$pay}";
                $Array[] = Array('song'=>$Name, 'singer'=>$singers, 'singers'=>$singer,'pay'=>$pay=='[免费]'?false:true);
                unset($singer, $singers, $Name, $pay, $value, $k, $v);
            }
            unset($this->Array , $this->Msg);
            $this->Array = Array('code'=>1, 'text'=>'歌曲列表获取成功', 'data'=>$Array, 'Msg'=>$text);
            $this->Msg = trim($Msg);
            $this->returns();
            return;
        }else{
            $n = ($n - 1);
            if(!(($data[$n]))){
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
                $singers = [];
                // print_r($data);
                $mid = $data['songmid'];
                $Name = $data['songname'];//歌名
                foreach($data['singer'] as $v){
                    $singers[] = $v['name'];
                }
                $singer = join(', ', $singers);//歌手
                $Music = $this->curl($mid);//歌曲链接
                $Cover = 'http://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['albummid'].'.jpg';//图片
                unset($this->Array , $this->Msg);
                $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('Id'=>$mid, 'Music'=>$Name, 'Cover'=>$Cover, 'Singer_Array'=>$singers, 'Singer'=>$singer, 'Url'=>$Music, 'Music_Url'=>'http://y.qq.com/n/yqq/#/'.$mid.'.html'));
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
        $type = $this->info['type'];
        $data = $this->Array;
        $Msg = $this->Msg;
        if(!isset($data['data']['Music'])){
            Switch($type){
                case 'text':
                need::send($Msg, 'text');
                break;
                default:
                need::send($data, 'json');
                break;
            }
        }else{
            $Name = $data['data']['Music'];//歌名
            $Url = $data['data']['Url'];//歌曲链接
            $Music = $data['data']['Music_Url'];//在线播放
            $Singer = $data['data']['Singer'];//歌手
            $Cover = $data['data']['Cover'];//封面图
            $tail = $this->info['tail'];
            Switch($type){
                case 'json':
                need::send('json:{"app":"com.tencent.structmsg","desc":"QQ音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$Name.'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"ctime":1646799816,"desc":"'.$Singer.'","jumpUrl":"'.$Music.'","musicUrl":"'.$Url.'","preview":"'.$Cover.'","sourceMsgId":"0","source_icon":"https://p.qpic.cn/qqconnect/0/app_100497308_1626060999/100?max-age=2592000&t=0","source_url":"http://ovooa.com/","tag":"'.$tail.'","title":"'.$Name.'","uin":2354452553}},"config":{"ctime":'.Time().',"forward":true,"token":"549b5afa08722eace91fdf1334a0a8c3","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":2354452553}"}', 'text');
                //need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"QQ音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$Singer.'","jumpUrl":"'.$Music.'","musicUrl":"'.$Url.'","preview":"'.$Cover.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$Name.'"}},"prompt":"[分享]'.$Name.'","ver":"0.0.0.1","view":"music"}', 'text');
                break;
                case 'xml':
                need::send('card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]'.str_replace('&','&amp;', $Name).'" sourceMsgId="0" url="'.str_replace('&','&amp;', $Music).'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.str_replace('&','&amp;', $Cover).'" src="'.str_replace('&','&amp;', $Url).'" /><title>'.str_replace('&','&amp;', $Name).'</title><summary>'.str_replace('&','&amp;', $Singer).'</summary></item><source name="'.$tail.'" icon="https://i.gtimg.cn/open/app_icon/01/07/98/56/1101079856_100_m.png?date=20200331&amp;_tcvassp_0_=750shp&amp;_tcvassp_0_1765997760=750shp" action="app" a_actionData="" i_actionData="" appid="100497308" /></msg>', 'text');
                //need::send('<msg serviceID="2" templateID="1" action="web" brief="[分享]'.str_replace('&','&amp;', $Name).'" sourceMsgId="0" url="'.str_replace('&','&amp;', $Music).'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.str_replace('&','&amp;', $Cover).'" src="'.str_replace('&','&amp;', $Url).'" /><title>'.str_replace('&','&amp;', $Name).'</title><summary>'.str_replace('&','&amp;', $Singer).'</summary></item><source name="'.$tail.'" icon="http://y.qq.com/favicon.ico" action="app" a_actionData="" i_actionData="" appid="100497308" /></msg>', 'text');
                break;
                case 'text':
                need::send($Msg, 'text');
                break;
                case 'lyric':
                need::send($this->lyric, 'text');
                break;
                default:
                $data['data']['lyric'] = $this->lyric;
                need::send($data, 'json');
                break;
            }
        }
        return;
    }
}


