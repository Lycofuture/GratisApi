<?php
header('content-type:application/json');
/* Start */

require ('../function.php'); // 引入函数文件
addApiAccess(83); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$qq = @$_REQUEST['qq'];
$Skey = @$_REQUEST['s'];
$p = @$_REQUEST['p'];
$group = @$_REQUEST['group'];
$op = @$_REQUEST['op']?:1;
$type = @$_REQUEST['type'];
if(!$qq){
	if($type == 'text'){
		echo '请输入QQ号';
		exit();
	}else{
		exit(need::json(array('code'=>-1,'text'=>'请输入QQ号')));
	}
}
if(!$group){
	if($type == 'text'){
		echo '请输入群号';
		exit();
	}else{
		exit(need::json(array('code'=>-2,'text'=>'请输入群号')));
	}
}
if(!$Skey){
	if($type == 'text'){
		echo '请输入Skey';
		exit();
	}else{
		exit(need::json(array('code'=>-3,'text'=>'请输入Skey')));
		
	}
}
if(!need::is_num($qq)){
	if($type == 'text'){
		exit('请输入正确的账号');
	}else{
		exit(need::json(array('code'=>-10,'text'=>'请输入正确的QQ号')));
	}
}
if(!need::is_num($group)){
	if($type == 'text'){
		exit('请输入正确的群号');
	}else{
		exit(need::json(array('code'=>-11,'text'=>'请输入正确的群号')));
	}
}
if(!need::is_Pskey($p))
{
	if($type == 'text'){
		exit('请输入正确的Pskey');
	}else{
		exit(need::json(array('code'=>-11,'text'=>'请输入正确的Pskey')));
	}
}
$bkn = need::GTK($Skey);
$url = 'https://qinfo.clt.qq.com/cgi-bin/qun_info/get_members_info_v1?friends=1&gc='.$group.'&bkn='.$bkn.'&src=qinfo_v3&_ti='.time();
$Cookie = 'uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$Skey.'; p_skey='.$p;
$Cookie = 'uin=o2830877581; skey=ZN77U6UUOK; p_uin=o2830877581; p_skey=8wE9ePmzJ-spx7nAyOPxVieHgvrVGFxUq-yuMvRph-A_; ';
$data = need::teacher_curl($url,[
	'cookie'=>$Cookie,
	'refer'=>'https://qinfo.clt.qq.com/qinfo_v3/member.html?groupuin='.$group,
	'ua'=>'Mozilla/5.0 (Linux; Android 9; OPPO R11t Build/PKQ1.190414.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/98.0.4758.102 MQQBrowser/6.2 TBS/046317 Mobile Safari/537.36 V1_AND_SQ_8.9.5_3176_YYB_D A_8090500 QQ/8.9.5.8845 NetType/WIFI WebP/0.3.0 Pixel/1080 StatusBarHeight/80 SimpleUISwitch/0 QQTheme/1000 InMagicWin/0 StudyMode/1 CurrentMode/2 CurrentFontScale/1.0 GlobalDensityScale/0.90000004 AppId/537129696',
	'Header'=>[
		'Host: qinfo.clt.qq.com',
		'upgrade-insecure-requests: 1',
		'user-agent: Mozilla/5.0 (Linux; Android 9; OPPO R11t Build/PKQ1.190414.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/98.0.4758.102 MQQBrowser/6.2 TBS/046317 Mobile Safari/537.36 V1_AND_SQ_8.9.5_3176_YYB_D A_8090500 QQ/8.9.5.8845 NetType/WIFI WebP/0.3.0 Pixel/1080 StatusBarHeight/80 SimpleUISwitch/0 QQTheme/1000 InMagicWin/0 StudyMode/1 CurrentMode/2 CurrentFontScale/1.0 GlobalDensityScale/0.90000004 AppId/537129696',
		'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/wxpic,image/sharpp,image/apng,image/tpg,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
		'sec-fetch-site: none',
		'sec-fetch-mode: navigate',
		'sec-fetch-user: ?1',
		'sec-fetch-dest: document',
		'accept-encoding: gzip, deflate, br',
		'accept-language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
		'cookie: p_uin=o'.$qq,
		'cookie: uin=o'.$qq,
		'cookie: skey='.$Skey,
		'cookie: p_skey='.$p
	],
	'Cookie'=>false
]);
/*
if($_REQUEST['key'] == 1){
echo $Cookie, $data, $url;
// print_r(need::$info);
exit();
}*/
$json = json_decode($data,true);
$code = $json['ec'];
if($code=='0'){
	if($op == '2354452553'){
		exit(need::json($json));
	}
	if($op == 1234){
		print_r($json);exit;
	}
	if($op == 1){
		if($type == 'text'){
			echo($json['owner']);
		}else{
			exit(need::json(array('code'=>1,'text'=>$json['owner'],'data'=>Array($json['owner']))));
		}
	}else
	if($op == '2'){
	$admin = $json['adm'];
	foreach($admin as $v){
		$arr[] = $v;
	}
		if($type == 'text'){
			$admin = preg_replace('/\[|\]|"/','',json_encode($arr,320));
			echo $admin;
			exit;
		}else{
			exit(need::json(array('code'=>1,'text'=>'获取成功','data'=>$arr)));
		}
	}else
	if($op == '3'){
		if($type == 'text'){
			echo($json['mem_num']);
			exit;
		}else{
			exit(need::json(array('code'=>1,'text'=>$json['mem_num'])));
		}
	}else
	if($op == '4'){
		if($type == 'text'){
			echo($json['max_num']);
			exit;
		}else{
			exit(need::json(array('code'=>1,'text'=>$json['max_num'])));
		}
	}else
	if($op == '5'){
		if($type == 'text'){
			echo($json['max_admin']);
			exit;
		}else{
			exit(need::json(array('code'=>1,'text'=>$json['max_admin'])));
		}
	}else
	if($op == '6'){
		if($type == 'text'){
			echo($json['level']);
			exit;
		}else{
			exit(need::json(array('code'=>1,'text'=>$json['level'])));
		}
	}else
	if($op == '7'){
		$refe = json_encode($json['members']);
		preg_match_all('/"([0-9]+?)":{(.*?)}/i',$refe,$array);
		$arr = [];
		foreach($array[1] as $k=>$v){
			$uinq = $array[1][$k];
			$arr[] = $uinq;
		}
		if($type == 'text'){
			$uin = preg_replace('/\[|\]|"/','',JSON_encode($arr,320));
			echo($uin);
			exit;
		}else{
			exit(need::json(array('code'=>1,'text'=>'获取成功','data'=>$arr)));
		}
	}else{
		$Master = $json['owner'];//群主
		if($type == 'text'){
			echo'群主：'.$Master.'\n';
			foreach($json['members'] as $k=>$v){
				echo '账号：'.$k.'\n';
				echo '入群时间：'.date('Y-m-d G:i:s',$json['members'][$k]['jt']).'\n';
				echo '最后发言：'.date('Y-m-d G:i:s',$json['members'][$k]['lst']).'\n';
			}
			foreach($json['adm'] as $v){
				echo '管理员：'.$v;
				echo '\n';
			}
		}else{
			foreach($json['members'] as $k=>$v){
				$array['uin'] = $k;
				$array['Time'] = date('Y-m-d G:i:s',$json['members'][$k]['jt']);
				$array['Speak_Time'] = date('Y-m-d G:i:s',$json['members'][$k]['lst']);
				$tut[] = $array;
				$uin_text .= $k.',';
			}
			foreach($json['adm'] as $v){
				$arr[] = $v;
			}
	   // print_r($json);
		   //$Group_name = need::teacher_curl(
			echo need::json(
				array(
					'code'=>1,
					'text'=>'获取成功',
					'data'=>array(
						'Master'=>$Master,
						'admin_text'=>str_replace(array('"','[',']'),'',json_encode($arr,320)),
						'uin_text'=>str_replace(',,,','',$uin_text.',,'),
						'admin'=>$arr,
						'uins'=>$tut)));
		exit;
		}
	}
}else
if ($code == -100005){
	if($type == 'text'){
		echo'账号：'.$qq.'并不在群'.$group.'中';
		exit;
	}else{
		exit(need::json(array('code'=>-4,'text'=>'账号：'.$qq.'并不在群'.$group.'中')));
	}
}else
if ($code == 4){
	if($type == 'text'){
			//print_r($json);exit;
		echo 'Skey已过期';
		exit();
	}else{
		exit(need::json(array('code'=>-5,'text'=>'Skey已过期')));
	}
}else
if ($code == 99997){
	if($type == 'text'){
			//print_r($json);exit;
		echo 'Skey已过期';
		exit();
	}else{
		exit(need::json(array('code'=>-7,'text'=>'anti malicious')));
	}
}else
{
	if($type == 'text'){
		echo '未知错误';
		exit;
	}else{
		exit(need::json(array('code'=>-6,'text'=>'未知错误，错误码：'.$code)));
	}
}
