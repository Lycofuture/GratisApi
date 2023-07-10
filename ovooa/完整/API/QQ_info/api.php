<?php
header('content-Type:application/json');
require '../../need.php';
require '../../curl.php';
require ("../function.php"); // 引入函数文件
addApiAccess(118); // 调用统计函数
addAccess();//调用统计函数*/
$Skey = @$_REQUEST['Skey']?:need::Robot('../../','Skey');
$Pskey = @$_REQUEST['Pskey']?:need::Robot('../../','vip.qq.com');
$QQ = @$_REQUEST['QQ']?:need::Robot('../../','Robot');
$uin = @$_REQUEST['uin'];
$Type = @$_REQUEST['type'];
if(!need::is_num($uin)){
    Switch($Type){
        case 'text':
        need::send('请输入正确账号','text');
        break;
        default:
        need::send(['code'=>-1,'text'=>'请输入正确账号'],'json');
        break;
    }
}/*
if(!preg_match('/[1-9][0-9]{5,10}/',$QQ) and $Skey){
    Switch($Type){
        case 'text':
        need::send('请输入正确账号','text');
        break;
        default:
        need::send(['code'=>-3,'text'=>'请输入正确账号'],'json');
        break;
    }
}*/
$cookie = 'uin=o'.$QQ.'; skey='.$Skey.'; p_uin=o'.$QQ.'; p_skey='.$Pskey.'; ';
$data = need::teacher_curl('https://club.vip.qq.com/guestprivilege?friend='.$uin,[
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0',
    'cookie'=>$cookie,
    'refer'=>'https://club.vip.qq.com/guestprivilege?friend='.$uin
]);
preg_match('/window\.__INITIAL_STATE__=([\s\S]*?);\(function/',$data,$data);
$data = json_decode($data[1],true);
if(@$_REQUEST['format'] == 1){
    print_r($data);//imgcache.gtimg.cn/vipstyle/mobile/client/privilege/img/prv-small/lv{level}_luzgz.png
}
if(!($data)){
    $data = need::teacher_curl('https://club.vip.qq.com/guestprivilege?friend='.$uin,[
        'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0',
        'cookie'=>need::cookie('vip.qq.com'),
        'refer'=>'https://club.vip.qq.com/guestprivilege?friend='.$uin
    ]);
    preg_match('/window\.__INITIAL_STATE__=([\s\S]*?);\(function/',$data,$data);
    $data = json_decode($data[1],true);
    if(!($data)){
        Switch($Type){
            case 'text':
            //echo 1;
            die('登录信息已过期');
            break;
            default:
            need::send(Array('code'=>-2,'text'=>'登录信息已过期'),'json');
            break;
        }
    }
}
$data = $data['privilege'];
$data_uin = $data['guestPrivileges'];
if(!($data_uin)){
    Switch($Type){
        case 'text':
        die('对方并未开通任何业务');
        break;
        default:
        need::send(Array('code'=>1,'text'=>'对方并未开通任何业务'),'json');
        break;
    }
}
Switch($Type){
    case 'text':
    echo '≮——业务查询——≯';
    echo "\n";
    foreach($data_uin as $k=>$v){
        $level = $data_uin[$k]['level'];//等级
        $name = $data_uin[$k]['name'];//名字
        $Ti = $data_uin[$k]['iExpireTime'];//到期时间
        if($Ti){
            $Time = "\n".'到期时间：'.date('Y-m-d',$Ti);
        }
        echo ($k + 1).'.';
        echo '业务名称：'.$name;
//        echo "\n";
        if($level > 0){
            echo "\n";
            echo '业务等级：'.$level;
        }
        echo $Time;
        echo "\n";
    }
    echo '≮——业务查询——≯';
    echo "\n";
    echo '共开通了'.count($data_uin).'项业务';
    exit();
    break;
    default:
    foreach($data_uin as $k=>$v){
    	// print_r($v);exit;
        $level = $data_uin[$k]['level'];//等级
        $name = $data_uin[$k]['name'];//名字
        $Time = $data_uin[$k]['iExpireTime'];//到期时间
        $Icon = $data_uin[$k]['levelIcon']?:$data_uin[$k]['smallIcon'];//业务图标
        if(!strstr($Icon,'http')){
            $Icon = 'http:'.$Icon;
        }
        $array[] = array('name'=>$name,'level'=>$level,'Icon'=>$Icon,'Time'=>($Time ? date('Y-m-d',$Time) : '查询失败'));
    }
    need::send(array('code'=>1,'text'=>'查询成功','data'=>$array),'json');
    break;
}
