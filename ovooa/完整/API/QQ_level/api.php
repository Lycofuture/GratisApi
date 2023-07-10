<?php
header('content-Type:application/json');
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(119); // 调用统计函数
addAccess();//调用统计函数*/
$Skey = @$_REQUEST['Skey'];
$Pskey = @$_REQUEST['Pskey'];
$QQ = @$_REQUEST['QQ'];
$uin = @$_REQUEST['uin'];
$Type = @$_REQUEST['type'];
if(!preg_match('/^[1-9][0-9]{5,11}$/',$uin)){
    Switch($Type){
        case 'text':
        need::send('请输入正确账号','text');
        break;
        default:
        need::send(['code'=>-1,'text'=>'请输入正确账号'],'json');
        break;
    }
}
$cookie = 'uin=o'.$QQ.'; skey='.$Skey.'; p_uin=o'.$QQ.'; p_skey='.$Pskey.'; ';
$header = [
    'Host: club.vip.qq.com',
    'Connection: keep-alive',
    'Accept: */*',
    'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045913 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0 CurrentMode/1 CurrentFontScale/1.0',
    'X-Requested-With: XMLHttpRequest',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'q-header-ctrl: 7'
];
if(need::is_Pskey($Pskey) && need::is_Skey($Skey)){
$data = json_decode(need::teacher_curl('https://club.vip.qq.com/api/vip/getQQLevelInfo?g_tk='.need::GTK($Pskey).'&requestBody=%7B%22sClientIp%22%3A%22127.0.0.1%22%2C%22sSessionKey%22%3A%22MfT8vw0UyE%22%2C%22iKeyType%22%3A1%2C%22iAppId%22%3A0%2C%22iUin%22%3A'.$uin.'%7D',[
    'cookie'=>$cookie,
    'refer'=>'https://club.vip.qq.com/card/friend?_wv=16778247&_wwv=68&_wvx=10&_proxy=1&_proxyByURL=1&platform=1&qq='.$uin.'&adtag=qun&aid=mvip.pingtai.mobileqq.androidziliaoka.fromqqqun',
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045913 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0 CurrentMode/1 CurrentFontScale/1.0'
]),true);
$data = $data['data']['mRes'];
}else{
    $cookie = need::cookie('vip.qq.com');
    $data = (need::teacher_curl('https://club.vip.qq.com/api/vip/getQQLevelInfo?g_tk='.need::GTK(need::cookie('vip.qq.com', true)).'&requestBody='.urlencode('{"sClientIp":"127.0.0.1","sSessionKey":"'.need::cookie('Skey', true).'","iKeyType":1,"iAppId":0,"iUin":"'.$uin.'"}'),[
        'cookie'=>$cookie,
        'refer'=>'https://club.vip.qq.com/card/friend?_wv=16778247&_wwv=68&_wvx=10&_proxy=1&_proxyByURL=1&qq='.$uin,
        'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0 CurrentMode/1 CurrentFontScale/1.0',
        'Header'=>$header
    ]));
    if(stristr($data, 'https://waf.tencent.com/501page.html?u=')){
        Switch($type){
            case 'text':
            need::send('被拉黑了，明天再来吧', 'text');
            break;
            default:
            need::send(array('code'=>-3, 'text'=>'被拉黑了，明天再来吧'));
            break;
        }
    }
    $data = json_decode($data, true);
    $data = $data['data']['mRes'];
        if(empty($data)){
            Switch($Type){
                case 'text':
                die('登录信息已过期');
                break;
                default:
                need::send(Array('code'=>-2,'text'=>'登录信息已过期。'),'json');
                break;
            
        }
    }
}
if(empty($data)){
            Switch($Type){
                case 'text':
                die('登录信息已过期');
                break;
                default:
                need::send(Array('code'=>-2,'text'=>'登录信息已过期。'),'json');
                break;
            
        }
}

/*
$data = $data['privilege'];//['friendLevelInfo'];
$Day_uin = $data['friendLevelInfo']['iTotalActiveDay'];//入网时长
$next_uin = $data['friendLevelInfo']['iNextLevelDay'];//下级时间
$level_uin = $data['friendLevelInfo']['iQQLevel'];//QQ等级
$name_uin = $data['friendLevelInfo']['sNickName'];//QQ昵称*/
$Time = @round((($data['iNoHideOnlineTime'] + $data['iPCQQOnlineTime']) / 60 / 60) , 2);//今日非隐身在线时长
$Day = $data['iTotalActiveDay'];//活动总天数|登记换算入网时长
$TheDays = $data['iRealDays'];//今日成长
$Next = @Intval($data['iNextLevelDay']);// / $TheDays);//计算之后的下次升级时间
$Level = $data['iQQLevel'];
$Level = $Level.'级('.@Level($Level).')';//QQ等级
$Name = $data['sNickName'];//昵称
Switch($Type){
    case 'text':
    need::send('账号：'.$uin."\n昵称：{$Name}\n等级：{$Level}\n等级换算入网时长：{$Day}天\n距离下一级还差{$Next}天(未加速)",'text');//今日成长：{$TheDays}天\n今日非隐身在线时长{$Time}小时
    break;
    default:
    $arr = [];
    $arr['uin'] = $uin;
    $arr['head'] = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$uin.'&spec=5';
    $arr['Level'] = preg_replace('/级\(.*\)/','',$Level);
    $arr['Icon'] = preg_replace('/.*?级\(|\)/','',$Level);
    $arr['Name'] = $Name;
    $arr['NetworkDay'] = $Day;
    $arr['Next'] = $Next;
    $arr['Active'] = $Time;
    $arr['TheDays'] = $TheDays;
    need::send(array('code'=>1,'text'=>'查询成功','data'=>$arr),'json');
    break;
}
function Level($Level){
    if($Level == 0){
        return '☆';
    }
    $h = Intval($Level / 64);
    $Level_h = Intval($Level - ($h * 64));
    $t = Intval($Level_h / 16);
    $Level_t = Intval($Level_h - ($t * 16));
    $y = Intval($Level_t / 4);
    $Level_y = Intval($Level_t - ($y * 4));
    $x = Intval($Level_y);
    $Level_t = Intval($Level_h - $x);
    for($i = 0 ; $i < $h ; $i++){
        $String .= '👑';
    }
    for($i = 0 ; $i < $t ; $i++){
        $String .= '☀️';
    }
    for($i = 0 ; $i < $y ; $i++){
        $String .= '🌙';
    }
    for($i = 0 ; $i < $x ; $i++){
        $String .= '⭐';
    }
    return $String;
}
function 备用(){
    return need::teacher_curl('http://d.ovooa.com/QQ_level.api?'.http_build_query(@$_REQUEST));
}