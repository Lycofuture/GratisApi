<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(104); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

require '../image.php';//引入图片处理函数

$QQ = @$_REQUEST['QQ'];

if(!need::is_num($QQ)) {

exit(need::json(array('code'=>-1, 'text'=>'缺少参数')));

}


$image = __DIR__.'/diu.png';

$detail = getimagesize($image); //获取图片详情

//echo json_encode($detail, 320);

//exit();

$image_type = image_type_to_extension($detail[2], false);//获取图片类型


$image_form = "imagecreatefrom{$image_type}";//函数

$image = $image_form($image);//创建图片

$tou = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=5';//头像链接

$img_h = intval($detail[1] / 3 -28);

//$time = time();

$array = [90, 180, 270, 0];

$rand = array_rand($array, 1);

$rand = $array[$rand];

//$tou = $QQ.'jpg';

$tou = getCircleAvatar($tou, $img_h, $rand);

imagecopy($image, $tou, 14, intval($detail[1] / 3) +8, 0, 0, $img_h, $img_h);

$image_output = "image{$image_type}";

imagesavealpha($image,  true);

header('content-type:'.$detail['mime']);

$image_output($image);

imagedestroy($image);

imagedestroy($tou);




function imageAvatar($url,  $size = 128, $angle=0)

{

//$detail = getimagesize($url);

//$type = image_type_to_extension($detail[2], false);

$type = need::teacher_curl($url, ['refer'=>1]);

$original_string = "imagecreatefromstring";

$avatarResource = $original_string($type);
$jpeg = @$_REQUEST['QQ'].'.png';
imagepng(imagerotate($avatarResource ,  $angle, 0), $jpeg);



//$avatarResource = scaleImg($jpeg, 640, 640);

//$avatarResource = imagecreatefrompng($jpeg);

/*$imageSize = getimagesizefromstring($original_string);

$width = $imageSize[0];

$height = $imageSize[1];*/

$width = imagesx($avatarResource);

$height = imagesy($avatarResource);

$w = $h = $size;

$squareAvatarResource = imageCenterCrop($avatarResource,  $w,  $h,  $width,  $height);

$newAvatarResource = imagecreatetruecolor($w,  $h);

imagealphablending($newAvatarResource,  false);

$transparent = imagecolorallocatealpha($newAvatarResource,  0,  0,  0,  127);

$r = $w / 2;

for ($x = 0; $x < $w; $x++) {
for ($y = 0; $y < $h; $y++) {
$c = imagecolorat($squareAvatarResource,  $x,  $y);

$_x = $x - $w / 2;

$_y = $y - $h / 2;

if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
imagesetpixel($newAvatarResource,  $x,  $y,  $c);

} else {
imagesetpixel($newAvatarResource,  $x,  $y,  $transparent);

}

}

}

imagesavealpha($newAvatarResource,  true);

imagedestroy($squareAvatarResource);

/*imagepng($newAvatarResource);

imagedestroy($newAvatarResource);*/


return $newAvatarResource;



}

     function scaleImg($picName,  $maxx = 800,  $maxy = 450)
    {
        $info = getimageSize($picName);//获取图片的基本信息
        $w = $info[0];//获取宽度
        $h = $info[1];//获取高度

        if($w<=$maxx&&$h<=$maxy){
            return $picName;
        }
        //获取图片的类型并为此创建对应图片资源
        switch ($info[2]) {
            case 1://gif
                $im = imagecreatefromgif($picName);
                break;
            case 2://jpg
                $im = imagecreatefromjpeg($picName);
                break;
            case 3://png
                $im = imagecreatefrompng($picName);
                break;
            default:
                die("图像类型错误");
        }
        //计算缩放比例
        if (($maxx / $w) > ($maxy / $h)) {
            $b = $maxy / $h;
        } else {
            $b = $maxx / $w;
        }
        //计算出缩放后的尺寸
        $nw = floor($w * $b);
        $nh = floor($h * $b);
        //创建一个新的图像源（目标图像）
        $nim = imagecreatetruecolor($nw,  $nh);

        //透明背景变黑处理
        //2.上色
        $color=imagecolorallocate($nim, 255, 255, 255);
        //3.设置透明
        imagecolortransparent($nim, $color);
        imagefill($nim, 0, 0, $color);


        //执行等比缩放
        imagecopyresampled($nim,  $im,  0,  0,  0,  0,  $nw,  $nh,  $w,  $h);
        //输出图像（根据源图像的类型，输出为对应的类型）
 //       $picInfo = pathinfo($picName);//解析源图像的名字和路径信息
        $savePath =@$_REQUEST['QQ'].'.png';
        switch ($info[2]) {
            case 1:
                imagegif($nim,  $savePath);
                break;
            case 2:
                imagejpeg($nim,  $savePath);
                break;
            case 3:
                imagepng($nim,  $savePath);
                break;

        }
        //释放图片资源
        imagedestroy($im);
        imagedestroy($nim);
        //返回结果
        return $savePath;
    }



