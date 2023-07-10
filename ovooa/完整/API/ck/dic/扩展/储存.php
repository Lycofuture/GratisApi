<?php

function dic_store_QQ($name,$text,$document){

$name = jiami($name);//通过加密函数加密储存标题

$array = array($name=>$text);//进行json格式化

if(!is_dir('./dic/缓存/'.$qq)){

mkdir('./dic/缓存/'.$qq,0777,true);

}else{}


if(!is_file('./dic/缓存/'.$qq.'/'.$document)){

$data = fopen('./dic/缓存/'.$qq.'/'.$document,"w");

fwrite($data,JSON_encode($array,320));

fclose($data);

}else{

$data = @file_get_contents('./dic/缓存/'.$qq.'/'.$document);

$mydata = fopen('./dic/缓存/'.$qq.'/'.$document,"w");

fwrite($mydata,$data."\n".JSON_encode($array,320));

fclose($mydata);

}

}



function dic_store_data($qq,$name,$text,$document){

$name = jiami($name);//通过加密函数加密储存标题

$array = array($name=>$text);//进行json格式化

if(!is_dir('./dic/缓存/'.$qq)){

mkdir('./dic/缓存/'.$qq,0777,true);

}else{}

if(!is_file('./dic/缓存/'.$qq.'/'.$document)){

$data = fopen('./dic/缓存/'.$qq.'/'.$document,"w");

fwrite($data,JSON_encode($array,320));

fclose($data);

}else{

$data = @file_get_contents('./dic/缓存/'.$qq.'/'.$document);

$mydata = fopen('./dic/缓存/'.$qq.'/'.$document,"w");

$array = JSON_encode($array,320);

if(preg_match('/"'.$name.'":"(.*?)"/s',$data,$date)){

$fountain = JSON_encode(array($name=>$date[1]),320);

$data = str_replace("\n".$fountain,"",$data);

fwrite($mydata,$data."\n".$array);

fclose($mydata);

}else{

fwrite($mydata,$data."\n".$array);

fclose($mydata);

}
}
}

function dic_store_w($name,$text,$document){

$nametext = jiami($name);//通过加密函数加密储存标题

$array = array($nametext=>$text);//进行json格式化


if(!is_file('./dic/缓存/'.$document)){

$data = fopen('./dic/缓存/'.$document,"w");

fwrite($data,JSON_encode($array,320));

fclose($data);

}else{

$data = @file_get_contents('./dic/缓存/'.$document);

if(preg_match('/"'.jiami("违禁词").'":"'.$text.'"/',$data)){

return false;

}else{

$mydata = fopen('./dic/缓存/'.$document,"w");

fwrite($mydata,$data."\n".JSON_encode($array,320));

fclose($mydata);

}
}
}

function dic_store_s($name,$text,$document){

$nametext = jiami($name);//通过加密函数加密储存标题

$array = array($nametext=>$text);//进行json格式化


if(!is_file('./dic/缓存/'.$document)){

$data = fopen('./dic/缓存/'.$document,"w");//打开文件

fclose($data);//关闭文件

}else{}

$data = @file_get_contents('./dic/缓存/'.$document);//file浏览打开文件获取内容

if(preg_match('/"'.jiami("违禁词").'":"'.$text.'"/',$data)){

$data_text=str_replace("\n".JSON_encode($array,320),'',$data);//将想删掉的违禁词替换掉

$mydata = fopen('./dic/缓存/'.$document,"w");//打开文件

fwrite($mydata,$data_text);//写入

fclose($mydata);//关闭文件

}
}


