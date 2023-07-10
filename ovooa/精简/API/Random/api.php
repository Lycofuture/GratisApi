<?php

require '../../need.php';

/**

 * PHP 统计字符重复次数和字符

 */

 
 $Type = @$_REQUEST['type'];
 $String = @$_REQUEST['value'];
 $ovooa = @$_REQUEST['key'];
 //$String = [];
 if(empty($String)){
     switch($Type){
         case 'text':
             need::send('请输入条件',$Type);
             break;
         default:
             need::send(array('code'=>-1,'text'=>'请输入条件'),$Type);
         break;
     }
     exit();
 }
  if(empty($ovooa)){
     switch($Type){
         case 'text':
             need::send('请输入键名',$Type);
             break;
         default:
             need::send(array('code'=>-2,'text'=>'请输入键名'),$Type);
         break;
     }
     exit();
 }
 if(!strstr($String,$ovooa)){
     switch($Type){
         case 'text':
             need::send('键名输入错误',$Type);
             break;
         default:
             need::send(array('code'=>-3,'text'=>'键名输入错误'),$Type);
         break;
     }
     exit();
 }
 

$str = explode($ovooa,$String);

$str = array_filter($str);

//echo($str);

$strRecord = array();

for ($i = 0; $i < (count($str) * 5); $i++) {

    $rand = array_rand($str,1);
    
    $stro .= $str[$rand].'@';

}
//echo $stro;exit;

$str = explode('@',$stro);

$str = array_filter($str);
//print_r($str);exit
//unset($str[(count($str) - 1)]);
//print_r($str);exit;
for ($i = 0; $i < count($str); $i++) {
    //默认设置为没有遇到过

    $found = 0;

        foreach ($strRecord as $k => $v) {

        if ($str[$i] == $v['key']) {

            //记录再次遇到的字符，count + 1；

            $strRecord[$k]['count'] += 1;

            //设置已经遇到过的，标记

            $found = 1;

            //如果已经遇到，不用再循环记录数组了，继续下一个字符串比较

            break;

        }

    }

    if (!$found) {

        //记录第一次遇到的字符，count + 1；

        $strRecord[] = array('key' => $str[$i], 'count' => 1);

    }

}
//print_r($strRecord);exit;
for($k = 0 ; $k < count($strRecord) ; $k++){
    $o = $strRecord[$k]['key'];
    $array[$o] = $strRecord[$k]['count'];
}
/*
foreach($strRecord as $k=>$v){
    $array[$k] = $v;
}

 */

//print_r($array);
/*
$str2 = '';

foreach ($strRecord as $k => $v) {

    foreach ($v as $key => $value) {

        $str2 .= $value;

    }

 

}
*/

arsort($array);//排序

foreach($array as $k=>$v){
    $String = $k;
    break;
}

 switch($Type){
     case 'text':
         need::send($String,$Type);
         break;
     default:
         need::send(array('code'=>1,'text'=>$String,'data'=>$array),$Type);
         break;
     }
 exit();

?>