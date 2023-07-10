<?php
include "./assist/parameter.php";
include_once "./dic/扩展/权限.php";
include_once "./dic/扩展/头部.php";
$selected = dic_DataRead("选歌状态", $robot, "text");
$songswitch = dic_DataRead("选歌开关", $robot, "text");
if ($msgtext == "点歌系统") {
    dic_headBasic();
    echo "点歌 [歌名]";
    echo "\n";
    echo "选歌模式[开/关]";
    echo "\n";
    echo "提示：拥有强大的选歌系统";
} elseif ($msgtext == "选歌模式开") {
    if (Admin_Permission($robot, $sendid) >= 80) {
        dic_DataWrite("选歌状态", $robot, 1);
        dic_DataWrite("选歌开关", $robot, 1);
        dic_head();
        echo $msgtext;
        echo "\n";
        echo "提示：成功打开选歌模式";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：" . Admin_Permission($robot, $sendid);
    }
} elseif ($msgtext == "选歌模式关") {
    if (Admin_Permission($robot, $sendid) >= 80) {
        dic_DataWrite("选歌状态", $robot, 0);
        dic_DataWrite("选歌开关", $robot, 0);
        dic_head();
        echo $msgtext;
        echo "\n";
        echo "提示：成功关闭选歌模式";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：" . Admin_Permission($robot, $sendid);
    }
} elseif (preg_match("/^(设置选歌数|设置选歌数量) ?([0-9]+)\$/", $msgtext, $match)) {
    if (Admin_Permission($robot, $sendid) >= 80) {
        dic_DataWrite("选歌数量", $robot, $match[2]);
        dic_head();
        echo $msgtext;
        echo "\n";
        echo "提示：成功设置选歌数量";
    } else {
        dic_head();
        echo "权限不足";
        echo "\n";
        echo "提示：当前权限：" . Admin_Permission($robot, $sendid);
    }
} elseif (preg_match("/^(点歌|点) ?(.*)\$/", $msgtext, $match) && $songswitch == 0) {
    dic_DataWrite("选歌状态", $robot, 0);
    $str = get_curl("https://c.y.qq.com/soso/fcgi-bin/client_search_cp?aggr=0&cr=1&flag_qc=0&p=1&n=1&w=" . urlencode($match[2]));
    $str = substr($str, 9, -1);
    $json = json_decode($str, true);
    if ($json["code"] == 0) {
        $list = $json["data"]["song"]["list"];
        if (empty($list[0])) {
            dic_head();
            echo "搜索无结果";
            dic_DataWrite("点歌数据", $robot, json_encode($list));
        } else {
            $jsonarray = $list[0];
            $songid = $jsonarray["songid"];
            $albummid = $jsonarray["albummid"];
            $songmid = $jsonarray["songmid"];
            $songname = $jsonarray["songname"];
            $singername = $jsonarray["singer"][0]["name"];
            echo 'json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]' . $songname . '","meta":{"music":{"sourceMsgId":"0","title":"' . $songname . '","desc":"' . $singername . '","preview":"y.gtimg.cn/music/photo_new/T002R150x150M000' . $albummid . '.jpg","tag":"QQ音乐","musicUrl":"https://v1.itooi.cn/tencent/url?id=' . $songmid . '","jumpUrl":"y.qq.com/n/yqq/song/' . $songmid . '.html","appid":100497308,"app_type":1,"action":"","source_url":"url.cn/5aSZ8Gc","source_icon":"url.cn/5tLgzTm","android_pkg_name":"com.tencent.qqmusic"}},"config":{"forward":true,"type":"normal","autosize":true}}';
        }
    } else {
        echo "点歌失败";
        echo "\n";
        echo "提示：code：" . $json["code"];
    }
} elseif (preg_match("/^(点歌|点) ?(.*)\$/", $msgtext, $match) && $songswitch == 1) {
    dic_DataWrite("选歌状态", $robot, 1);
    $num = dic_DataRead("选歌数量", $robot, "text");
    $p = dic_DataRead("点歌页数", $robot, "text");
    dic_DataWrite("点歌页数", $robot, 1);
    dic_DataWrite("点歌内容", $robot, $match[2]);
    $str = get_curl("https://c.y.qq.com/soso/fcgi-bin/client_search_cp?aggr=0&cr=1&flag_qc=0&p=1&n={$num}&w=" . urlencode($match[2]));
    $str = substr($str, 9, -1);
    $json = json_decode($str, true);
    if ($json["code"] == 0) {
        $list = $json["data"]["song"]["list"];
        if (empty($list[0])) {
            dic_head();
            echo "搜索无结果";
            dic_DataWrite("点歌数据", $robot, json_encode($list));
        } else {
            dic_DataWrite("点歌数据", $robot, json_encode($list));
            for ($i = 0; $i < count($list); $i++) {
                $jsonarray = $list[$i];
                $songname = $jsonarray["songname"];
                $songid = $jsonarray["songid"];
                $singername = $jsonarray["singer"][0]["name"];
                $payplay = $jsonarray["pay"]["payplay"];
                echo $i + 1;
                echo "．";
                echo $songname;
                echo "-";
                echo $singername;
                echo $payplay == 0 ? "[免费]" : "[收费]";
                if ($i + 1 != count($list)) {
                    echo "\n";
                }
            }
        }
        echo "\n";
        echo "页数：".$p." 页";
        echo "\n";
        echo "提示：发送：选[数字]";
        echo "\n";
        echo "切换页数：发送：下一页 上一页";
    } else {
        dic_head();
        echo "点歌失败";
        echo "\n";
        echo "提示：code：" . $json["code"];
    }
} elseif (preg_match("/^(选择|选) ?([0-9]+)\$/", $msgtext, $match) && $selected == 1 && $songswitch == 1) {
    $list = json_decode(dic_DataRead("点歌数据", $robot, "text"), true);
    $jsonarray = $list[$match[2] - 1];
    if (!empty($jsonarray)) {
        $songid = $jsonarray["songid"];
        $albummid = $jsonarray["albummid"];
        $songmid = $jsonarray["songmid"];
        $songname = $jsonarray["songname"];
        $singername = $jsonarray["singer"][0]["name"];
        echo 'json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]' . $songname . '","meta":{"music":{"sourceMsgId":"0","title":"' . $songname . '","desc":"' . $singername . '","preview":"y.gtimg.cn/music/photo_new/T002R150x150M000' . $albummid . '.jpg","tag":"QQ音乐","musicUrl":"https://v1.itooi.cn/tencent/url?id=' . $songmid . '","jumpUrl":"y.qq.com/n/yqq/song/' . $songmid . '.html","appid":100497308,"app_type":1,"action":"","source_url":"url.cn/5aSZ8Gc","source_icon":"url.cn/5tLgzTm","android_pkg_name":"com.tencent.qqmusic"}},"config":{"forward":true,"type":"normal","autosize":true}}';
    } else {
        dic_head();
        echo "选歌失败 当前歌曲不存在";
    }
} elseif (($msgtext == "下一页") && $songswitch == 1) {
    dic_DataWrite("选歌状态", $robot, 1);
    $num = dic_DataRead("选歌数量", $robot, "text");
    $p = dic_DataRead("点歌页数", $robot, "text");
    $w = dic_DataRead("点歌内容", $robot, "text");
    $p = $p + 1;
    $str = get_curl("https://c.y.qq.com/soso/fcgi-bin/client_search_cp?aggr=0&cr=1&flag_qc=0&p={$p}&n={$num}&w=" . urlencode($w));
    $str = substr($str, 9, -1);
    $json = json_decode($str, true);
    if ($json["code"] == 0) {
        $list = $json["data"]["song"]["list"];
        if (empty($list[0])) {
            dic_head();
            echo "已经到底了";
        } else {
            dic_DataWrite("点歌页数", $robot, $p);
            dic_DataWrite("点歌数据", $robot, json_encode($list));
            for ($i = 0; $i < count($list); $i++) {
                $jsonarray = $list[$i];
                $songname = $jsonarray["songname"];
                $songid = $jsonarray["songid"];
                $singername = $jsonarray["singer"][0]["name"];
                $payplay = $jsonarray["pay"]["payplay"];
                echo $i + 1;
                echo "．";
                echo $songname;
                echo "-";
                echo $singername;
                echo $payplay == 0 ? "[免费]" : "[收费]";
                if ($i + 1 != count($list)) {
                    echo "\n";
                }
            }
        }
        echo "\n";
        echo "页数：".$p." 页";
        echo "\n";
        echo "提示：发送：选[数字]";
        echo "\n";
        echo "切换页数：发送：下一页 上一页";
    } else {
        dic_head();
        echo "点歌失败";
        echo "\n";
        echo "提示：code：" . $json["code"];
    }
} elseif (($msgtext == "上一页") && $songswitch == 1) {
    dic_DataWrite("选歌状态", $robot, 1);
    $num = dic_DataRead("选歌数量", $robot, "text");
    $p = dic_DataRead("点歌页数", $robot, "text");
    $w = dic_DataRead("点歌内容", $robot, "text");
    $p = $p - 1;
    $str = get_curl("https://c.y.qq.com/soso/fcgi-bin/client_search_cp?aggr=0&cr=1&flag_qc=0&p={$p}&n={$num}&w=" . urlencode($w));
    $str = substr($str, 9, -1);
    $json = json_decode($str, true);
    if ($json["code"] == 0) {
        $list = $json["data"]["song"]["list"];
        if (empty($list[0])||$p==0) {
            dic_head();
            echo "已经到顶了";
        } else {
            dic_DataWrite("点歌页数", $robot, $p);
            dic_DataWrite("点歌数据", $robot, json_encode($list));
            for ($i = 0; $i < count($list); $i++) {
                $jsonarray = $list[$i];
                $songname = $jsonarray["songname"];
                $songid = $jsonarray["songid"];
                $singername = $jsonarray["singer"][0]["name"];
                $payplay = $jsonarray["pay"]["payplay"];
                echo $i + 1;
                echo "．";
                echo $songname;
                echo "-";
                echo $singername;
                echo $payplay == 0 ? "[免费]" : "[收费]";
                if ($i + 1 != count($list)) {
                    echo "\n";
                }
            }
        }
        echo "\n";
        echo "页数：".$p." 页";
        echo "\n";
        echo "提示：发送：选[数字]";
        echo "\n";
        echo "切换页数：发送：下一页 上一页";
    } else {
        dic_head();
        echo "点歌失败";
        echo "\n";
        echo "提示：code：" . $json["code"];
    }
}