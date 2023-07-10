<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(35); // 调用统计函数
require '../../need.php';
require '../../curl.php';
/* End */
$type = (@$_GET['type']); 
$form = @$_REQUEST['form']?:'随机';
$value = [
    '男头'=>14,
    '随机'=>15,
    '女头'=>13
//    '情侣'=>12,
];
if(empty($value[$form])){
    $value[$form] = 15;
}
//print_r($value);exit;
$arr=range(0,37);#43
//if($
shuffle($arr);
foreach($arr as $values){
    $k = $values;
    break;
}
$str = 'http://elf.static.maibaapp.com/content/json/avatars/li-'.$value[$form].'-'.$k.'.json'; 
//echo $str;exit();
$html=file_get_contents($str);
$result = preg_match_all('/(.*?)in\":\"(.*?)\"(.*?)/', $html, $arr);
$arr=range(0,$result); 
shuffle($arr); 
foreach($arr as $values); 
$str = "/in\":\"(.*?)\"/"; 
if(preg_match_all($str,$html,$trstr)){
    Switch($type){
        case 'text':
        echo "http://webimg.maibaapp.com/content/img/avatars/";
        echo "".$trstr[1][$values];
        exit();
        break;
        case 'image':
        need::send('http://webimg.maibaapp.com/content/img/avatars/'.$trstr[1][$values],'image');
        exit();
        break;
        default:
        need::send([
            'code'=>1,
            'text'=>'http://webimg.maibaapp.com/content/img/avatars/'.$trstr[1][$values]
            ],$type
        );
        exit();
        break;
    }
}else{
    Switch($type){
        case 'text':
        need::send('获取失败','text');
        exit();
        break;
        default:
        need::send([
            'code'=>-1,
            'text'=>'获取失败'
        ]);
        exit();
        break;
    }
}



?>