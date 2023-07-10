<?php
/* Start */
/*require ("../function.php"); // 引入函数文件
addApiAccess(143); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
//mb_internal_encoding("UTF-8"); // 设置编码
$Msg = @$_REQUEST['Msg']?:@$_REQUEST['msg'];
$img_width = @$_REQUEST["width"]?:'200';
$size = @$_REQUEST['size']?:12;
if(!is_numEric($img_width) || $img_width < 50 || $img_width > 8000){
    $img_width = 200;
}
if(!is_numEric($size) || $size < 1 || $size > 100){
    $size = 12;
}
$r = @$_REQUEST['r']?:@$_REQUEST['R']?:255;
if(!is_numEric($r) || $r > 255 || $r < 0){
    $r = 255;
}
$g = @$_REQUEST['g']?:@$_REQUEST['G']?:192;
if(!is_numEric($g) || $g > 255 || $g < 0){
    $r = 192;
}
$b = @$_REQUEST['b']?:@$_REQUEST['B']?:203;
if(!is_numEric($b) || $b > 255 || $b < 0){
    $r = 203;
}
$type = @$_REQUEST["type"];
$h = @$_REQUEST["h"];
if($Msg==''){
    auto_image('请输入Msg', $h, '1.ttf', $size, $img_width, $r, $g, $b);
}
if($img_width < 1){
    auto_image('参数width致命错误', $h, '1.ttf', $size, $img_width, $r, $g, $b);
}
auto_image($Msg, $h, '1.ttf', $size, $img_width, $r, $g, $b);
function auto_image($Msg, $h=null, $font='1.ttf', $size=12, $img_width=200, $r=255, $g=192, $b=203){
    $line = 1;
    $ttf_size = $size;
    //echo $img_width;exit;
    $width_s = imagettfbbox($ttf_size, 0, $font, str_replace($h, "\n", $Msg));
    //print_r($width_s);exit;
    $font_width = imagettfbbox($size, 0, $font, '整')[2];
    $font_width_en = imagettfbbox($size, 0, $font, 'A')[2];
    //echo $font_width_en;exit;
    $widths = $width_s[2];
    if(!$h){
        $len = 16.7 * $size;
        
        if($widths > $img_width){
            $len = $img_width + 4;
        }else{
            $len = $widths+4;
        }
        $text = autowrap($size, 0, $font, $Msg, ($len), $line); // 自动换行处理
        //$height = ($ttf_size*1.75*$line);
        $height = $size * 1.75 * $line;
        $image = imagecreatetruecolor($len, $height); // 创建画布(宽度,高度)
        $color = imagecolorAllocate($image, $r, $g, $b);//设置画布颜色
        imagefill($image,0,0,$color);//开始填充颜色
        imagettftext($image, $ttf_size, 0, 1, ($ttf_size * 1.333), 0, $font, $text);//写入到图片中(图片,字体大小,角度,横向,纵向,颜色,内容)
        header ("Content-type: image/png");
        imagepng($image);//输出图片
        imagedestroy($image);//关闭图片
    }else{
        $text = str_replace($h,"\n",$Msg);//替换换行符
        $array = explode("\n",$text);
        $strlen = 0;
        $len = 0;
        $len_en = 0;
        foreach($array as $v){
        	//判断最长的文本是哪一行
            $strlen < mb_strlen($v) ? $strlen = mb_strlen($v) : $strlen = $strlen;
        }
        $width = $strlen * 12 + $size * 2;//计算出所需要的宽度
        if($img_width < $width){
        	//使最宽处处于设置宽度的范围内
            $width = $img_width + 4;
        }
        unset($text);
        foreach($array as $v){
            $text .= autowrap($size, 0, $font, $v, ($width), $line)."\n";
        }
        $text = trim($text);
        $num_h = substr_count($text,"\n");
        if(count($array) > 1){
            $num_h = ($num_h + 1);
        }
        $height = (($ttf_size*1.75)+(($ttf_size*1.75)*$num_h));
        $height = $num_h * $size * 1.75;
        $image = imagecreatetruecolor($width, $height); // 创建画布(宽度,高度)
        $color = imagecolorAllocate($image, $r, $g, $b);//设置画布颜色
        imagefill($image,0,0,$color);//开始填充颜色
        imagettftext($image, $ttf_size, 0, 1, ($ttf_size * 1.333), 0, $font, $text);//写入到图片中(图片,字体大小,角度,横向,纵向,颜色,内容)
        header ("Content-type: image/png");
        imagepng($image);//输出图片
        imagedestroy($image);//关闭图片
    }
}

function autowrap($fontsize, $angle, $fontface, $string, $width,&$line) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度,自动增高
	$content = "";
	// 将字符串拆分成一个个单字 保存到数组 letter 中
	$letter = array();
	for ($i=0;$i<mb_strlen($string);$i++) {
		$letter[] = mb_substr($string, $i, 1);
	}
	foreach ($letter as $l) {
		$teststr = $content.$l;
		$testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
		// 判断拼接后的字符串是否超过预设的宽度
		
		if (($testbox[2] > $width) && ($content !== "")) {
			$content .= "\n";
			$line++;
		}
		
		$content .= $l ;
		unset($l,$teststr,$testbox);
	}
	return trim($content);
}