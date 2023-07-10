<?php
header('content-type:application/json;');
require '../../curl.php';
require '../../need.php';
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(123); // 调用统计函数
$num = @$_REQUEST['num'];
$type = @$_REQUEST['type'];
if(empty($num) || !is_numeric($num) || $num > 2000){
    $num = 1;
}
$data = file('./dou_dou.txt');
if(empty($data)){
    Switch($type){
        case 'text':
        need::send('数据获取失败,请重试','text');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'数据获取失败,请重试'),'json');
        break;
    }
}
//$rand = array_rand($data,1);
if($num === 1){
    $rand = array_rand($data,1);
    $array = @$data[$rand];
    Switch($type){
        case 'text':
        need::send(trim($array),'text');
        break;
        default:
        need::send(array('code'=>1,'text'=>'获取成功','data'=>array('image'=>trim($array))));
        break;
    }
}else{
    Switch($type){
        case 'text':
        for($i = 0 ; $i < $num && $i < Count($data) ; $i ++){
            $rand = array_rand($data,1);
            echo $data[$rand];
//            echo "\n";
        }
        exit();
        break;
        default:
        $array = [];
        for($i = 0 ; $i < $num && $i < Count($data) ; $i ++){
            $rand = array_rand($data,1);
            $array[] = trim($data[$rand]);
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>Array('image'=>$array)));
        break;
    }
}

//print_r($data[1]);