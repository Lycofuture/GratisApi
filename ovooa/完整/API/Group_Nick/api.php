<?php
header('content-type:application/json');
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(124); // 调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
$Request = need::Request();
$Skey = @$Request['Skey'];
$Pskey = @$Request['Pskey'];
$QQ = @$Request['QQ'];
$uin = @$Request['uin'];
$type = @$Request['type'];
if(!need::is_num($uin) || !need::is_num($QQ)){
	Switch($type){
		case 'text':
		need::send('请输入正确的账号！','text');
		break;
		default:
		need::send(array('code'=>-1,'text'=>'请输入正确的账号！'));
		break;
	}
}
$Group = @$Request['Group'];
if(!need::is_num($Group)){
	Switch($type){
		case 'text':
		need::send('请输入正确的群号！','text');
		break;
		default:
		need::send(array('code'=>-2,'text'=>'请输入正确的群号！'));
		break;
	}
}
if(empty($Skey)){
	Switch($type){
		case 'text':
		need::send('请输入正确的Skey！','text');
		break;
		default:
		need::send(array('code'=>-3,'text'=>'请输入正确的Skey！'));
		break;
	}
}
if(empty($Pskey)){
	Switch($type){
		case 'text':
		need::send('请输入正确的Pskey！','text');
		break;
		default:
		need::send(array('code'=>-4,'text'=>'请输入正确的Pskey！'));
		break;
	}
}
$url = 'https://qun.qq.com/interactive/userhonor?gc='.$Group.'&uin='.$uin.'&_wv=3&&_wwv=128';
$data = need::teacher_curl($url,[
 //   'cookie'=>'p_uin=o'.$QQ.'; uin=o'.$QQ.'; pac_uid=1_'.$QQ.'; skey='.$Skey.'; p_skey='.$Pskey,
	'cookie'=>'qq_locale_id=2052; traceid=5cf9e5db6f; pvid=5809361318; p_uin=o'.$QQ.'; uin=o'.$QQ.'; pac_uid=1_'.$QQ.'; iip=0; skey='.$Skey.'; p_skey='.$Pskey,
	'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080 StatusBarHeight/84 SimpleUISwitch/0 QQTheme/1000 InMagicWin/0 StudyMode/0',
	'Header'=>[
		'Host: qun.qq.com',
		'Connection: keep-alive',
		'Upgrade-Insecure-Requests: 1',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
		'X-Requested-With: com.tencent.mobileqq',
		'Sec-Fetch-Site: none',
		'Sec-Fetch-Mode: navigate',
		'Sec-Fetch-User: ?1',
		'Sec-Fetch-Dest: document',
		'Accept-Encoding: gzip, deflate',
		'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
	]
]);
//print_r($data);exit;
preg_match('/__INITIAL_STATE__=([\s\S]*?)<\/script>/',$data,$data);
$data = json_decode($data[1],true);
// print_r($data);exit;
$Level = $data['myLevel'];
$nick = $data['nick'];
if(empty($data['medalList'])){
	Switch($type){
		case 'text':
		need::send('Pskey已过期！','text');
		break;
		default:
		need::send(array('code'=>-5,'text'=>'Pskey已过期！'));
		break;
	}
}
$array = array();
foreach($data['medalList'] as $k=>$v){
	if(!$v['wear_ts'] == 0){
		$name = $v['name'];
		if($v['mask'] == 315){
			$name = $data['myTag'];
		}
	}
	$array[] = $v['mask'] == 315 ? $data['myTag'] : $v['name'];
}
Switch($type){
	case 'text':
	echo '账号：'.$uin;
	echo "\n";
	echo '昵称：'.$nick;
	echo "\n";
	echo '群聊等级：'.$Level;
	echo "\n";
	need::send('群聊头衔：'.$name,'text');
	break;
	default:
	need::send(array(
		'code'=>1,
		'text'=>'获取成功',
		'data'=>array(
			'uin'=>$uin,
			'nick'=>$nick,
			'level'=>$Level,
			'name'=>$name,
			'tags'=>$array
		)
	));
	break;
}
//print_r($data);