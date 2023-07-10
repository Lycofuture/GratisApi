<?php

require ('./access/parameter.php');//传入参数

echo '$发送$';

$data = dic_cache_get('./dic/缓存/违禁.php');

$date = dic_cache_get('./dic/缓存/违禁词.php');

$num = preg_match_all('/"'.jiami("违禁词").'":"(.*?)"/',$date,$textwei);

foreach($textwei[1] as $k=>$v){

$weijinci .= $textwei[1][$k].',';

}//循环获取违禁词

$sea_text = $weijinci."€€";

$sea_text = str_replace(',€€','',$sea_text);//替换掉最后一个循环多余的符号

$sea_text=str_replace(',','|',$sea_text);//替换成“或”

//判断收到的消息
if(preg_match('/^(群管)$/',$msg)){

dic_str();

dic_head();

echo "〔事件〕：群管系统\n";

echo "〔发送〕：查违禁词\n";

echo "〔发送〕：违禁检测开\n";

echo "〔发送〕：违禁检测关\n";

echo "〔发送〕：字数检测开\n";

echo "〔发送〕：字数检测关\n";

echo "〔发送〕：添加违禁词+内容";

}else

if(preg_match('/^(查违禁词)$/',$msg)){

dic_str();

if(!$date){
//判断违禁词列表是否为空
dic_head();

echo "〔事件〕：查违禁词\n";

echo "〔提示〕：违禁词列表空空如也";

}else{

dic_head();

echo "〔事件〕：查违禁词\n";

echo "〔列表〕：\n";

echo $weijinci;

}

}else 

if(preg_match('/^添加违禁词 ?(.*?)$/',$msg,$tianjw)){

dic_str();

if(dic_master()){

if(preg_match('/"'.jiami("违禁词").'":"'.$tianjw[1].'"/',$date)){
//判断违禁词是否存在
dic_head();

echo "〔事件〕：添加违禁词\n";

echo "〔提示〕：添加失败".$tianjw[1]."已存在";
 
}else{

dic_head();

echo "〔事件〕：添加违禁词\n";

echo "〔提示〕：已将违禁词".$tianjw[1]."添加";

dic_store_w("违禁词",$tianjw[1],"违禁词.php");
//通过独立封装函数进行添加违禁词
}}else{

dic_head();

echo "〔事件〕：添加违禁词\n";

echo "〔提示〕：权限不足！";

}

}else

if(preg_match('/^删除违禁词 ?(.*?)$/',$msg,$text_shan)){

dic_str();

if(dic_master()){

if(preg_match('/"'.jiami("违禁词").'":"'.$text_shan[1].'"/',$date)){
//判断违禁词是否存在
dic_head();

echo "〔事件〕：删除违禁词\n";

echo "〔提示〕：已将违禁词".$text_shan[1]."删除";

dic_store_s("违禁词",$text_shan[1],"违禁词.php");

}else{

dic_head();

echo "〔事件〕：删除违禁词\n";

echo "〔提示〕：删除失败".$text_shan[1]."不存在";

}}else{

dic_head();

echo "〔事件〕：删除违禁词\n";

echo "〔提示〕：权限不足！";

}

}else

if(preg_match('/^清空违禁词$/',$msg)){

dic_str();

if(dic_master()){

dic_head();

echo "〔事件〕：清空违禁词\n";

echo "〔提示〕：清空成功！";

unlink('./dic/缓存/违禁词.php');//用系统函数将文件整个删掉

$data = dic_cache_get('./dic/缓存/违禁词.php');//通过封装函数进行访问实行创建空白文件的目的


}else{

dic_head();

echo "〔事件〕：清空违禁词\n";

echo "〔提示〕：权限不足！";

}

}else

if(preg_match('/^设置禁言时间 ?([0-9]+)$/',$msg,$time_text)){

dic_str();

if(dic_master()){

dic_head();

echo "〔事件〕：设置禁言时间\n";

echo "〔时间〕：".$time_text[1]."分钟\n";

echo "〔提示〕：设置成功！";

dic_open("禁言时间",$time_text[1],"违禁.php");//通过封装函数进行储存时间

}else{

dic_head();

echo "〔事件〕：设置禁言时间\n";

echo "〔提示〕：权限不足！";

}

}else

if(preg_match('/^违禁检测 ?(开|关)$/',$msg,$data_key)){

dic_str();

dic_head();

if(dic_master()){

if($data_key[1]=="开"){

echo "〔事件〕：开启违禁检测\n";

echo "〔提示〕：开启成功";

dic_open("违禁检测","开","违禁.php");///同上一条

}else{

echo "〔事件〕：关闭违禁检测\n";

echo "〔提示〕：关闭成功";

dic_open("违禁检测","关","违禁.php");//同上一条

}
}else{



echo "〔事件〕：开关违禁检测\n";

echo "〔提示〕：权限不足！";

}



}else

if(preg_match('/^(.*?)('.$sea_text.')(.*?)$/',$msg,$preg_sea)){

$date_text=dic_cache_get('./dic/缓存/违禁.php');//读取设置

preg_match('/"'.jiami("禁言时间").'":"([0-9]+)"/',$date_text,$date_time);//读取时间

preg_match('/"'.jiami('违禁检测').'":"(.*?)"/',$data,$preg_key);//读取开关

$preg_key = $preg_key[1];//读取开关

if($preg_key=="开" || !$preg_key){
//判断是否为开

if(!dic_admin() || !dic_master()){

dic_str();

dic_head();

if(dic_admin_Robot()){

echo "〔事件〕：触发违禁词".$preg_sea[2]."\n";

echo "〔提示〕：执行禁言";

$date_time=intval($date_time[1]*60);//读取时间

echo '$禁 %群号% %QQ% '.$date_time.'$';//进行禁言

}else

if(!dic_admin_Robot()){

echo "〔提示〕：您触发了违禁词".$preg_sea[2]."\n";

echo "〔提示〕：我不是管理员无法制裁你！岂可修！";

}
}else{

dic_str();

dic_head();

echo "〔提示〕：触发违禁词".$preg_sea[2]."\n";

echo "〔提示〕：你竟然有权限！岂可修！";

}

}else{}

}else

if(preg_match('/^字数检测 ?(开|关)$/',$msg,$Switch)){

dic_str();

dic_head();

if(dic_master()){

if($Switch[1]=="开"){

dic_open("字数检测开关","开","违禁.php");

echo "〔事件〕：开启字数检测\n";

echo "〔提示〕：已开启字数检测";

}else

if($Switch[1]=="关"){

dic_open("字数检测开关","关","违禁.php");

echo "〔事件〕：关闭字数检测\n";

echo "〔提示〕：已关闭字数检测";

}

}else{

echo "〔事件〕：开关字数检测\n";

echo "〔提示〕：权限不足！";

}

}else

if(preg_match('/^禁@.* ([0-9]+)$/',$msg,$msg_time)){

if(!$at){

echo "〔事件〕：禁言\n";

echo "〔提示〕：请不要复制@";

}else

if(dic_admin() || dic_master()){

if(dic_admin_Robot){

echo "〔事件〕：禁言\n";

echo "〔被禁〕：\$群昵称 %群号% ".$at."\$\n";

echo "〔被禁〕：".$at."\n";

echo "〔提示〕：活该";

echo '$禁 %群号% '.$at.' '.intval($msg_time[1]*60).'$';

}else{

echo "〔事件〕：禁言\n";

echo "〔被禁〕：\$群昵称 %群号% ".$at."\$\n";

echo "〔被禁〕：".$at."\n";

echo "〔提示〕：我没权限…";

}
}else{

echo "〔事件〕：禁言\n";

echo "〔提示〕：你没有权限";

}

}else

if(preg_match('/^解@.*$/',$msg)){

if(!$at){

echo "〔事件〕：解除禁言\n";

echo "〔提示〕：请不要复制@";

}else

if(dic_admin() || dic_master()){

if(dic_admin_Robot){

echo "〔事件〕：解除禁言\n";

echo "〔解禁〕：\$群昵称 %群号% ".$at."\$\n";

echo "〔解禁〕：".$at."\n";

echo "〔提示〕：么么叽";

echo '$禁 %群号% '.$at.' 0$';

}else{

echo "〔事件〕：解除禁言\n";

echo "〔解禁〕：\$群昵称 %群号% ".$at."\$\n";

echo "〔解禁〕：".$at."\n";

echo "〔提示〕：我没权限…";

}
}else{

echo "〔事件〕：解除禁言\n";

echo "〔提示〕：你没有权限";

}
}else

if($msg){

$data = dic_cache_get('./dic/缓存/违禁.php');

preg_match('/"'.jiami("禁言时间").'":"([0-9]+)"/',$data,$date_time);//读取时间

$date_time=intval($date_time[1]*60);//读取时间

preg_match('/"'.jiami("字数检测开关").'":"(.*?)"/',$data,$Switch_msg);

$Switch_msg = $Switch_msg[1];

if(!$Switch_msg || $Switch_msg=="开"){

if(!preg_match('/"'.jiami("字数检测").'":"([0-9]+)"/',$data,$msg_num)){

dic_open("字数检测","200","违禁.php");

}else

$num = mb_strlen($msg, 'UTF-8');

if($msg_num[1] > $num){}else{

if(!dic_admin() || !dic_master()){

if(dic_admin_Robot()){//判断机器人是否是管理群主

dic_str();

dic_head();

echo "〔事件〕：字数检测\n";

echo "〔提示〕：当前字数：".$num."\n";

echo "〔提示〕：设置字数：".$msg_num[1]."\n";

echo "〔提示〕：您的字数已超标！\n";

echo "〔提示〕：立即执行禁言！";

echo '$禁 %群号% %QQ% '.$date_time.'$';//进行禁言

}else

if(!dic_admin_Robot()){//如果机器人不是群主或者管理

dic_str();

dic_head();

echo "〔事件〕：字数检测\n";

echo "〔提示〕：当前字数：".$num."\n";

echo "〔提示〕：设置字数：".$msg_num[1]."\n";

echo "〔提示〕：您的字数已超标！\n";

echo "〔提示〕：但是我没办法禁言你！岂可修";

}

}else{

dic_str();

dic_head();

echo "〔事件〕：字数检测\n";

echo "〔提示〕：当前字数：".$num."\n";

echo "〔提示〕：设置字数：".$msg_num[1]."\n";

echo "〔提示〕：您的字数已超标！\n";

echo "〔提示〕：但是我没办法禁言你！岂可修";

}

}

}

}









