<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(18); // 调用统计函数
require '../../curl.php';
require '../../need.php';
/* End */

$msg = @explode("\n", @$_REQUEST["msg"]);
$key = @$_REQUEST['key']?:'=';
$name=@$_REQUEST["name"]?:"金币";
$id=@$_REQUEST["id"]?:"5";
$type = @$_REQUEST['type']?:'text';
$head = @$_REQUEST['head']?:'————排行榜————';
$form = @$_REQUEST['form']?:'china';
$limit = @$_REQUEST['limit'];
$icon_Ranking = @$_REQUEST['icon_Ranking']?:'🏅';
$icon_name = @$_REQUEST['icon_name']?:'✨';
$icon_user = @$_REQUEST['icon_user']?:'🏅';
$user_true = @$_REQUEST['user_true']?:'QQ';
$user_name = @$_REQUEST['user_name'];
if(!empty($limit)){
    $limit = $limit."\n";
}
$data_list = [];
$value = @$_REQUEST['value'];
if (!($msg)){
    Switch($type){
        case 'text':
        die('没有数据哦');
        break;
        default:
        need::send([
            'code'=>-1,
            'text'=>'没有数据哦'
        ],'json');
        exit();
        break;
    }
    exit();

}
foreach($msg as $k=>$v){
    if($v){
        $data_num=@explode($key, $msg[$k]);
        if(!isset($data_num[0]) || !isset($data_num[1]))
        {
        	break;
        }
        //print_r($data_num);exit();
        $data_list[$k]["qq"]=$data_num[0];
        $data_list[$k]["money"]=@$data_num[1]?:null;
    }
}
if($data_list) {
	array_multisort(array_column($data_list,'money'),SORT_DESC,$data_list);
	$data_list_one=array_slice($data_list,0,$id);
	$end=end($data_list_one);
} else {
	need::send('没有数据', 'text');
}
/*
if(!$data_list_one){
    exit('没有数据哦');
}*/
echo $head;
echo "\n";
foreach($data_list_one as $k=>$v){
    $sign = '';
    $number = chinanum($k+1);
    if($end!==$v){
        if($v['qq'] == $value){
            $Ranking = $k+1;
        }
        if($k == 0){
            $sign = '🥇';
        }
        if($k == 1){
            $sign = '🥈';
         }
         if($k == 2){
            $sign = '🥉';
         }
       /*  if($form == 'china'){
             $number = chinanum($k+1);
         }*/
         if($form == 'number'){
             $number = ($k+1);
         }
        echo $icon_Ranking;
        echo '第'.$number.'名:'.name($v["qq"]).$sign."\n";
        echo $icon_name;
        echo $name.':'.$v["money"]."\n";
        echo $limit;
    }else{
        if($v['qq'] == $value){
            $Ranking = $k+1;
        }
        if($k == 0){
            $sign = '🥇';
        }
        if($k == 1){
            $sign = '🥈';
         }
         if($k == 2){
            $sign = '🥉';
         }/*
         if($form == 'china'){
             $number = chinanum($k+1);
         }*/
         if($form == 'number'){
             $number = ($k+1);
         }
        echo $icon_Ranking;
        echo '第'.$number.'名:'.name($v["qq"]).$sign."\n";
        echo $icon_name;
        echo $name.':'.$v["money"];
        echo "\n";
        if(empty($value)){
            //echo "\n"
            echo trim($limit);
       //     echo 1;
        }else{
         //   echo "\n";
            echo $limit;
        }
    }
}
if(empty($limit)){
    $h = "\n";
}
if($value){
    if($form == 'china'){
         $number = chinanum($Ranking);
     }
     if($form == 'number'){
         $number = $Ranking;
     }
    if($user_true == '您'){
        $value = '您';
    }
    if($Ranking){
        echo $h;
        echo $icon_user;
        echo name($value);
        echo '是第';
        echo $number;
        echo '名';
    }else{
        echo $h;
        echo $icon_user;
        echo name($value);
        echo '不在排名内';
    }
}



function chinanum($num){
    $char = array("零","一","二","三","四","五","六","七","八","九");
    $dw = array("","十","百","千","万","亿","兆");
    $retval = "";
    $proZero = false;
    for($i = 0;$i < strlen($num);$i++){
        if($i > 0)    $temp = (int)(($num % pow (10,$i+1)) / pow (10,$i));
        else $temp = (int)($num % pow (10,1));
         
        if($proZero == true && $temp == 0) continue;
         
        if($temp == 0) $proZero = true;
        else $proZero = false;
         
        if($proZero)
        {
            if($retval == "") continue;
            $retval = $char[$temp].$retval;
        }
        else $retval = $char[$temp].$dw[$i].$retval;
    }
    if($retval == "一十") $retval = "十";
    $retval = str_replace('一十','十',$retval);
    return $retval;
}

function name($QQ){
    $data = json_decode(need::teacher_curl('http://ovooa.com/API/qqxx/?QQ='.$QQ),true);
    if($data['code'] == 1 && $_REQUEST['user_name'] == 'QQ'){
        $name = $data['data']['name'];
    }else{
        $name = $QQ;
    }
    return $name;
}
