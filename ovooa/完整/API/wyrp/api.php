<?php

header('content-type:application/json');

/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(4); // 调用统计函数
addAccess();//调用统计函数
require ('../../need.php');//引用封装好的函数文件
/* End */

$songid = @$_REQUEST['id'];
if(empty($songid) || !is_numeric($songid)){
    $array = array(71385702,2884035,3778678,3779629,19723756);
    $rand = array_rand($array,1);
    $songid = $array[$rand];
}
$New = New 网易热评;
$array = $New->解析歌单($songid);
$id = $New->随机获取($array);
$New->解析歌曲($id);
$New->解析热评();
class 网易热评{
    protected $header = array(
                'Host: music.163.com',
                'Origin: http://music.163.com',
                'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36',
                'Content-Type: application/x-www-form-urlencoded',
                'Referer: http://music.163.com/search/',
            );
    public function 解析歌单($id){
        $this->listid = $id;
        $post_data = http_build_query(array('s'=>'100','id' =>$id,'n'=>'100','t'=>'100'));
        $url = "http://music.163.com/api/v6/playlist/detail";
        $data = need::teacher_curl($url,[
            'post'=>$post_data,
            'ua'=>'NeteaseMusic/8.6.31.211201171945(8006031);Dalvik/2.1.0 (Linux; U; Android 11; PCLM10 Build/RKQ1.200928.002)',
            'Header'=>$this->header
        ]);
        $array = json_decode($data,true)['playlist']['trackIds'];
        //print_r($array);exit;
        return $array;
    }
    public function 随机获取($array){
        $id = $array[array_rand($array,1)]['id'];//$data['Playlist']['data'][$rand]['id'];
        $this->id = $id;
        return $id;
    }
    public function 解析歌曲($id){
        $url = 'http://music.163.com/api/song/detail/?id='.$id.'&ids=%5B'.$id.'%5D&csrf_token=';
        $data = json_decode(need::teacher_curl($url),true);
        $this->music = $data['songs'][0]['name'];
        $this->name = $data['songs'][0]['artists'][0]['name'];
        $this->Picture = $data['songs'][0]['album']['picUrl'];
        $this->musicurl = 'http://music.163.com/song/media/outer/url?id='.$id;
        $this->id = $id;
        return;
    }
    public function 解析热评(){
        $songid = $this->id;
        //echo $songid;exit;
        $url = 'https://music.163.com/weapi/v1/resource/comments/R_SO_4_' . $songid . '?csrf_token=';
        $getEncSecKey = '2a98b8ea60e8e0dd0369632b14574cf8d4b7a606349669b2609509978e1b5f96ed8fbe53a90c0bb74497cd2eb965508bff5bfa065394a52ea362539444f18f423f46aded5ed9a1788d110875fb976386aa4f5d784321433549434bccea5f08d1888995bdd2eb015b2236f5af15099e3afbb05aa817c92bfe3214671e818ea16b';
        $key = '{"rid":"R_SO_4_' . $songid . '","offset":"0","total":"true","limit":"20","csrf_token":""}';
        $key = $this->aesGetParams($key);
        $cookie = array('params'=>$key,'encSecKey'=>$getEncSecKey);
        $headers = $this->header;
        $data = need::teacher_curl($url,[
            'post'=>http_build_query($cookie),
            'refer'=>'https://music.163.com',
            'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
            'Header'=>$headers
        ]);
        $data = json_decode($data,true);
        $rand = @array_rand($data['hotComments'],1)?:0;
        $Nick = $data['hotComments'][$rand]['user']['nickname'];
        if(empty($Nick)){
            $array = $this->解析歌单($this->listid);
            $id = $this->随机获取($array);
            $this->解析歌曲($id);
            $this->解析热评();
        }
        $array = array('code'=>1,'text'=>'获取成功','data'=>array('Music'=>$this->music,'name'=>$this->name,'Picture'=>$this->Picture,'Url'=>$this->musicurl, 'id'=>$this->id, 'Content'=>$data['hotComments'][$rand]['content'],'Nick'=>$Nick));
        if(@$_REQUEST['type'] == 'text'){
            echo $data['hotComments'][$rand]['content'];
            echo "\n\n";
            echo '  ——《'.$this->name.'·'.$this->music.'·'.$Nick.'》';
        }else{
            need::send($array,'json');
        }
    }
    public function aesGetParams($param, $method = 'AES-128-CBC', $key = 'JK1M5sQAEcAZ46af', $options = '0', $iv = '0102030405060708'){
        $firstEncrypt = openssl_encrypt($param, $method, '0CoJUm6Qyw8W8jud', $options, $iv);
        $secondEncrypt = openssl_encrypt($firstEncrypt, $method, $key, $options, $iv);
        return $secondEncrypt;
    }
}