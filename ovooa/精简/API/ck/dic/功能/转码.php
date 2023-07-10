<?php

/*编码而已*/

require ('./access/parameter.php');//传入参数

echo '$发送$';

if(preg_match('/^(转码|工具)$/',$msg)){

dic_str();

dic_head();

echo "〔发送〕：加密+内容\n";

echo "〔发送〕：解密+内容\n";

echo "〔发送〕：b64编码+内容\n";

echo "〔发送〕：b64解码+内容\n";

echo "〔发送〕：URL编码+内容\n";

echo "〔发送〕：URL解码+内容";

}else

if(preg_match('/^加密 ?(.*?)$/',$msg,$text_msg)){

$text_msg = $text_msg[1];

if($text_msg==''){

dic_str();

dic_head();

echo "〔事件〕：加密内容\n";

echo "〔提示〕：请输入需要加密的内容";

}else{

dic_str();

echo jiami($text_msg);

}

}else

if(preg_match('/^解密 ?(.*?)$/',$msg,$text_msg)){

$text_msg = $text_msg[1];

if($text_msg==''){

dic_str();

dic_head();

echo "〔事件〕：解密内容\n";

echo "〔提示〕：请输入需要解密的内容";

}else{

dic_str();

echo jiemi($text_msg);

}

}else

if(preg_match('/^b64编码 ?(.*?)$/i',$msg,$text_msg)){

$text_msg = $text_msg[1];

if($text_msg==''){

dic_str();

dic_head();

echo "〔事件〕：base64编码\n";

echo "〔提示〕：请输入需要编码的内容";

}else{

dic_str();

echo base64_encode($text_msg);

}

}else

if(preg_match('/^b64解码 ?(.*?)$/i',$msg,$text_msg)){

$text_msg = $text_msg[1];

if($text_msg==''){

dic_str();

dic_head();

echo "〔事件〕：base64解码\n";

echo "〔提示〕：请输入需要解码的内容";

}else{

dic_str();

echo base64_decode($text_msg);

}
}else

if(preg_match('/^URL编码 ?(.*?)$/i',$msg,$text_msg)){

$text_msg = $text_msg[1];

if($text_msg==''){

dic_str();

dic_head();

echo "〔事件〕：URLEncoder编码\n";

echo "〔提示〕：请输入需要编码的内容";

}else{

dic_str();

$URLencode=URLencode($text_msg);

$URLencode = str_replace('%','％',$URLencode);//替换成中文或者其他的

echo '$替换 @ '.$URLencode.'@％@%25@$';//QR词库的尿性

}

}else

if(preg_match('/^URL解码 ?(.*?)$/i',$msg,$text_msg)){

$text_msg = $text_msg[1];

if($text_msg==''){

dic_str();

dic_head();

echo "〔事件〕：URLDecoder解码\n";

echo "〔提示〕：请输入需要解码的内容";

}else{

dic_str();

echo URLdecode($text_msg);

}
}










