<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(115); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$url = @$_REQUEST['url'];
$type = @$_REQUEST['type'];
$String = need::teacher_curl($url);
if(empty($String)){
    Switch($type){
        case 'text':
        need::send('你图没了','text');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'你图没了'));
        break;
    }
}
file_put_Contents(md5($url),$String);
$image = new imagick(md5($url));
$image->setimageformat('jpeg');
$name = strtolower($image->getImageFormat());//获取图片格式
$image->minifyImage();//缩放两倍
$image->getImageBlob();
file_put_Contents(md5($url),$image);//$image->writeimages(md5($url),true);//保存本地
$data = json_decode(need::teacher_curl('https://checkimage.querydata.org/api',[
    'post'=>[
        'image'=>new curlfile(realpath(md5($url)),'image/'.$name,md5($url)),
        'type'=>'image/'.$name
    ],
    'Header'=>[
        'Accept'=>'image/jpeg,image/png,image/gif,*/*',
        'Content-type'=>'image/'.$name
    ]
]),true);
unlink(md5($url));
if(empty($data)){
    Switch($type){
        case 'text':
        need::send('获取失败请重试');
        break;
        default:
        need::send(array('code'=>-2,'text'=>'获取失败请重试'));
        break;
    }
}
foreach($data as $v){
    $name = $v['className'];
    $array[$name] = round(($v['probability'] * 100),2);
}
if(empty($array)){
    Switch($type){
        case 'text':
        need::send('未知错误');
        break;
        default:
        need::send(array('code'=>-3,'text'=>'未知错误'));
        break;
    }
}
if($array['Porn'] >= 40){
    $h = $array['Porn'].'%';
}
if($array['Sexy'] >= 40){
    $h = $array['Sexy'].'%';
}
if($array['Hentai'] >= 40){
    $h = $array['Hentai'].'%';
}
if(empty($h)){
    $h = ($array['Sexy'] + $array['Porn'] + $array['Hentai']).'%';
}
Switch($type){
    case 'text':
    echo '普通比例：'.$array['Neutral'].'%';
    echo "\n";
    echo '中性比例：'.$array['Drawing'].'%';
    echo "\n";
    echo "色情比例：".$h;
    exit();
    break;
    default:
    need::send(array('code'=>1,'text'=>'获取成功','data'=>array('ord'=>$array['Neutral'].'%','mid'=>$array['Drawing'].'%','GK'=>$array['Hentai'].'%','ras'=>$array['Porn'].'%','nak'=>$array['Sexy'].'%','anyse'=>$h)));
    break;
}
//print_r($array);
