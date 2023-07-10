<?php
header('Content-Type:application/json');
require '../../need.php';
require '../../curl.php';
require ("../function.php"); // 引入函数文件
addApiAccess(120); // 调用统计函数
addAccess();//调用统计函数
$Skey = @$_REQUEST['Skey'];
$Pskey = @$_REQUEST['Pskey'];
$Group = @$_REQUEST['Group'];
$QQ = @$_REQUEST['QQ'];
$type = @$_REQUEST['type'];
$Gtk = need::GTK($Skey);
if(!need::is_num($QQ)){
    Switch($type){
        case 'text':
        need::send('请输入正确的账号','text');
        break;
        default:
        need::send(Array(
            'code'=>-1,
            'text'=>'请输入正确的账号'
        ));
        break;
    }
}
if(!need::is_num($Group)){
    Switch($type){
        case 'text':
        need::send('请输入正确的群号','text');
        break;
        default:
        need::send(Array(
            'code'=>-2,
            'text'=>'请输入正确的群号'
        ));
        break;
    }
}
if(!($Skey)){
    Switch($type){
        case 'text':
        need::send('请输入Skey','text');
        break;
        default:
        need::send(Array(
            'code'=>-3,
            'text'=>'请输入Skey'
        ));
        break;
    }
}
if(!($Pskey)){
    Switch($type){
        case 'text':
        need::send('请输入Pskey','text');
        break;
        default:
        need::send(Array(
            'code'=>-4,
            'text'=>'请输入Pskey'
        ));
        break;
    }
}
$data = json_decode(need::teacher_curl('https://qun.qq.com/v2/luckyword/proxy/domain/qun.qq.com/cgi-bin/group_lucky_word/draw_lottery?bkn='.$Gtk,[
    'refer'=>'https://qun.qq.com/v2/luckyword/index?qunid='.$Group.'&_wv=67108865&_nav_txtclr=FFFFFF&_wvSb=0&source=enter',
    'Header'=>[
        'qname-service: 976321:131072',
        'qname-space: Production',
        'Content-Type: application/json;charset=UTF-8',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
        'Accept: application/json, text/plain, */*'
    ],
    'cookie'=>'p_skey='.$Pskey.'; qq_locale_id=2052; p_uin=o'.$QQ.'; uin=o'.$QQ.'; skey='.$Skey,
    'post'=>json_encode(['group_code'=>$Group]),
]),true);

$code = $data['retcode'];
$Msg = $data['msg'];
if($code == 0){
    $data = $data['data'];
    if(!($data)){
        Switch($type){
            case 'text':
            die('恭喜您抽中了空气！');
            break;
            default:
            need::send(Array('code'=>1,'text'=>'恭喜您抽中了空气'));
            break;
        }
    }
    
    $data = $data['word_info']['word_info'];
    $name = $data['wording'];
    $desc = $data['word_desc'];
    Switch($type){
        case 'text':
        echo '恭喜您！抽到了'.$name;
        echo "\n";
        echo '寓意是：'.$desc;
        exit();
        break;
        default:
        need::send(Array(
            'code'=>1,
            'text'=>'获取成功',
            'data'=>Array(
                'Character'=>$name,
                'Desc'=>$desc
            )));
        break;
    }
}else
if($code == 11004){
    Switch($type){
        case 'text':
        need::send('今日抽字符已达上限！','text');
        break;
        default:
        need::send(Array(
            'code'=>-5,
            'text'=>'今日字符抽取次数已上限'
        ));
        break;
    }
}else
if($code == 41){
    Switch($type){
        case 'text':
        need::send('坏掉的Pskey哦','text');
        break;
        default:
        need::send(Array(
            'code'=>-6,
            'text'=>'Pskey已失效'
        ));
    }
}else
if($code == 10005){
    Switch($type){
        case 'text':
        need::send('群信息获取错误','text');
        break;
        default:
        need::send(Array(
            'code'=>-7,
            'text'=>'群信息获取错误'
        ));
    }
}else{
    Switch($type){
        case 'text':
        echo '未知错误=>';
        echo $Msg;
        exit();
        break;
        default:
        need::send(Array(
            'code'=>-8,
            'text'=>$Msg
        ));
    }
//if(
}

//print_r($data.http_build_query(array('group_code'=>$Group)));