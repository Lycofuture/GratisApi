<?php
//问情api开源
$qq=$_GET["qq"];//需要生成的QQ号
if($qq==''){
header("Content-type: text/html; charset=utf-8");
echo 'QQ参数为空';//初步判断
}else{
header("Content-type:image/png");
$image = imagecreatetruecolor(95,85);
$white = imagecolorallocate($image, 255, 255, 255);
//imagefill($image, 0, 0, $white);//填充白色
$qqimage1 =  imagecreatefromstring(file_get_contents('https://q4.qlogo.cn/g?b=qq&nk='.$qq.'&s=640')); 
$qianimg = imagecreatefrompng('./data/cp/cp.png'); 
imagecopyresized($image,$qqimage1,24,25,0,0,85,69,820,970);
imagecopyresized($image,$qianimg,0,0,0,0, 1325,1085,1025,1076);//合成
imagepng($image);
imagedestroy($qqimage1);
imagedestroy($qianimg);
imagedestroy($image);//释放资源
}
?>