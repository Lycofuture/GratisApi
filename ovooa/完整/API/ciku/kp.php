<?php
header("Content-type: image/jpeg");
include "./assist/common.php";
include("./assist/parameter.php");
$read = dic_DataRead("窥屏ip", $groupid, "text");
$ip = real_ip();
$city=get_ip_city($ip);
if (!strstr($read,$ip)) {
dic_DataWrite("窥屏ip", $groupid, $read."IP:".$ip."\n地址:".$city."\n\n");
}
echo get_curl("http://q2.qlogo.cn/headimg_dl?spec=0&dst_uin=1569097443");
?>