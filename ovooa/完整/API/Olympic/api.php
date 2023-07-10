<?php
header('content-type:application/json');
require ("../function.php"); // 引入函数文件
addApiAccess(131); // 调用统计函数
addAccess();//调用统计函数
require '../../need.php';//调用json函数
require '../../curl.php';//调用curl函数
$type = @$_REQUEST['type'];
$Num = @$_REQUEST['num']?:1;
if(!is_numeric($Num)){
    $Num = 1;
}
//if($Num < 1
$URL = 'https://oppo.go2yd.com/Website/contents/get-olympic-medal?appid=oppobrowser&type=all';
$data = json_decode(need::teacher_curl($URL,[
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
    'refer'=>'https://heytap.go2yd.com/article/olympic_H5?s=browser&appid=browser&net=wifi&model=PBEM00&distribution=oppo&iflowApp=browser&utk=V2_VKhpCxLNBjI8ZKIkghRnFvqldmuPq8lmcOP_Luo150g&relatednews_style=0&__fromId__=8000&__statParams__=sourceMedia&__source__=yidian&sourceMedia=yidian&__cmtCnt__=45&__styleType__=2&__publisher_id__=w0MPlsU9oGQtM0g6Z-Ndpg&openv=&__pf__=detail'
]),true);
$data = $data['result'];
if(empty($data)){
    Switch($type){
        case 'text':
            need::send('获取失败',$type);
            exit();
        default:
            need::send(array('code'=>-1,'text'=>'获取失败'));
            exit();
    }
}
Switch($type){
    case 'text':
        for($i = 0 ; $i < $Num && $i < count($data) ; $i++){
            $name = $data[$i]['country_name'];//名字
            $Ranking = $data[$i]['rank'];//排名
            $image = $data[$i]['country_flag'];
            $gold = $data[$i]['medal_gold_count'];
            $silver = $data[$i]['medal_silver_count'];
            $bronze = $data[$i]['medal_bronze_count'];
            $count = $data[$i]['medal_sum_count'];
            $Time = $data[$i]['update_time'];
            echo '国家：'.$name;
            echo "\n";
            echo '金牌：'.$gold;
            echo "\n";
            echo '银牌：'.$silver;
            echo "\n";
            echo '铜牌：'.$bronze;
            echo "\n";
            echo '排名：'.$Ranking;
            echo "\n";
            echo '更新：'.$Time;
            echo "\n";
        }
        echo "\n";
        echo '共有'.count($data).'名,目前显示'.$Num.'名';
        exit();
    default:
        for($k = 0 ; $k < $Num && $k < count($data) ; $k++){
            $name = $data[$k]['country_name'];//名字
            $Ranking = $data[$k]['rank'];//排名
            $image = $data[$k]['country_flag'];
            $gold = $data[$k]['medal_gold_count'];
            $silver = $data[$k]['medal_silver_count'];
            $bronze = $data[$k]['medal_bronze_count'];
            $count = $data[$k]['medal_sum_count'];
            $Time = $data[$k]['update_time'];
            $array[] = array('Name'=>$name,'Ranking'=>$Ranking,'Image'=>$image,'Gold'=>$gold,'Silver'=>$silver,'Bronze'=>$bronze,'Count'=>$count,'Time'=>$Time);
        }
    need::send(array('code'=>1,'text'=>'获取成功！','Tips'=>'Ranking=排名,Name=国家,Image=旗帜,Gold=🥇,Silver=🥈,Bronze=🥉,Count=奖牌总数,Time=更新时间','data'=>$array),$type);
}