<?php
$ip = $_SERVER['REMOTE_ADDR'];//获取当前访问者的ip
$logFilePath = __DIR__.'/log/';//日志记录文件保存目录
$fileht = __DIR__.'/.htaccess2';//被禁止的ip记录文件
$allowtime = 10;//防刷新时间 在提示“警告：不要刷新的太频繁”之后 10秒后重置计算时间
$allownum = 3;//防刷新次数
$allowRefresh = 1;//在允许刷新次数之后加入禁止ip文件中 在提示“HTTP 444”之后 5秒后可刷新的次数，超过则加入黑名单

if (!file_exists($fileht)) {
    file_put_contents($fileht, '');
}

//加入禁止ip
$time = time();
$fileforbid = $logFilePath . 'forbidchk.dat';

//防刷新
$str = '';
$file = $logFilePath . 'ipdate.dat';
if (!file_exists($logFilePath) && !is_dir($logFilePath)) {
    mkdir($logFilePath, 0775);
}
if (!file_exists($file)) {
    file_put_contents($file, '');
}
$o = fopen($fileforbid, 'a');
$o = ($time - filemtime($fileforbid));
if (file_exists($fileforbid)) {
    if ($o > 30) {
        //检测文件是否存在超过30秒
        @unlink($fileforbid);
    } else {
        $fileforbidarr = @file($fileforbid);
        if ($ip == substr($fileforbidarr[0], 0, strlen($ip))) {
            if ($time - substr($fileforbidarr[1], 0, strlen($time)) > 120) {
                @unlink($fileforbid);
            } else {
                if ($fileforbidarr[2] > $allowRefresh) {
                echo $allowRefresh,"123456789";
                    file_put_contents($fileht, $ip . "\n", FILE_APPEND);
                    @unlink($fileforbid);
                } else {
                    $fileforbidarr[2]++;
                    file_put_contents($fileforbid, $fileforbidarr);
                }
            }
        }
    }
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
    $numtem = empty(str_replace(array("\r","\n","\r\n"),'',$numtem)) ? 0 : $numtem;
    if ($time - $timetem < $allowtime) {
        if ($iptem != $checkip) {
            $str .= $v;
        } else {
            $yesno = false;
            if ($uritem != $checkuri) {
                $str .= $iptem . $checkuri . $time . "\n";
            }else{
                if ($numtem < $allownum) {
                    $str .= $iptem . $uritem . $timetem . ($numtem + 1) . "\n";
                }else{
                    if (!file_exists($fileforbid)) {
                        $addforbidarr = array($ip . "\n", time() . "\n", 1);
                        file_put_contents($fileforbid, $addforbidarr);
                    }
                    $uri = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    file_put_contents($logFilePath . 'forbided_ip.log', $ip . '--' . date('Y-m-d H:i:s', time()) . '--' . $uri . "\n", FILE_APPEND);
                    $timepass = $timetem + $allowtime - $time;
                    header('HTTP/1.0 444');
                    echo $timepass,123456789;
                    exit();
                }
            }
        }
    }
}
if ($yesno) {
    $str .= $checkip . $checkuri . $time . "\n";
}
//echo $str;
file_put_contents($file, $str);

$filehtarr = @file($fileht);
if (in_array($ip . "\n", $filehtarr)) {
    echo '黑';
    header('HTTP/1.0 444');
    exit();
}
//*/

?>