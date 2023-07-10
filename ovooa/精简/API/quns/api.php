<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(72); // 调用统计函数
/* End */

require ('../../curl.php');//引入curl文件

require ('../../need.php');//引入bkn文件

$qq = @$_GET["qq"];//获取key的QQ

$Skey = @$_GET["skey"];//获取Skey

$pskey = @$_GET["pskey"];//获取pskey

$Group = @$_GET["group"];//上下管理的群号

$uin = @$_GET["uin"];//上下管理的人

$kt = @$_GET["kt"];//1上2下

if ($qq == '' || $qq == null){

exit(need::json(array('code'=>-1,'text'=>'请输入提供key的QQ')));

}

if ($Skey == '' || $Skey == null){

exit(need::json(array('code'=>-2,'text'=>'请输入Skey')));

}

if ($pskey == '' || $pskey == null){

exit(need::json(array('code'=>-3,'text'=>'请输入pskey')));

}

if ($uin == '' || $uin == null){

exit(need::json(array('code'=>-4,'text'=>'请输入升降人的QQ号')));

}

if($kt == '' || $kt == null){

exit(need::json(array('code'=>-5,'text'=>'请输入是上管理还是下管理')));

}


if ($kt != '1' && $kt != '2'){

exit(need::json(array('code'=>-6,'text'=>'1为上2为下其他不行')));

}

if ($Group == '' || $Group == null){

exit(need::json(array('code'=>-7,'text'=>'请输入群号')));

}

if ($kt == '1'){

$kta = '1';

}else

if ($kt == '2' ){

$kta = '0';

}

$bkn = need::GTK($Skey);

$html = need::teacher_curl('https://qun.qq.com/cgi-bin/qun_mgr/set_group_admin',[
'cookie'=>'p_uin=o'.$qq.';uin=o'.$qq.';skey='.$Skey.';p_skey='.$pskey.'',
'post'=>[
'gc'=>$Group,
'ul'=>$uin,
'op'=>$kta,
'bkn'=>$bkn]
]);//post请求

$json = json_decode($html, true);

$ul = $json["ul"];//json格式读取ul

if ($ul != $uin){

exit(need::json(array('code'=>-8,'text'=>'设置失败原因1：key过期。原因2：可能'.$uin.'本身就是/不是管理员。原因3：群管理满了')));//ul为空则未知错误

}else

{

echo need::json(array('code'=>1,'text'=>'设置成功！'));//ul有号就是成功

}

?>