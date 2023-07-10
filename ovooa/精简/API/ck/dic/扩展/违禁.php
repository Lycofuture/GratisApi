<?php

function dic_open($name,$text,$dic_z){

$name = jiami($name);

$array=JSON_encode(array($name=>$text),320);

if(!is_file('./dic/缓存/'.$dic_z)){

$data = fopen('./dic/缓存/'.$dic_z,"w");

fwrite($data,"\n".$array);

fclose($data);

}else{

$date = dic_cache_get('./dic/缓存/'.$dic_z);

if(preg_match('/"'.$name.'":"(.*?)"/',$date,$date_text)){

$str_rep=JSON_encode(array($name=>$date_text[1]),320);

$str_rep = str_replace("\n".$str_rep,'',$date);

$data = fopen('./dic/缓存/'.$dic_z,"w");

fwrite($data,$str_rep."\n".$array);

fclose($data);

}else{

if(!$date){

$data = fopen('./dic/缓存/'.$dic_z,"w");

fwrite($data,"\n".$array);

fclose($data);

}else{

$data = fopen('./dic/缓存/'.$dic_z,"w");

fwrite($data,$date."\n".$array);

fclose($data);

}

}

}

}
