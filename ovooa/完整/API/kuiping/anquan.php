<?php

header('Content-type: text/html; charset=utf-8');
$ip = $_SERVER['REMOTE_ADDR'];//获取当前访问者的ip
$logFilePath = './log/';//日志记录文件保存目录
$fileht = '.htaccess2';//被禁止的ip记录文件
$allowtime = 30;//防刷新时间 在提示“警告：不要刷新的太频繁”之后 30秒后重置计算时间
$allownum = 20;//防刷新次数
$allowRefresh = 5;//在允许刷新次数之后加入禁止ip文件中 在提示“警告：不要刷新的太频繁”之后 5秒后可刷新的次数，超过则加入黑名单

if (!file_exists($fileht)) {
    file_put_contents($fileht, '');
}
$filehtarr = @file($fileht);
if (in_array($ip . "\r\n", $filehtarr)) {
    exit(need::json(array("code"=>"5","text"=>"抱歉，您的ip已被屏蔽。\\n如正常使用情况下被屏蔽，请进群：820323177")));
}
//加入禁止ip
$time = time();
$fileforbid = $logFilePath . 'forbidchk.dat';
if (file_exists($fileforbid)) {
    if ($time - filemtime($fileforbid) > 30) {
        @unlink($fileforbid);
    } else {
        $fileforbidarr = @file($fileforbid);
        if ($ip == substr($fileforbidarr[0], 0, strlen($ip))) {
            if ($time - substr($fileforbidarr[1], 0, strlen($time)) > 120) {
                @unlink($fileforbid);
            } else {
                if ($fileforbidarr[2] > $allowRefresh) {
                    file_put_contents($fileht, $ip . "\r\n", FILE_APPEND);
                    @unlink($fileforbid);
                } else {
                    $fileforbidarr[2]++;
                    file_put_contents($fileforbid, $fileforbidarr);
                }
            }
        }
    }
}
//防刷新
$str = '';
$file = $logFilePath . 'ipdate.dat';
if (!file_exists($logFilePath) && !is_dir($logFilePath)) {
    mkdir($logFilePath, 0777);
}
if (!file_exists($file)) {
    file_put_contents($file, '');
}
$uri = $_SERVER['REQUEST_URI'];
//获取当前访问的网页文件地址
$checkip = md5($ip);
$checkuri = md5($uri);
$yesno = true;
$ipdate = @file($file);
foreach ($ipdate as $k => $v) {
    $iptem = substr($v, 0, 32);
    $uritem = substr($v, 32, 32);
    $timetem = substr($v, 64, 10);
    $numtem = substr($v, 74);
    if ($time - $timetem < $allowtime) {
if ($iptem != $checkip) {
$str .= $v;
} else {
$yesno = false;
if ($uritem != $checkuri) {
$str .= $iptem . $checkuri . $time . "\r\n";
}else{
if ($numtem < $allownum) {
$str .= $iptem . $uritem . $timetem . ($numtem + 1) . "\r\n";
}else{
if (!file_exists($fileforbid)) {
$addforbidarr = array($ip . "\r\n", time() . "\r\n", 1);
file_put_contents($fileforbid, $addforbidarr);
}
$uri = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
file_put_contents($logFilePath . 'forbided_ip.log', $ip . '--' . date('Y-m-d H:i:s', time()) . '--' . $uri . "\r\n", FILE_APPEND);
$timepass = $timetem + $allowtime - $time;
//exit('<body style="background-image: url(pic.jpg);background-attachment: fixed;background-repeat: no-repeat;background-size:100%; cover;-moz-background-size: cover;"> <script type="text/javascript">window.alert("温馨提示：\n\n尊敬的用户您好！\n当您收到此提示，即表示您在30秒内连续刷新本站已达三次，正常情况下不会出现这样的情况，如您在短时间内继续刷新您将无法再次进入本站。");</script>');

exit(need::json(array("code"=>"5","text"=>"抱歉，您的ip已被屏蔽。\\n如正常使用情况下被屏蔽，请进群：820323177")));
                }
            }
        }
    }
}
if ($yesno) {
    $str .= $checkip . $checkuri . $time . "\r\n";
}
file_put_contents($file, $str);


?>