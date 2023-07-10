<?php
header('Content-type:application/json');
require ("../function.php"); // 引入函数文件
addApiAccess(76); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../need.php');//引入bkn文件
$Uin=@$_REQUEST['Uin']?:@$_REQUEST['qq'];
$Skey=@$_REQUEST['skey']?:@$_REQUEST['Skey'];
$Group=@$_REQUEST['group']?:@$_REQUEST['Group'];
$format = @$_REQUEST['format'];
$Msg = @$_REQUEST['Msg']?:@$_REQUEST['msg'];
$n = @$_REQUEST['b']?:@$_REQUEST['n'];
$page = @$_REQUEST["p"]?:@$_REQUEST['page']?:'1';
$num = @$_REQUEST["ts"]?:@$_REQUEST['num']?:'10';
$type = @$_REQUEST['type'];
$GTK=need::GTK((String)$Skey);
new 一起听歌(['Msg'=>$Msg, 'format'=>$format, 'n'=>$n, 'page'=>$page, 'num'=>$num, 'Uin'=>$Uin, 'Skey'=>$Skey, 'Group'=>$Group, 'GTK'=>$GTK, 'type'=>$type]);
class 一起听歌{
    protected $info = array();
    protected $array = array();
    protected $Msg;
    public function __construct(array $array){
        foreach($array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        $info = $this->info;
        $Group = $info['Group'];
        if(!need::is_num($Group)){
            unset($this->Msg, $this->array);
            $this->Msg = '请输入正确的群号';
            $this->array = array('code'=>-1, 'text'=>'请输入正确的群号');
            $this->returns();
            return;
        }
        $Uin = $info['Uin'];
        if(!need::is_num($Uin)){
            unset($this->Msg, $this->array);
            $this->Msg = '请输入正确的账号';
            $this->array = array('code'=>-2, 'text'=>'请输入正确的账号');
            $this->returns();
            return;
        }
        $Skey = $info['Skey'];
        if(!need::is_Skey($Skey)){
            unset($this->Msg, $this->array);
            $this->Msg = '请输入正确的Skey';
            $this->array = array('code'=>-3, 'text'=>'请输入正确的Skey');
            $this->returns();
            return;
        }
        $page = $info['page'];
        if(!is_numEric($page)){
            $info['page'] = 1;
            $this->info = $info;
        }
        if(method_exists($this, $info['format'])){
            $method = $info['format'];
            $this->$method();
            return;
        }else{
            echo '既然你触发了这个，证明你不会用。'."\n那就送你个词库吧~\n点击下载↓\n".'http://ovooa.com/API/17tg/17tg.txt';//file_get_Contents('./17tg.txt');
            return;
        }
    }
    public function open(){
        $info = $this->info;
        $Group = $info['Group'];
        $Uin = $info['Uin'];
        $Skey = $info['Skey'];
        $GTK = $info['GTK'];
        $url = "https://web.qun.qq.com/cgi-bin/media/set_media_state?t=0.85959781718529&g_tk=".$GTK."&state=1&gcode=".$Group."&qua=V1_AND_SQ_8.4.1_1442_YYB_D&uin=".$Uin."&format=json&inCharset=utf-8&outCharset=utf-8";
        $cookie = 'uin=o'.$Uin.'; p_uin=o'.$Uin.'; skey='.$Skey.';';
        $data = need::teacher_curl($url, [
            'cookie'=>$cookie
        ]);
        preg_match_all("/retcode\":(.*?)}/", $data, $retcode);
        $retcode=$retcode[1][0];
        Switch($retcode){
            case '0':
            unset($this->Msg, $this->array);
            $this->Msg = '已开启一起听歌';
            $this->array = array('code'=>1, 'text'=>'已开启一起听歌');
            $this->returns();
            break;
            case 100061:
            unset($this->Msg, $this->array);
            $this->Msg = '已开启，请不要重复';
            $this->array = array('code'=>-4, 'text'=>'已开启，请不要重复');
            $this->returns();
            break;
            case 100051:
            unset($this->Msg, $this->array);
            $this->Msg = '开启失败，权限不足';
            $this->array = array('code'=>-5, 'text'=>'开启失败，权限不足');
            $this->returns();
            break;
            case 100000:
            unset($this->Msg, $this->array);
            $this->Msg = '开启失败，Skey已过期';
            $this->array = array('code'=>-6, 'text'=>'开启失败，Skey已过期');
            $this->returns();
            break;
            case 100041:
            unset($this->Msg, $this->array);
            $this->Msg = '开启失败，权限不足';
            $this->array = array('code'=>-14, 'text'=>'开启失败，权限不足');
            $this->returns();
            break;
            default:
            unset($this->Msg, $this->array);
            $this->Msg = '开启失败，未知错误'.$retcode;
            $this->array = array('code'=>-7, 'text'=>'开启失败，未知错误');
            $this->returns();
            break;
        }
        return;
    }
    public function close(){
        $info = $this->info;
        $Group = $info['Group'];
        $Uin = $info['Uin'];
        $Skey = $info['Skey'];
        $GTK = $info['GTK'];
        $url="https://web.qun.qq.com/cgi-bin/media/set_media_state?t=0.85959781718529&g_tk=".$GTK."&state=0&gcode=".$Group."&qua=V1_AND_SQ_8.4.1_1442_YYB_D&uin=".$Uin."&format=json&inCharset=utf-8&outCharset=utf-8";
        $cookie = 'uin=o'.$Uin.'; skey='.$Skey.'; p_uin=o'.$Uin.'';
        $data = need::teacher_curl($url, [
            'cookie'=>$cookie,
            'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045913 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0 CurrentMode/1 CurrentFontScale/1.0'
        ]);
        preg_match_all('/retcode":(.*?)}/', $data, $retcode);
        $retcode=$retcode[1][0];
        Switch($retcode){
            case '0':
            unset($this->Msg, $this->array);
            $this->Msg = '已关闭一起听歌';
            $this->array = array('code'=>1, 'text'=>'已关闭一起听歌');
            $this->returns();
            break;
            case 100061:
            unset($this->Msg, $this->array);
            $this->Msg = '已关闭，请不要重复';
            $this->array = array('code'=>-4, 'text'=>'已关闭，请不要重复');
            $this->returns();
            break;
            case 100051:
            unset($this->Msg, $this->array);
            $this->Msg = '关闭失败，权限不足';
            $this->array = array('code'=>-5, 'text'=>'关闭失败，权限不足');
            $this->returns();
            break;
            case 100000:
            unset($this->Msg, $this->array);
            $this->Msg = '关闭失败，Skey已过期';
            $this->array = array('code'=>-6, 'text'=>'关闭失败，Skey已过期');
            $this->returns();
            break;
            case 100041:
            unset($this->Msg, $this->array);
            $this->Msg = '关闭失败，权限不足';
            $this->array = array('code'=>-14, 'text'=>'关闭失败，权限不足');
            $this->returns();
            break;
            default:
            unset($this->Msg, $this->array);
            $this->Msg = '关闭失败，未知错误';
            $this->array = array('code'=>-7, 'text'=>'关闭失败，未知错误');
            $this->returns();
            break;
        }
        return;
    }
    protected function search(){
        $info = $this->info;
        $Group = $info['Group'];
        $Uin = $info['Uin'];
        $Skey = $info['Skey'];
        $GTK = $info['GTK'];
        $page = $info['page'];
        $n = $info['n'];
        $Msg = $info['Msg'];
        if(!$Msg){
            unset($this->Msg, $this->array);
            $this->Msg = '请输入需要搜索的歌名';
            $this->array = array('code'=>-13, 'text'=>'请输入需要搜索的歌名');
            $this->returns();
            return;
        }
        $num = $info['num'];
        $url="https://web.qun.qq.com/cgi-bin/media/search_music?t=0.6528915256057382&g_tk=".$GTK."&keyword=".urlencode($Msg)."&page=".$page."&limit=".$num."&gcode=".$Group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$Uin."&format=json&inCharset=utf-8&outCharset=utf-8";
        $cookie= 'uin=o'.$Uin.'; skey='.$Skey.'; p_uin=o'.$Uin.'';
        $data = need::teacher_curl($url, [
            'cookie'=>$cookie
        ]);
        $Array = json_decode(str_replace(array('�'), '', $data), true);
        //print_r($Array);exit;
        $song = $Array['result']['song_list'];
        $count = count($song);
        if(empty($count)){
            unset($this->Msg, $this->array);
            $this->Msg = '未搜索到相关歌曲';
            $this->array = array('code'=>-9, 'text'=>'未搜索到相关歌曲');
            $this->returns();
            return;
        }
        $array = [];
        foreach($song as $v){
            $id = $v['songid'];//id
            $name = $v['name'];//歌名;
            $singer = '';
            foreach($v['singer_list'] as $va){
                $singer .= $va['name'].'##';
            }
            $singer = trim($singer,'##');//歌手
            $cover = $v['pic'];//封面图
            $time = $v['duration'];//时间
            $int = intval($time / 60);
            $second = ($time - ($int * 60));
            $Time = sprintf('%02d', $int) . ':' . sprintf('%02d', $second);
            $array[] = array('song'=>$name,'singer'=>$singer,'cover'=>$cover,'mid'=>$id,'time'=>$Time,'uint'=>$time);
        }
        if(!$n || !is_numEric($n) || !$array[($n -1)]){
            $retcode=$Array['retcode'];
            Switch($retcode){
                case '0':
                unset($this->Msg, $this->array);
                $Message_array = array();
                $Message = '——添加歌曲——';
                $Message .= "\n";
                for ($x=0; $x < $count && $x<$num; $x++) {
                    $aa = $array[$x]['song'];//歌名
                    $bb = $array[$x]['singer'];//歌手
                    $Message .= ($x+1)."：".$aa."-".$bb."\n";
                    $Message_array[] = $aa."-".$bb."\n";
                }
                $this->Msg = $Message;
                //echo $Message;
                $this->array = array('code'=>1, 'text'=>'获取成功', 'data'=>['data'=>$Message, 'array'=>$array, 'count'=>$count]);
                $this->returns();
                return;
                break;
                case 100061:
                unset($this->Msg, $this->array);
                $this->Msg = '已添加，请不要重复';
                $this->array = array('code'=>-4, 'text'=>'已开启，请不要重复');
                $this->returns();
                break;
                case 100051:
                unset($this->Msg, $this->array);
                $this->Msg = '搜索失败，权限不足';
                $this->array = array('code'=>-5, 'text'=>'搜索失败，权限不足');
                $this->returns();
                break;
                case 100000:
                unset($this->Msg, $this->array);
                $this->Msg = '搜索失败，Skey已过期';
                $this->array = array('code'=>-6, 'text'=>'搜索失败，Skey已过期');
                $this->returns();
                break;
                case 100041:
                unset($this->Msg, $this->array);
                $this->Msg = '添加失败，权限不足';
                $this->array = array('code'=>-14, 'text'=>'添加失败，权限不足');
                $this->returns();
                break;
                default:
                unset($this->Msg, $this->array);
                $this->Msg = '搜索失败，未知错误';
                $this->array = array('code'=>-7, 'text'=>'搜索失败，未知错误');
                $this->returns();
                break;
            }
            return;
        }else{
            $b = ($n-1);
            $songid = $array[$b]['mid'];//id
            if(!$songid){
                unset($this->Msg, $this->array);
                $this->Msg = '添加失败！无法获取歌曲id！疑似“付费”请换首歌重试！';
                $this->array = array('code'=>-8, 'text'=>'添加失败！无法获取歌曲id！疑似“付费”请换首歌重试！');
                $this->returns();
                return;
            }
            $name=$array[$b]['song'];//歌名
            $list=$array[$b]['singer'];//歌手
            $cover=$array[$b]['cover'];//图片
            $duration=$array[$b]['uint'];//时间
            $time=$array[$b]['time'];//时间
            $url="https://web.qun.qq.com/cgi-bin/media/oper_music?t=0.21004511223164646&g_tk=".$GTK;
            $post='oper_type=1&song_list=[{"song_id":"'.$songid.'","name":"'.$name.'","sub_title":"","singer_list":["'.$list.'"],"cover":"'.$cover.'","duration":'.$duration.',"has_added":0}]&gcode='.$Group.'&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin='.$Uin.'&format=json&inCharset=utf-8&outCharset=utf-8';
            $cookie='uin=o'.$Uin.'; skey='.$Skey.'; p_uin=o'.$Uin.'';
            $data=json_decode(need::teacher_curl($url, [
               'cookie'=>$cookie,
               'post'=>$post
            ]), true);
            if(!$data){
                unset($this->Msg, $this->array);
                $this->Msg = "±img:{$cover}±已将歌曲[".$name."]加进歌单！";
                $this->array = array('code'=>-7, 'text'=>'未知错误，换首歌试试');
                $this->returns();
                return;
            }
            $retcode = $data['retcode'];
            Switch($retcode){
                case '0':
                unset($this->Msg, $this->array);
                $this->Msg = "±img:{$cover}±已将歌曲[".$name."]加进歌单！";
                $this->array = array("code"=>"1","text"=>"已将歌曲[".$name."]加进歌单！",'data'=>array("cover"=>$cover,'song'=>$name,'singer'=>$list,'time'=>$time));
                $this->returns();
                break;
                case 100001:
                $songid=$array[$b]['mid'];//id
                $name=$array[$b]['song'];//歌名
                $singer=$array[$b]['singer'];//歌手
                $cover=$array[$b]['cover'];//图片
                $duration=$array[$b]['uint'];//时间
                $post = 'oper_type=1&song_list=[{"song_id":"'.$songid.'","name":"'.$name.'","sub_title":"","singer_list":'.json_encode(explode('、',$singer), 320).',"cover":"'.$cover.'","duration":'.$duration.',"spanName":"<span style=\"color: #00cafc\">'.$name.'</span>","spanTitle":"","spanSinger":"'.$singer.'","exactMatch":true}]&gcode='.$Group.'&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin='.$Uin.'&format=json&inCharset=utf-8&outCharset=utf-8';
                $url="https://web.qun.qq.com/cgi-bin/media/oper_music?t=0.21004511223164646&g_tk=".$GTK;
                $cookie='uin=o'.$Uin.'; skey='.$Skey.'; p_uin=o'.$Uin.'';
                $data=json_decode(need::teacher_curl($url, [
                   'cookie'=>$cookie,
                   'post'=>$post
                ]), true);
                $retco=$data['retcode'];
                Switch($retco){
                    case '0':
                    unset($this->Msg, $this->array);
                    $this->Msg = "±img:{$cover}±已将歌曲[".$name."]加进歌单！";
                    $this->array = array('code'=>-7, 'text'=>'未知错误，换首歌试试');
                    $this->returns();
                    break;
                    case 100001:
                    unset($this->Msg, $this->array);
                    $this->Msg = "添加失败一起听歌未开启";
                    $this->array = array('code'=>-10, 'text'=>'添加失败一起听歌未开启');
                    $this->returns();
                    break;
                    case 100041:
                    unset($this->Msg, $this->array);
                    $this->Msg = '添加失败，权限不足';
                    $this->array = array('code'=>-14, 'text'=>'添加失败，权限不足');
                    $this->returns();
                    break;
                    default:
                    unset($this->Msg, $this->array);
                    $this->Msg = "添加失败请重试";
                    $this->array = array('code'=>-7, 'text'=>'添加失败，未知错误');
                    $this->returns();
                    break;
                }
                break;
                default:
                unset($this->Msg, $this->array);
                $this->Msg = "添加失败请重试";
                $this->array = array('code'=>-7, 'text'=>'添加失败，未知错误');
                $this->returns();
                break;
            }
            return;
        }
        return;
    }
    public function Uinnum(){
        $info = $this->info;
        $Group = $info['Group'];
        $Uin = $info['Uin'];
        $Skey = $info['Skey'];
        $GTK = $info['GTK'];
        $page = $info['page'];
        $n = $info['n'];
        $Msg = $info['Msg'];
        $num = $info['num'];
        $url="https://web.qun.qq.com/qunmusic/listener?uin=".$Group."&uinType=1&_wwv=128&_wv=2";
        //https://web.qun.qq.com/qunmusic/listener?uin=820323177&uinType=1&_wwv=128&_wv=2
        $cookie='uin=o'.$Uin.'; skey='.$Skey.'; p_uin=o'.$Uin.'';
        $data=need::teacher_curl($url, [
            'cookie'=>$cookie,
            'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045913 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0 CurrentMode/1 CurrentFontScale/1.0'
        ]);
        $result=@preg_match_all('/__INITIAL_STATE__=([\s\S]*?)<\/script>/',$data,$nute);
        $array = json_decode($nute[1][0], true);
        //print_r($data);exit;
        $count = count($array['memberList']);
        if($array && !$count){
            unset($this->Msg, $this->array);
            $this->Msg = '听歌人数为空';
            $this->array = array('code'=>-11, 'text'=>'听歌人数为空');
            $this->returns();
            return;
        }
        if(!$count){
            unset($this->Msg, $this->array);
            $this->Msg = '没有开启一起听歌';
            $this->array = array('code'=>-10, 'text'=>'没有开启一起听歌');
            $this->returns();
            return;
        }
        //$Message .= $count;
        if($count == $num || $num == "1"){
            $pagea = intval($count/$num);
        }else{
            $pagea = intval($count/$num+1);
        }//计算总页数
        if($page > $pagea || $page < 1){
            unset($this->Msg, $this->array);
            $this->Msg = '页数不存在';
            $this->array = array('code'=>-12, 'text'=>'页数不存在');
            $this->returns();
            return;
        }
        $pageb = intval($page-1);
        $pageb = intval($pageb*$num);
        $pagec = intval($page*$num-1);
        $Message_array = [];
        $Message_Array = [];
        $Message = '——听歌人数——';
        $Message .= "\n";
        for ($x = $pageb ; $x < $count && $x <= $pagec ; $x++) {
            $Message_array[] = $array['memberList'][$x]['nick'].'('.$array['memberList'][$x]['uin'].')';
            $Message_Array[] = ['Nick'=>$array['memberList'][$x]['nick'], 'Uin'=>$array['memberList'][$x]['uin']];
            $Message .= ($x + 1);
            $Message .= '.';
            $Message .= $array['memberList'][$x]['nick'];
            $Message .= '(';
            $Message .= $array['memberList'][$x]['uin'];
            $Message .= ')';
            $Message .= "\n";
        }
        $Message .= '第'.$page.'/'.$pagea.'页';
        $Message .= "\n";
        $Message .= '提示：共有'.$count.'人在听歌';
        unset($this->Msg, $this->array);
        $this->Msg = $Message;
        $this->array = array("code"=>1,"text"=>"获取成功",'data'=>['data'=>$Message_array, 'array'=>$Message_Array, 'count'=>$count]);
        $this->returns();
        return;
    }
    public function change(){
        $info = $this->info;
        $Group = $info['Group'];
        $Uin = $info['Uin'];
        $Skey = $info['Skey'];
        $GTK = $info['GTK'];
        $page = $info['page'];
        $n = $info['n'];
        $Msg = $info['Msg'];
        $num = $info['num'];
        $url="https://web.qun.qq.com/cgi-bin/media/get_music_list?t=0.3637193874264979&g_tk=".$gtk."&gcode=".$Group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$Uin."&format=json&inCharset=utf-8&outCharset=utf-8";
        $cookie='uin=o'.$Uin.'; skey='.$Skey.'; p_uin=o'.$Uin.'';
        $data = need::teacher_curl($url, [
            'cookie'=>$cookie
        ]);
        $Array = json_decode($data,true);
        $data = $Array['result']['song_list'];
        if(!$data){
            unset($this->Msg, $this->array);
            $this->Msg = '未知错误';
            $this->array = array('code'=>-7, 'text'=>'未知错误');
            $this->returns();
            return;
        }
        $array = [];
        foreach($data as $v){
            $song = $v['song'];
            $name = $song['bytes_name'];//歌名
            $singer = '';
            foreach($song['rpt_bytes_singer'] as $v){
                $singer .= $v.'，';
            }
            $singer = trim($singer,'，');
            $time = $song['uint32_duration'];//播放时间
            $int = intval($time / 60);
            $second = ($time - ($int *60));
            $Time = sprintf('%02d', $int) .':'. sprintf('%02d', $second);//时间
            $cover = $song['bytes_cover'];//图
            $id = $song['str_song_id'];//id
            $array[] = array('song'=>$name,'singer'=>$singer,'cover'=>$cover,'time'=>$Time,'mid'=>$id,'uint'=>$time);
        }
        $count = count($array);
        if(!$count){
            unset($this->Msg, $this->array);
            $this->Msg = '播放列表空空如也';
            $this->array = array('code'=>-11, 'text'=>'播放列表空空如也');
            $this->returns();
            return;
        }
        if($count == $num || $num == "1"){
            $pa = intval($count/$num);
        }else{
            $pa = intval($count/$num+1);
        }//计算总页数
        if($page > $pa || $page < 1){
            unset($this->Msg, $this->array);
            $this->Msg = '页数不存在';
            $this->array = array('code'=>-12, 'text'=>'页数不存在');
            $this->returns();
            return;
        }
        $pb = intval($page-1);
        $pb = intval($pb*$num);
        $pc = intval($page*$num-1);
        $Message_array = [];
        if(!$n || !is_numEric($n) || !$array[($n-1)]){
            $Message .= '——歌曲列表——';
            $Message .= "\n";
            for ($x = $pb ; $x < $count && $x <= $pc ; $x++){
                $aa=$array[$x]['song'];//歌名
                $bb=$array[$x]['singer'];//歌手
                $Time = $array[$x]['time'];//时间
                $Message .= ($x+1);
                $Message .= '.';
                $Message .= $aa;
                $Message .= '-';
                $Message .= $bb;
                $Message .= '-'.$Time;
                $Message .= "\n";
                //$Message_array[] = $aa .'-'. $bb.'('.$Time.')';
            }
            $Message .= '第'.$page.'/'.$pa.'页';
            $Message .= "\n";
            $Message .= ( '提示：共有'.$count.'首歌');
            $array = array_slice($array, $pb, $num);
            unset($this->Msg, $this->array);
            $this->Msg = $Message;
            $this->array = array("code"=>"1","text"=>'获取成功','data'=>['data'=>$Message,'array'=>$array,'count'=>$count]);
            $this->returns();
            return;
        }else{
            $b=($n-1);
            $song = $array[$b]['song'];
            $singer = $array[$b]['singer'];
            $cover = $array[$b]['cover'];
            $time = $array[$b]['time'];
            $songid = $array[$b]['mid'];
            $url="https://web.qun.qq.com/cgi-bin/media/play_next_song?t=0.8287074011709832&g_tk=".$gtk."&song_id=".$songid."&gcode=".$Group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$Uin."&format=json&inCharset=utf-8&outCharset=utf-8";
            $post='0';
            $cookie='uin=o'.$Uin.'; p_uin=o'.$Uin.'; skey='.$Skey;
            $data = json_decode(need::teacher_curl($url, [
                'cookie'=>$cookie
            ]) ,true);
            $retcode=$data['retcode'];
            Switch($retcode){
                case '0':
                unset($this->Msg, $this->array);
                $this->Msg = "已切换歌曲[".$song."]";
                $this->array = array('code'=>1, 'text'=>"已切换歌曲[".$song.']', 'data'=>Array('cover'=>$cover, 'music'=>$song, 'singer'=>$singer, 'time'=>$time, 'songid'=>$songid));
                $this->returns();
                return;
                break;
                case 100061:
                unset($this->Msg, $this->array);
                $this->Msg = '已开启，请不要重复';
                $this->array = array('code'=>-4, 'text'=>'已开启，请不要重复');
                $this->returns();
                break;
                case 100051:
                unset($this->Msg, $this->array);
                $this->Msg = '切换失败，权限不足';
                $this->array = array('code'=>-5, 'text'=>'切换失败，权限不足');
                $this->returns();
                break;
                case 100000:
                unset($this->Msg, $this->array);
                $this->Msg = '切换失败，Skey已过期';
                $this->array = array('code'=>-6, 'text'=>'切换失败，Skey已过期');
                $this->returns();
                break;
                case 100041:
                unset($this->Msg, $this->array);
                $this->Msg = '切换失败，权限不足';
                $this->array = array('code'=>-14, 'text'=>'切换失败，权限不足');
                $this->returns();
                break;
                default:
                unset($this->Msg, $this->array);
                $this->Msg = '切换失败，未知错误';
                $this->array = array('code'=>-7, 'text'=>'切换失败，未知错误');
                $this->returns();
                break;
            }
            return;
        }
        return;
    }
    public function delete(){
        $info = $this->info;
        $Group = $info['Group'];
        $Uin = $info['Uin'];
        $Skey = $info['Skey'];
        $GTK = $info['GTK'];
        $page = $info['page'];
        $n = $info['n'];
        $Msg = $info['Msg'];
        $num = $info['num'];
        $url="https://web.qun.qq.com/cgi-bin/media/get_music_list?t=0.3637193874264979&g_tk=".$gtk."&gcode=".$Group."&qua=V1_AND_SQ_8.3.0_1362_YYB_D&uin=".$Uin."&format=json&inCharset=utf-8&outCharset=utf-8";
        $cookie='uin=o'.$Uin.'; skey='.$Skey.'; p_uin=o'.$Uin.'';
        $data = need::teacher_curl($url, [
            'cookie'=>$cookie
        ]);
        $Array = json_decode($data,true);
        $data = $Array['result']['song_list'];
        if(!$data){
            unset($this->Msg, $this->array);
            $this->Msg = '未知错误';
            $this->array = array('code'=>-7, 'text'=>'未知错误');
            $this->returns();
            return;
        }
        $array = [];
        foreach($data as $v){
            $song = $v['song'];
            $name = $song['bytes_name'];//歌名
            $singer = '';
            foreach($song['rpt_bytes_singer'] as $v){
                $singer .= $v.'，';
            }
            $singer = trim($singer,'，');
            $time = $song['uint32_duration'];//播放时间
            $int = intval($time / 60);
            $second = ($time - ($int *60));
            $Time = sprintf('%02d', $int) .':'. sprintf('%02d', $second);//时间
            $cover = $song['bytes_cover'];//图
            $id = $song['str_song_id'];//id
            $array[] = array('song'=>$name,'singer'=>$singer,'cover'=>$cover,'time'=>$Time,'mid'=>$id,'uint'=>$time);
        }
        $count = count($array);
        if(!$count){
            unset($this->Msg, $this->array);
            $this->Msg = '播放列表空空如也';
            $this->array = array('code'=>-11, 'text'=>'播放列表空空如也');
            $this->returns();
            return;
        }
        if($count == $num || $num == "1"){
            $pa = intval($count/$num);
        }else{
            $pa = intval($count/$num+1);
        }//计算总页数
        if($page > $pa || $page < 1){
            unset($this->Msg, $this->array);
            $this->Msg = '页数不存在';
            $this->array = array('code'=>-12, 'text'=>'页数不存在');
            $this->returns();
            return;
        }
        $pb = intval($page-1);
        $pb = intval($pb*$num);
        $pc = intval($page*$num-1);
        $Message_array = [];
        if(!$n || !is_numEric($n) || !$array[($n-1)]){
            $Message .= '——歌曲列表——';
            $Message .= "\n";
            for ($x = $pb ; $x < $count && $x <= $pc ; $x++){
                $aa=$array[$x]['song'];//歌名
                $bb=$array[$x]['singer'];//歌手
                $Time = $array[$x]['time'];//时间
                $Message .= ($x+1);
                $Message .= '.';
                $Message .= $aa;
                $Message .= '-';
                $Message .= $bb;
                $Message .= '-'.$Time;
                $Message .= "\n";
                //$Message_array[] = $aa .'-'. $bb.'('.$Time.')';
            }
            $Message .= '第'.$page.'/'.$pa.'页';
            $Message .= "\n";
            $Message .= ( '提示：共有'.$count.'首歌');
            $array = array_slice($array, $pageb, $num);
            unset($this->Msg, $this->array);
            $this->Msg = $Message;
            $this->array = array("code"=>"1","text"=>'获取成功','data'=>['data'=>$Message,'array'=>$array,'count'=>$count]);
            $this->returns();
            return;
        }else{
            $b=($n-1);
            $song = $array[$b]['song'];
            $singer = $array[$b]['singer'];
            $cover = $array[$b]['cover'];
            $time = $array[$b]['time'];
            $songid = $array[$b]['mid'];
            $duration = $array[$b]['uint'];
            //print_r($array[$b]);
            $url="https://web.qun.qq.com/cgi-bin/media/oper_music?t=0.9837291536011012&g_tk=".$GTK;
            $post='oper_type=2&song_list=[{"song_id":"'.$songid.'","name":"'.$song.'","sub_title":"","singer_list":["'.$singer.'"],"cover":"'.$cover.'","duration":'.$duration.',"current":0,"is_invalid":0,"can_delete":1}]&gcode='.$Group.'&qua=V1_AND_SQ_8.4.1_1442_YYB_D&uin='.$Uin.'&format=json&inCharset=utf-8&outCharset=utf-8';
            $cookie='uin=o'.$Uin.'; p_uin=o'.$Uin.'; skey='.$Skey;
            $data = json_decode(need::teacher_curl($url, [
                'cookie'=>$cookie,
                'post'=>$post
            ]) ,true);
            $retcode=$data['retcode'];
            Switch($retcode){
                case '0':
                unset($this->Msg, $this->array);
                $this->Msg = "已删除歌曲[".$song."]";
                $this->array = array('code'=>1, 'text'=>"已删除歌曲[".$song."]", 'data'=>Array('cover'=>$cover, 'music'=>$song, 'singer'=>$singer, 'time'=>$time, 'songid'=>$songid));
                $this->returns();
                return;
                break;
                case 100061:
                unset($this->Msg, $this->array);
                $this->Msg = '已开启，请不要重复';
                $this->array = array('code'=>-4, 'text'=>'已开启，请不要重复');
                $this->returns();
                break;
                case 100051:
                unset($this->Msg, $this->array);
                $this->Msg = '删除失败，权限不足';
                $this->array = array('code'=>-5, 'text'=>'删除失败，权限不足');
                $this->returns();
                break;
                case 100000:
                unset($this->Msg, $this->array);
                $this->Msg = '删除失败，Skey已过期';
                $this->array = array('code'=>-6, 'text'=>'删除失败，Skey已过期');
                $this->returns();
                break;
                case 100041:
                unset($this->Msg, $this->array);
                $this->Msg = '删除失败，权限不足';
                $this->array = array('code'=>-14, 'text'=>'删除失败，权限不足');
                $this->returns();
                break;
                default:
                unset($this->Msg, $this->array);
                $this->Msg = '删除失败，未知错误';
                $this->array = array('code'=>-7, 'text'=>'删除失败，未知错误');
                $this->returns();
                break;
            }
            return;
        }
    }
    public function returns(){
        $array = $this->array;
        $Msg = $this->Msg;
        $type = $this->info['type'];
        Switch($type){
            case 'text':
            need::send($Msg, 'text');
            break;
            default:
            need::send($array, 'json');
            break;
        }
        return;
    }
}
