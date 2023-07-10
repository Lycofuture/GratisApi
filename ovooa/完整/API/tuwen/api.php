<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(33); // 调用统计函数
/* End */
$tus = rand(1,36);
//1.配置图片路径  
$img=@$_GET["tu"]?:$tus;//获取自定义图片规格
if ($img>35){
echo "tu参数请填写0-36";exit;}
$t=rand(1,5);
$time=time();
$imgsj = __DIR__."/img/".$img.".png";//图片位置
$src = $imgsj;

//获取图片信息

$info = getimagesize($src);
$imgk=$info[0];
$imgkdxz=$imgk-60;
$imgkdxz = @$_GET['wzkd']?:$imgkdxz;

//获取图片扩展名

$type = image_type_to_extension($info[2],false);

//动态的把图片导入内存中

$fun = "imagecreatefrom{$type}";

$image = $fun($src);

function autowrap($fontsize, $angle, $fontface, $string, $width) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
	$content = "";

	// 将字符串拆分成一个个单字 保存到数组 letter 中
	for ($i=0;$i<mb_strlen($string);$i++) {
		$letter[] = mb_substr($string, $i, 1);
	}

	foreach ($letter as $l) {
		$teststr = $content." ".$l;
		$testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
		// 判断拼接后的字符串是否超过预设的宽度
		if (($testbox[2] > $width) && ($content !== "")) {
			$content .= "\n";
		}
		$content .= $l;
	}
	return $content;
}


$msg = @$_GET['msg'];
$wb = @$_GET['wb']?:"——独角兽API";
$ttf=@$_GET["zt"]?:$t;
$d=@$_GET["dx"]?:46;
$x=@$_GET["x"]?:90;
$y=@$_GET["y"]?:700;
$tm=@$_GET["tm"]?:0;
$hh=@$_GET["h"]?:"|";
$msg=$msg.$hh.$hh.$wb;
$font=__DIR__."/ttf/".$ttf.".ttf";
$txt=str_replace($hh, "\n", $msg);
$zt = rand(1,5);
$txt = autowrap(46, 0, __DIR__."/ttf/".$zt.".ttf", $txt, $imgkdxz); // 自动换行处理
/*3.设置字体颜色和透明度*/
$color = imagecolorallocatealpha($image, rand(0,255), rand(0,255), rand(0,255), $tm);
//4.写入文字 (图片资源，字体大小，旋转角度，坐标x 左右，坐标y 上下，颜色，字体文件，内容) 
imagettftext($image, $d, 0, $x, $y, $color, $font, $txt);  

header('Content-type:'.$info['mime']);//设置输出类型


$func = "image{$type}";

$func($image);//输出图片

fastcgi_finish_request();//快速完成上面请求
time_sleep_until(time()+30);//延时30秒做下面请求

imagedestroy($image);//释放图片资源

//不需要?>结尾