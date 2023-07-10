<?php
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(105); // 调用统计函数
addAccess();//调用统计函数
/* End */
require ('../../need.php');//引用封装好的函数文件
require '../image.php';//引入图片处理函数
$QQ = @$_REQUEST['QQ'];
if(!need::is_num($QQ)){
    exit(need::json(array('code'=>-1, 'text'=>'缺少参数')));
}
if(!need::is_num($QQ)){
    exit(need::json(array('code'=>-2, 'text'=>'参数不正确')));
}
$image = need::read_all('./pa1', 'png', 'jpg', 'jpeg');//原图
$rand = array_rand($image);
$image = __DIR__.'/pa1/'.$image[$rand]['name'];
//echo '<title>';
//echo $image;
//echo '</title>';
$detail = getimagesize($image); //获取图片详情
//echo json_encode($detail, 320);
//exit();
$image_type = image_type_to_extension($detail[2], false);//获取图片类型
$image_form = "imagecreatefrom{$image_type}";//函数
$image = $image_form($image);//创建图片
$tou = 'http://q2.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=5';//头像链接
$tou = getCircleAvatar($tou, ($detail[1] / 6));
imagecopy($image, $tou, 4, intval($detail[1] - ($detail[1] / 5)) ,  0, 0, intval($detail[1]/6), intval($detail[1]/6));
$image_output = "image{$image_type}";
// echo $detail['mime'];
header('content-type: '.$detail['mime'].';');
$image_output($image);
imagedestroy($image);
imagedestroy($tou);
