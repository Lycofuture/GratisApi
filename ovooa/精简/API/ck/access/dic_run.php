<?php

function dic_run($name)
{
    $array = read_all($name);
    for($a=0;$a<count($array);$a++){
   // foreach ($array as $k=>$v) {
        require $array[$a]["path"].$array[$a]["name"];
    }
}

