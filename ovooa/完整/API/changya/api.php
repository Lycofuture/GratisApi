<?php
header('content-type:application/json');
require '../../curl.php';
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(128); // 调用统计函数
addAccess();//调用统计函数
$type = @$_REQUEST['type'];
$id = @$_REQUEST['id'];
if($id){
	preg_match('/piece\/(.*?)\?/',$id,$uid);
	$id = $uid[1]?:$id;
}
if(empty($id)){
	$file = @file('Cy_cache.txt');
	$rand = @array_rand($file,1);
	$id = @trim($file[$rand]);
}
$url = 'https://m.api.singduck.cn/user-piece/'.$id.'?userId=2001391065';
//echo $url;exit();
//$url = 'https://m.api.singduck.cn/user-piece/AXOUHWli0B7Eiut3cALL?userId=2001391065';
$i = 0;
while($i <= 5){
	$data = need::teacher_curl($url);
	preg_match('/"application\/json" crossorigin="anonymous">([\s\S]*?)<\/script>/',$data,$data);
	$data = json_decode(@$data[1],true);
	//echo '哈';
	if($data){
		break;
	}
	unset($data);
}
if(empty($data)){
	Switch($type){
		case 'text':
		need::send('获取失败请重试','text');
		break;
		default:
		need::send(array('code'=>-1,'text'=>'获取失败请重试','url'=>$url));
		break;
	}
}
$data = @$data['props']['pageProps'];//总数据
//print_r($data);exit;
$user = @$data['singerUserInfo'];//用户信息
$username = @$user['nickname'];//用户名字
$userimage = @$user['avatar_url'];//用户头像
$song = @$data['clip'];//歌曲信息
$songname = @$song['songName'];//歌名
$songsinger = join("\n", @$song['singerName']);
$songurl = @$song['audioSrc'];//播放链接
if(empty($songurl)){
	Switch($type){
		case 'text':
		need::send('播放链接获取失败请重试','text');
		break;
		default:
		need::send(array('code'=>-2,'text'=>'播放链接获取失败请重试'));
		break;
	}
}

$songlyric = join("\n", @$song['lyrics']);
if(empty($songlyric)){
	$songlyric = '纯音乐,请欣赏';
}
//need::send($songlyric,'text');
$songlyric = @str_replace('Array', '', trim($songlyric));//歌词
$mend = @$data['pieces'];//推荐
foreach($mend as $k=>$v){
	$audioid = @$v['audioId'];//获取歌曲ID并且存起来
	//print_r($v);exit;
	$file = @file_get_contents(__DIR__.'/./Cy_cache.txt');
	if(stristr($file, $audioid) != false){
		file_put_contents(__DIR__.'/./Cy_cache.txt',$audioid.PHP_EOL, FILE_APPEND);
	}
}
/*
$open = fopen('./w.txt','w');
fwrite($open,need::json($i));
fclose($open);*/
Switch($type){
	case 'text':
	echo '±img='.$userimage.'±';
	echo "\n";
	echo '歌名：'.$songname;
	echo "\n";
	echo '原唱：'.$songsinger;
	echo "\n";
	echo '翻唱：'.$username;
	echo "\n";
	echo '播放链接：'.$songurl;
	echo "\n";
	echo '歌词：';
	echo "\n";
	echo $songlyric;
	exit();
	break;
	default:
	need::send(array(
		'code'=>1,
		'text'=>'获取成功',
		'data'=>array(
			'song_name'=>$songname,
			'song_singer'=>$songsinger,
			'user_name'=>$username,
			'user_image'=>$userimage,
			'song_url'=>$songurl,
			'song_lyric'=>$songlyric,
			'url'=>$url
		)
	),'json');
}
//$rand = array_rand($mend,1);//随机获取一个键
//print_r($recommend);