<?php
header('Content-type:Application/json');
require '../curl.php';
require '../need.php';
include "qqbot.class.php";
$Token = $_REQUEST['Token'];
$type = $_REQUEST['type'];
$format = $_REQUEST['format'];
if ($Token == '123') {
    $Robot = $_REQUEST['Robot']?:10001;
    $password = $_REQUEST['password'];//登录密码
    $cookie = new cookie($Robot, $password);
    if(!$format){
        echo $cookie->cookies($type);
    }else{
        $a = $cookie->cookies($type);
        echo $cookie->Pskey;
    }
}

class cookie{
    protected $Robots = [10001, 10002];
    protected $Robot;
    public function __construct($Robot, $password = null){
        $this->Robot = $Robot;
        if($password){
            $this->put(__DIR__.'./cache/'.$Robot.'/password.txt', $password);
        }
        $Robots = $this->Robots;
        foreach($Robots as $v){
            if(!is_dir(__DIR__.'./cache/'.$v)){
                mkdir(__DIR__.'./cache/'.$v, 0776, true);
            }
        }
    }
    public function cookies($type){
        $MQ_Api = new qqbotTopSdk;
        $Robot = $this->Robot;
        if(empty($MQ_Api->Api_QQBOT('Api_GetQQVIPPsKey', [$Robot])['data']['ret'])){
            $this->Login($Robot,$type);
            //return;
        }
        Switch($type){
            case 'vip.qq.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetQQVIPPsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            $data = teacher_curl('https://club.vip.qq.com/guestprivilege?friend=2354452553',[
                'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0',
                'cookie'=>$cookie,
                'refer'=>'https://club.vip.qq.com/guestprivilege?friend=2354452553'
            ]);
            preg_match('/window\.__INITIAL_STATE__=([\s\S]*?);\(function/',$data,$data);
            $data = json_decode($data[1],true);
            if(empty($data)){
                $this->Login($Robot,$type);
                return;
            }
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            break;
            case 'qzone.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetZonePsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            case 'blog.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetBlogPsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            break;
            case 'info.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetQQInfoPsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            break;
            case 'room.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetClassRoomPsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            break;
            case 'jubao.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetJuBaoPsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            break;
            case 'tenpay.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetTenPayPsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            $data = JSON_decode(teacher_curl('https://myun.tenpay.com/cgi-bin/clientv1.0/qwallet_account_list.cgi?limit=1&uin='.$Robot,[
                'cookie'=>$cookie
            ]),true);
            $Code = $data['retcode'];
            if($Code == 66210007){
                $this->Login($Robot,$type);
                return;
            }
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            break;
            case 'qun.qq.com':
            $Pskey = str_replace(array('p_uin=o'.$Robot.'; p_skey=',';',' '),'',$MQ_Api->Api_QQBOT('Api_GetGroupPsKey', [$Robot])['data']['ret']);
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            $url = 'https://qun.qq.com/cgi-bin/qun_mgr/get_group_list';
            $data = json_decode(teacher_curl($url,[
                'refer'=>'https://qun.qq.com/member.html',
                    'post'=>'bkn='.getGTK($this->cookies('Skey')),
                    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0',
                    'cookie'=>$cookie
            ]),true);
            if(empty($data)){
                $this->Login($Robot,$type);
                return;
            }
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
            break;
            case 'Clientkey':
            $Pskey = $MQ_Api->Api_QQBOT('Api_GetClientkey', [$Robot])['data']['ret'];
            $cookie = $Pskey;
            break;
            case 'y.qq.com':
            if(is_file(__DIR__.'./cache/'.$Robot.'/y.qq.com.txt')){
                $file = $this->put(__DIR__.'./cache/'.$Robot.'/y.qq.com.txt','',false);
                $Data = teacher_curl('https://i.y.qq.com/v8/playsong.html?platform=11&appshare=android_qq&appversion=10170509&hosteuin=owok7evkow4koz**&songmid=001zMQr71F1Qo8&type=0&appsongtype=1&_wv=1&source=qq&ADTAG=qfshare',[
                    'cookie'=>$file,
                    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.120 MQQBrowser/6.2 TBS/045714 Mobile Safari/537.36 V1_AND_SQ_8.3.9_340_TIM_D QQ/3.4.0.3018 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/0 QQTheme/1015712',
                    'refer'=>'https://i.y.qq.com/',
                    'Header'=>[
                    'Host: i.y.qq.com',
			        'Connection: keep-alive',
			        'Upgrade-Insecure-Requests: 1',
			        'Sec-Fetch-Mode: navigate',
			        'Sec-Fetch-User: ?1',
			        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
			        'X-Requested-With: com.tencent.tim',
			        'Sec-Fetch-Site: none',
			        'Accept-Encoding: gzip, deflate, br',
			        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
                    ]
                ]);
			    preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$Data,$data_music);
			    /*preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$data,$data_music);
		        $data_music = json_decode($data_music[1], true);
		        $songurl = $data_music['songList'][0]['url'];
		        $pay = $data_music['songList'][0]['pay']['price_album'];*/
			    //print_r($Data);exit;
			    $data_music = json_decode($data_music[1],true);
			    //print_r($data_music);exit;
			    $songurl = $data_music['songList'][0]['url'];
			    if($songurl){
			        $cookie = $file;
			        preg_match('/p_skey=(.*?);/',$file,$files);
			        $Pskey = $files[1];
			        break;
			    }else{
			        @unlink(__DIR__.'./cache/'.$Robot.'/y.qq.com.txt');
			        $this->cookies($type);
			    }
            }else{
                $Clientkey = $MQ_Api->Api_QQBOT('Api_GetClientkey', [$Robot])['data']['ret'];
                $URL = 'https://ssl.ptlogin2.qq.com/jump?ptlang=2052&clientuin='.$Robot.'&clientkey='.$Clientkey.'&u1=https://y.qq.com/n/ryqq/profile/like/song&code='.$Clientkey.'&state=state';
                $data = Json_encode(teacher_curl(teacher_curl($URL,['loadurl'=>1]),['GetCookie'=>1])['Cookie'][0],320);
                //print_r($data);
                preg_match('/p_skey=(.*?);/',$data,$date);
                $Pskey = $date[1];
                preg_match('/pt4_token=(.*?);/',$data,$date);
                $p4 = 'pt4_token='.$date[1].';';
                $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; pt4_token='.$pt4.'; ';
                $put = $this->put(__DIR__.'./cache/'.$Robot.'/y.qq.com.txt',$cookie);
                break;
            }
            break;
            case 'id.qq.com':
            $Clientkey = $MQ_Api->Api_QQBOT('Api_GetClientkey', [$Robot])['data']['ret'];
            $URL = 'https://ssl.ptlogin2.qq.com/jump?ptlang=2052&clientuin='.$Robot.'&clientkey='.$Clientkey.'&u1=https%3A%2F%2Fid.qq.com%2Findex.html';
            $data = Json_encode(teacher_curl(teacher_curl($URL,['loadurl'=>1]),['GetCookie'=>1])['Cookie'][0],320);
            //print_r($data);
            preg_match('/p_skey=(.*?);/',$data,$date);
            $Pskey = $date[1];
            preg_match('/pt4_token=(.*?);/',$data,$date);
            $p4 = 'pt4_token='.$date[1].';';
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; pt4_token='.$pt4.'; ';
            break;
            case 'ti.qq.com':
            if(is_file(__DIR__.'./cache/'.$Robot.'/ti.qq.com.txt')){
                $file = $this->put('ti.qq.com','',false);
                $Data = json_decode(teacher_curl('https://oidb.tim.qq.com/v3/oidbinterface/oidb_0xc2d_0?sdkappid=3087&actype=2',[
                    'cookie'=>$file,
                    'refer'=>'https://ti.qq.com/new-user-guide/index.html'
                ]),true);
                if($data['ErrorCode'] == 0){
                    preg_match('/p_skey=(.*?);/',$file,$date);
                    $Pskey = $date[1];
                    $cookie = $file;
                    break;
                }else{
                    unlink('ti.qq.com');
			        $this->cookies($type);
                }
            }else{
                $Clientkey = $MQ_Api->Api_QQBOT('Api_GetClientkey', [$Robot])['data']['ret'];
                $URL = 'https://ssl.ptlogin2.qq.com/jump?ptlang=2052&clientuin='.$Robot.'&clientkey='.$Clientkey.'&u1=https://ti.qq.com/new-user-guide/index.html';
                $data = Json_encode(teacher_curl(teacher_curl($URL,['loadurl'=>1]),['GetCookie'=>1])['Cookie'][0],320);
                //print_r($data);
                preg_match('/p_skey=(.*?);/',$data,$date);
                $Pskey = $date[1];
                preg_match('/pt4_token=(.*?);/',$data,$date);
                $p4 = 'pt4_token='.$date[1].';';
                $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; pt4_token='.$pt4.'; ';
                $this->put(__DIR__.'./cache/'.$Robot.'/ti.qq.com.txt', $cookie);
                break;
            }
            break;
            case 'Robot':
            $Pskey = $Robot;
            break;
            case 'game.qq.com':
            $Clientkey = $MQ_Api->Api_QQBOT('Api_GetClientkey', [$Robot])['data']['ret'];
            $URL = 'https://ssl.ptlogin2.qq.com/jump?ptlang=2052&clientuin='.$Robot.'&clientkey='.$Clientkey.'&u1=https://game.qq.com/m/m201910/index.html';
            $data = Json_encode(teacher_curl(teacher_curl($URL,['loadurl'=>1]),['GetCookie'=>1])['Cookie'][0],320);
            //print_r($data);
            preg_match('/p_skey=(.*?);/',$data,$date);
            $Pskey = $date[1];
            preg_match('/pt4_token=(.*?);/',$data,$date);
            $p4 = 'pt4_token='.$date[1].';';
            $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; pt4_token='.$pt4.'; ';
            break;
            case 'Skey':
            $Pskey = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'];
            $data = JSON_decode(teacher_curl('https://admin.qun.qq.com/cgi-bin/qun_admin/get_join_link',[
                'post'=>[
                    'gc'=>820323177,
                    'type'=>'1',
                    'bkn'=>getGTK($Pskey)
                ],
                'cookie'=>$Pskey
             ]),true);
             $Code = $JSON["ec"];//状态码
             if($Code == 4){
                 $this->Login($Robot,$type);
                 return;
             }
             preg_match('/skey=(.*)/',$MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'],$Skey);
             $Pskey = $Skey[1];
             $cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'];
             break;
        }
        if(empty($Pskey)){
            $this->Pskey = 'null';
            return 'null';
        }
        //$cookie = $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'].'; p_uin=o'.$Robot.'; p_skey='.$Pskey.'; ';
        $this->Pskey = $Pskey;
        return $cookie;
    }
    public function Login($Robot,$type){
        $Time = $_SERVER['REQUEST_TIME'];
        $file = @$this->put(__DIR__.'./cache.txt','',false);
        $password = $this->put(__DIR__.'./cache/'.$Robot.'/password.txt', '', false);
        if(empty($file)){
            $MQ_Api = new qqbotTopSdk;
            $a = $MQ_Api->Api_QQBOT('Api_AddQQ', [$Robot,$password]);
            if($a['data']['ret']=='真'){
                $a = $MQ_Api->Api_QQBOT('Api_Login', [$Robot,$password]);
                $Time = ($_SERVER['REQUEST_TIME'] + 20);
                $this->put(__DIR__.'./cache.txt',$Time);
                $this->Login($Robot,$type);
                return;
            }else{
                return;
            }
        }else
        if($file <= $Time && !empty($Time)){
        	@unlink(__DIR__.'./cache.txt');
            $this->cookies($type);
            return;
        }else{
            $Time = ($file - $Time);
            return;
            sleep($Time);
            @unlink(__DIR__.'./cache.txt');
            $this->cookies($type);
            return;
        }
    }
    public function put($file, $string = null, $bool = true){
        if($bool == true){
            file_put_Contents($file, $string);
            return true;
        }else{
            if(!is_file($file)){
                return false;
            }else{
                $string = @file_Get_Contents($file);
                return $string;
            }
        }
    }
}