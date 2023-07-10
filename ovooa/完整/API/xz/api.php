<?php

/* Start */

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(36); // 调用统计函数

require "../../need.php";//引入封装好的函数

/* End */

$name= @$_REQUEST["msg"];
$type = @$_REQUEST["type"];
if($name==""){exit(need::json(array("code"=>"-2","text"=>"抱歉，输入为空。")));}
$name=str_replace('座','',$name);
$jk=Array(
"白羊"=>"1",
"金牛"=>"2",
"双子"=>"3",
"巨蟹"=>"4",
"狮子"=>"5",
"处女"=>"6",
"天秤"=>"7",
"天蝎"=>"8",
"射手"=>"9",
"摩羯"=>"10",
"水瓶"=>"11",
"双鱼"=>"12");
$l=$jk[$name];
if($l==""){
    exit(need::json(array('code'=>-1,'text'=>'不存在此类型，请查证后重试。')));
}

$image = 'http://'.$_SERVER['HTTP_HOST'].'/API/xz/Cache/image/'.$name.'.png';
$z=file_get_contents("http://cal.meizu.com/android/unauth/horoscope/gethoroscope.do?type=".$l."&date=".date("Y-m-d")."&searchType=0");
$z=myTrim($z);
$p=preg_match_all('/{"contentAll":"(.*?)","contentCareer":"(.*?)","contentFortune":"(.*?)","contentHealth":"(.*?)","contentLove":"(.*?)","contentTravel":"(.*?)","date":(.*?),"direction":"(.*?)","enemies":"(.*?)","friends":"(.*?)","horoscopeType":(.*?),"id":(.*?),"lucklyColor":"(.*?)","lucklyTime":"(.*?)","mark":(.*?),"numbers":(.*?),"pointAll":(.*?),"pointCareer":(.*?),"pointFortune":(.*?),"pointHealth":(.*?),"pointLove":(.*?),"pointTravel":(.*?),"shorts":"(.*?)"}/',$z,$z);
if($p==0){exit(need::json(array("code"=>"-3","text"=>"抱歉，获取出错。")));}
$sy=$z[2][0];//事业运势
$cf=$z[3][0];//财富运势
$cf=str_replace('\n',"\n",$cf);
$aq=$z[5][0];//爱情运势
$fw=$z[8][0];//贵人方位
$py=$z[10][0];//贵人星座
$ys=$z[13][0];//幸运颜色
$sz=$z[16][0];//幸运数字
$ts=$z[23][0];//提示

$data="星座：".$name."！换！贵人方位：".$fw."！换！贵人星座：".$py."！换！幸运数字：".$sz."！换！幸运颜色：".$ys."！换！爱情运势：".$aq."！换！财富运势：".$cf."！换！事业运势：".$sy."！换！提示：".$ts;

if($type == "text"){

$data1 = str_replace('！换！',"\n",$data);
echo '±img=';
echo $image;
echo '±';

echo $data1;

}else{

$data2 = str_replace('！换！',"\n",$data);

echo need::json(array("code"=>"1","text"=>$data2, 'data'=>['image'=>$image]));

}

function myTrim($str)
{
 $search = array(" ","　","\r","\r","\t");
 $replace = array("","","","","");
 return str_replace($search, $replace, $str);
}

?>