<?php
header('Content-type:Application/json;');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(80); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件
/* End */
@$Request = need::request();
$msg = @$Request["msg"];
$n = @$Request["n"];
$num = @$Request["sc"]?:15;
$p = @$Request["p"]?:1;
$type = @$Request["type"];
$tail = @$Request['tail']?:'来自咪咕音乐';

$Music = New Music_migu(array('Name'=>$msg, 'num'=>$num, 'page'=>$p, 'n'=>$n, 'tail'=>$tail));
$Music->GetName();
$Music->GetMusic();
$Music->returns($type);

class Music_migu{
    protected $info = [];
    protected $refer = 'https://m.music.migu.cn/v3/search?keyword=%E5%91%A8%E6%9D%B0%E4%BC%A6';
    public $array = [];
    public $text;
    public $Music;
    public $data;
    protected $ua = 'Mozilla/5.0 (Linux; Android 10; PCLM10 Build/QKQ1.191021.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.92 Mobile Safari/537.36';
    public function __construct(array $array){
        foreach($array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->need = New need;
        $this->Getexamine();
    }
    protected function Getexamine(){
        $num = $this->info['num'];
        if($num < 1 || empty($num) || !is_numEric($num)){
            $this->info['num'] = 10;
        }
        $page = $this->info['page'];
        if($page < 1 || empty($page) || !is_numEric($page)){
            $this->info['page'] = 1;
        }
        $n = $this->info['n'];
        if(!empty($n)){
            if($n < 1 || empty($n) || !is_numEric($n)){
                $this->info['n'] = 1;
            }
        }
        return ;
    }
    public function GetName(){
        if(empty($this->info['Name'])){
            unset($this->array, $this->text);
            $this->array['code'] = -1;
            $this->array['text'] = '请输入歌名';
            $this->text = '请输入歌名';
            return;
        }
        $url = 'https://m.music.migu.cn/migu/remoting/scr_search_tag?rows='.$this->info['num'].'&type=2&keyword='.urlencode($this->info['Name']).'&pgc='.$this->info['page'];
        $data = json_decode($this->need->teacher_curl($url,[
            'ua'=>$this->ua,
            'refer'=>$this->refer,
            'rtime'=>3,
            'ctime'=>3
        ]), true);
        $data = $data['musics'];
        if(empty($data)){
            unset($this->array, $this->text);
            $this->array['code'] = -2;
            $this->array['text'] = '未搜索到有关于'.$this->info['Name'].'的歌曲';
            $this->text = '未搜索到有关于'.$this->info['Name'].'的歌曲';
            return;
        }
        $num = $this->info['num'];
        $array = [];
        $text = null;
        $array_text = [];
        for($i = 0 ; $i < $num && $i < count($data) ; $i++){
            $song = $data[$i]['title'];
            $singer = $data[$i]['singerName'];
            $array[] = ['song'=>$song, 'singer'=>$singer, '_singer'=>explode(', ', $singer)];
            $array_text[] = $song.'—'.$singer;
            $text .= ($i + 1).'.'.$song.'—'.$singer."\n";
        }
        unset($this->array, $this->text);
        $this->array['code'] = 1;
        $this->array['text'] = '获取成功';
        $this->array['data'] = $array;
        $this->array['msg'] = $array_text;
        $this->text = trim($text);
        return $data;
    }
    public function GetMusic(){
        $n = ($this->info['n'] - 1);
        $data = $this->GetName();
        if(empty($data[$n])){
            $this->GetName();
            return;
        }
        //print_r($data);exit;
        $url = 'http://c.musicapp.migu.cn/MIGUM2.0/v1.0/content/resourceinfo.do?copyrightId='. $data[$n]['copyrightId'].'&resourceType=2';
        $url = json_decode(need::teacher_curl($url), true);
        $url = (isset($url['resource'][0]['newRateFormats']) ? ($url['resource'][0]['newRateFormats'][1]['url'] ? $url['resource'][0]['newRateFormats'][1]['url'] : ($url['resource'][0]['newRateFormats'][0]['url'] ? $url['resource'][0]['newRateFormats'][0]['url'] : ($url['resource'][0]['newRateFormats'][2]['url'] ? $url['resource'][0]['newRateFormats'][2]['url'] :  ($url['resource'][0]['newRateFormats'][3]['androidUrl'] ? $url['resource'][0]['newRateFormats'][3]['androidUrl'] : false)))) : false);
        $url = preg_replace('/ftp.*?:21/', 'https://freetyst.nf.migu.cn', $url);
        $song = $data[$n]['title'];
        $singer = $data[$n]['singerName'];
        $Music = ($url?: $data[$n]['mp3']);
        $cover = $data[$n]['cover'];
        $this->info['id'] = $data[$n]['copyrightId'];
        $lyrics = $this->Getlyrics();
        unset($this->array, $this->text);
        if($Music){
            $this->array['code'] = 1;
            $this->array['text'] = '获取成功';
            $this->array['data'] = array('musicname'=>$song,'singer'=>$singer,'image'=>$cover,'musicurl'=>$Music,'lyric'=>$lyrics);
            $this->text = '±img='.$cover."±\n歌曲：{$song}\n歌手：{$singer}\n播放链接：{$Music}";
        }else{
            $this->array['code'] = -3;
            $this->array['text'] = '获取失败，歌曲可能为付费歌曲';
            $this->text = '获取失败，歌曲可能为付费歌曲';
        }
        return;
    }
    public function Getlyrics(){
        $id = $this->info['id'];
        if(empty($id)){
            return;
        }
        $url = 'https://m.music.migu.cn/migu/remoting/cms_detail_tag?cpid='.$id;
        $data = json_decode($this->need->teacher_curl($url, [
            'ua'=>$this->ua,
            'refer'=>'https://m.music.migu.cn/migu/remoting/cms_detail_tag?cpid=600'
        ]), true);
        $lyrics = trim(str_replace(array('\r\n', '\r', '\n', "\r\n", "\r", "\n"), "\n", $data['data']['lyricLrc']));
        return $lyrics;
    }
    public function returns($type){
        $data = $this->array;
        $need = $this->need;
        if($data['data']['singer']){
            $mp3 = $data['data']['musicurl'];//播放链接
            $cover = $data['data']['image'];//图片
            $song = $data['data']['musicname'];//标题
            $singer = $data['data']['singer'];//作者
            $tail = $this->info['tail'];
            Switch($type){
                case 'text':
                $need->send($this->text, 'text');
                break;
                case 'xml':
                $mp3a = str_replace('&','&amp;',$mp3);
                $covera = str_replace('&','&amp;',$cover);
                $need->send('card:3<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]咪咕音乐" sourceMsgId="0" url="'.$mp3a.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$covera.'" src="'.$mp3a.'" /><title>'.$song.'</title><summary>'.$singer.'</summary></item><source name="'.$tail.'" icon="https://music.migu.cn/favicon.ico" action="" appid="-1" /></msg>', 'text');
                break;
                case 'json':
                // $need->send('json:{"app":"com.tencent.structmsg","config":{"ctime":1648823655,"forward":true,"token":"907d9264fe49902535e38969db3aa30c","type":"normal"},"desc":"音乐","extra":{"app_type":1,"appid":1101053067,"msg_seq":7081643655090552868,"uin":2354452553},"meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":1101053067,"ctime":1648823655,"desc":"'.$singer.'","jumpUrl":"'.$mp3.'","musicUrl":"'.$mp3.'","preview":"'.$cover.'","sourceMsgId":"0","source_icon":"https://open.gtimg.cn/open/app_icon/01/05/30/67/1101053067_100_m.png?t=1648794417","source_url":"","tag":"'.$tail.'","title":"'.$song.'","uin":2354452553}},"prompt":"[分享]'.$song.'","ver":"0.0.0.1","view":"music"}', 'text');
                $need->send('json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$song.'","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":1101053067,"ctime":1655529704,"desc":"'.$singer.'","jumpUrl":"'.$mp3.'","musicUrl":"'.$mp3.'","preview":"'.$cover.'","sourceMsgId":"0","source_icon":"https:\/\/open.gtimg.cn\/open\/app_icon\/01\/05\/30\/67\/1101053067_100_m.png?t=1655373457","source_url":"","tag":"'.$tail.'","title":"晴天","uin":2352323151}},"config":{"ctime":1655529704,"forward":true,"token":"a6d0a182e4f0f8e686b59762f3abe945","type":"normal"}}', 'text');
                break;
                default:
                $need->send($data, 'json');
                break;
            }
            return;
        }
        Switch($type){
            case 'text':
            $this->need->send($this->text, 'text');
            break;
            default:
            $this->need->send($this->array, 'json');
            break;
        }
        return;
    }
    public function __call($function, $arg){
        unset($this->array, $this->text);
        $this->array['code'] = -100;
        $this->array['text'] = '调用未知方法：'.$function;
        $this->text = '调用未知方法：'.$function;
        return;
    }
}

