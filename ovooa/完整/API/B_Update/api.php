<?php
header('content-type:application/json');
require '../../curl.php';
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(121); // 调用统计函数
addAccess();//调用统计函数
$num = @$_REQUEST['num']?:1;
$type = @$_REQUEST['type'];
if($num < 1){
    $num = 1;
}
if(!is_numeric($num)){
    $num = 1;
}
$data = json_decode(need::teacher_curl('https://bangumi.bilibili.com/api/timeline_v2_global?'),true);
$code = $data['code'];//状态码
$message = $data['message'];
if($code == 0 && $message == 'success'){
    $data = $data['result'];
    Switch($type){
        case 'text';
        for($k = 0 ; $k < $num && $k < Count($data) ; $k++){
            $Time = $data[$k]['lastupdate_at']?:'暂无更新';
            $Count = $data[$k]['bgmcount']?:'暂无更新';
        $Erae = $data[$k]['area'];//国家
        $Name = $data[$k]['title'];//名字
        $Url = 'https://www.bilibili.com/bangumi/play/ss'.$data[$k]['season_id'];//播放链接
        echo '番剧：'.$Name;
        echo "\n";
        echo '国家：'.$Erae;
        echo "\n";
        echo '总集数：'.$Count;
        echo "\n";
        echo '更新时间：'.$Time;
        echo "\n";
        echo '播放链接：'.$Url;
        echo "\n\n";
        }
        echo '提示：以上数据来源于B站';
        exit();
        break;
        default:
        for($k = 0 ; $k < $num && $k < Count($data) ; $k++){
            $Time = $data[$k]['lastupdate_at']?:'暂无更新';
            $Count = $data[$k]['bgmcount']?:'暂无更新';
            $array[] = array('Country'=>$data[$k]['area'],'Name'=>$data[$k]['title'],'Count'=>$Count,'Url'=>'https://www.bilibili.com/bangumi/play/ss'.$data[$k]['season_id'],'Time'=>$Time);
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>$array),'jsonp');
        break;
    }
}
//print_r($array);