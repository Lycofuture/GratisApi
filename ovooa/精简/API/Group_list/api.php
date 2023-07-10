<?php
header('content-type:application/json;');
require '../../need.php';
require '../../curl.php';
require ("../function.php"); // 引入函数文件
addApiAccess(127); // 调用统计函数
addAccess();//调用统计函数
$type = @$_REQUEST['type'];
$Skey = @$_REQUEST['Skey'];
$Pskey = @$_REQUEST['Pskey'];
$Robot = @$_REQUEST['QQ'];
if(empty($Skey)){
    Switch($type){
        case 'text':
        need::send('请输入Skey','text');
        break;
        default:need::send(array('code'=>-1,'text'=>'请输入Skey'));
        break;
    }
}
if(empty($Pskey)){
    Switch($type){
        case 'text':
        need::send('请输入Pskey','text');
        break;
        default:need::send(array('code'=>-2,'text'=>'请输入Pskey'));
        break;
    }
}
if(!need::is_num($Robot)){
    Switch($type){
        case 'text':
        need::send('请输入正确的账号','text');
        break;
        default:need::send(array('code'=>-3,'text'=>'请输入正确的账号'));
        break;
    }
}

$url = 'https://qun.qq.com/cgi-bin/qun_mgr/get_group_list';
$data = json_decode(need::teacher_curl($url,[
    'refer'=>'https://qun.qq.com/member.html',
    'post'=>'bkn='.need::GTK($Skey),
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0',
    'cookie'=>'p_uin=o'.$Robot.'; uin=o'.$Robot.'; skey='.$Skey.'; p_skey='.$Pskey
]),true);
if(empty($data)){
    Switch($type){
        case 'text':
        need::send('未知错误！请重试！','text');
        break;
        default:
        need::send(array('code'=>-6,'text'=>'未知错误'));
        break;
    }
}
$code = $data['ec'];
if($code == 4){
    Switch($type){
        case 'text':
        need::send('Pskey已失效','text');
        break;
        default:
        need::send(array('code'=>-4,'text'=>'Pskey已失效'));
        break;
    }
}
//print_r($data);exit;
$list_1 = $data['join'];//加入的
$list_2 = $data['manage'];//管理的
$list_3 = $data['create'];//创建的
if(empty($list_1) && empty($list_2) && empty($list_3)){
    Switch($type){
        case 'text':
        need::send('群列表获取失败，可能未加入任何群','text');
        break;
        default:
        need::send(array('code'=>-5,'text'=>'群列表获取失败，可能未加入任何群'));
        break;
    }
}
$array = array();
if(!empty($list_1)){
    foreach($list_1 as $v){
        $array[] = $v;
    }
}
if(!empty($list_2)){
    foreach($list_2 as $v){
        $array[] = $v;
    }
}
if(!empty($list_3)){
    foreach($list_3 as $v){
        $array[] = $v;
    }
}
Switch($type){
    case 'text':
    for($k = 0 ; $k < (count($array)-1) ; $k++){
        echo $array[$k]['gc'];
        echo ',';
    }
    need::send(End($array)['gc'],'text');
    break;
    default:
    need::send(array('code'=>1,'text'=>'获取成功','data'=>$array));
    break;
}
//print_r($array);