<?php
/* Start */
/*
require ("../function.php"); // 引入函数文件
addApiAccess(143); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
//mb_internal_encoding("UTF-8"); // 设置编码
$Msg = $_REQUEST['Msg']?:$_REQUEST['msg'];
$img_width = $_REQUEST["width"]?:'300';
$size = $_REQUEST['size']?:12;
if(!is_numEric($img_width) || $img_width < 300 || $img_width > 8000){
    $img_width = 300;
}
if(!is_numEric($size) || $size < 1 || $size > 100){
    $size = 12;
}
$r = $_REQUEST['r']?:$_REQUEST['R']?:255;
if(!is_numEric($r) || $r > 255 || $r < 0){
    $r = 255;
}
$g = $_REQUEST['g']?:$_REQUEST['G']?:192;
if(!is_numEric($g) || $g > 255 || $g < 0){
    $r = 192;
}
$b = $_REQUEST['b']?:$_REQUEST['B']?:203;
if(!is_numEric($b) || $b > 255 || $b < 0){
    $r = 203;
}
$type = $_REQUEST["type"];
$h = $_REQUEST["h"];
if($Msg==''){
    auto_image('请输入Msg', $h, './1.ttf', $size, $img_width, $r, $g, $b);
}
if($img_width < 1){
    auto_image('参数width致命错误', $h, './1.ttf', $size, $img_width, $r, $g, $b);
}
auto_image($Msg, $h, './1.ttf', $size, $img_width, $r, $g, $b);
function auto_image($Msg, $h=null, $font='./1.ttf', $size=12, $img_width=200, $r=255, $g=192, $b=203){
    $ttf_size = $size;
    $width = imagettfbbox($ttf_size, 0, $font, $Msg);
    $font_width = imagettfbbox($ttf_size, 0, $font, '正')[2];
    //echo($font_width);exit;
    $font_width_en = imagettfbbox($ttf_size, 0, $font, 'H')[2];
    $widths = 0;
    $width = $width[2];
    if($width > $img_width){
        $width = $img_width;
    }
    if(!$h){
        $text = auto_wrap($ttf_size, 0, $font, $Msg, $width, $line, $widths); // 自动换行处理
        $lens = $widths + $size + mb_strlen($Msg) +$font_width;
        $line = 1.5;
        $text = auto_wrap($ttf_size, 0, $font, $Msg, ($lens - $size), $line, $widths); // 自动换行处理
        $num_h = substr_count($text,"\n");
        $height = ($line * ($font_width / 1.4) + $size * 0.5);
        $image = imagecreatetruecolor(($lens+$size)*1.1, $height); // 创建画布(宽度,高度)
        $color = imagecolorAllocate($image, $r, $g, $b);//设置画布颜色
        imagefill($image,0,0,$color);//开始填充颜色
        imagettftext($image, $ttf_size, 0, 1, ($ttf_size * 1.333), 0, $font, $text);//写入到图片中(图片,字体大小,角度,横向,纵向,颜色,内容)
        header ("Content-type: image/png");
        imagepng($image);//输出图片
        imagedestroy($image);//关闭图片
        exit;
    }else{
        $text = str_replace($h,"\n",$Msg);//替换换行符
        $array = explode("\n",$text);
        $line = 2;
        $widths = 0;
        foreach($array as $v){
            $texts .= autowrap($ttf_size, 0, $font, $v, $width, $line, $widths)."\n";
        }
        unset($texts);
        /*
        echo $width;
        exit;
        */
        $lens = $widths;// - $font_width - $size);
        /*
        echo ($lens - (mb_strlen($Msg) * 1.2 + $font_width) );
        exit;
        */
        $line = 2.6;
        $widths = 0;
        foreach($array as $v){
            $texts .= autowrap($ttf_size, 0, $font, $v,($lens - (mb_strlen($Msg) * 1.8 + $font_width) ), $line, $widths)."\n";
        }
        $text = trim($texts);
        $num_h = substr_count($texts,"\n");
        $height = ($num_h * ($font_width + ($font_width / 3))+$line);
        $image = imagecreatetruecolor($lens+$size, $height); // 创建画布(宽度,高度)
        $color = imagecolorAllocate($image, $r, $g, $b);//设置画布颜色
        imagefill($image,0,0,$color);//开始填充颜色
        imagettftext($image, $ttf_size, 0, 1, ($ttf_size * 1.333), 0, $font, $text);//写入到图片中(图片,字体大小,角度,横向,纵向,颜色,内容)
        header ("Content-type: image/png");
        imagepng($image);//输出图片
        imagedestroy($image);//关闭图片
    }
}

function autowrap($fontsize, $angle, $fontface, $string, $width,&$line, &$widths) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度,自动增高
	$content = "";
	// 将字符串拆分成一个个单字 保存到数组 letter 中
	for ($i=0;$i<mb_strlen($string);$i++) {
		$letter[] = mb_substr($string, $i, 1);
	}
	$size = imagettfbbox($fontsize, $angle, $fontface, '整')[2];
	if(empty($letter)){
	    $widths = mb_strlen($string) * 2 * $size ;
	    return $string;
	}
	$widtho = 0;
	foreach ($letter as $l) {
		$teststr .= $l .'';
		$String .= $l;
		$testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
		// 判断拼接后的字符串是否超过预设的宽度
		if( $testbox[2] <= $width){
	        $widtho = $testbox[2];
	        //echo '1,';
	    }
		if (($testbox[2] > $width) && ($content !== "")) {
		    if($width >= $testbox[2]){
		        $widtho = $testbox[2];
		    }
		    //print_r($testbox);exit;
			$content .= "\n";
			$line+=2;
			unset($String,$teststr);
		}
		unset($testbox,$teststr);
		$content .= $l." ";
	}
	$widths = $widtho * 2;// ($size * mb_strlen($string));
	return trim($content);
}
function auto_wrap($fontsize, $angle, $fontface, $string, $width,&$line, &$widths) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度,自动增高
	$content = "";
	// 将字符串拆分成一个个单字 保存到数组 letter 中
	for ($i=0;$i<mb_strlen($string);$i++) {
		$letter[] = mb_substr($string, $i, 1);
	}
	$size = imagettfbbox($fontsize, $angle, $fontface, '整')[2];
	$widtho = 0;
	foreach ($letter as $l) {
		$teststr .= $l .'';
		$String .= $l;
		$testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
		// 判断拼接后的字符串是否超过预设的宽度
		if( $testbox[2] > $widtho){
	        $widtho = $testbox[2];
	        //echo '1,';
	    }
		if (($testbox[2] > $width) && ($content !== "")) {
		    if($widtho < $testbox[2]){
		        $widtho = $testbox[2];
		    }
		    //print_r($testbox);exit;
			$content .= "\n";
			$line+=2;
			unset($String,$teststr);
		}
		$content .= $l." ";
	}
	$widths = $widtho ;// ($size * mb_strlen($string));
	return trim($content);
}