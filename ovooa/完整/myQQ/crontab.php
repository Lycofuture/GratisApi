<?php
header('Content-type:Application/Json');
// 定时任务
// 调用：http://ip/crontab.php?s=c-water
include "qqbot.class.php";
$MQ_Api = new qqbotTopSdk;
if ($_GET['s'] == 'c') {
    $Robot = 2830877581;
    $array['cookie'] =  $MQ_Api->Api_QQBOT('Api_GetCookies', [$Robot])['data']['ret'];
    $array['cookie'] = $array['cookie'].$MQ_Api->Api_QQBOT('Api_GetTenPayPsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetJuBaoPsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetClassRoomPsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetGroupPsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetZonePsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetQQVIPPsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetQQInfoPsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetBlogPsKey', [$Robot])['data']['ret'].$MQ_Api->Api_QQBOT('Api_GetQQMusicPsKey', [$Robot])['data']['ret'];
    $array = array('Clientkey'=>$MQ_Api->Api_QQBOT('Api_GetClientkey', [$Robot])['data']['ret'],'Uin'=>2830877581);
    echo(Json_encode($array,320));
}
