<?php
Header('content-type: application/json');
// require '../function.php';
require './Meting.php';
require '../../need.php';
$array = array('netease', 'tencent', 'xiami', 'kugou', 'baidu', 'kuwo');/*[
    'tencent',
    'netease',
    'baidu',
    'xiaomi',
    'kugou',
    'kuwo'
];*/
$Request = need::request();
$type = @$Request['type'] ?: 'netease';
$Msg = @$Request['Msg']?:@$Request['msg'];
$search = isset($Request['search']) ? json_decode($Request['search'], true) : [];
if(!in_array($type, $array)){
    need::send(array('Message'=>false));
}
use Metowolf\Meting;
$Meting = new Meting($type);
$format = @$Request['format']?:'search';
method_exists($Meting, $format) ? $format = $format : $format = 'search';
$Message = json_decode($Meting->format(true)->$format($Msg, $search), true);
if(!$Message){
    need::send(array('Message'=>false));
}else{
    need::send($Message);
}
