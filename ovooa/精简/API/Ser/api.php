<?php
header('content-type:application/json; charset="utf-8";');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(116); // 调用统计函数
addAccess();//调用统计函数
/* End */
require '../../need.php';
$msg = urldecode((String)@$_REQUEST['name']);
$type = @$_REQUEST['type'];
if(empty($msg)){
    if($type == 'text'){
    
        die("请输入名字，看看你的二次元身份吧");
    }else{
    
        die(need::json(array('code'=>-1,'text'=>'请输入名字！','Tips'=>'接口提供于三三！')));
    
    }
}
$md5 = $msg. '_'. date('Y-m-d');//str_replace(array('@',' '),'',$msg);
$data = md5($md5);
$left = substr($data, 0, 16);
$right = substr($data, 16, 16);
//属性
$sx1 = md5($left);
$sx2 = md5($right);
$sx1 = preg_replace('/[a-z]/','',$sx1);    //str_replace(array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m"),"",$sx1);
$sx2 = preg_replace('/[a-z]/','',$sx2);    //str_replace(array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m"),"",$sx2);
$sx = substr($sx1,1,3)*substr($sx2,1,3);
$sx1 = substr($sx,1,1);
$sx2=substr($sx,2,1);
if($sx1 == 1){
    $sx1 = "腹黑";
}else 
if($sx1 == 2){
    $sx1 = "毒舌";
}else
if($sx1 == 3){
    $sx1 = "傲娇";
}else
if($sx1 == 4){
    $sx1 = "腐化";
}else
if($sx1 == 5){
    $sx1 = "三无";
}else
if($sx1 == 6){
    $sx1 = "男恐";
}else
if($sx1 == 7){
    $sx1 = "病娇";
}else
if($sx1 == 8){
    $sx1 = "百合";
}else{
    $sx1 = "元气";
}
if($sx2 == 1){
    $sx2 = "腹黑";
}else if($sx2 == 2){
    $sx2 = "毒舌";
}else
if($sx2 == 3){
    $sx2 = "傲娇";
}else
if($sx2 == 4){
    $sx2 = "腐化";
}else
if($sx2 == 5){
    $sx2 = "三无";
}else
if($sx2 == 6){
    $sx2 = "男恐";
}else
if($sx2 == 7){
    $sx2 = "病娇";
}else
if($sx1 == 8){
    $sx1 = "百合";
}else{
    $sx2 = "元气";
}
if($sx1 == $sx2){
    $sx = $sx1;
}else{
    $sx = $sx1."和".$sx2;
}
//瞳色
$eye = str_replace(array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m"),"",$data);
$eye = substr($eye,1,4);
$eye = $eye*3;
$eye = substr($eye,1,1);
if($eye == 1){
    $eye = "黑色";
}else
if($eye == 2){
    $eye = "红色";
}else if($eye == 3){
    $eye = "蓝色";
}else
if($eye == 4){
    $eye = "棕色";
}else
if($eye == 5){
    $eye = "紫色";
}else
if($eye == 6){
    $eye = "深蓝色";
}else
if($eye == 7){
    $eye = "银色";
}else
if($eye == 8){
    $eye = "红色";
}else{
    $eye = "粉色";
}
//cup
$cup = substr(str_replace(array('1','2','3','4','5','6','7','8','9','0'),"",$data),1,1);
if($cup == "a"){
$cup_a = substr($data,5,1);
if(is_numeric($cup_a)){
    $cup = "Acup";
}else{
    $cup = "飞机场";
}
}else
if($cup == "b"){
    $cup = "Bcup";
}else
if($cup == "c"){
    $cup = "Ccup";
}else
if($cup == "d"){
    $cup = "Dcup";
}else
if($cup == "e"){
    $cup = "Gcup";
}else{
    $cup = "惊天巨乳";
}
//头发
$hire = preg_replace('/[a-z]/', '', $left, -1, $count2);    //str_replace(array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m"),"",$left,$count2);
//echo $hire;
$hire = (substr(intval($hire), 0, intval(strlen($hire) / 2) > 1 ? intval(strlen($hire) / 2) : $hire));
//echo 123000, $hire;
$hire = substr(($hire * $count2), 0, 1);
if($hire<=2){
    $hire = "双马尾";
}else
if($hire == 3){
    $hire = "麻花辫";
}else
if($hire>3 and $hire<=6){
    $hire = "长发";
}else
if($hire>6 and $hire<=8){
    $hire = "短发";
}else{
    $hire = "卷发";
}
//头发颜色
$hire_color=md5($data).$left;
$hire_color=str_replace(array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m"),"",$hire_color);
$hire_color=substr($hire_color,2,1);
if($hire_color == 1){
    $hire_color = "黑色";
}else
if($hire_color == 2){
    $hire_color = "白色";
}else
if($hire_color == 3){
    $hire_color = "棕色";
}else
if($hire_color == 4){
    $hire_color = "红色";
}else
if($hire_color == 5){
    $hire_color = "银白色";
}else
if($hire_color == 6){
    $hire_color = "蓝色";
}else
if($hire_color == 7){
    $hire_color = "金色";
}else
if($hire_color == 8){
    $hire_color = "紫色";
}else
if($hire_color == 9){
    $hire_color = "粉色";
}else{
    $hire_color = "蓝绿色";
}
//身高
str_replace(array('1','2','3','4','5','6','7','8','9','0'),"",md5(substr($left,0,8)),$count1);
str_replace(array('1','2','3','4','5','6','7','8','9','0'),"",md5(substr($right,0,8)),$count2);
$data_hight = ($count1 * 4 + $count2 * 4 + 10);
if($data_hight>190){
    $data_hight = 153;
}
if($data_hight<135){
    $data_hight = 135;
}
//脸型
$data_long = strlen(str_replace(array('1','2','3','4','5','6','7','8','9','0'),"",$data));
if($data_long<=9){
    $data_long = "方形脸";
}
else
if($data_long == 10 or $data_long == 11){
    $data_long = "瓜子脸";
}else
if($data_long == 12){
    $data_long = "圆脸";
}else{
$data_long = '长脸';
}
//身份
$sf = md5($data_long.$data_hight.$hire_color.$hire.$cup.$eye.$sx);
$sf = str_replace(array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m"),"",$sf);
$sf = substr($sf,1,4);
$sf = $sf*3;
$sf = substr($sf,1,1);
if($sf == 1){
    $sf = "白痴少女";
}else
if($sf == 2){
    $sf = "学生会长";
}else
if($sf == 3){
    $sf = "笨蛋";
}else
if($sf == 4){
    $sf = "天才少女";
}else
if($sf == 5){
    $sf = "偶像";
}else
if($sf == 6){
    $sf = "女仆";
}else
if($sf == 7){
    $sf = "网瘾少女";
}else
if($sf == 8){
    $sf = "千金小姐";
}else
if($sf == 9){
    $sf = "公主";
}else{
    $sf = "笨蛋";
}
if($type == 'text'){
    echo "二次元少女的".$msg."，长着".$data_long."，身高".$data_hight."，".$hire_color.$hire."，".$cup." ,瞳色".$eye."，".$sx."属性，是".$sf."。";
    
    exit();
    
}else{
    echo need::json(array('code'=>1,'text'=>"二次元少女的".$msg."，长着".$data_long."，身高".$data_hight.",".$hire_color.$hire."，".$cup." ,瞳色".$eye."，".$sx."属性，是".$sf."。",'Tips'=>'接口提供于三三'));
    exit;
}
?>