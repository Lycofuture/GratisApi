<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(114); // 调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */



header('content-type:application/json');


$QQ = @$_REQUEST['QQ'];

$Skey = @$_REQUEST['Skey'];

$pskey = @$_REQUEST['pskey'];

$Group = @$_REQUEST['Group'];

$type = @$_REQUEST['type'];


if(!need::is_num($QQ)){

    if($type == 'text'){
    
        exit('请输入正确的账号');
        
    }else{
    
        exit(need::json(array('code'=>-1,'text'=>'请输入正确的账号')));
        
    }
    
}

if(!need::is_num($Group)){

    if($type == 'text'){
    
        exit('请输入正确的群号');
        
    }else{
    
        exit(need::json(array('code'=>-1,'text'=>'请输入正确的群号')));
        
    }
    
}

if(empty($Skey)){

    if($type == 'text'){

        exit('请输入Skey');
        
    }else{
    
        exit(need::json(array('code'=>-3,'text'=>'请输入Skey')));
    
    }
    
}


if(empty($pskey)){

    if($type == 'text'){

        exit('请输入pskey');
        
    }else{
    
        exit(need::json(array('code'=>-4,'text'=>'请输入pskey')));
    
    }
    
}

$url = 'https://qun.qq.com/interactive/honorlist?gc='.$Group.'&type=1&_wv=3&_wwv=129';

$Cookie = 'uin=o'.$QQ.'; p_uin=o'.$QQ.'; skey='.$Skey.'; p_skey='.$pskey;

$ua = 'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/85 SimpleUISwitch/1 QQTheme/3063 InMagicWin/0 StudyMode/0';

$data = need::teacher_curl($url,[

    'cookie'=>$Cookie,
    'ua'=>$ua,
    'Header'=>['Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9']
    ]);
    
$code = preg_match('/__INITIAL_STATE__=([\s\S]*?)<\/script>/',$data,$data);

if(!$code){ 

    if($type == 'text'){

        exit('获取失败,请重试');
        
    }else{
    
        exit(need::json(array('code'=>-5,'text'=>'获取失败,请重试')));
        
    }

}

$data = json_decode($data[1],true);
    
if(!count($data['talkativeList'])){

    if($type == 'text'){

        exit('Skey已过期');
    
    }else{
    
        exit(need::json(array('code'=>-6,'text'=>'Skey已过期')));
        
    }
    
}
    
$Nick = $data['currentTalkative']['nick'];

$Day = $data['currentTalkative']['day_count'];

$uin = $data['currentTalkative']['uin'];

if(!$uin){

    if($type == 'text'){

        echo '----🐉----';
    
        echo "\n";
    
        echo '虚位以待';
    
        echo "\n";
    
        echo '----🐉----';
        
    }else{
     
        exit(need::json(array('code'=>-7,'text'=>'虚位以待')));
        
    }
    
}else

if($uin){

    if($type == 'text'){

        echo '----🐉----';
    
        echo "\n";

        echo '昵称：'.$Nick;
    
        echo "\n";
    
        echo '账号：'.$uin."\n";
    
        echo '蝉联：'.$Day.'天';
    
        echo "\n";
    
        echo '----🐉----';
    
    }else{
    
        echo need::json(array('code'=>1,'text'=>'获取成功','data'=>array('Uin'=>$uin,'Day'=>$Day,'Nick'=>$Nick)));
    
    }
    
}

