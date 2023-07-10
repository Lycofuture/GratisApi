<?php
header('content-type:application/json');
require '../../need.php';
require '../../curl.php';
require ("../function.php"); // 引入函数文件
addApiAccess(118); // 调用统计函数
addAccess();//调用统计函数
$msg = $_REQUEST['msg'];
$type = $_REQUEST['type'];
if(empty($msg)){
    Switch($type){
        case 'text':
        need::send('请输入所需要的值','text');
        break;
        default:
        need::send(Array('code'=>-1,'text'=>'请输入所需要的值'),'json');
        break;
    }
}
$Msg = str_replace('@','',$msg);
if(mb_strlen($Msg) < 10 && mb_strlen($Msg) < 44){
    Switch($type){
        case 'text':
        need::send('请输入正确的Key值','text');
        break;
        default:
        need::send(Array('code'=>-2,'text'=>'请输入正确的Key值'),'json');
        break;
    }
}

$BKN = need::GTK($msg);
Switch($type){
    case 'text':
    need::send($BKN,'text');
    break;
    default:
    need::send(Array('code'=>1,'text'=>$BKN),'json');
    break;
}
