<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(23); // 调用统计函数
/* End */
$type=@$_GET['type'];
session_start();
if($type == 1){
    getValidate(100,25);
}else if($type == 2){
    getCode2(4,100,40);
}else{
    getCode(4,100,40);
}

//加法
function getValidate($w,$h){
  $img = imagecreate($w,$h);
  $gray = imagecolorallocate($img,255,255,255);
  $black = imagecolorallocate($img,rand(0,200),rand(0,200),rand(0,200));
  $red = imagecolorallocate($img, 255, 0, 0);
  $white = imagecolorallocate($img, 255, 255, 255);
  $green = imagecolorallocate($img, 0, 255, 0);
  $blue = imagecolorallocate($img, 0, 0, 255);
  imagefilledrectangle($img, 0, 0, 100, 30, $black);
  for($i = 0;$i < 80;$i++){
    imagesetpixel($img, rand(0,$w), rand(0,$h), $gray);
  }
  $num1 = rand(1,20);
  $num2 = rand(1,20);
  $_SESSION['code'] = $num1+ $num2;
  imagestring($img, 5, 5, rand(1,10), $num1, $red);
  imagestring($img,5,30,rand(1,10),"+", $white);
  imagestring($img,5,45,rand(1,10),$num2, $green);
  imagestring($img,5,65,rand(1,10),"=", $blue);
  imagestring($img,5,80,rand(1,10),"?", $red);
  header("content-type:image/png");
  imagepng($img);
  imagedestroy($img);
}

//纯数字
function getCode($num,$w,$h) {
 $code = "";
 for ($i = 0; $i < $num; $i++) {
 $code .= rand(0, 9);
 }
 //4位验证码也可以用rand(1000,9999)直接生成
 //将生成的验证码写入session，备验证时用
 $_SESSION['code'] = $code;
 //创建图片，定义颜色值
 header("Content-type: image/PNG");
 $im = imagecreate($w, $h);
 $black = imagecolorallocate($im, 0, 0, 0);
 $gray = imagecolorallocate($im, 200, 200, 200);
 $bgcolor = imagecolorallocate($im, 255, 255, 255);
 //填充背景
 imagefill($im, 0, 0, $gray);
 //画边框
 imagerectangle($im, 0, 0, $w-1, $h-1, $black);
 //随机绘制两条虚线，起干扰作用
 $style = array ($black,$black,$black,$black,$black,
 $gray,$gray,$gray,$gray,$gray
 );
 imagesetstyle($im, $style);
 $y1 = rand(0, $h);
 $y2 = rand(0, $h);
 $y3 = rand(0, $h);
 $y4 = rand(0, $h);
 imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
 imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);
 //在画布上随机生成大量黑点，起干扰作用;
 for ($i = 0; $i < 80; $i++) {
 imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
 }
 //将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
 $strx = rand(8, 20);
 for ($i = 0; $i < $num; $i++) {
 $strpos = rand(1, 20);
 imagestring($im, 9, $strx, $strpos, substr($code, $i, 1), $black);
 $strx += rand(8, 20);
 }
 imagepng($im);//输出图片
 imagedestroy($im);//释放图片所占内存
}

//字母数字
function getCode2($num,$w,$h) {
 $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
 $code = "";
 for ($i = 0; $i < $num; $i++) {
 $code .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
 }
 //4位验证码也可以用rand(1000,9999)直接生成
 //将生成的验证码写入session，备验证时用
 $_SESSION['code'] = $code;
 //创建图片，定义颜色值
 header("Content-type: image/PNG");
 $im = imagecreate($w, $h);
 $black = imagecolorallocate($im, 0, 0, 0);
 $gray = imagecolorallocate($im, 200, 200, 200);
 $bgcolor = imagecolorallocate($im, 255, 255, 255);
 $red = imagecolorallocate($im, 255, 0, 0);
 //填充背景
 imagefill($im, 0, 0, $gray);
 //画边框
 imagerectangle($im, 0, 0, $w-1, $h-1, $black);
 //随机绘制两条虚线，起干扰作用
 $style = array ($black,$black,$black,$black,$black,
 $gray,$gray,$gray,$gray,$gray
 );
 imagesetstyle($im, $style);
 $y1 = rand(0, $h);
 $y2 = rand(0, $h);
 $y3 = rand(0, $h);
 $y4 = rand(0, $h);
 imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
 imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);
 //在画布上随机生成大量黑点，起干扰作用;
 for ($i = 0; $i < 60; $i++) {
 imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
 }
 //将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
 $strx = rand(8, 20);
 for ($i = 0; $i < $num; $i++) {
 $strpos = rand(1, 20);
 imagestring($im, 9, $strx, $strpos, substr($code, $i, 1), $red);
 $strx += rand(8, 20);
 }
 imagepng($im);//输出图片
 imagedestroy($im);//释放图片所占内存
}
?>