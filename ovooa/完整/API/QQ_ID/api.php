<?php
header('content-type:application/json');
require '../../curl.php';
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(130); // 调用统计函数
addAccess();//调用统计函数
$QQ = @$_REQUEST['QQ']?:need::cookie('Robot', true);
$Skey = @$_REQUEST['Skey']?:need::cookie('Skey', true);
$uin = @$_REQUEST['uin'];
$Pskey = @$_REQUEST['Pskey']?:need::cookie('vip.qq.com', true);
$type = @$_REQUEST['type'];
if(empty($QQ) || empty($Skey) || empty($uin) || empty($Pskey) ){
    Switch($type){
        case 'text':
        need::send('缺少参数','text');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'缺少参数'));
        break;
    }
}

if(!need::is_num($QQ) || !need::is_num($uin)){
    Switch($type){
        case 'text':
        need::send('请输入正确的账号','text');
        break;
        default:
        need::send(array('code'=>-2,'text'=>'请输入正确的参数'));
        break;
    }
}
$url = 'https://club.vip.qq.com/api/trpc/qid_server/GetQid';
$bkn = need::GTK($Pskey);
$date = need::teacher_curl($url,[
    'post'=>'g_tk='.$bkn.'&uin='.$uin,
    'cookie'=>'uin='.$QQ.'; skey='.$Skey.'; p_skey='.$Pskey.'; ',
    'refer'=>'https://club.vip.qq.com/'
]);
$data = json_decode($date,true);
//    print_r($data);exit;
if($data['code']=='0'){
    $array = array(
        'code'=>1,
        'text'=>'获取成功',
        'data'=>array(
        'QID'=>$data['data']['qid']?:'对方没有设置QID'
        )
    );
}else
if(preg_match('/redirecting to/i',$date) || empty($data)){
    $url = 'https://club.vip.qq.com/api/trpc/qid_server/GetQid';
    $bkn = need::GTK(need::cookie('vip.qq.com',true));
    $date = need::teacher_curl($url,[
        'post'=>'g_tk='.$bkn.'&uin='.$uin,
        'cookie'=>need::cookie('vip.qq.com'),
        'refer'=>'https://club.vip.qq.com/'
    ]);
    //print_r($date);exit;
    $data = json_decode($date,true);
    //print_r($data);exit;
    if(preg_match('/redirecting to/i',$date) || empty($data)){
        $array = array(
            'code'=>-3,
            'text'=>'获取失败,Error->Cookie失效'
        );
    }else{
        $array = array(
            'code'=>1,
            'text'=>'获取成功',
            'data'=>array(
            'QID'=>$data['data']['qid']?:'对方没有设置QID'
            )
        );
    }
}else{
    $array = array(
        'code'=>-3,
        'text'=>'获取失败,Error->'.$date
    );
}
    Switch($type){
        case 'text':
        $text = $array['data']['QID']?:($array['data']['qid'] || $array['text']);
        need::send($text,'text');
        break;
        default:
        need::send($array);
        break;
    }


