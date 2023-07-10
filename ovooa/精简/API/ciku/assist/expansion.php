<?php
function read_all($dir)
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
                    if ($suffix == "php") {
                        $textarray[] = array("path" => $dir . DIRECTORY_SEPARATOR, "name" => $fl);
                    }
                }
            }
        }
    }
    return $textarray;
}
function extended_run($F744F6FB63E3D3F4DAF332BEEE4B065F)
{
    $FFB24F9CB2FBA5868BC229B9E54B3C55 = read_all($F744F6FB63E3D3F4DAF332BEEE4B065F);
    foreach ($FFB24F9CB2FBA5868BC229B9E54B3C55 as $AF52ABC87AD6853A69C617EC7FDB640A) {
        include $AF52ABC87AD6853A69C617EC7FDB640A["path"] . $AF52ABC87AD6853A69C617EC7FDB640A["name"];
    }
}