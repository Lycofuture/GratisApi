<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数

require ("../../need.php");

require ("../../curl.php");

/* End */

$msg = $_GET['msg'];

$sc = $_GET["sc"]?:"10";

$b = $_GET['n'];

$p = $_GET["p"]?:"1";

$data=need::teacher_curl('https://c.y.qq.com/soso/fcgi-bin/client_search_cp?aggr=0&catZhida=1&lossless=0&sem=1&w='.$msg.'&n='.$sc.'&t=12&p='.$p.'&remoteplace=sizer.yqqlist.mv&hostUin=0&format=jsonp&inCharset=utf-8&outCharset=utf-8',[
'ua'=>'Mozilla/5.0 (Linux; Android 10; PCLM10 Build/QKQ1.191021.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.92 Mobile Safari/537.36',
'refer'=>'https://c.y.qq.com',
'cookie'=>'idt=1607829124; RK=WeIx1/8yGH; ptcz=70716ea3513123b5444b8225b37ca58713ac78be23fb713e989cfd4d1702d54e; pgv_pvi=5568916480; pgv_si=s8715523072; qm_authimgs_id=2; qm_verifyimagesession=h0100e8e214be18e6e9c1f4b94d719618dfc937becf3af3b8e8ad216f0305309d53cb0a121a1a9228b0; pgv_pvid=5260692045; tvfe_boss_uuid=e4d8b209c3ac5bbc; pgv_info=ssid=s3290801536; o_cookie=2354452553; ts_uid=4968718114; _qpsvr_localtk=0.656222836757496; ptui_loginuin=2354452553; euin=owok7evkow4koz**; tmeLoginType=2; yq_index=0; yq_playschange=1; player_exist=0; yq_playdata=s_0_1_2; yplayer_open=0; ts_refer=ADTAGmyqq; psrf_access_token_expiresAt=1618338023; yqq_stat=0',
'Header'=>[
'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
accept-encoding: gzip, deflate
accept-language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
]
]);
$json = json_decode($data, true);
$s=count($json["data"]["mv"]["list"]);

echo $data;


?>