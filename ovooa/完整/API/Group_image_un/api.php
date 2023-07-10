<?php
header('Content-type:image/png;jpeg;gif;');
require_once('../../curl.php');
require_once('../../need.php');
require ("../function.php"); // 引入函数文件
addApiAccess(141); // 调用统计函数
addAccess();//调用统计函数
//print_r(Level(82));exit;
$Request = need::Request();
$Uin = isset($Request['Uin']) ? $Request['Uin'] : null;
$Skey = isset($Request['Skey']) ? $Request['Skey'] : null;
$Pskey = isset($Request['Pskey']) ? $Request['Pskey'] : null;
$Group = isset($Request['Group']) ? $Request['Group'] : null;
$QQ = isset($Request['QQ']) ? $Request['QQ'] : null;
$name = isset($Request['name']) ? $Request['name'] : '名字';
$host = $_SERVER['SERVER_NAME'];
$yiyan = @$Request['yiyan']?:need::teacher_curl('http://'.$host.'/yiyan.api?type=text');
if(!need::is_num($Uin) || !need::is_num($Group) || !need::is_num($QQ)){
    $imagick = new imagick('./not_Uin.jpg');
    echo $imagick->getImageBlob();
    exit();
}
$one_num = preg_match_all('/1/',$Uin);
if(empty($Skey)){
    $imagick = new imagick('./not_Skey.jpg');
    echo $imagick->getImageBlob();
    exit();
}
if(empty($Pskey)){
    $imagick = new imagick('./not_Pskey.jpg');
    echo $imagick->getImageBlob();
    exit();
}
$font = './1.ttf';
$file_name = __DIR__.'/'.md5($Uin);
$header_url = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$Uin.'&spec=5';
$header_str = need::teacher_curl($header_url);
try{
    file_put_Contents($file_name,$header_str);
} catch (\Exception $e){
    unlink($file_name);
    die('头像获取失败');
}
/* 群信息 */
$title = Json_decode(need::teacher_curl('http://'.$host.'/API/Group_Nick/?QQ='.$QQ.'&Skey='.$Skey.'&Pskey='.$Pskey.'&uin='.$Uin.'&Group='.$Group),true)['data'];
$g_l = $title['level']?:'查询失败';
$g_n = $title['name']?:'查询失败';
/* End */
/* QQ等级 */
$QQLevel = Json_decode(need::teacher_curl('http://'.$host.'/QQ_level.api?&uin='.$Uin.'&Group='.$Group),true)['data']['Level']?:1;
/* End */
/* 背景图 */
$image = new imagick();
$image->readImage('./Bg/'.$QQLevel.'.png');//实例化背景
$image->setformat('png');//设置为png
/* End */
/* 实例化字体 */
$draw = new imagickdraw();//实例化文本
$draw->setfont($font);//设置字体
$draw->setfontsize(50);//字体大小
$draw->setfillcolor('black');//颜色
$name = autowrap(50,0,$font,$name,900);
if(substr_count($name,"\n") >= 3){
    $name_height = 50;
}else
if(substr_count($name,"\n") >= 2){
    $name_height = 100;
}else{
    $name_height = 150;
}
$draw->annotation(800,$name_height,$name);//写入字体
$image->drawImage($draw);//字体与图片合并
$draw->setfontsize(30);//字体大小
$draw->setfillcolor('rgb(105,105,105)');//颜色
$draw->annotation(975,329,$g_l);//写入文本
$draw->annotation(1000,388,$g_n);
$yiyan = autowrap(30,0,$font,$yiyan,900);
$draw->annotation(810,480,$yiyan);
$image->drawimage($draw);
$draw->setfillcolor('black');
//$draw->annotation(1236,230,$Uin);
//$draw->annotation(810,440,'群聊等级：'.$g_l);
$image->drawimage($draw);
/* End */
/* 小翅膀+QQ号 */
/*
$chap = new imagick('./chap.png');
$chap->setformat('png');
$image->compositeimage($chap,Imagick::COMPOSITE_DEFAULT,1200,200);
$chap->rotateImage('',90);
$len = strlen($Uin);
if($one_num){
    $len = ((($len - $one_num)* 18) + ($one_num  * 10));
}else{
    $len = ($len * 18);
}
$image->compositeimage($chap,Imagick::COMPOSITE_DEFAULT,(1236+$len),200);*/
/* End */
/* 业务图标 */
/*
$info = Json_decode(need::teacher_curl('http://'.$host.'/API/QQ_info/?uin='.$Uin),true);
if($info['code'] == 1){
    $count = count($info['data']);
    $height = 0;
    $width = 0;
    $i = 1;
    foreach($info['data'] as $k=>$v){
        $Icon = $v['Icon'];
        try{
            $imagick = new imagick($Icon);
        } catch (\Exception $e){
            $String = need::teacher_curl($Icon);
            file_put_Contents(__DIR__.'/'.md5($Icon),$String);
            $imagick = new imagick(__DIR__.'/'.md5($Icon));
        } catch (\Error $e){
            break;
        }
        $imagick->setformat('png');
        $imagick->resizeImage(100,30,Imagick::FILTER_LANCZOS,1);
        $w = $imagick->getimagewidth();
        if($width >= 300){
            $i++;
            $line = (1100 + ($width * 1.2));
            $width = 0;
        }else{
            $width = $width?:$w;
            $line = (1100 + ($width * 1.2));
        }
        $width = ($width + $imagick->getimagewidth());
        if($imagick->getimageheight() > $height){
            $height = $imagick->getimageheight();
        }
        $image->compositeimage($imagick,Imagick::COMPOSITE_DEFAULT,$line,(200+(($i * 2) * $height)));
        unset($imagick);
    }
}*/
/* QQ头像 */
try{
    $header = new imagick($file_name);
} catch (\Exception $e){
    unlink($file_name);
    $imagick = new imagick('./not_Uin.jpg');
    echo $imagick->getImageBlob();
    exit();
}
$header->setformat('png');
$header->resizeImage(640,640,Imagick::FILTER_LANCZOS,1);
$image->compositeimage($header,Imagick::COMPOSITE_DEFAULT,0,0);
/* End */
//$image->compositeimage($canvas,Imagick::COMPOSITE_DEFAULT,1100,30);
/* 获取大致颜色并画边框 */
$header->quantizeImage( 10, Imagick::COLORSPACE_RGB, 0, false, false );
$header->uniqueImageColors();
$colorarr = GetImagesColor($header);
foreach($colorarr as $val){
    $r += $val['r'];
    $g += $val['g'];
    $b += $val['b'];
}

$r = round($r/6);
$g = round($g/6);
$b = round($b/6);
//header('Content-type:Application/Json');
//print_r($colorarr);exit("rgb({$r},{$g},{$b})");
$image->BorderImage("rgb({$r},{$g},{$b})",10,10);
/* End */
header('Content-type:image/png');
echo $image->getImageBlob();
unlink($file_name);

function autowrap($fontsize, $angle, $fontface, $string, $width) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
    //$string = str_replace(array('\\n',"\n"),"\n",$string);
	$content = "";
	$letter = [];
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

function GetImagesColor( Imagick $im ){
    $colorarr = array();
    $it = $im->getPixelIterator();
    $it->resetIterator();
    $i = 0;
    while( $row = $it->getNextIteratorRow() ){
        $i++;
        foreach ( $row as $pixel ){
            $colorarr[] = $pixel->getColor();
        }
        if($i>=3){
            break;
        }
    }
    return $colorarr;
}

