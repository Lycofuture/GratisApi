<?php
require '../../need.php';
require '../../curl.php';
require ("../function.php"); // 引入函数文件
addApiAccess(118); // 调用统计函数
addAccess();//调用统计函数*/
$QQ = @$_REQUEST['QQ'];
if(!need::is_num($QQ)){
    $image = new imagick('./tumeile.jpg');
    header('content-type:image/jpeg');
    echo $image;
    exit();
}
$url = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=5';
$String = need::teacher_curl($url);
if(empty($String)){
    unlink(__DIR__.'/'.md5($url));
    $image = new imagick('./tumeile.png');
    header('content-type:image/png');
    echo $image;
    exit();
}
file_put_contents(__DIR__.'/'.md5($url), $String);
$image = new imagick(__DIR__.'/'.md5($url));
// @unlink(__DIR__.'/'.md5($url));
    $image->resizeImage(235, 235, Imagick::FILTER_LANCZOS, 1);
    $image->rotateImage("rgb(0, 5, 5)", 45);
    $image->transparentPaintImage('rgb(0, 5, 5)', 0.0, 0.0, false);
    $image->despeckleimage();
    $img = new imagick();
    $img->newimage(640, 640, 'rgb(255, 255, 255)', 'png');
    $imagick = new imagick('./si1.png');
    $imagick->setimageformat('png');
    $imagick->resizeImage(640, 640, Imagick::FILTER_LANCZOS, 1);
    $img->compositeImage($image, Imagick::COMPOSITE_ATOP, 0, 395);
    $image = new imagick(__DIR__.'/'.md5($url));
    @unlink(__DIR__.'/'.md5($url));
    $image->resizeImage(235, 235, Imagick::FILTER_LANCZOS, 1);
    $image->rotateImage("rgb(0, 5, 5)", -44);
    $image->transparentPaintImage('rgb(0, 5, 5)', 0.0, 0.0, false);
    $image->despeckleimage();
    $img->compositeImage($image, Imagick::COMPOSITE_ATOP, 360, 142);
    $img->compositeImage($imagick, Imagick::COMPOSITE_ATOP, 0, 0);
    
header('content-type:image/png');
echo $img->getImageBlob();
