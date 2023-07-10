<?php 
ignore_user_abort(true);  //关闭浏览器，服务器也能自动执行。
require '../../curl.php';
require '../../need.php';

require ("../function.php"); // 引入函数文件
addApiAccess(133); // 调用统计函数
addAccess();//调用统计函数*/
$url = @$_REQUEST['url'];//'http://gchat.qpic.cn/gchatpic_new/0/0-0-2A441BED47ED18117A2A9D7E98A4252F/0?term=2';
//$url = 'http://gchat.qpic.cn/gchatpic_new/0/0-0-2BF2E29EEE05D750B11D5A6B2E48F059/0';
// echo $url;exit;
$QQ = @$_REQUEST['QQ'];
$year = @$_REQUEST['year']?:16;
if(!is_Numeric($year) || strlen($year) > 2){
	$year = 16;
}
if( strlen($year) < 2){
	$year = '0'.$year;
}
if(!need::is_num($QQ) && empty($url)){
	header('content-type:image/jpeg');
	$image = new imagick(__DIR__.'/tumeile.jpg');
	echo $image;
	exit();
}
if(empty($url) && need::is_num($QQ)){
	$url = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=5';
}
$String = need::teacher_curl($url,['ctime'=>3,'rtime'=>5]);
if(empty($String)){
	header('content-type:image/jpeg');
	$image = new imagick(__DIR__.'/tumeile.jpg');
	echo $image;
	exit();
}

//$image = imagecreatefromString($String);
//imagepng($image,__DIR__.'/'.md5($url).'.png');
file_put_contents(__DIR__.'/'.md5($url).'', $String);
try{
	$image = new Imagick(__DIR__.'/'.md5($url));
}catch (\Exception $e){
	unlink(__DIR__.'/'.md5($url));
	header('content-type:image/jpeg');
	$image = new imagick(__DIR__.'/tumeile.jpg');
	echo $image;
	exit();
}
$format = $image->getImageFormat();
if($format != 'GIF'){
	$image->setimageformat('png');
	$size = $image->getimagegeometry();//获取宽
	$width = $size['width'];//宽度
	$height = $size['height'];//高度
	// $imagick = new imagick(__DIR__.'/hua2.png');
	// $imagick->setimageformat('png');
	// $imagick->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);
	// $image->compositeImage($imagick,Imagick::COMPOSITE_ATOP,0,0);
	$image->modulateImage(100,0,100);
	if(!empty($_REQUEST['year'])){
		$draw = new imagickdraw();
		$draw->setfillcolor('black');
		$draw->setfont(__DIR__.'/1.ttf');
		$draw->setfontsize($width * 0.2);
		$image->annotateImage($draw,$width / 12,$height / 1.19,0,'享年'.$year.'岁');
	}
	header('Content-type: image/png');
	echo $image->getImageBlob();
}else{
	require __DIR__.'/ICP/anquan.php';
	$delay = $_REQUEST['delay']?:5;
	if(!is_numeric($delay) || $delay > 30){
		$delay = 5;
	}
	$image->setimageformat('GIF');//设置图像格式
	$format = $image->coalesceImages();//获取每一帧
	if(count($format) > 40){
		unlink(__DIR__.'/'.md5($url));
		$image = file_get_contents(__DIR__.'/big.jpg');
		header('content-type:image/jpeg');
		echo $image;
		exit();
	}
	//echo count($format);exit;
	$GIF = new imagick();//创建图像
	$GIF->setFormat('gif');//设置图像格式
	foreach($format as $k=>$v){
		$size = $v->getimagegeometry();//获取宽
		$v->setformat('GIF');
		$width = $size['width'];//宽度
		$height = $size['height'];//高度
		// $imagick = new imagick(__DIR__.'/hua.png');//初始化边框图
		// $imagick->setimageformat('GIF');//设置边框图格式
		// $imagick->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);//调整图片大小
		// $v->compositeImage($imagick,Imagick::COMPOSITE_ATOP,0,0);//合并边框图
		$v->modulateImage(100,0,100);//设置为黑白色
		$GIF->addImage($v);//添加到GIF图片上
		$GIF->setImageDelay($delay);//设置播放速度
		// file_put_contents(__DIR__.'/gif/'.md5($url.$k).'.gif',$v);//把每一帧保存起来
		//unset($format[$k]);
		unset($imagick, $size, $width, $height, $v);
	}
	/*
	$count = count($format);
	unset($format,$imagick,$size,$width,$height,$image);
	
	for($i = 0 ; $i < $count ; $i++){
		$img = new imagick();//初始化imagick
		$img->readImage(__DIR__.'/gif/'.md5($url.$i).'.gif');//以本地方式创建
	//	$img->modulateImage(100,0,100);//设置为黑白色
		$img->setformat('gif');//设置为gif
		*/
		/*
		$size = $img->getimagegeometry();//获取宽
		$width = $size['width'];//宽度
		$height = $size['height'];//高度
		$imagick = new imagick(__DIR__.'/hua.png');//初始化边框图
		$imagick->setimageformat('GIF');//设置边框图格式
		$imagick->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);//调整图片大小
		$img->compositeImage($imagick,Imagick::COMPOSITE_ATOP,0,0);//合并边框图
		$img->modulateImage(100,0,100);//设置为黑白色
		*/
		/*
		$GIF->addImage($img);//添加到GIF图片上
		$GIF->setImageDelay($delay);//设置播放速度
		unset($img);//消除赋值
	}*/
	header('content-type:image/GIF');
	 $GIF->writeImages(__DIR__.'/'.md5($url).'.gif',true);
	 $image = file_get_contents(__DIR__.'/'.md5($url).'.gif');
	 echo $image;
}
fastcgi_finish_request();//先返回上面的内容
time_sleep_until(time()+10);//延迟10秒后执行下面的命令
unlink(__DIR__.'/'.md5($url).'.png');
unlink(__DIR__.'/'.md5($url).'.gif');
unlink(__DIR__.'/'.md5($url));
for($i = 0 ; $i < count($format) ; $i++){
	unlink(__DIR__.'/gif/'.md5($url.$i).'.gif');
}
?>