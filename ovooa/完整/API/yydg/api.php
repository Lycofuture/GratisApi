<?php

header('content-type:application/JSON');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(31); // 调用统计函数
require "../../need.php";//引入封装好的函数
require '../../curl.php';

/* End */
$msg=@$_GET["msg"];
$n=@$_GET["n"];
$p = @$_GET["p"]?:1;
$sc = @$_GET["sc"]?:"10";
$type = @$_REQUEST['type'];
$div = @$_REQUEST['div']?:'=';


if(!$msg){

exit('请输入歌名');

}

$url = @file_get_contents('http://api.ring.kugou.com/ring/all_search?q='.urlencode($msg).'&t=1&subtype=1&p='.$p.'&pn='.$sc.'&plat=3');

if(!$url){

exit('未知错误');

}

$pst= json_decode($url);

//print_r($pst);

//exit;

$sl=$pst->response->musicInfo;
$cou= count($sl);
if(!$n){
for ($i = 0 ; $i < $cou ; $i++)
{
$mz=$pst->response->musicInfo[$i]->singerName;
$gm=$pst->response->musicInfo[$i]->ringName;
$tp=$pst->response->musicInfo[$i]->image->head;
$mm=$pst->response->musicInfo[$i]->duration;
echo ($i+1).'.'.$gm.'-'.$mz.'-'.$mm."秒\n";
}
//echo '发送数字即可选择';
}else if($n > $cou || $n < 1){

echo "序列号中没有〔".$n."〕";

}else{

$URL = $pst->response->musicInfo[($n-1)]->url;

$image = $pst->response->musicInfo[($n-1)]->diy->diy_user_headurl;

$singer = $pst->response->musicInfo[($n-1)]->singerName;

$song = $pst->response->musicInfo[($n-1)]->ringName;

if($type == 'text'){
    
    echo '±img'.$div.$image.'±'."\n";
    echo '歌手：'.$singer."\n";
    echo '歌名：'.$song."\n";
    echo '播放链接：'.$URL."\n";
    exit;

}else

if($type == 'url'){

    echo $URL;

}else{

    echo need::json(array('code'=>1,'text'=>'获取成功','data'=>array('song'=>$song,'singer'=>$singer,'image'=>$image,'url'=>$URL)));
    exit;
    }
}
?>