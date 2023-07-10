<?php
header('content-type:application/json');
require '../../curl.php';
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(122); // 调用统计函数
addAccess();//调用统计函数
$num = @$_REQUEST['num']?:1;
$type = @$_REQUEST['type'];
$from = @$_REQUEST['from'];
$T = @$_REQUEST['Time'];
$go = @$_REQUEST['go'];
$to = @$_REQUEST['to'];
$data = json_decode(need::teacher_curl('https://bangumi.bilibili.com/web_api/timeline_cn?'),true);
// print_r($data);exit;
/*
echo "\n";
echo date('n-d');
exit;*/
$code = $data['code'];//状态码
$message = $data['message'];
//echo $code.$message;exit;
if($code == 0 && $message = 'success' && $data['result']){
// print_r($data);exit;
    foreach($data['result'] as $k=>$v){
        if(isset($data['result'][$k]['date']) && $data['result'][$k]['date'] == date('n-j')){
            $data = $data['result'][$k];
        }
    }
//    print_r(date('n-d'));exit;
    $Time = @$data['date'];
//    $week = $data['day_of_week'];
    $data = @$data['seasons'] ?: [];
    for($k = 0 ; $k < Count($data) ; $k++){
        $cover = $data[$k]['cover'];//封面
        $Update = $data[$k]['pub_index'];//更新至
        $Time = $data[$k]['pub_time'];//更新时间
        $id = $data[$k]['season_id'];//番剧id
        $Name = $data[$k]['title'];//番剧名字
        $status = $data[$k]['season_status'];//番剧总集
        $array[] = array('Name'=>$Name,'Picture'=>$cover,'Update'=>$Update,'Time'=>$Time,'Url'=>'https://www.bilibili.com/bangumi/play/ss'.$id);
    }
//    print_r($array);exit;
}else{
    Switch($type){
        case 'text':
        die('获取失败！请重试');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'获取失败请重试'));
        break;
    }
}
if($from == 1){
    Switch($type){
        case 'text':
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
            echo $go;
            echo $Picture;
            echo $to;
            echo "\n番名：";
            echo $Name;
            echo "\n";
            echo '更新至';
            echo $Update;
            echo "\n";
            echo '更新时间：';
            echo $Time;
            echo "\n";
            echo '播放链接：';
            echo $Url;
            echo "\n\n";
        }
        echo '好耶~今日已更新'.Count($array).'部番剧！';
        exit();
        break;
        default:
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
            $json[] = array('Name'=>$Name,'Picture'=>$Picture,'Update'=>$Update,'Time'=>$Time,'Url'=>$Url);
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>$json));
        break;
    }
}
/* ↑国漫 ↓日漫 */
$data = json_decode(need::teacher_curl('https://bangumi.bilibili.com/web_api/timeline_global?'),true);
$code = $data['code'];//状态码
$message = $data['message'];
if($code == 0 && $message = 'success'){
    foreach($data['result'] as $k=>$v){
        if($data['result'][$k]['date'] == date('n-d')){
            $data = $data['result'][$k];
        }
    }
    $Time = @$data['date'];
//    $week = $data['day_of_week'];
    $data = @$data['seasons'] ?: [];
    // print_r($data);exit;
    for($k = 0 ; $k < @Count($data) ; $k++){
        $cover = $data[$k]['cover'];//封面
        $Update = $data[$k]['pub_index'];//更新至
        $Time = $data[$k]['pub_time'];//更新时间
        $id = $data[$k]['season_id'];//番剧id
        $Name = $data[$k]['title'];//番剧名字
        $status = $data[$k]['season_status'];//番剧总集
        $array[] = array('Name'=>$Name,'Picture'=>$cover,'Update'=>$Update,'Time'=>$Time,'Url'=>'https://www.bilibili.com/bangumi/play/ss'.$id);
    }
}else{
    Switch($type){
        case 'text':
        die('获取失败！请重试');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'获取失败请重试'));
        break;
    }
}
if(empty($array)){
    Switch($type){
        case 'text':
        die('获取失败！请重试');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'获取失败请重试'));
        break;
    }
}

//print_r($array);exit;

if($from == 2){
    Switch($type){
        case 'text':
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
            echo $go;
            echo $Picture;
            echo $to;
            echo "\n番名：";
            echo $Name;
            echo "\n";
            echo '更新至';
            echo $Update;
            echo "\n";
            echo '更新时间：';
            echo $Time;
            echo "\n";
            echo '播放链接：';
            echo $Url;
            echo "\n\n";
        }
        echo '好耶~今日已更新'.Count($array).'部番剧！';
        exit();
        break;
        default:
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
            $json[] = array('Name'=>$Name,'Picture'=>$Picture,'Update'=>$Update,'Time'=>$Time,'Url'=>$Url);
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>$json));
        break;
    }
}else
/* 所有 ↓ */
if($from == 3){
    array_multisort(array_column($array, 'Time'), SORT_DESC, $array);
    Switch($type){
        case 'text':
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
            echo $go;
            echo $Picture;
            echo $to;
            echo "\n番名：";
            echo $Name;
            echo "\n";
            echo '更新至';
            echo $Update;
            echo "\n";
            echo '更新时间：';
            echo $Time;
            echo "\n";
            echo '播放链接：';
            echo $Url;
            echo "\n\n";
        }
        echo '好耶~今日已更新'.Count($array).'部番剧！';
        exit();
        break;
        default:
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
            $json[] = array('Name'=>$Name,'Picture'=>$Picture,'Update'=>$Update,'Time'=>$Time,'Url'=>$Url);
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>$json));
        break;
    }
}else
/* 按时间 ↓ */
if($from == 4 && $T){
    array_multisort(array_column($array, 'Time'), SORT_DESC, $array);
    //print_r($array);exit();
    if(!empty($array)){
        Switch($type){
            case 'text':
            $i = 0;
            for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
                if($array[$k]['Time'] == $T){
                    $i++;
                    $Name = $array[$k]['Name'];
                    $Picture = $array[$k]['Picture'];
                    $Update = $array[$k]['Update'];
                    $Url = $array[$k]['Url'];
                    $Time = $array[$k]['Time'];
                    echo $go;
                    echo $Picture;
                    echo $to;
                    echo "\n番名：";
                    echo $Name;
                    echo "\n";
                    echo '更新至';
                    echo $Update;
                    echo "\n";
                    echo '更新时间：';
                    echo $Time;
                    echo "\n";
                    echo '播放链接：';
                    echo $Url;
                    echo "\n\n";
                }
            }
            if($i != 0){
                echo '好耶~更新了'.$i.'部番剧！';
            }else{
                echo '不好耶！还没有更新番剧！';
            }
            exit();
            break;
            default:
            $json = [];
            for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
                if($array[$k]['Time'] == $T){
                    $Name = $array[$k]['Name'];
                    $Picture = $array[$k]['Picture'];
                    $Update = $array[$k]['Update'];
                    $Url = $array[$k]['Url'];
                    $Time = $array[$k]['Time'];
                    $json[] = array('Name'=>$Name,'Picture'=>$Picture,'Update'=>$Update,'Time'=>$Time,'Url'=>$Url);
                }
            }
            if (!$json){
                need::send(Array('code'=>-2, 'text'=>'番剧没有更新'));
            }else{
                need::send(array('code'=>1,'text'=>'获取成功','data'=>$json));
            }
            break;
        }
    }
}else{
    array_multisort(array_column($array, 'Time'), SORT_ASC, $array);
    Switch($type){
        case 'text':
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
            echo $go;
            echo $Picture;
            echo $to;
            echo "\n番名：";
            echo $Name;
            echo "\n";
            echo '更新至';
            echo $Update;
            echo "\n";
            echo '更新时间：';
            echo $Time;
            echo "\n";
            echo '播放链接：';
            echo $Url;
            echo "\n\n";
        }
        echo '好耶~今日已更新'.Count($array).'部番剧！';
        exit();
        break;
        default:
        for($k = 0 ; $k < $num && $k < Count($array) ; $k++){
            $Name = $array[$k]['Name'];
            $Picture = $array[$k]['Picture'];
            $Update = $array[$k]['Update'];
            $Url = $array[$k]['Url'];
            $Time = $array[$k]['Time'];
        $json[] = array('Name'=>$Name,'Picture'=>$Picture,'Update'=>$Update,'Time'=>$Time,'Url'=>$Url);
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>$json));
        break;
    }
}
