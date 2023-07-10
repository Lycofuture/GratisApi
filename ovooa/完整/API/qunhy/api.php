<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(82); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$QQ = @$_REQUEST['QQ'];

$Skey = @$_REQUEST['Skey'];

$Pskey = @$_REQUEST['Pskey'];

$Group = @$_REQUEST['Group'];

$type = @$_REQUEST['type'];
/*
if($p<1){

exit(need::json(array("code"=>"-8","text"=>"页数最小为1！")));

}
*/
//$p = ($p-1);

$sc = @$_REQUEST['Num']?:"5";

//$bkn = need::GTK($Skey);


if(!preg_match('/[1-9][0-9]{5,11}/',(String)$QQ)){
    Switch($type){
        case 'text':
            need::send('请输入正确的账号！',$type);
            exit();
        default:
            need::send(array("code"=>"-1","text"=>"请输入提供Skey的QQ号"),$type);
        exit();
    }

}

if(empty($Skey)){
    Switch($type){
        case 'text':
            need::send('请输入Skey',$type);
        default:
            need::send(array("code"=>"-2","text"=>"请输入Skey"),$type);
        exit();
    }

}

if(!preg_match('/[1-9][0-9]{4,11}/',(String)$Group)){
    Switch($type){
        case 'text':
            need::send('请输入群号',$type);
            exit();
        default:
            need::send(array("code"=>"-3","text"=>"请输入需要查询的群号"),$type);
        exit();
    }
}

$data = need::teacher_curl('https://qun.qq.com/m/qun/activedata/speaking.html?gc='.$Group.'&time=0&_wv=3&&_wwv=128',[
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0',
    'cookie'=>'uin=o'.$QQ.'; p_uin=o'.$QQ.'; skey='.$Skey.'; p_skey='.$Pskey.'; '
]);

preg_match('/window\.__INITIAL_STATE__=([\s\S]*?)<\/script>/',$data,$data);

$data = json_decode($data[1] , true);

//print_r($data);exit();

$Group = $data['gc'];//群号

$data = $data["speakingList"];//["g_most_act"];

$num = count($data);

if($num == "0"){
    Switch($type){
        case 'text':
            need::send('暂未查询到活跃数据',$type);
            exit();
        default:
            need::send(array("code"=>"-4","text"=>"暂未查询到活跃数据"));
        exit();
    }
}

Switch($type){
    case 'text':
        echo '<——发言排行——>';
        for($i = 0; $i < $sc && $i < $num ; $i++){
            echo "\n";
            echo '排名：第'.chinanum(($i + 1)).'名';
            echo "\n";
            echo '账号：'.$data[$i]['uin'];
            echo "\n";
            echo '连续活跃：'.$data[$i]['active'].'天';
            echo "\n";
            echo '发言条数：'.$data[$i]['msgCount'].'条';
        }
        echo "\n";
        echo '提示：每月第一天更新，每七天更新一次';
        echo "\n";
        echo '<——发言排行——>';
        exit();
    default:
        foreach($data as $k=>$v){
            $array[] = array('uin'=>$data[$k]['uin'],'day'=>$data[$k]['active'],'count'=>$data[$k]['msgCount'],'ranking'=>'第'.chinanum(($k + 1)).'名');
        }
        need::send(array('code'=>1,'text'=>'获取成功','data'=>$array,'Tips'=>'每月第一天更新，每七天更新一次'),$type);
        exit();
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
    return $retval;
}


?>