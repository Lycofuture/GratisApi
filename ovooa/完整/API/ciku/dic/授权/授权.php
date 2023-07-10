<?php
include "./assist/parameter.php";
include_once "./dic/扩展/权限.php";
$Authstatus = dic_DataRead("授权状态", $robot, "text");
if($auth==0){
echo "QR QRSpeed 授权用户专用";
exit;
}elseif (preg_match("/^授权 ?(.*)\$/", $msgtext, $match)) {
    if ($Authstatus < 100) {
        if (authcode($match[1], 'DECODE', SYS_KEY, 0) == $robot) {
            dic_DataWrite("授权状态", $robot, 100);
            echo "主人QQ：" . $thehost;
            echo "\n";
            echo "机器人QQ：" . $robot;
            echo "\n";
            echo "授权成功";
            echo "\n";
            echo "提示：全部功能授权";
        } else {
            echo "授权码错误";
            echo "\n";
            echo "主人QQ：" . $thehost;
            echo "\n";
            echo "提示：只有主人才能授权";
        }
    } else {
        echo "主人QQ：" . $thehost;
        echo "\n";
        echo "机器人QQ：" . $robot;
        echo "\n";
        echo "当前机器人已授权";
        echo "\n";
        echo "提示：请放心授权";
    }
    exit;
} elseif ($msgtext == "删除授权" || $msgtext == "取消授权") {
    if ($Authstatus >= 100) {
        if ($thehost == $sendid) {
            dic_DataWrite("授权状态", $robot, 0);
            echo "主人QQ：" . $thehost;
            echo "\n";
            echo "机器人QQ：" . $robot;
            echo "\n";
            echo $msgtext . "成功";
            echo "\n";
            echo "提示：成功移除";
        } else {
            echo "权限不足";
            echo "\n";
            echo "主人QQ：" . $thehost;
            echo "\n";
            echo "提示：只有主人才能" . $msgtext;
        }
    } else {
        echo "主人QQ：" . $thehost;
        echo "\n";
        echo "机器人QQ：" . $robot;
        echo "\n";
        echo "当前机器人未授权";
        echo "\n";
        echo "提示：无需" . $msgtext;
    }
    exit;
} elseif ($msgtext == "生成授权码") {
    if ($Authstatus < 100) {
        if (Admin_Permission($robot, $sendid) > 100) {
           echo "主人QQ：" . $thehost;
           echo "\n";
           echo "机器人QQ：" . $robot;
           echo "\n";
           echo "机器授权码：" . authcode($robot, 'ENCODE', SYS_KEY, 0);
           echo "\n";
           echo "提示：只有才能获取";
        } else {
            echo "权限不足";
            echo "\n";
            echo "主人QQ：" . $thehost;
            echo "\n";
            echo "提示：只有主人才能授权";
        }
    } else {
        echo "主人QQ：" . $thehost;
        echo "\n";
        echo "机器人QQ：" . $robot;
        echo "\n";
        echo "当前机器人已授权";
        echo "\n";
        echo "提示：请放心授权";
    }
   exit;
} elseif ($Authstatus < 100) {
    echo "主人QQ：" . $thehost;
    echo "\n";
    echo "机器人QQ：" . $robot;
    echo "\n";
    echo "机器人未授权";
    echo "\n";
    echo "主人发送：授权";
    echo "\n";
    echo "提示：联系主人授权";
    exit;
}