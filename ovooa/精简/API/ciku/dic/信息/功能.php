<?php
include("./assist/parameter.php");//传入变量
include_once "./dic/扩展/头部.php";
if (preg_match("/^(功能|菜单|帮助|menu)\$/", $msgtext)) {
    $Config = read_all("./dic/配置");
    $Text = "";
    dic_headBasic();
    for ($i = 0; $i < count($Config); $i++) {
        $array = $Config[$i];
        $path = $array["path"];
        $name = $array["name"];
        $str = substr($name, 0, strpos($name, '.'));
        if (($i + 1) % 2 == 0 && $i == count($Config) - 1) {
            $Text = $Text . ($i + 1) . "." . $str;
        } elseif (($i + 1) % 2 == 0) {
            $Text = $Text . ($i + 1) . "." . $str."\n";
        } else {
            $Text = $Text . ($i + 1) . "." . $str;
        }
    }
    echo $Text ."";
    dic_foot($robot);
}elseif (preg_match("/^设置尾巴 ?([\s\S]*?)\$/", $msgtext, $match)) {
    dic_DataWrite("尾巴",$robot,$match[1]);
    dic_head();
    echo "设置尾巴成功";
}