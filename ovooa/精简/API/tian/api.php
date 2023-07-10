<?php
Header('Content-Type:Application/json;');
require '../../need.php';
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(147); // 调用统计函数
/* End */
$request = need::request();
$url = isset($request['url'])? $request['url'] : 'http://q2.qlogo.cn/headimg_dl?dst_uin=2354452553&spec=5';
$path = __DIR__.'/';
$bg = $path .'./bg.png';
$images = $path.'./Cache/'.Md5($url);
// preg_match('/url=(.*)/', $_SERVER["REQUEST_URI"], $url);
// $url = urldecode($url[1])?:'http://q2.qlogo.cn/headimg_dl?dst_uin=2354452553&spec=5';
if(!file_exists($images)){
    if(!$url){
        Header('Content-type:image/jpeg;');
        $image = new imagick($path.'./badimageurl.jpg');
        echo $image->getImageBlob();
        exit();
    }
    $String = need::teacher_curl($url,['ctime'=>3, 'rtime'=>3]);
    if(!$String){
        Header('Content-type:image/jpeg;');
        $image = new imagick($path.'./badimageurl.jpg');
        echo $image->getImageBlob();
        exit();
    }
    file_put_Contents($images, $String);
}

try{
    $image = New imagick($images);
    $format = $image->getImageFormat();//获取图片格式
    if($format == 'GIF'){
        $image = $image->coalesceImages();
        foreach($image as $v){
            $image =  $v;//获取每一帧
            break;
        }
    }
    //490, 406
    //815 546
    //285宽285高
    $width = $image->getImagewidth();
    $height = $image->getImageHeight();
    if($width == $height){
        $width = 285;
        $height = 285;
    }else
    if($width > $height){
        $zhi = (285 / $width);
        $width = 285;
        $height = ($height * $zhi);
    }else
    if($width < $height)
    {
        $zhi = (285 / $height);
        $height = 285;
        $width = ($width * $zhi);
    }else
    if($width < 285 && $height < 285){
        $zhi = (285 / $height);
        $height = 285;
        $width = ($width * $zhi);
        // exit;
    }
    if($width < 285 && $height > $width){
        $zhi = (285 - $width) / 285;
        $width = 285;
        $height = $height * $zhi + $height;
    }
    if($width > $height && $height < 285){
        $zhi = (285 - $height) / 285;
        $height = 285;
        $width = $width * $zhi + $width;
    }
    $image->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);//修改头像框大小与头像一样
    $image->rotateImage('rgb(255, 255, 255)', -16.5);//旋转<483*336
    $w = (4.9 * 20);//旋转角度增加的长度…
    $width = intval($width + $w);
    $height = intval($height + $w);
    $imagick = New imagick();
    $imagick->newimage(690, 660, 'rgb(255,255,255)');//创建空白图层
    $imagick->setimageformat('png');
    $Header = new imagick($bg);//创建主图案
    // print_r($height);exit;
    $image->resizeImage(
    	intval($width * 1.2), 
    	intval($height * 1.2), 
    	Imagick::FILTER_LANCZOS, 
    	1
    );//修改大小
    $imagick->compositeImage($image, Imagick::COMPOSITE_ATOP, (-10), 660 - $height - $w + 4);//合并图
    $imagick->compositeImage($Header,Imagick::COMPOSITE_ATOP,0, 0);//合并主图
    Header('Content-type:image/png');
    @unlink($images);
    echo $imagick->getImageBlob();
}catch (\Exception $e){
    @unlink($images);
        Header('Content-type:image/jpeg;');
        $image = new imagick($path.'./notfoundimage.jpg');
        echo $image->getImageBlob();
        exit();
}