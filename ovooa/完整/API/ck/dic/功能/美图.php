<?php

require ('./access/parameter.php');//传入参数

echo '$发送$';

if(preg_match('/^(美图)$/',$msg)){

dic_head();

dic_str();


echo "〔发送〕：看图\r";
echo "〔发送〕：美女\r";
echo "〔发送〕：美腿\r";
echo "〔发送〕：cosplay";

}else

if(preg_match('/^(美女)$/',$msg)){

$data = teacher_curl('http://lkaa.top/API/sjmn/api.php?n=3&shuc=txt');
dic_str();
echo "±img=".$data."±";

}else

if(preg_match('/^(美腿)$/',$msg)){

$data = teacher_curl('http://lkaa.top/API/meitui/api.php?type=text');
$data = JSON_decode($data);
$data = $data->text;
dic_str();
echo "±img=".$data."±";

}else

if(preg_match('/^(cosplay)$/i',$msg)){

$data = teacher_curl('http://lkaa.top/API/cosplay/api.php');
dic_str();
echo "±img=".$data."±";

}else

if(preg_match('/^看图 ?(.*?)$/',$msg,$msg_img)){

if(!$img && !$msg_img[1]){

dic_str();

dic_head();

echo "〔事件〕：看图\n";
echo "〔提示〕：请携带图片或者MD5发送";

}else{

dic_str();

dic_head();

echo "〔事件〕：看图\n";

echo "〔链接〕：http://gchat.qpic.cn/gchatpic_new/0/0-0-".$msg_img[1].$img."/0";

}
}





