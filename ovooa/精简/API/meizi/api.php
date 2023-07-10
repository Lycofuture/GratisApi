<?php
header('Content-Type:Application/Json');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(106); // 调用统计函数*/
require '../../need.php';
require '../../curl.php';
//https://p1.pstatp.com/origin/ff3f0001f2a60fd53292
/* End */
//echo need::http('https://pic.rmb.bdstatic.com/bjh/dc3899cc4b03d262aa9c0fb810acc177.jpeg');exit;
$type = @$_REQUEST['type'];
$file = file('./meizi.txt');
//while(true){
    $rand = Array_rand($file,1);
    $URL = trim($file[$rand]);
    //$URL = need::teacher_curl('http://so.lkaa.top/Image/mei.php');
    /*
    if(need::http($URL) == 200){
        //return '';
        //break;
    }else{
        $files = @file_get_Contents('./meizi.txt');
        $open = fopen('./meizi.txt','w');
        fwrite($open,str_replace(array("\n{$URL}"),'',$files));
        fclose($open);
        unset($rand,$url,$files,$open);
    }*/
//}
if($type == 'text'){
    echo $URL;
    exit();
}
if($type == 'image'){
    header('Content-type:image/jpeg;image/png;');
    echo need::teacher_curl($URL);
    exit();
}

echo need::json(['code'=>1,'text'=>$URL]);
exit();


