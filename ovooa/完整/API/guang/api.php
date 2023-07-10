<?php
header('Content-type: application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(19); // 调用统计函数

require '../../need.php';

require '../../curl.php';
/* End */
//echo '禁止请求！';
//exit;
    $counter = intval(file_get_contents("counter.dat"));  
$_SESSION['#'] = true;  
$counter++;
file_put_contents("counter.datt",$counter);
$fp = fopen("counter.dat","w");
fwrite($fp, $counter);
fclose($fp); 
?>
<?php
$Type = @$_REQUEST['type'];

$n = @$_REQUEST['n']?:1;

$arr = array('4e4d610cdf714d2966000000','4ef0a35c0569795756000000','4e4d610cdf714d2966000002','4e4d610cdf714d2966000003','4fb47a305ba1c60ca5000223','4fb479f75ba1c65561000027','4fb47a195ba1c60ca5000222','4fb47a465ba1c65561000028','4e4d610cdf714d2966000005','4e4d610cdf714d2966000007','4e4d610cdf714d2966000001','4ef0a3330569795757000000','5109e04e48d5b9364ae9ac45','5109e05248d5b9368bb559dc','4e4d610cdf714d2966000006','4e4d610cdf714d2966000004');//美女,情感,风景,动漫,城市,视觉,创意,物语,机械,游戏,动物,艺术,文字,明星,男人

$key = $arr[($n -1)];

if($n < 16){
    $url = 'http://service.aibizhi.adesk.com/v1/vertical/category/'.$key.'/vertical?limit=30&skip='.rand(0,200).'&adult=true&first=1&order=hot';
    $data = json_decode(need::teacher_curl($url),true);
    $data = $data['res']['vertical'];
    $rand = array_rand($data,1);
    $image = $data[$rand]['img'];
    
}else

if($n == 16){

    $data = json_decode(need::teacher_curl('http://service.aibizhi.adesk.com/v1/wallpaper/album/5109f37f48d5b9364ae9ac49/wallpaper?limit=30&adult=false&first=0&order=new&skip='.rand(0,100),['ua'=>'picasso,276,nearme']),true);
    $data = $data['res']['wallpaper'];
    $rand = array_rand($data,1);
    $image = $data[$rand]['img'];
    
}else{

    $key = $arr[3];
    
    $url = 'http://service.aibizhi.adesk.com/v1/vertical/category/'.$key.'/vertical?limit=30&skip='.rand(0,800).'&adult=true&first=1&order=hot';
    
    $data = json_decode(need::teacher_curl($url),true);
    
    $data = $data['res']['vertical'];
    $rand = array_rand($data,1);
    $image = $data[$rand]['img'];
    
}
// print_r($data);
if($Type == 'text'){

    echo $image;
    
    exit;
    
}else

if($Type == 'web'){

    echo '<div class="panel-heading" style="background: linear-gradient(to right,#b221ff,#14b7ff,#8ae68a);"><center><a href="./api.php?n='.$n.'&type=web"><font color="#FF4500"><b>—————>点我进行更换<————</b></font></a></center></div></div><img src="'.$image.'"></html>';
    
}else

if($Type == 'image'){

    header('location:'.$image);
    
}else{

    echo need::json(array('code'=>1,'text'=>$image));
    
    exit();
    
}

    

    
    
/*
//判断处理
    if($n==1){
        $tpwd = 'http://service.aibizhi.adesk.com/v1/vertical/category/'.$arr['美女'].'/vertical?limit=30&skip='.rand(0,800).'&adult=true&first=1&order=hot';//$arr['美女'];//'shaon/shaon.dat';
        $data = json_decode(need::teacher_curl($tpwd),true);
        $data = $data['res']['vertical'];
        $rand = array_rand($data,1);
        $tpwd = $data[$rand]['img'];
//        echo $data[$rand]['img'];
    }else
    if($n==2){
//2是二次元动漫
        $tpwd = 'http://service.aibizhi.adesk.com/v1/vertical/category/'.$arr['动漫'].'/vertical?limit=30&skip='.rand(0,500).'&adult=true&first=1&order=hot';//'2cy/2cy.dat';
        $data = json_decode(need::teacher_curl($tpwd),true);
        $data = $data['res']['vertical'];
        $rand = array_rand($data,1);
        $tpwd = $data[$rand]['img'];
    }else
    if($n==3){
//3小姐姐
        $tpwd ='xiaojiejie/xiaojiejie.dat';
    }else
    if($n == 4){
    
        $data = json_decode(need::teacher_curl('http://service.aibizhi.adesk.com/v1/wallpaper/album/5109f37f48d5b9364ae9ac49/wallpaper?limit=30&adult=false&first=0&order=new&skip='.rand(0,100),['ua'=>'picasso,276,nearme']),true);
        $data = $data['res']['wallpaper'];
        $rand = array_rand($data,1);
        $tpwd = $data[$rand]['img'];
    }else{
//4动漫
        $tpwd = 'http://service.aibizhi.adesk.com/v1/vertical/category/'.$arr['动漫'].'/vertical?limit=30&skip='.rand(0,500).'&adult=true&first=1&order=hot';//'dongman/dongman.dat';
        $data = json_decode(need::teacher_curl($tpwd),true);
        $data = $data['res']['vertical'];
        $rand = array_rand($data,1);
        $tpwd = $data[$rand]['img'];
    }
    
    
    /*else
    if($n==5){
//6性感图片
        $tpwd ='rxsnu/rxsnu.dat';
    }else 
    if($n==6){
//5日系少女
        $tpwd ='xgtp/xgtp.dat';
    }else
    if($n==7){
//绝美诱人
        $tpwd ='juemei/juemei.dat';
    }else
    if($n==8){
//绝美巨乳
        $tpwd ='juemei/juru.dat';
    }*/

//echo $tpwd;
/*
if($n==3){
$filename = $tpwd;
//从文本获取链接zcjun.com
$pics = [];
$fs = fopen($filename, "r");
while(!feof($fs)){
	$line=trim(fgets($fs));
	if($line!=''){
		array_push($pics, $line);
	}
}
//从数组随机获取链接
$tpwd = $pics[array_rand($pics)];

$pickp =$pic;
}
if($shuc=="tp")
{
echo'<div class="panel-heading" style="background: linear-gradient(to right,#b221ff,#14b7ff,#8ae68a);"><center><a href="./api.php?n='.$n.'&shuc=tp"><font color="#FF4500"><b>—————>点我进行更换<————</b></font></a></center></div></div><img src="'.$tpwd.'"></html>';
}else
if($shuc=="txt")
{
echo $tpwd;
//图片直链输出
}

else if($shuc=="sq")
{
echo "±img=".$tpwd;
echo "±";

//图片直链输出
}
else if($shuc=="json")
{
echo need::json(array('code'=>1,'text'=>$tpwd));
//图片直链输出
}
else
{
echo '未知错误:站长QQ:2354452553';
}

}


*/
?>