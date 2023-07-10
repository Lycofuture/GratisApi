<?php
include("./assist/parameter.php");//传入变量
include_once "./dic/扩展/权限.php";//传入函数
include_once "./dic/扩展/货币.php";
include_once "./dic/扩展/头部.php";
if ($msgtext == "读写测试") {
echo dic_DataRead("窥屏ip", $groupid . "_" . $robot, "text");//读取文件(text格式)
//echo dic_DataRead("管理员","497441193","json");//读取文件(json格式)
dic_DataWrite("管","497441193","http://dic.xaoxin.cn/kp.php?groupid=1&robot=1");//写入文件
}elseif ($msgtext == "权限测试") {
echo Admin_Permission($robot,$sendid);//权限代码
}elseif ($msgtext == "访问测试") {
echo get_curl("http://baidu.com");
}elseif ($msgtext == "ip测试") {
echo real_ip();
}elseif ($msgtext == "ip归属测试") {
$ip=real_ip();
echo get_ip_city($ip);
}elseif ($msgtext == "版本") {
echo VERSION;
}elseif ($msgtext == "取中间测试") {
echo getSubstr("欢迎使用小鑫网络词库。","使用","。");
}elseif ($msgtext == "测") {
$archive = new PclZip("archive.zip");
$list = $archive->add("/", PCLZIP_OPT_NO_COMPRESSION);
echo $list;
}elseif ($msgtext == "窥屏测试") {
echo 'card:1<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><msg serviceID="15" templateID="1" action="web" brief="推荐群聊：小鑫科技" sourceMsgId="0" url="https://jq.qq.com/?_wv=1027&amp;k=5xuxH1U" flag="0" adverSign="0" multiMsgFlag="0"><item layout="0" mode="1" advertiser_id="0" aid="0"><summary>推荐群聊</summary><hr hidden="false" style="0" /></item><item layout="2" mode="1" advertiser_id="0" aid="0"><picture cover="http://qr.baiyun.ml/kp.php?groupid='.$groupid.'" w="0" h="0" needRoundView="0" /><title>窥屏检测</title><summary>欢迎使用窥屏检测</summary></item><source name="" icon="" action="" appid="-1" /></msg>';
echo "\$调用 10000 窥屏检测1\$";
}elseif ($msgtext == "窥屏检测1") {
echo dic_DataRead("窥屏ip", $groupid, "text");
dic_DataWrite("窥屏ip", $groupid, "");
}elseif (preg_match("/^去标签 ?(.*)\$/", $msgtext, $match)) {
echo strip(get_curl($match[1]));
}elseif (preg_match("/^(访问|访|get) ?(.*)\$/i", $msgtext, $match)) {
echo get_curl($match[2]);
}elseif (preg_match("/^语音 ?(.*)\$/i", $msgtext, $match)) {
echo "\$发送 群 ptt ".$groupid." -1 ".$match[1]."\$";
}elseif (preg_match("/^(测速|网站测速) ?(.*)\$/", $msgtext, $match)) {
set_time_limit(0);
$se = explode(' ',microtime());
$ntime = $se[0] + $se[1];
$shell=get_curl($match[2]);
$se1=explode(' ',microtime());
$etime=$se1[0] + $se1[1];
$htime=$etime - $ntime;
$hstime=round($htime,3);
dic_head();
echo "加载的网站:{$match[2]}\n用时(秒):{$hstime}";
}elseif (preg_match("/^ip归属查询 ?(.*)\$/", stripslashes($msgtext), $match)) {
echo get_ip_city($match[1]);
}elseif (preg_match("/^转json ?([\s\S]*?)\$/", stripslashes($msgtext), $match)) {
echo "json:".$match[1];
}elseif (preg_match("/^执行 ?([\s\S]*?)\$/", stripslashes($msgtext), $match)) {
echo $match[1];
}elseif (preg_match("/^域名估价 ?(.*)\$/", $msgtext, $match)) {
$json=get_curl("https://www.ymw.cn//domainevaluate/getdomainprice.html?domain=".$match[1]);
$array=json_decode($json,true);
$code=$array[map];
echo "您正在申请评估域名是 ：".$match[1];
echo "\n";
echo "系统评估价值 ≈ ".$array[domainprice]."元";
echo "\n";
echo "alexa排名:".$code[alexa];
echo "\n";
echo "日均IP:".$code[ip];
echo "\n";
echo "日均PV:".$code[pv];
echo "\n";
echo "谷歌PR:".$code[googlePR];
echo "\n";
echo "百度权重:".$code[baiduQz];
echo "\n";
echo "谷歌收录:".$code[googleSite];
echo "\n";
echo "百度收录:".$code[baiduSite];
echo "\n";
echo "360收录:".$code[s360Site];
}
