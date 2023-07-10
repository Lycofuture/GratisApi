<?php
header('Content-type:Application/json');
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(144); // 调用统计函数
require '../../need.php';
$Request = need::request();
$url = isset($Request['url']) ? $Request['url'] : null;
// echo $url;
$num = isset($Request['num']) ? $Request['num'] : 1;
if(!is_numEric($num) || $num > 10 || $num < 1){
    $num = 1;
}
$type = isset($Request['type']) ? $Request['type'] : null;
if(empty($url)){
    Switch($type){
        case 'text':
        need::send('请输入网址 or ip', 'text');
        break;
        default:
        need::send(array('code'=>-1, 'text'=>'请输入网址 or ip'));
        break;
    }
}
if(!stristr($url, 'http') && !stristr($url, '.')){
    Switch($type){
        case 'text':
        need::send('请输入网址 or ip', 'text');
        break;
        default:
        need::send(array('code'=>-1, 'text'=>'请输入网址 or ip'));
        break;
    }
}
$array = @parse_url($url);
// print_r($array);
$url = isset($array['host'])? $array['host'] : explode('/', $array['path'])[0];
preg_match('/[^\|&]+/', $url, $url);
$url = stripslashes($url[0]);
// print_r($url);
// print_r(preg_match('/([^\s]+\.){1,}/', $url));
if(!preg_match('/^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$/', $url) && !preg_match('/^[a-zA-Z]+\.[a-zA-Z]+$/', $url) && !preg_match('/([^\s]+\.){1,}/', $url)){
    Switch($type){
        case 'text':
        need::send('请输入网址 or ip', 'text');
        break;
        default:
        need::send(array('code'=>-1, 'text'=>'请输入网址 or ip'));
        break;
    }
}
$scheme = isset($array['scheme']) ? $array['scheme'] : 'http://';
if(!strstr($scheme, '://')){
    $scheme = $scheme.'://';
}
//print_r($array);exit;
// echo $url;
exec('ping -c '.$num.' '.$url.' 2>&1', $info);
preg_match("/\((.*?)\)/",$info[0],$str);//获取的ip
// print_r($info);
$ip = $str[1];
$data = json_decode(need::teacher_curl('http://ovooa.com/API/IPdz/api.php?IP='.$ip), true);//json_decode(file_get_contents("http://opendata.baidu.com/api.php?query=".$ip."&co=&resource_id=6006&t=1433920989928&ie=utf8&oe=utf-8&format=json"),true);
$address = isset($data['text']) ? $data['text'] : '未知';//$se["data"][0]["location"];
preg_match("/(.*?)packetstransmitted,(.*?)received,(.*?)packetloss,time(.*?)ms/",kth($info[(count($info)-2)]),$str);//获取丢包率，总耗时
preg_match('/time=(.*)/', $info[1], $t);
$num =$str[1];//发送数据包数
$receive =$str[2];//接受数据包数
$abandon =$str[3];//丢包率
$Times = $t[1] ?: '超时';//$str[4];//总耗时
preg_match("/=[\s]*(.*?)\/(.*?)\/(.*?)\/(.*?)ms/",kth(end($info)),$str);//获取丢包率，总耗时
// rtt min/avg/max/mdev = 166.623/166.623/166.623/0.000 ms
$small = @$str[1]?:"超时";//最小延迟
$average = @$str[2]?:"超时";//平均延迟
$max = @$str[3]?:"超时";//最大延迟
if (empty(kth($ip))){
    Switch($type){
        case 'text':
        need::send((isset($info[0]) ? '发生了错误：'.$info[0] : '请输入网址 or ip'), 'text');
        break;
        default:
        need::send(array('code'=>-1, 'text'=>(isset($info[0]) ? '发生了错误：'.$info[0] : '请输入网址 or ip')));
        break;
    }
}else{
    Switch($type){
        case 'text':
        echo '网址：'.$scheme.$url;
        echo "\n";
        echo 'IP地址：'.$ip;
        echo "\n";
        echo '地址：'.$address;
        echo "\n";
        echo '最小延迟：'.$small;
        echo "\n";
        echo '最大延迟：'.$max;
        echo "\n";
        echo '平均延迟：'.$average;
        echo "\n";
        echo '发送数据包：'.$num;
        echo "\n";
        echo '接收数据包：'.$receive;
        echo "\n";
        echo '丢包率：'.$abandon;
        echo "\n";
        echo '总耗时：'.$Times;
        exit;
        break;
        default:
        need::send(array('code'=>1, 'text'=>'获取成功', 'data'=>array('url'=>$scheme.$url,'IP'=>$ip, 'address'=>$address, 'small'=>$small, 'max'=>$max, 'average'=>$average, 'num'=>$num, 'receive'=>$receive, 'abandon'=>$abandon, 'Times'=>$Times)));
        break;
    }
}
function kth($str){
    $search = array(" ","　","\n","\r","\t");
    $replace = array("","","","","");
    return str_replace($search, '', $str);
}