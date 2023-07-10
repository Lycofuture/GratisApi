<?

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$Skey = @$_GET["s"];

$qq = @$_GET["qq"];

$gtk = need::GTK((String)$Skey);

$uin = @$_GET["uin"];

$data = need::teacher_curl('https://club.vip.qq.com/api/vip/getQQLevelInfo?g_tk='.$gtk.'&requestBody=%7B%22sClientIp%22%3A%22127.0.0.1%22%2C%22sSessionKey%22%3A%22'.$Skey.'%22%2C%22iKeyType%22%3A1%2C%22iAppId%22%3A0%2C%22iUin%22%3A'.$qq.'%7D',[
'refer'=>'https://club.vip.qq.com/card/friend?_wv=16778247&_wwv=68&_wvx=10&_proxy=1&_proxyByURL=1&platform=1&qq='.$uin.'&adtag=qun&aid=mvip.pingtai.mobileqq.androidziliaoka.fromqqqun',
'cookie'=>'uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$Skey.''
]);

preg_match_all('/href="(.*?)"/',$data,$data);

$data_url=str_replace('&amp;','&',$data[1][0]);

exit($data_url);


