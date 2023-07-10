<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(103); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$QQ = $_REQUEST['QQ'];

if(empty($QQ)){

exit(need::json(array('code'=>-1,'text'=>'请输入QQ')));

}

if(!preg_match('/[1-9][0-9]{4,11}/',$QQ)){

exit(need::json(array('code'=>-2,'text'=>'请输入正确的QQ！')));

}

$image = __DIR__.'/./zan.jpg';//原图

$image_detail = getimagesize($image); //获取图片详情

$image_type = image_type_to_extension($image_detail[2],false);//获取图片类型

$image_form = "imagecreatefrom{$image_type}";//函数

$image = $image_form($image);//创建图片

$image_tou = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=140';//头像链接

//$fopen = fopen('2354452553.jpg','w');

//$string = file_get_contents($image_tou);

//fwrite($fopen,$string);

//fclose($fopen);

/*$image_tou_detail = getimagesize($image_tou);//获取图片详情

$image_tou_type = image_type_to_extension($image_tou_detail[2],false);//获取图片类型

$image_tou_form = "imagecreatefrom{$image_tou_type}";//函数

$image_tou = $image_tou_form($image_tou);//创作图片
*/
$image_tou = getCircleAvatar($image_tou,128);//裁剪为圆形

imagecopy($image,$image_tou,10,250,0,0,128,128);//拼合图片

$image_output = "image{$image_type}";

header('content-type:'.$image_detail['mime']);

$image_output($image);

imagedestroy($image);

imagedestroy($image_tou);



function getCircleAvatar($url, $size = 128)

{

//$detail = getimagesize($url);

//$type = image_type_to_extension($detail[2],false);

$type = need::teacher_curl($url,['refer'=>1]);

$original_string = "imagecreatefromstring";

$avatarResource = $original_string($type);

/*$imageSize = getimagesizefromstring($original_string);

$width = $imageSize[0];

$height = $imageSize[1];*/

$width = imagesx($avatarResource);

$height = imagesy($avatarResource);

$w = $h = $size;

$squareAvatarResource = imageCenterCrop($avatarResource, $w, $h, $width, $height);

$newAvatarResource = imagecreatetruecolor($w, $h);

imagealphablending($newAvatarResource, false);

$transparent = imagecolorallocatealpha($newAvatarResource, 0, 0, 0, 127);

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
$scale = max($width / $w, $height / $h); //计算缩放比例

//设置缩略图的坐标及宽度和高度

$w1 = $width / $scale;

$h1 = $height / $scale;

$x = ($w - $w1) / 2;

$y = ($h - $h1) / 2;

$img = imagecreatetruecolor($width, $height);

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


