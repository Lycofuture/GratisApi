<?php
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(25); // 调用统计函数
require '../../need.php';
  $msg=@$_GET["msg"] ?: 1234;
  $type=@$_GET["type"];
  $image = imagecreatetruecolor(40, 25);
  $bgcolor = imagecolorallocate($image,255,255,255);
  imagefill($image, 0, 0, $bgcolor);
    $fontcolor = imagecolorallocate($image, rand(0,120),rand(0,120), rand(0,120));
    imagestring($image,5,1,2,$msg,$fontcolor);
  for($i=0;$i<200;$i++){
    $pointcolor = imagecolorallocate($image,rand(50,200), rand(50,200), rand(50,200));    
    imagesetpixel($image, rand(1,40), rand(1,20), $pointcolor);
  }if ($type==null||$type=="1"){
  header('Content-Type: image/png');
  imagepng($image);
  imagedestroy($image);
  }else {
  echo need::json(array("code"=>"1","text"=>'http://ovooa.com/API/yzmt/?msg='.$msg));
  }
?>