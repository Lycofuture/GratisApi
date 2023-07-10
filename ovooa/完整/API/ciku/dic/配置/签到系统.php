<?php
include "./assist/parameter.php";
include_once "./dic/扩展/货币.php";
include_once "./dic/扩展/头部.php";
$date = date("Y-m-d");
$Checkintime = dic_DataRead("签到时间", $groupid . '_' . $sendid, "text");
if ($msgtext == "签到系统") {
    dic_headBasic();
    echo "发送：签到 即可签到";
} elseif (preg_match("/^(签到|打卡|冒牌|打豆豆)\$/", $msgtext)) {
    if (strtotime($Checkintime) < strtotime($date)) {
        $rand = rand(3000, 5000);
        $Checkinranking = dic_DataRead("签到排行", $groupid . "_" . $date, "text");
        $Checkinranking++;
        dic_DataWrite("签到排行", $groupid . "_" . $date, $Checkinranking);
        dic_DataWrite("签到时间", $groupid . '_' . $sendid, $date);
        Currency_Inc($groupid, $sendid, $rand);
        dic_head();
        echo "[签到状态]：签到成功";
        echo "\n";
        echo "[签到排名]：{$Checkinranking}名";
        echo "\n";
        echo "[签到奖励]：" . $rand . Currency_Unit();
        echo "\n";
        echo "[用户" . Currency_Unit() . "]：" . Currency_Reading($groupid, $sendid) . Currency_Unit();
    } else {
        dic_head();
        echo "[签到状态]：签到失败";
        echo "[失败原因]：今日已签到";
    }
}