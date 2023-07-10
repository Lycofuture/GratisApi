<?php
header('content-type:Application/json');
require_once '../../need.php';
require_once '../../curl.php';
$type = @$_REQUEST['type']?:@$_REQUEST['Type'];
$num = @$_REQUEST['num'];
if(empty($num) || !is_numEric($num) || $num > 100){
    $num = 1;
}
$file = file('./cache/lure.txt');
$array = array[];
$return = '';
if($type == 'image'){
    $rand = array_rand($file,1);
    need::send(trim($file[$rand]),'image');
}
for($i = 0 ; $i < $num ; $i++){
    $rand = array_rand($file,1);
    $return .= trim($file[$rand])."\n";
    $array[] = trim($file[$rand]);
}
Switch($type){
    case 'text':
    need::send(trim($return),'text');
    break;
    default:
    need::send(array('code'=>1,'text'=>'获取成功','data'=>$array));
    break;
}