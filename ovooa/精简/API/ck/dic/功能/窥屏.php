<?php

require ('./access/parameter.php');//传入参数

echo '$发送$';

if(preg_match('/^(窥屏)$/',$msg,$msg_get)){

if(dic_master()){

$rand = rand(1000,9999999);

echo 'card:1<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'yes\' ?><msg serviceID="14" templateID="1" action="plugin" actionData="AppCmd://OpenContactInfo/?uin=2354452553" a_actionData="mqqapi://card/show_pslcard?src_type=internal&amp;source=sharecard&amp;version=1&amp;uin=" i_actionData="mqqapi://card/show_pslcard?src_type=internal&amp;source=sharecard&amp;version=1&amp;uin=" brief="推荐了" sourceMsgId="0" url="" flag="1" adverSign="0" multiMsgFlag="0"><item layout="0" mode="1" advertiser_id="0" aid="0"><summary>推荐联系人</summary><hr hidden="false" style="0" /></item><item layout="2" mode="1" advertiser_id="0" aid="0"><picture cover="http://lkaa.top/API/kuiping/api.php?a='.$group.'b'.$rand.'&amp;type=1" w="0" h="0" /><title>窥屏检测</title><summary>检测中，请等待5秒钟</summary></item><source name="" icon="" action="" appid="-1" /></msg>';

echo '$调用 5200 窥屏结果'.$rand.'$';

}else{

dic_str();

dic_head();

echo "〔事件〕：窥屏检测\n";

echo "〔提示〕：您没有权限";

}

}else

if(preg_match_all('/^窥屏结果 ?([0-9]+)$/',$msg,$msg_num)){

$data = teacher_curl('http://lkaa.top/API/kuiping/api.php?a='.$group.'b'.$msg_num[1][0]);

echo $data;

}




