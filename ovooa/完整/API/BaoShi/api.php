<?php
header('content-Type:Application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(38); // 调用统计函数
require "../../need.php";//引入封装好的函数
/* End */
$msg = @$_REQUEST["msg"]?:date('H');
$n = @$_REQUEST["n"]?:'mp3';
$Type = @$_REQUEST['type']?:@$_REQUEST['Type'];
$host = @$_SERVER['HTTP_HOST'];
/*
if ($msg==""||$n==""){

exit(need::json(array("code"=>"-1","text"=>"请填写参数msg与选择n！")));

}
*/

$path = './'.$n.'/'.$msg.'.'.$n;

Switch($n){
    case 'amr':
    Switch($Type){
        case 'text':
        send ('http://'.$host.'/API/BaoShi/amr/'.$msg.'.amr','text');
        break;
        default:
        need::send(array('code'=>1,'text'=>'http://'.$host.'/API/BaoShi/amr/'.$msg.'.amr'));
        break;
    }
    break;
    case 'mp3':
    Switch($Type){
        case 'text':
        need::send('http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3','text');
        break;
        default:
        need::send(array('code'=>1,'text'=>'http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3'));
        break;
    }
    break;
    case 'xml':
    Switch($Type){
        case 'text':
        send ('card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[报时]整点报时" sourceMsgId="0" url="http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="http://'.$host.'/API/BaoShi/7.jpg" src="http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3" /><title>整点报时</title><summary>报时'.date('G').':'.date('i').'</summary></item><source name="整点报时" icon="http://'.$host.'/API/BaoShi/7.jpg" action="app" appid="0" /></msg>','text');
        break;
        default:
        need::send('card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="2" templateID="1" action="web" brief="[报时]整点报时" sourceMsgId="0" url="http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3" flag="0" adverSign="0" multiMsgFlag="0"><item layout="2"><audio cover="http://'.$host.'/API/BaoShi/7.jpg" src="http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3" /><title>整点报时</title><summary>报时'.date('G').':'.date('i').'</summary></item><source name="整点报时" icon="http://'.$host.'/API/BaoShi/7.jpg" action="app" appid="0" /></msg>','text');
        break;
    }
    break;
    case 'audio':
    if(file_exists('./mp3/'.$msg.'.mp3')){
        $data = file_get_contents('./mp3/'.$msg.'.mp3');
        header('content-type: audio');
        echo $data;
    }else{
        Switch($Type){
            case 'text':
            send ('http://'.$host.'/API/BaoShi/amr/'.$msg.'.amr','text');
            break;
            default:
            need::send(array('code'=>1,'text'=>'获取成功','data'=>array('mp3'=>'http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3','amr'=>'http://'.$host.'/API/BaoShi/amr/'.$msg.'.amr')));
            break;
        }
    }
    break;
    default:
    Switch($Type){
        case 'text':
        send ('http://'.$host.'/API/BaoShi/amr/'.$msg.'.amr','text');
        break;
        default:
        need::send(array('code'=>1,'text'=>'获取成功','data'=>array('mp3'=>'http://'.$host.'/API/BaoShi/mp3/'.$msg.'.mp3','amr'=>'http://'.$host.'/API/BaoShi/amr/'.$msg.'.amr')));
        break;
    }
    break;
}


?>