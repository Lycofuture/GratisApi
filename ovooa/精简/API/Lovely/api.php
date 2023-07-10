<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(113); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$Type = @$_REQUEST['type'];


$url = 'http://service.aibizhi.adesk.com/v1/wallpaper/album/51a626c848d5b928978ff8da/wallpaper?limit=9999&adult=false&first=0&order=new';//&skip='.rand(0,150);
//http://service.aibizhi.adesk.com/v1/wallpaper/album/51a626c848d5b928978ff8da/wallpaper?limit=30&adult=false&first=0&order=new

$data = json_decode(need::teacher_curl($url,['ua'=>'picasso,285,nearme',
'Header'=>['Host: service.aibizhi.adesk.com']]), true);
    $data = $data['res']['wallpaper'];
    $rand = array_rand($data,1);
    $image = $data[$rand]['img'];
    
    // print_r($data);
if($Type == 'text'){

    echo $image;
    
    exit();
    
}else

if($Type == 'image'){

    header('location:'.$image);
    
}else{

    echo need::json(array('code'=>1,'text'=>$image));

    exit;

}