<?php 
//ini_set('display_errors',1); 
require '../../curl.php';
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(129); // 调用统计函数
addAccess();//调用统计函数
$url = @$_REQUEST['url'];
$type = @$_REQUEST['type'];
//$url = 'http://gchat.qpic.cn/gchatpic_new/0/0-0-2BF2E29EEE05D750B11D5A6B2E48F059/0';
if(empty($url)){
    Switch($type){
        case 'json':
        need::send(Array('code'=>-1,'text'=>'http://ovooa.com/API/xian/tumeile.jpg'));
        break;
        case 'text':
        need::send('http://ovooa.com/API/xian/tumeile.jpg','text');
        break;
        default:
        header('content-type:image/jpeg');
        $image = new imagick('./tumeile.jpg');
        echo $image;
        exit();
    }
}

$String = need::teacher_curl($url,['ctime'=>3,'rtime'=>5]);
if(empty($String)){
// exit('哈哈哈');
    Switch($type){
        case 'json':
        need::send(Array('code'=>-1,'text'=>'http://ovooa.com/API/xian/tumeile.jpg'));
        break;
        case 'text':
        need::send('http://ovooa.com/API/xian/tumeile.jpg','text');
        break;
        default:
        header('content-type:image/jpeg');
        $image = new imagick('./tumeile.jpg');
        echo $image;
        exit();
    }
}
/*
$image = imagecreatefromString($String);
imagepng($image,md5($url).'.png');
*/
//imagestory($image);
file_put_contents(__DIR__.'/cache/'. md5($url).'',$String);
//exit();
try{
    $image = new Imagick(__DIR__.'/cache/'. md5($url));
}catch (\Exception $e){
    unlink(__DIR__.'/cache/'. md5($url));
    Switch($type){
        case 'json':
        need::send(Array('code'=>-1,'text'=>'http://ovooa.com/API/xian/tumeile.jpg'));
        break;
        case 'text':
        need::send('http://ovooa.com/API/xian/tumeile.jpg','text');
        break;
        default:
        header('content-type:image/jpeg');
        $image = new imagick('./tumeile.jpg');
        echo $image;
        exit();
    }
}
$format = $image->getImageFormat();
if($format != 'GIF'){
   $image->setImageFormat('png');
   $image->charcoalImage(0.7,0.5);
   if(empty($image)){
       unlink(__DIR__.'/cache/'. md5($url));
       Switch($type){
           case 'json':
           need::send(Array('code'=>-1,'text'=>'http://ovooa.com/API/xian/tumeile.jpg'));
           break;
            case 'text':
            need::send('http://ovooa.com/API/xian/tumeile.jpg','text');
            break;
            default:
            header('content-type:image/jpeg');
            $image = new imagick('./tumeile.jpg');
            echo $image;
            exit();
        }
    }
    $image->writeImages(__DIR__.'/cache/'. md5($url).'.png',true);
    Switch($type){
        case 'json':
        echo need::json(Array('code'=>1,'text'=>'http://ovooa.com/API/xian/cache/'. md5($url).'.png'));
        break;
        case 'text':
        echo ('http://ovooa.com/API/xian/cache/'. md5($url).'.png');
        break;
        default:
        header('Content-type: image/png');
        echo $image; 
        break;
//    exit();
    }
}else{
    //require './ICP/anquan.php';
    $delay = $_REQUEST['delay']?:5;
    if(!is_numeric($delay) || $delay > 30){
        $delay = 5;
    }
    $image->setimageformat('GIF');
    $format = $image->coalesceImages();
    if(count($format) > 45){
        $image = file_get_contents('./big.jpg');
        header('content-type:image/jpeg');
        echo $image;
        unlink(__DIR__.'/cache/'. md5($url));
        exit();
    }
    $GIF = new imagick();
    $GIF->setFormat('gif');
    foreach($format as $k=>$v){
        file_put_contents(__DIR__.'./gif/cache/'. md5($url.$k).'.gif',$v);
    }
    for($i = 0 ; $i < count($format) ; $i++){
        $img = new imagick();
        $img->readImage(__DIR__.'./gif/cache/'. md5($url.$i).'.gif');
        $img->charcoalImage(0.7,0.5);
        $img->setformat('gif');
        $GIF->addImage($img);
        $GIF->setImageDelay($delay);
        unset($img);
    }
    header('content-type:image/GIF');
     $GIF->writeImages(__DIR__.'/cache/'. md5($url).'.gif',true);
     $image = file_get_contents(__DIR__.'./cache/'. md5($url).'.gif');
     echo $image;
}
fastcgi_finish_request();//先返回上面的内容
time_sleep_until(time()+10);//延迟10秒后执行下面的命令
@unlink(__DIR__.'/cache/'. md5($url).'.png');
@unlink(__DIR__.'/cache/'. md5($url).'.gif');
@unlink(__DIR__.'/cache/'. md5($url));
for($i = 0 ; $i < count($format) ; $i++){
    @unlink(__DIR__.'./gif/cache/'. md5($url.$i).'.gif');
}

?>