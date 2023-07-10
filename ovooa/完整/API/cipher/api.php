<?php
require '../../need.php';
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(145); // 调用统计函数
$request = need::request();
$Msg = @$request['Msg']?:@$request['msg'];
$type = @$request['type'];
if(empty(need::nate($Msg))){
    Switch($type){
        case 'text':
        need::send('请输入需要加密的文本', 'text');
        break;
        default:
        need::send(Array('code'=>-1, 'text'=>'请输入需要加密的文本'));
        break;
    }
}
$key = @$request['key']?:'ovooa';
if(strlen($key) > 64){
    Switch($type){
        case 'text':
        need::send('key过长，请尝试减短', 'text');
        break;
        default:
        need::send(Array('code'=>-1, 'text'=>'key过长，请尝试减短'));
        break;
    }
}
$code = @$request['code']?:'encode';
if($code === 'encode'){
    $code = 'E';
}else{
    $code = 'D';
}
$String = need::encrypt($Msg, $code, $key);
Switch($type){
    case 'text':
    need::send($String, 'text');
    break;
    default:
    need::send(Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('String'=>$Msg, 'Ciphertext'=>$String, 'key'=>$key)), 'json');
    break;
}