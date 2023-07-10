<?php

header('content-type:application/json');

/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(39); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件
/* End */

$songid = @$_REQUEST['id'];
$tail = @$_REQUEST['tail']?:'网抑云音乐';
$type = @$_REQUEST['type'];
if(empty($songid) || !is_numeric($songid)){
    $array = array(71385702,2884035,3778678,3779629,19723756);
    $rand = array_rand($array,1);
    $songid = $array[$rand];
}
New Music_163_Rand(Array('id'=>$songid, 'type'=>$type, 'tail'=>$tail));

class Music_163_Rand{
    protected $info = [];
    protected $Array = [];
    protected $Msg;
    protected $id;
    protected $header = array(
                'Host: music.163.com',
                'Origin: http://music.163.com',
                'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36',
                'Content-Type: application/x-www-form-urlencoded',
                'Referer: http://music.163.com/search/',
            );
    public function __construct(Array $Array){
        foreach($Array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        $id = $this->info['id'];
        if(!is_numEric($id)){
            unset($this->Array, $this->Msg);
            $this->Array = Array('code'=>-1, 'text'=>'请输入正确的歌单id');
            $this->Msg = '请输入正确的歌单id';
            $this->returns();
            return;
        }
        $this->Getid();
    }
    public function Getid(){
        $id = $this->info['id'];
        $post_data = http_build_query(array('s'=>'100','id' =>$id,'n'=>'100','t'=>'100'));
        $url = "http://music.163.com/api/v6/playlist/detail";
        $data = need::teacher_curl($url,[
            'post'=>$post_data,
            'ua'=>'NeteaseMusic/8.6.31.211201171945(8006031);Dalvik/2.1.0 (Linux; U; Android 11; PCLM10 Build/RKQ1.200928.002)',
            'Header'=>$this->header
        ]);
        $array = json_decode($data,true)['playlist']['trackIds'];
        $this->id = $array[array_rand($array,1)]['id'];//$data['Playlist']['data'][$rand]['id'];
        $this->Analysis();
        return $array;
    }
    protected function Analysis(){
        $id = $this->id;
        $url = 'http://music.163.com/api/song/detail/?id='.$id.'&ids=%5B'.$id.'%5D&csrf_token=';
        $data = json_decode(need::teacher_curl($url),true);
        if(empty($data['songs'][0])){
            unset($this->Array, $this->Msg);
            $this->Array = Array('code'=>-1, 'text'=>'请输入正确的歌单id');
            $this->Msg = '请输入正确的歌单id';
            $this->returns();
            return;
        }
        $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('song'=>$data['songs'][0]['name'], 'singer'=>$data['songs'][0]['artists'][0]['name'], 'cover'=>$data['songs'][0]['album']['picUrl'], 'Music'=>'http://music.163.com/song/media/outer/url?id='.$id, 'id'=>$id));
        $this->Msg = "±img=".$data['songs'][0]['album']['picUrl']."±\n歌曲：".$data['songs'][0]['name']."\n歌手：".$data['songs'][0]['artists'][0]['name']."\n播放链接：：http://music.163.com/song/media/outer/url?id={$id}";
        $this->returns();
        return;
    }
    protected function returns(){
        $type = $this->info['type'];
        $data = $this->Array;
        if($data['data']['Music']){
            $song = $data['data']['song'];//歌名
            $mp3 = $data['data']['Music'];//歌曲链接
            $name = $data['data']['singer'];//歌手
            $cover = $data['data']['cover'];//图片
            $url = $data['data']['Music'];//跳转链接
            $tail = $this->info['tail'];//小尾巴
            Switch($type){
                case 'text':
                need::send($this->Msg, 'text');
                break;
                case 'json':
                need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"随机音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$name.'","jumpUrl":"'.$url.'","musicUrl":"'.$mp3.'","preview":"'.$cover.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$song.'"}},"prompt":"[分享]'.$song.'","ver":"0.0.0.1","view":"music"}', 'text');
                break;
                case 'xml':
                echo 'card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?>';
                need::send('<msg serviceID="2" templateID="1" action="web" brief="[分享]QQ飙升榜" sourceMsgId="0" url="'.$url.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.str_replace('&', '&amp;', $cover).'" src="'.str_replace('&', '&amp;', $mp3).'" /><title>'.str_replace('&', '&amp;', $song).'</title><summary>'.str_replace('&', '&amp;', $name).'</summary></item><source name="'.$tail.'" icon="https://url.cn/53tgeq7" url="" action="app" a_actionData="com.tencent.qqmusic" i_actionData="tencent1101079856://" appid="1101079856" /></msg>', 'text');
                default:
                need::send($data, 'json');
                break;
            }
        }else{
            Switch($type){
                case 'text':
                need::send($this->Msg, 'text');
                break;
                default:
                need::send($data);
                break;
            }
        }
    }
    public function aesGetParams($param, $method = 'AES-128-CBC', $key = 'JK1M5sQAEcAZ46af', $options = '0', $iv = '0102030405060708'){
        $firstEncrypt = openssl_encrypt($param, $method, '0CoJUm6Qyw8W8jud', $options, $iv);
        $secondEncrypt = openssl_encrypt($firstEncrypt, $method, $key, $options, $iv);
        return $secondEncrypt;
    }
}