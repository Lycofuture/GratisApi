<?php

/*

*图片处理函数

*/

//require '../curl.php';//引入curl函数,让图片出来的更快


function getCircleAvatar($url, $size = 128,$angle = 0)

{

//$detail = getimagesize($url);

//$type = image_type_to_extension($detail[2],false);

$type = need::teacher_curl($url,['refer'=>1]);

$original_string = "imagecreatefromstring";

$avatarResource = $original_string($type);

$avatarResource = imagerotate($avatarResource , $angle,0);

/*$imageSize = getimagesizefromstring($original_string);

$width = $imageSize[0];

$height = $imageSize[1];*/

$width = imagesx($avatarResource);

$height = imagesy($avatarResource);

$w = $h = (int)$size;
/*
if($angle!=0 && $angle!=180){

$w = $size*1.5;

$h = $size*1.5;

}*/

$squareAvatarResource = imageCenterCrop($avatarResource, $w, $h, $width, $height);


$newAvatarResource = imagecreatetruecolor($w, $h);

imagealphablending($newAvatarResource, false);

$transparent = imagecolorallocatealpha($newAvatarResource, 255, 255, 255, 127);

$r = $w / 2;

for ($x = 0; $x < $w; $x++) {
for ($y = 0; $y < $h; $y++) {
$c = imagecolorat($squareAvatarResource, $x, $y);

$_x = $x - $w / 2;

$_y = $y - $h / 2;

if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
imagesetpixel($newAvatarResource, $x, $y, $c);

} else {
imagesetpixel($newAvatarResource, $x, $y, $transparent);

}

}

}

imagesavealpha($newAvatarResource, true);

imagedestroy($squareAvatarResource);

/*imagepng($newAvatarResource);

imagedestroy($newAvatarResource);*/

return $newAvatarResource;

}

function imageCenterCrop($originalImageObj, $width, $height, $w, $h)

{
	$width = (int) $width;
	$height = (int) $height;
$scale = max($width / $w, $height / $h); //计算缩放比例

//设置缩略图的坐标及宽度和高度

$w1 = $width / $scale;

$h1 = $height / $scale;

$x = ($w - $w1) / 2;

$y = ($h - $h1) / 2;

$img = imagecreatetruecolor((int)$width, (int)$height);

//调整默认颜色

$color = imagecolorallocate($img, 255, 255, 255);

imagefill($img, 0, 0, $color);

//裁剪

imagecopyresampled($img, $originalImageObj, 0, 0, $x, $y, $width, $height, $w1, $h1);

imagedestroy($originalImageObj);

return $img;

}

/**

* 处理圆角图片

* @param resource $im 图像资源对象

* @param int $radius 圆角半径 长度默认为15，处理成圆型

* @return resource

* @author wangyu

* @createTime 2018/8/15 8:42

*/

function imageRadius($im, int $radius = 15)

{
$w = imagesx($im);

$h = imagesy($im);

// $radius = $radius == 0 ? (min($w, $h) / 2) : $radius;

$img = imagecreatetruecolor($w, $h);

imagesavealpha($img, true);

$bg = imagecolorallocatealpha($img, 255, 255, 255, 127); //拾取一个完全透明的颜色,最后一个参数127为全透明

imagefill($img, 0, 0, $bg);

$r = $radius; //圆角半径

for ($x = 0; $x < $w; $x++) {
for ($y = 0; $y < $h; $y++) {
$rgbColor = imagecolorat($im, $x, $y);

if (($x >= $radius && $x <= ($w - $radius)) || ($y >= $radius && $y <= ($h - $radius))) {
//不在四角的范围内,直接画

imagesetpixel($img, $x, $y, $rgbColor);

} else {
//在四角的范围内选择画

//上左

$y_x = $r; //圆心X坐标

$y_y = $r; //圆心Y坐标

if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
imagesetpixel($img, $x, $y, $rgbColor);

}

//上右

$y_x = $w - $r; //圆心X坐标

$y_y = $r; //圆心Y坐标

if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
imagesetpixel($img, $x, $y, $rgbColor);

}

//下左

$y_x = $r; //圆心X坐标

$y_y = $h - $r; //圆心Y坐标

if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
imagesetpixel($img, $x, $y, $rgbColor);

}

//下右

$y_x = $w - $r; //圆心X坐标

$y_y = $h - $r; //圆心Y坐标

if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
imagesetpixel($img, $x, $y, $rgbColor);

}

}

}

}

return $img;

}


