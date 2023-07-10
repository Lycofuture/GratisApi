<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(51); // 调用统计函数
addAccess();//调用统计函数
require "../../need.php";//引入封装好的函数


/* End */

$type = @$_REQUEST['type'];

$time = @$_REQUEST['time']?:date('Y-m-d');

$data=need::teacher_curl('http://open.iciba.com/dsapi/?date='.$time);


if(empty($data)){

    $file = @file_get_contents('./'.$time.'.txt');
    
    if(!$file){
    
        $text = need::teacher_curl('http://lkaa.top/API/yiyan/api.php?m=1&type=text');
        
        $open = fopen('./'.$time.'.txt','w');
        
        fwrite($open,$text);
        
        fclose($open);
        
        }
        
    $file = @file_get_contents('./'.$time.'.txt');
    
    $English = json_decode(need::teacher_curl('http://lkaa.top/API/qqfy/api.php?msg='.$file.'&type=json'),true);
    

    if($type == 'text'){
    
        exit($file."\n\n".$English['text']."\n\n时间：".$time);
        
    }else{

        exit(need::json(array("code"=>"1","text"=>"获取出错但是从内部获取一条",'data'=>array('English'=>$English['text'],'Chinese'=>$file,'Picture'=>'http://gchat.qpic.cn/gchatpic_new/0/0-0-AC7796A1CD78664DD2BC11142C885F01/0','Time'=>$time))));
    
    }

}

$json = json_decode($data, true);

$Ptt = $json['tts'];//音频

$English = $json['content'];//英文

$Chinese = $json['note'];//汉语

$Time = $json['dateline'];//时间

$Picture = $json['fenxiang_img'];//图片

$array = array('code'=>1,'text'=>'获取成功','data'=>array('English'=>$English,'Chinese'=>$Chinese,'Ptt'=>$Ptt,'Picture'=>$Picture,'Time'=>$Time));


if($type=="tion" || $type=="text"){

echo $Chinese."\n\n".$English."\n\n时间：".$Time;

}else{

    echo need::json($array);

}








?>