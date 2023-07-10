<?php
header('Content-type:Application/Json');
print_r(need::read_all('./S'));

function need::read_all($dir)
{
    if (!is_dir($dir)) {
        return array();
    }
    $handle = opendir($dir);
    if ($handle) {
        while (($fl = readdir($handle)) !== false) {
            $temp = iconv('utf-8', 'utf-8', $dir . DIRECTORY_SEPARATOR . $fl);
            //转换成utf-8格式
            //如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
            if (!(is_dir($temp) && $fl != '.' && $fl != '..')) {
                if ($fl != '.' && $fl != '..') {
                    $suffix = substr(strrchr($fl, '.'), 1);
                    if ($suffix == "png") {
                        $textarray[] = array("path" => $dir . DIRECTORY_SEPARATOR, "name" => $dir.'/'.$fl);
                    }
                }
            }
        }
    }
    return $textarray;
}