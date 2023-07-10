<?php
include "./assist/parameter.php";
include_once "./dic/扩展/权限.php";
include_once "./dic/扩展/头部.php";
if ($msgtext == "群管系统") {
    dic_headBasic();
    echo "踢 [QQ]\r\n禁 [QQ] [时间]\r\n踢 @AT\r\n禁 @AT [时间]\r\nPS：支持@";
} elseif (preg_match("/^(禁|禁言) ?([0-9]+) ([0-9]+)$/", $msgtext, $match)) {
    if (Admin_Permission($robot, $sendid) >= 80) {
        $jtime = $match[3] * 60;
        dic_head();
        echo "\$禁 {$groupid} {$match[2]} {$jtime}\$";
        echo "成功禁言：{$match[2]}";
        echo "\n";
        echo "禁言时间：[{$match[3]}]分钟";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：".Admin_Permission($robot, $sendid);
    }
} elseif (preg_match("/^(解|解言) ?([0-9]+)$/", $msgtext, $match)) {
    if (Admin_Permission($robot, $sendid) >= 80) {
        $jtime = 0;
        dic_head();
        echo "\$禁 {$groupid} {$match[1]} {$jtime}\$";
        echo "成功解禁：{$match[1]}";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：".Admin_Permission($robot, $sendid);
    }
} elseif (preg_match("/^(禁|禁言) ?@.* ([0-9]+)$/", $msgtext, $match)) {
    if (Admin_Permission($robot, $sendid) >= 80) {
        $jtime = $match[2] * 60;
        dic_head();
        echo "\$禁 {$groupid} %AT0% {$jtime}\$";
        echo "成功禁言：%AT0%";
        echo "\n";
        echo "禁言时间：[{$match[2]}]分钟";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：".Admin_Permission($robot, $sendid);
    }
} elseif (preg_match("/^(解|解言) ?@.*$/", $msgtext, $match)) {
    if (Admin_Permission($robot, $sendid) >= 80) {
        $jtime = 0;
        dic_head();
        echo "\$禁 {$groupid} %AT0% {$jtime}\$";
        echo "成功解禁：{%AT0%}";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：".Admin_Permission($robot, $sendid);
    }
} elseif (preg_match("/^(踢|踢人) ?([0-9]+)$/", $msgtext, $match)) {
    if (Admin_Permission($robot, $sendid) >= 80) {
        dic_head();
        echo "\$踢 {$groupid} {$match[2]}\$";
        echo "成功踢出：{$match[2]}";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：".Admin_Permission($robot, $sendid);
    }
} elseif (preg_match("/^(踢|踢人) ?@.* ?$/", $msgtext, $match)) {
    if (Admin_Permission($robot, $sendid) >= 80) {
        dic_head();
        echo "\$踢 {$groupid} %AT0%\$";
        echo "成功踢出：%AT0%";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：".Admin_Permission($robot, $sendid);
    }
}