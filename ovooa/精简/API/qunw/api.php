<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */



$qq = @$_GET["qq"];

$group = @$_GET["group"];

$Skey = (String)@$_GET["s"];

$bkn = need::GTK($Skey);

$data = need::teacher_curl('https://pan.qun.qq.com/cgi-bin/group_file/get_file_list?gc='.$group.'&bkn='.$bkn.'&start_index=0&cnt=50&filter_code=0&folder_id=%2F&show_onlinedoc_folder=1',[
"ua"=>"Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) QQ/9.3.6.27263 Chrome/43.0.2357.134 Safari/537.36 QBCore/3.43.1298.400 QQBrowser/9.0.2524.400",
"cookie"=>"uin=o".$qq."; skey=".$Skey."",
"refer"=>"https://pan.qun.qq.com/clt_filetab/groupShareClientNew.html?groupid=".$group.""
]);

$JSON = JSON_decode($data);//将JSON转换成php可用JSON

$code = $JSON->ec;//状态码

$file_list = $JSON->file_list;//列表

$count = count($file_list);//数量

foreach($file_list as $k=>$v){

echo $file_list[$k]->name."\r";

}

//exit(need::json($code));


