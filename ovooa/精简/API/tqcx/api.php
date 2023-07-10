<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
header("Content-type: text/html; charset=UTF-8");
if(!$_GET['msg'])exit("抱歉，出错了！\n请输入城市名！");
$msg = $_GET['msg'];
$n = $_GET['n'];
 $str = file_get_contents("compress.zlib://http://m.nmc.cn/f/rest/autocomplete?q=$msg&limit=10&timestamp=1547115792491");//56691|威宁|553100|贵州省|weining|AGZ
$str=str_replace('|','<br>',$str);
$str = str_replace(PHP_EOL,'<hr><hr>', $str);
$str = "<hr>".$str."<hr>";
$stre = '/<hr>(.*?)<br>(.*?)<br>(.*?)<br>(.*?)<br>(.*?)<br>(.*?)<hr>/';
$result = preg_match_all($stre,$str,$trstr);
if($result== 0){
echo "搜索不到与".$_GET["msg"]."的相关天气，请稍后重试或换个关键词试试。";
}else{
if($result== 1){
$a=$trstr[1][0];
$data = file_get_contents("compress.zlib://http://m.nmc.cn/f/rest/real/".$a."");
preg_match_all('/"city":"(.*?)","province":"(.*?)"},"week":"(.*?)","moon":"(.*?)","jie_qi":"(.*?)","publish_time":"(.*?)","weather":{"temperature":(.*?),"temperatureDiff":(.*?),"airpressure":(.*?),"humidity":(.*?),"rain":(.*?),"rcomfort":(.*?),"icomfort":(.*?),"info":"(.*?)","img":"(.*?)","feelst":(.*?)},"wind":{"direct":"(.*?)","power":"(.*?)","speed":"(.*?)"},"warn":{"alert":"(.*?)","pic":"(.*?)","province":"(.*?)","city":"(.*?)","url":"(.*?)","issuecontent":"(.*?)"/',$data,$data);//1地名，2省，5节气，6更新时间，7现在温度，9气压，10湿度，12舒适度，14天气，16体感温度，17风向，18风力，19风速，
$a = $data[1][0]; //小地名
$b = $data[2][0]; //省
$s = $data[5][0]; //节气
$c = $data[6][0]; //更新时间
$d = $data[7][0]; //现在温度
$e = $data[9][0]; //气压hPa，
$f = $data[10][0]; //湿度
$g = $data[12][0]; //舒适度
$h = $data[14][0]; //天气情况
$i = $data[16][0]; //体感温度
$q = $data[17][0]; //风向
$w = $data[18][0]; //程度
$r = $data[25][0]; //预警
echo "".$a."［".$b."］\\n\\n天气：".$h."  舒适度：".$g."\\n时实温度：".$d."℃ 体感：".$i."℃\\n气压：".$e."hPa  湿度：".$f."%\\n风向：".$q."  程度：".$w."";
if($r== 9999){}else{echo "\\n预警提示：$r";}
echo "\\n更新时间：".$c."\\n".$s."";
}else{
if($n== null){
for( $i = 0 ; $i < $result && $i < 15 ; $i ++ ){
$s=$trstr[2][$i];//小地名
$c=$trstr[4][$i];//省
echo ($i+1)."：".$c."$s\n";}
echo "共搜索到与【".$_GET["msg"]."】的相关天气信息".$result."条！";
}else{
$n = $_GET['n'];
$i=($n-1);
$a=$trstr[1][$i];//id
$data = file_get_contents("compress.zlib://http://m.nmc.cn/f/rest/real/".$a."");
preg_match_all('/"city":"(.*?)","province":"(.*?)"},"week":"(.*?)","moon":"(.*?)","jie_qi":"(.*?)","publish_time":"(.*?)","weather":{"temperature":(.*?),"temperatureDiff":(.*?),"airpressure":(.*?),"humidity":(.*?),"rain":(.*?),"rcomfort":(.*?),"icomfort":(.*?),"info":"(.*?)","img":"(.*?)","feelst":(.*?)},"wind":{"direct":"(.*?)","power":"(.*?)","speed":"(.*?)"},"warn":{"alert":"(.*?)","pic":"(.*?)","province":"(.*?)","city":"(.*?)","url":"(.*?)","issuecontent":"(.*?)"/',$data,$data);//1地名，2省，5节气，6更新时间，7现在温度，9气压，10湿度，12舒适度，14天气，16体感温度，17风向，18风力，19风速，
$a = $data[1][0]; //小地名
$b = $data[2][0]; //省
$s = $data[5][0]; //节气
$c = $data[6][0]; //更新时间
$d = $data[7][0]; //现在温度
$e = $data[9][0]; //气压hPa，
$f = $data[10][0]; //湿度
$g = $data[12][0]; //舒适度
$h = $data[14][0]; //天气情况
$i = $data[16][0]; //体感温度
$q = $data[17][0]; //风向
$w = $data[18][0]; //程度
$s = $data[19][0]; //风速m/s
$r = $data[25][0]; //预警
echo "".$a."［".$b."］\r\n\r\n天气：".$h."  舒适度：".$g."\r\n时实温度：".$d."℃ 体感：".$i."℃\r\n气压：".$e."hPa  湿度：".$f."%\r\n风向：".$q."  程度：".$w."";
if($r== 9999){}else{echo "\r\n预警提示：$r";}
echo "\r\n更新时间：".$c."\r\n".$s."";}}}
?>