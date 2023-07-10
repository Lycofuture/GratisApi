<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
require '../../curl.php';
require '../../need.php';

$qq=@$_GET['QQ'];
$skey=@$_GET['Skey'];
$pskey=@$_GET['Pskey'];
$get=need::GTK((String)$pskey);
$a = need::GTK((String)$skey);

if($qq=='' || $qq==null){

echo "请填写需要查询的QQ";

exit;

}

if($skey=='' || $skey==null){

echo "请填写skey";

exit;

}





$url=need::teacher_curl("https://api.unipay.qq.com/v1/r/1450000515/wechat_query?cmd=4&pf=vip_m-pay_html5-html5&pfkey=pfkey&from_h5=4&from_https=2&format=jsonp__getQBBalance&openid=".$qq."&openkey=".$skey."&session_id=uin&session_type=skey&g_tk={$get}&bkn={$a}",[
    'cookie'=>'g_tk='.$get.'; uin=o'.$qq.'; p_uin=o'.$qq.'; skey='.$skey.' ;bkn='.$a.'; p_skey='.$pskey.'; ']);
/*
$url = need::teacher_curl('https://mq.api.unipay.qq.com/v1/r/1450000515/get_qqacct_info',['post'=>'encrypt_msg=06D659888D1F67AA5939DD397FFED4D172408705BE5DB69DDE49777DCDE62CD266484937768C099A259CBA3EC069C2C7F4D03EC5C5A5B15012393AB6B95C67C34EEFEF22F54BF984D93061F64F657D4D5CB583BE5D6E0CEC5B29528B812795C7C92B575947E535D4CF4865A8BCE8449AE12755172D64FEA423B3BF15B122742229DAB5B00A902E102FF807BD1E776725&openid=2830877581&format=json&msg_len=129&version=2&amode=1&session_token=&req_from=sdk&sdkversion=3.7.5c&pfkey=pfKey&key_time=1623706531&pf=qq_m_qq-2013-android-537035451-mvip.pingtai.mobileqq.mywallet.00001&key_len=newkey',
'ua'=>'Dalvik/2.1.0 (Linux; U; Android 11; PCLM10 Build/RKQ1.200928.002)',
'cookie'=>'qpaybuff=;'
]);*/

preg_match('/qb_balance" : ([0-9]+),/si',$url,$qb);//Q币
preg_match('/"ret" : (.*?),/',$url,$ret);//状态码
preg_match('/"balance":"(.*?)"/',$url,$money);//元
//$ret=$ret[1][0];
//preg_match_all('/"qb_balance" : (.*?),/',$url,$qb_balance);
//$qb_balance=$qb_balance[1][0];
//$qb_balance=($qb_balance/100);
//$url=file_get_contents("https://xn--ehqa2882c.cc/api/qqqb/api.php?qq=".$qq."&skey=".$skey."");
//preg_match_all('/balance":"(.*?)"/',$url,$balance);
//$balance=$balance[1][0];
//$balance=($balance/100);
//$ret = $url["retcode"];


echo $url;


if($ret[1]==0){

    $money = ($money[1] / 100);

    $qb = ($qb[1] / 100);

    echo "可用余额:".$money?:'0';

    echo "元\n";

    echo "可用Q币:".$qb."个";

}else{

    echo "查看失败，请重试!";

}

/*
function need::GTK($skey){
$len = strlen($skey);
$hash = 5381;
for ($i = 0; $i < $len; $i++) {
$hash += ($hash << 5 & 2147483647) + ord($skey[$i]) & 2147483647;
$hash &= 2147483647;
}
return $hash & 2147483647;
}
*/

?>