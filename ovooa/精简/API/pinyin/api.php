<?php

/* Start */

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(41); // 调用统计函数*/

require "../../need.php";//引入封装好的函数



/* End */

header('content-type:application/json');

//preg_match("/([\x{4e00}-\x{9fa5}]+)(.*?),/u",$string);

$string = @$_REQUEST['msg'];

$type = @$_REQUEST['type'];

$format = @$_REQUEST['format'];

$bol = @$_REQUEST['bol'];

if(!preg_match("/([\x{4e00}-\x{9fa5}]+)/u",$string)){

    if($type == 'text'){

        echo '你中文呢';

        exit();
        
    }else{
     
         echo need::json(Array('code'=>-1,'text'=>'中文被你吃了？'));
         
         exit();
         
    }

}

if($format == 1){

    $pinyin = json_decode(file_get_contents('./111.json'),true);

}else{

    $pinyin = json_decode(file_get_contents('./115.json'),true);

}

for ($i=0;$i<mb_strlen($string);$i++) {

		$letter[] = mb_substr($string, $i, 1);

	}

if($type == 'text'){
    $str = '';
    foreach($letter as $v){

        $str .= $pinyin[$v]?:$v;
        $str .= $bol;

    }

    echo preg_replace('/'.$bol.'$/', '', $str);
    
    exit();

}else{
    $str = [];
    foreach($letter as $v){

        $str[] = $pinyin[$v]?:$v;

    }

    echo need::json(Array('code'=>1,'text'=>'获取成功','data'=>$str),1);
    
    exit();

}
