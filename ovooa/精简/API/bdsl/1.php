<?php

/* Start */

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

$array = array('e99bb6'=>'30','e4b880'=>'31','e4ba8c'=>'32','e4b889'=>'33','e59b9b'=>'34','e4ba94'=>'35','e585ad'=>'36','e4b883'=>'37','e585ab'=>'38','e4b99d'=>'39','e58d81'=>'3130','e799be'=>'313030');

//echo '\''.str_replace('\\u4E','',need::hex_encode('兆')).'\'=>';

//echo '\''.str_replace('\\u4E','',need::hex_encode('00000000000000')).'\',';

//echo "\r".chinanum('十二');

/*$num = "十二";

$num = rep_replace($num,"。");

$num = explode('。',$num);

for($a = 0 ; $a < count($num) ; $a++){

$num_1 = str_replace('\\u4E','',need::hex_encode($num[$a]));

$num_2 = $array[$num_1];

$num_3 .= need::hex_decode($num_2);

echo $num_1;

}
*/
echo chinanum('三百亿五千三百二十二万九千七百零一');


function chinanum($num){

if(preg_match('/零/',$num)){

$num = str_replace('零','',$num);

}


$array = array('e99bb6'=>'30','e4b880'=>'31','e4ba8c'=>'32','e4b889'=>'33','e59b9b'=>'34','e4ba94'=>'35','e585ad'=>'36','e4b883'=>'37','e585ab'=>'38','e4b99d'=>'39','e58d81'=>'30','e799be'=>'303030','e58d83'=>'303030','e4b887'=>'303030','e4babf'=>'303030');//,'e58d81e4b887'=>'3030','e799bee4b887'=>'303030','e58d83e4b887'=>'30303030','e58d81e4babf'=>'3030','e799bee4babf'=>'30303030','e58d83e4babf'=>'3030303030','e4b887e4babf'=>'303030303030','e58586'=>'30303030303030');

$num_num = rep_replace($num,"。");

$num_num = explode('。',$num_num);

//$num = str_replace('\\u4E','',need::hex_encode(json_encode($num,320)));

for($a = 0 ; $a < count($num_num) ; $a++){

$num_1 = str_replace('\\u4E','',need::hex_encode($num_num[$a]));

if($array[$num_1]){

$num_2 = $array[$num_1];

}else{

$num_2 = $num_1;

}

$num_3 .= need::hex_decode($num_2);

}

//30053229701

if(mb_strlen($num_3) > mb_strlen($num)){

$music =  str_replace('10','',$num_3);



$music = str_replace('00','0',$music);

$old_num = preg_match_all('/([1-9])0/',$music,$new_num);

$arr = array();

for($i = 0 ; $i < $old_num ; $i++){

$arr[$i] = $new_num[1][$i].'0';//str_replace($new_num[1][$i].'0','/[1-9]/',$music);

}

foreach($arr as $k=>$v){

$arr_num = str_replace('0','',$arr[$k]);

$music = str_replace($arr[$k],$arr_num,$music);

}

$music = str_replace('取','0',$music);

return $music;

}else{

return $num_3;

}

}



function rep_replace($text,$end){

$newstr = "";
for($i = 0; $i < mb_strlen($text);  $i ++)
{
   $newstr .= mb_substr($text, $i , 1) . $end;
   
}

   $text = $newstr.']';
   
   $text = str_replace($end.']','',$text);

return $text;

}



function mb_str_array($str,$charset) {

$strlen=mb_strlen($str);

while($strlen){

$array[]=mb_substr($str,0,1,$charset);

$str=mb_substr($str,1,$strlen,$charset);

$strlen=mb_strlen($str);

}

$arr =array_unique($array,SORT_NUMERIC); //过滤重复字符

$str = implode('',$arr); //合并数组

return $str;

}

