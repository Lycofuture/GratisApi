<?php
//set_time_limit(0);
//error_reporting(0);
$MQ_MSG = file_get_contents("php://input");
$MQ_MSG_JSON = json_decode($MQ_MSG, true);
$MQ_MSG_JSON['MQ_msg'] = urldecode($MQ_MSG_JSON['MQ_msg']);

//日志，生产环境关闭
// $fp = fopen("log.txt", "a");
// flock($fp, LOCK_EX);
// fwrite($fp, $MQ_MSG . "\t\n");
// flock($fp, LOCK_UN);
// fclose($fp);
//默认忽略自己的消息，若有插件需要就移到插件里面去

if ($MQ_MSG_JSON['MQ_robot'] == $MQ_MSG_JSON['MQ_fromQQ']) {
    //return;
}
require '../Newneed.php';
include "qqbot.class.php";
$include_api = new qqbotTopSdk;
$pluginManager = new PluginManager();

$MQ_NEW_MSG = [
    "MQ_Api" => $include_api,
    'MQ_robot' => @$MQ_MSG_JSON['MQ_robot'],
    'MQ_type' => @$MQ_MSG_JSON['MQ_type'],
    'MQ_type_sub' => @$MQ_MSG_JSON['MQ_type_sub'],
    'MQ_fromID' => @$MQ_MSG_JSON['MQ_fromID'],
    'MQ_fromQQ' => @$MQ_MSG_JSON['MQ_fromQQ'],
    'MQ_passiveQQ' => @$MQ_MSG_JSON['MQ_passiveQQ'],
    'MQ_msg' => @$MQ_MSG_JSON['MQ_msg'],
    'MQ_msgSeq' => @$MQ_MSG_JSON['MQ_msgSeq'],
    'MQ_msgID' => @$MQ_MSG_JSON['MQ_msgID'],
    'MQ_msgData' => @$MQ_MSG_JSON['MQ_msgData'],
    'MQ_timestamp' => @$MQ_MSG_JSON['MQ_timestamp']
];
$pluginManager->trigger("Plugin", $MQ_NEW_MSG);
if(preg_match('/^图 ?(.+)$/', $MQ_NEW_MSG['MQ_msg'], $Msg) && ($MQ_NEW_MSG['MQ_fromQQ'] == 2354452553 || $MQ_NEW_MSG['MQ_fromQQ'] == 2936354837)){
    //require '../Newneed.php';
    $Group = $MQ_NEW_MSG['MQ_fromID'];
    $Uin = $MQ_NEW_MSG['MQ_fromQQ'];
    file_put_Contents(__DIR__.'./1.jpg', need::teacher_curl($Msg[1]));
    $a = $include_api->Api_QQBOT('Api_UpLoad', [2830877581, __DIR__.'\\1.jpg']);
    $a = $include_api->Api_QQBOT('Api_SendMsgEx', [2830877581, 2, $Group, $Uin, '好', 0]);
    file_put_Contents(__DIR__.'./1.json', need::json($a));
    @unlink(__DIR__.'\\1.jpg');
}
