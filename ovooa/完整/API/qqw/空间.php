<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$Skey = $_GET["s"];

$qq = $_GET["qq"];

$pskey = $_GET["p"];

$bkn = need::GTK($Skey);

$data = need::teacher_curl('https://r.qzone.qq.com/cgi-bin/user/cgi_personal_card?uin='.$qq.'&g_tk='.$bkn,[
'cookie'=>'uin=o'.$qq.'; skey='.$Skey.'p_uin=o'.$qq//.'; bkn='.$bkn.'; g_tk='.$bkn
]);

exit($data);



