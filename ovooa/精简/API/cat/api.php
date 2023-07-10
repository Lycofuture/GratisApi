<?php
header('Content-type:Application/json');
require '../../need.php';//引入文件
$Request = need::request();//获取参数
$Msg = @$Request['Msg']?:@$Request['msg'];//名字
$page = @$Request['page']?:1;//页数
$n = @$Request['n'];//搜索时的选择
$s = @$Request['s'];//章节选择
$num = @$Request['num'];//输出数量
$type = @$Request['type'];//输出格式
$id = @$Request['id'];//解析id->soundid 并非作品id
$p = @$Request['p'];//章节翻页
$New = New cat(array('Name'=>$Msg, 'page'=>$page, 'n'=>$n, 'type'=>$type ,'s'=>$s, 'num'=>$num, 'id'=>$id, 'p'=>$p));
$New->GetName();
$New->Analysis();
$New->Analysisid();
$New->returns($type);
class cat{
    protected $info = [];
    public $Msg;
    public $array;
    public $id;
    protected $data;
    protected  $header = [
        'Host: www.missevan.com',
        'Connection: keep-alive',
        'Accept: application/json',
        'X-Requested-With: mark.via',
        'Sec-Fetch-Site: same-origin',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Dest: empty',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
    ];
    
    public function __construct(array $array){
        foreach($array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    
    public function ParameterException(){
        $page = $this->info['page'];
        if($page < 1 || !is_numEric($page)){
            $this->info['page'] = 1;
        }
        $Name = $this->info['Name'];
        if(empty($Name)){
            unset($this->array, $this->Msg);
            $this->array['code'] = -1;
            $this->array['text'] = '请输入需要搜索的名字';
            $this->Msg = '请输入需要搜索的名字';
            $this->returns($this->info['type']);
            return;
        }
        $Name = urlencode(urldecode($Name));
        $this->info['Name'] = $Name;
        $num = $this->info['num'];
        if($num < 1 || !is_numEric($num)){
            $this->info['num'] = 30;
        }
        $s = $this->info['s'];
        if(empty($s) || !is_numEric($s)){
            $this->info['s'] = 0;
        }
        return;
    }
    public function GetName(){
        $Name = $this->info['Name'];
        $page = $this->info['page'];
        $url = 'https://www.missevan.com/dramaapi/search?s='.$Name.'&page='.$page.'&page_size='.$this->info['num'];
        $data = json_decode(need::teacher_curl($url, [
            'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
            'refer'=>'https://www.missevan.com/sound/search?t=all&s='.$Name.'&p='.$page.'&o=0&c=0',
            'Header'=>$this->header,
            'cookie'=>'acw_tc=2f624a2e16416075060942646e6e6cd3b5105a0addbae9292e73c7880e47cf; b_lsid=C2103110C7_17E376EAA37; MSESSID=1qr4n5rc02ej1j7e0c2le4eni5; buvid_fp=268BF75C-B997-4F85-A760-1882C3C322A7148828infoc'
        ]), true)['info']['Datas'];
        $this->data = $data;
        $this->Analysis();
        return $data;
    }
    public function Analysis(){
        $data = $this->data;
        if(empty($data)){
            unset($this->array, $this->Msg);
            $this->Msg = '这里似乎什么都没有';
            $this->array['code'] = -4;
            $this->array['text'] = '这里似乎什么都没有';
            return;
        }
        $n = $this->info['n'];
        if(empty($n) || $n < 1){
            $count = @count($data);
            for($i = 0 ; $i < $this->info['num'] && $i < $count ; $i++){
                $array[] = array('Name'=>$data[$i]['name'], 'Cover'=>$data[$i]['cover'], 'New'=>$data[$i]['newest'], 'Abstract'=>$data[$i]['abstract']);
                $Msg .= ($i+1).'.'.$data[$i]['name']."\n";
            }
            unset($this->array, $this->Msg);
            $this->Msg = trim($Msg);
            $this->array['code'] = 1;
            $this->array['text'] = '获取成功';
            $this->array['data'] = $array;
            return $data;
        }else{
            $data = $data[($n-1)];
            if(empty($data)){
                $this->info['n'] = 0;
                $this->Analysis();
                return;
            }
            $id = $data['id'];
            $url = 'https://www.missevan.com/dramaapi/getdrama?drama_id='.$id;
            $data = json_decode(need::teacher_curl($url, [
                'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
                'refer'=>'https://www.missevan.com/mdrama/'.$id,
                'Header'=>$this->header,
                'cookie'=>'acw_tc=2f624a2e16416075060942646e6e6cd3b5105a0addbae9292e73c7880e47cf; b_lsid=C2103110C7_17E376EAA37; MSESSID=1qr4n5rc02ej1j7e0c2le4eni5; buvid_fp=268BF75C-B997-4F85-A760-1882C3C322A7148828infoc'
            ]), true)['info']['episodes']['episode'];
            $num = $this->info['num'];
            $count = @count($data)?:0;
            $page = $this->info['p'];
            $p_u = $page * $num;//到下页数量
            $p = $page - 1;
            $page_n = ($count / $num) + 1;
            $p_n = $p * $num;//当前显示
            if($page > $page_n){
                unset($this->array, $this->Msg);
                $this->Msg = '这页似乎什么都没有';
                $this->array['code'] = -5;
                $this->array['text'] = '这页似乎什么都没有';
                return;
            }
            for($i = $p_n ; $i < $p_u ; $i++){
                $array[] = array('Name'=>$data[$i]['name'], 'id'=>$data[$i]['id'], 'sound_id'=>$data[$i]['sound_id']);
                $Msg .= ($i+1).'.'.$data[$i]['name']."\n";
            }
            if(empty($array)){
                unset($this->array, $this->Msg);
                $this->Msg = '获取失败，作品不存在';
                $this->array['code'] = -3;
                $this->array['text'] = '获取失败，作品不存在';
                return ;
            }
            unset($this->array, $this->Msg);
            $Msg = trim($Msg);
            $this->Msg = $Msg;
            $this->array['code'] = 1;
            $this->array['text'] = '获取成功';
            $this->array['data'] = $array;
            return $data;
        }
    }
    
    public function Analysisid(){
        $id = $this->info['id'];
        if(empty($id)){
            $data = $this->Analysis();
            $s = ($this->info['s'] - 1);
            if($s >= 0){
                $id = $data[$s]['sound_id'];
            }
        }
        if(empty($id)){
            return;
        }
        $url = 'https://www.missevan.com/sound/getsound?soundid='.$id;
        $data = json_decode(need::teacher_curl($url, [
            'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
            'refer'=>'https://www.missevan.com/sound/player?id='.$id,
            'Header'=>$this->header,
            'cookie'=>'acw_tc=2f624a2e16416075060942646e6e6cd3b5105a0addbae9292e73c7880e47cf; b_lsid=C2103110C7_17E376EAA37; MSESSID=1qr4n5rc02ej1j7e0c2le4eni5; buvid_fp=268BF75C-B997-4F85-A760-1882C3C322A7148828infoc'
        ]), true)['info']['sound'];
        if(empty($data)){
            unset($this->array, $this->Msg);
            $this->array['code'] = -2;
            $this->array['text'] = '解析失败，soundid不存在';
            $this->Msg = '解析失败，soundid不存在';
            return;
        }
        unset($this->array, $this->Msg);
        $this->array['code'] = 1;
        $this->array['text'] = '获取成功';
        $this->array['data'] = array('Name'=>$data['soundstr'], 'id'=>$data['id'], 'user'=>$data['username'], 'Cover'=>$data['front_cover'], 'sound'=>$data['soundurl'], 'sound_128'=>$data['soundurl_128']);
        $this->Msg = '±img='.$data['front_cover']."\n作者：".$data['username']."\n章节：".$data['soundstr']."\n播放链接：".$data['soundurl']?:$data['soundurl_128'];
        return;
    }
    
    public function returns($type){
        if(empty($this->array['data']['sound'])){
            Switch($type){
                case 'text':
                need::send($this->Msg, 'text');
                break;
                default:
                need::send($this->array, 'json');
                break;
            }
            return;
        }
        $data = $this->array['data'];
        Switch($type){
            case 'text':
            need::send($this->Msg, 'text');
            break;
            case 'json':
            need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"QQ音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$data['user'].'","jumpUrl":"'.$data['sound'].'","musicUrl":"'.$data['sound'].'","preview":"'.$data['Cover'].'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"猫耳FM","title":"'.$data['Name'].'"}},"prompt":"[分享]'.$data['Name'].'","ver":"0.0.0.1","view":"music"}', 'text');
            break;
            case 'xml':
            $url = str_replace('&', '&amp;', $data['sound']);
	        $music = str_replace('&', '&amp;',$data['sound']);
	        $cover = str_replace('&', '&amp;',$data['Cover']);
	        $singer = str_replace('&', '&amp;',$data['user']);
	        $song = str_replace('&', '&amp;',$data['Name']);
	        need::send('card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[分享]'.$song.'" sourceMsgId="0" url="'.$url.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.$cover.'" src="'.$music.'" /><title>'.$song.'</title><summary>'.$singer.'</summary></item><source name="猫耳FM" icon="https://s1.hdslb.com/bfs/maoer/assets/images/index/57.png" action="app" a_actionData="" i_actionData="" appid="100497308" /></msg>','text');
	        break;
	        default:
	        need::send($this->array, 'json');
	        break;
        }
        return;
    }
}