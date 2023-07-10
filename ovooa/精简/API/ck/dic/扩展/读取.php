<?php

function dic_cache_get($name){

if(!is_dir('./dic/缓存/'.$qq)){

mkdir($qq);

}else{}

if(!is_file($name)){

$data = fopen($name,"w");

fclose($data);

$data = @file_get_contents($name);

return $data;

}else{

$data = @file_get_contents($name);

return $data;

}}

