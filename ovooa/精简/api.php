<?php
header('Content-type:application/json');
require './need.php';
$data = need::teacher_curl($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/Data/api.php?type=getAdminInfo&apikey=3367468');
$json = json_decode($data, true);
// print_r($data);exit;
$api = $json["data"]["api"];//接口数量
$spider = $json["data"]["spider"];//蜘蛛总数量
$spider_data1 = $json["data"]["spider_data"][3];//昨日蜘蛛
$spider_data = $json["data"]["spider_data"][4];//今日蜘蛛
$post = $json["data"]["post"];//公告数量
$link = $json["data"]["link"];//友情链接数量
$feedback = $json["data"]["feedback"];//反馈数量
$access = ($json["data"]["access"] >= 10000) ? round(($json["data"]["access"] / 10000), 2).'万' :$json["data"]["access"];//总调用
$access_datat = ($json["data"]["access_data"][3] >= 10000) ? round(($json["data"]["access_data"][3] / 10000), 2) .'万' : $json["data"]["access_data"][3];//昨日调用
$access_data = ($json["data"]["access_data"][4] >= 10000) ? round(($json["data"]["access_data"][4] / 10000), 2) . '万' : $json["data"]["access_data"][4];//今日调用
echo '┏地址：'.$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
echo "\n";
echo '┣本站接口：' . $api . '个';
echo "\n";
echo '┣本月爬虫：'.$spider.'个';
echo "\n";
echo '┣今日爬虫：'.$spider_data.'个';
echo "\n";
if($feedback){
    echo '┣已有反馈'.$feedback.'个';
    echo "\n";
    echo '┣请注意查看反馈';
    echo "\n";
}
echo !$access ? '' : '┣本月调用：'.$access.'次';
echo "\n";
echo '┣今日调用：'.$access_data.'次';
echo "\n";
echo (!$access_datat) ? '' : '┣昨日调用：'.$access_datat.'次'. "\n";

// echo '┣昨日对比：'. (!$json['data']['access_data'][3] ? '100%' : round((((($json["data"]["access_data"][4] ?: 1) - ($json["data"]["access_data"][3] ?: 0)) / ($json["data"]["access_data"][3] ?: 1)) * 100), 2).'%');
echo '┣是昨日的：'.  (!$json['data']['access_data'][3] ? '100%' : round((($json["data"]["access_data"][4] ?: 1) / ($json["data"]["access_data"][3] ?: 1) * 100), 2).'%');
echo "\n";
echo '┗友情链接：'.$link.'个';
?>