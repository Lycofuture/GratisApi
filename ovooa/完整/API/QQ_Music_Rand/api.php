<?php
header("Content-type: application/json; charset=text");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(40); // 调用统计函数
addAccess();//调用统计函数
require "../../need.php";//引入封装好的函数
/* End */
$msg=@$_REQUEST['msg']?:"热歌榜";
$type=@$_REQUEST["type"];
$Skey = @$_REQUEST['Skey']?:need::cookie('../../','Skey');
$Pskey = @$_REQUEST['Pskey']?:need::cookie('../../','y.qq.com');
$uin = @$_REQUEST['uin']?:need::cookie('../../','Robot');
$tail = @$_REQUEST['tail']?:'QQ音乐';
$id = @$_REQUEST['id'];
New Music_rand_QQ(Array('Msg'=>$msg, 'type'=>$type, 'Uin'=>$uin, 'Skey'=>$Skey, 'Pskey'=>$Pskey, 'tail'=>$tail, 'id'=>$id));

class Music_rand_QQ{
    protected $Music = ['飙升榜'=>'https://i.y.qq.com/n2/m/share/details/toplist.html?ADTAG=myqq&from=myqq&channel=10007100&id=62', '热歌榜'=>'https://i.y.qq.com/n2/m/share/details/toplist.html?ADTAG=myqq&from=myqq&channel=10007100&id=26', '新歌榜'=>'https://i.y.qq.com/n2/m/share/details/toplist.html?ADTAG=myqq&from=myqq&channel=10007100&id=27'];
    protected $info = [];
    protected $Msg;
    protected $url;
    protected $Array = [];
    //protected $preg = ['飙升榜'=>
    public function __construct(Array $Array){
        foreach($Array as $k=>$v){
            $this->info[$k] = $v;
        }
        $Music = $this->Music[$Array['Msg']]?:$this->Music['热歌榜'];
        $this->url = $Music;
        $this->ParameterException();
    }
    protected function ParameterException(){
        $info = $this->info;
        if(!($info['Msg'])){
            $Music = $this->Music[$Array['Msg']]?:$this->Music['热歌榜'];
            $this->url = $Music;
        }
        $id = $this->info['id'];
        if($id && is_numEric($id)){
            if(strlen($id) <= 4){
                $this->url = 'https://i.y.qq.com/n2/m/share/details/toplist.html?ADTAG=myqq&from=myqq&channel=10007100&id='.$id;
            }else{
                $this->url = 'https://i.y.qq.com/n2/m/share/details/taoge.html?id='.$id;
            }
        }else{
            unset($this->info['id']);
        }
        if(!$this->url) $this->url = $this->Music['飙升榜'];
        return $this->GetMusic();
    }
    protected function GetMusic(){
        $url = $this->url;
        $Data = need::teacher_curl($url);
        if(isset($this->info['id']) && stristr($url, 'taoge.html')){
            preg_match('/firstPageData\s*=\s*([\s\S]*?);[\r\n\s]*<\/script>/i', $Data, $Data);
            $Data = json_decode(trim($Data[1]), true)['taogeData'];
            $Title = $Data['title'];//歌单名字
            $id = $Data['id'];//歌单id
            if(!($id)){
                unset($this->Array, $this->Msg);
                $this->Array = Array('code'=>-1, 'text'=>'请输入正确的歌单id');
                $this->Msg = '请输入正确的歌单id';
                $this->returns();
                return;
            }
            $num = $Data['songnum'];//歌曲数量
            $Nick = $Data['host_nick'];//歌单创建人
            $desc = $Data['desc']?:'暂无介绍';
            $cover = $Data['picurl'];//歌单封面
            $tag_Array = $Data['tag'];
            if(!($tag_Array)){
                $tag = '';
            }else{
                foreach($tag_Array as $v){
                    $tag .= $v.',';
                }
                $tag = trim($v, ',');
            }
            $Uin = $Data['creator']['musicid'];//创建人账号
            $list = $Data['songlist'];
            $rand = Array_rand($list, 1);
            $mid = $list[$rand]['mid'];
            $Array = json_decode(need::teacher_curl('http://ovooa.com/API/QQ_Music/?mid='.$mid), true);
            $Array['data']['Uin_Data'] = Array('id'=>$id, 'Uin'=>$Uin, 'Title'=>$Title, 'tag'=>$tag_Array, 'desc'=>$desc, 'picture'=>$cover, 'Nick'=>$Nick, 'num'=>$num);
            $this->Array = $Array;
            $this->returns();
            //echo $Data;
            return;
        }else{
            preg_match('/firstPageData\s*=\s*([\s\S]*?)[\r\n\s]*<\/script>/i', $Data, $Data);
            $Data = json_decode(trim($Data[1]), true)['toplistData']['song'];
            // print_r($Data);
            if(!($Data)){
                unset($this->Array, $this->Msg);
                $this->Array = Array('code'=>-1, 'text'=>'请输入正确的歌单id');
                $this->Msg = '请输入正确的歌单id';
                $this->returns();
                return;
            }
            $rand = Array_rand($Data, 1);
            $id = $Data[$rand]['songId'];
            $this->Array = json_decode(need::teacher_curl('http://ovooa.com/API/QQ_Music/?songid='.$id), true);
            $this->returns();
            return;
        }
    }
    public function returns(){
        $type = $this->info['type'];
        $Data = $this->Array;
        if($Data['data']['mid']){
            $song = $Data['data']['song'];//歌名
            $mp3 = $Data['data']['music'];//歌曲链接
            $name = $Data['data']['singer'];//歌手
            $cover = $Data['data']['picture'];//图片
            $url = $Data['data']['url'];//跳转链接
            $tail = $this->info['tail'];//小尾巴
            Switch($type){
                case 'json':
                need::send('json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"随机音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$name.'","jumpUrl":"'.$url.'","musicUrl":"'.$mp3.'","preview":"'.$cover.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$song.'"}},"prompt":"[分享]'.$song.'","ver":"0.0.0.1","view":"music"}', 'text');
                break;
                case 'xml':
                echo 'card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?>';
                need::send('<msg serviceID="2" templateID="1" action="web" brief="[分享]QQ飙升榜" sourceMsgId="0" url="'.$url.'" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="'.str_replace('&', '&amp;', $cover).'" src="'.str_replace('&', '&amp;', $mp3).'" /><title>'.str_replace('&', '&amp;', $song).'</title><summary>'.str_replace('&', '&amp;', $name).'</summary></item><source name="'.$tail.'" icon="https://url.cn/53tgeq7" url="" action="app" a_actionData="com.tencent.qqmusic" i_actionData="tencent1101079856://" appid="1101079856" /></msg>', 'text');
                break;
                case 'text':
                echo '±img='.$cover.'±';
                echo "\n";
                echo '歌名：'.$song;
                echo "\n";
                echo '歌手：'.$name;
                echo "\n";
                echo '歌曲链接：'.$mp3;
                exit();
                break;
                default:
                need::send($Data, 'json');
                break;
            }
        }else{
            Switch($type){
                case 'text':
                need::send($Data['text'], 'text');
                break;
                default:
                // unset($Data['data']['Uin_Data']);
                need::send($Data);
                break;
            }
        }
    }
}
