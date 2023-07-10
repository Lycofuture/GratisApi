<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(70); // 调用统计函数
/* End */
require ('../../curl.php');//引入curl文件
require ('../../need.php');//引入bkn文件
$s = @$_REQUEST["s"];//获取Skey
$p = @$_REQUEST["p"];//获取pskey
$qq = @$_REQUEST["qq"];//获取key的QQ
$Group = @$_REQUEST["group"];//获取查询群号
$type = @$_REQUEST["type"];//输出方式
if(empty($s)){
    Switch($type){
        case 'text':
        need::send('请输入Skey','text');
        break;
        default:
        need::send(array("code"=>"-1","text"=>"请输入skey"));//如果Skey为空则报错并关闭
        break;
    }
}else
if(empty($p)){
Switch($type){
        case 'text':
        need::send('请输入Pskey','text');
        break;
        default:
        need::send(array("code"=>"-2","text"=>"请输入Pskey"));//
        break;
    }
}else
if(!need::is_num($qq)){
    Switch($type){
        case 'text':
        need::send('请输入QQ','text');
        break;
        default:
        need::send(array("code"=>"-3","text"=>"请输入QQ"));//
        break;
    }
}else
if(!need::is_num($Group)){
    Switch($type){
        case 'text':
        need::send('请输入群号','text');
        break;
        default:
        need::send(array("code"=>"-4","text"=>"请输入群号"));//
        break;
    }
}else{
	$bkn = need::GTK($s);//访问腾讯网站的bkn/已经封装好了
$header = [
    'Host: qun.qq.com',
    'Connection: keep-alive',
    'Content-Length: 46',
    'Accept: application/json, text/javascript, */*; q=0.01',
    'X-Requested-With: XMLHttpRequest',
    'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045913 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0 CurrentMode/1 CurrentFontScale/1.0',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Origin: https://qun.qq.com',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'Referer: https://qun.qq.com/member.html',
    'Accept-Encoding: gzip, deflate, br',
    'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
];
$post = need::teacher_curl('https://qun.qq.com/cgi-bin/qun_mgr/search_group_members',[
    'cookie'=>'uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$s.'; p_skey='.$p.'; ',
    'post'=>http_build_query([
        'gc'=>$Group,
        'st'=>'0',
        'end'=>'20',
        'sort'=>'0',
        'bkn'=>$bkn
    ]),
    'Header'=>$header,
    'refer'=>'https://qun.qq.com/member.html',
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045913 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/4G WebP/0.3.0 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0 CurrentMode/1 CurrentFontScale/1.0'
]);//用已经写好的curl方法进行post访问
$post = json_decode($post, true);//解析JSON
//print_r($post);exit;
$ren = $post["count"];//本群人数
if($ren == 0){
    Switch($type){
        case 'text':
        need::send('Pskey已过期或者账号不在群内','text');
        break;
        default:
        need::send(array("code"=>"-5","text"=>"Pskey已过期或者账号不在群内"));//如果Skey为空则报错并关闭
        break;
    }
}
if($ren != 0){
    $array = [];
    for ($a = 0 ; $a <= $post["adm_num"] ; $a++){
        if ($post["mems"][$a]["role"] == "0"){
            $role = $post["mems"][$a]["uin"];//0为群主
        }else
        if($post["mems"][$a]["role"] == "1"){
            $role = $post["mems"][$a]["uin"];//1为管理
        }
        if($post["mems"][$a]["g"] == "1"){
            $g = "女";//1为女
        }else
        if($post["mems"][$a]["g"] == "0"){
            $g = "男";//0为男
        }else{
            $g = "变态";//其它为无性人
        }
        $array[] = $role;//for循环并储存
    }
}
Switch($type){
    case 'text':
    need::send(str_replace(array('[',']'),'',JSON_encode($array,320)),'text');
    break;
    default:
    need::send(array("code"=>"1","data"=>$array));//输出JSON数组格式
    break;
}
}
