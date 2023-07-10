<?php
include "./fanfa/parameter.php";
include_once "./dic/扩展/ping.php";
include_once "./dic/扩展/头部.php";
if ($msgtext == "网络系统") {
    dic_headBasic();
    echo "Ping [域名/IP]";
} elseif (preg_match("/^(ping|Ping) ?(.*)\$/", $msgtext, $match)) {
    $array = ping_time($match[2]);
    dic_head();
    echo "[域名/IP]：" . $match[2];
    echo "\n";
    echo "[Ping-最小]：" . $array["ping_min"] * 1000;
    echo "\n";
    echo "[Ping-最大]：" . $array["ping_max"] * 1000;
    echo "\n";
    echo "[Ping-平均]：" . $array["ping_avg"] * 1000;
}