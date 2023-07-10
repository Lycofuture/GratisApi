<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(22); // 调用统计函数
/* End */
header("Content-type: text/html; charset=utf-8");
function jiequ($txt1,$q1,$h1)
{
 $txt1=strstr($txt1,$q1);
 $cd=strlen($q1);
 $txt1=substr($txt1,$cd);
 $txt1=strstr($txt1,$h1,"TRUE");
 return $txt1;
}
$msg=$_GET['msg'];
$b=$_GET['b'];
$list=file_get_contents("http://m.moji.com/api/citysearch/".$msg);
$result = preg_match_all("/{\"cityId\":(.*?),\"city_lable\":(.*?),\"counname\":\"(.*?)\",\"id\":(.*?),\"localCounname\":\"(.*?)\",\"localName\":\"(.*?)\",\"localPname\":\"(.*?)\",\"name\":\"(.*?)\",\"pname\":\"(.*?)\"}/",$list,$nute);
if ($msg==""){
echo "请输入需要查询的城市地区名称";
}
else if($b== null)
{
for ($x=0; $x < $result && $x<=19; $x++) 
{
$jec=$nute[6][$x];
$je=$nute[7][$x];
echo ($x+1)."：".$je."-".$jec."\n";
}
echo "提示：发送天气选+以上序号\n例如：天气选1";
}
else
if($b>20||$b<1)
{
echo "请按以上序号选择";
}
else
{
$b=$_GET['b'];
$b=($b-1);
$je=$nute[1][$b];
$jec=$nute[6][$b];
$lis=file_get_contents("http://m.moji.com/api/redirect/".$je);
$bb=jiequ($lis,"<div class=\"weak_wea\">","<div class=\"exponent\">");
$bb=str_replace(' ', '', $bb);
preg_match_all("/<lidata-high=\"(.*?)\"data-low=\"(.*?)\">/",$bb,$aa);
preg_match_all("/<em>(.*?)<\/em>/",$bb,$qq);
preg_match_all("/<dd><strong>(.*?)<\/strong><\/dd>/",$bb,$cc);
preg_match_all("/<pclass=\"(.*?)\">(.*?)<\/p><dlclass=\"wind\">/",$bb,$dd);
preg_match_all("/<dd>(.*?)<\/dd>/",$bb,$ee);
preg_match_all("/<dd>(.*?)<\/dd>/",$bb,$ff);
echo "☁.查询：".$jec."\n";
echo "☁.日期：".$qq[1][0]."\n";
echo "☁.温度：".$aa[2][0]."～".$aa[1][0]."℃\n";
echo "☁.天气：".$cc[1][0]."\n";
echo "☁.风度：".$ee[1][2]."-".$ff[1][3]."\n";
echo "☁.空气质量：".$dd[2][0]."\n";
echo "\n";
echo "☁.日期：".$qq[1][1]."\n";
echo "☁.温度：".$aa[2][1]."～".$aa[1][1]."℃\n";
echo "☁.天气：".$cc[1][1]."\n";
echo "☁.风度：".$ee[1][6]."-".$ff[1][7]."\n";
echo "☁.空气质量：".$dd[2][1]."\n";
echo "\n";
echo "☁.日期：".$qq[1][2]."\n";
echo "☁.温度：".$aa[2][2]."～".$aa[1][2]."℃\n";
echo "☁.天气：".$cc[1][2]."\n";
echo "☁.风度：".$ee[1][10]."-".$ff[1][11]."\n";
echo "☁.空气质量：".$dd[2][2]."";
}
?>