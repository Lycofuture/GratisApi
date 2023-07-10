<?php 
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(1); // 调用统计函数
/* End */
	if($_GET['type'] == 'image'){
		$imgurl = 'http://ai.kenaisq.top/API/60s.php?address=不为空则不显示地址&name=我';
	}	
    header("Location:".$imgurl);//跳转输出图片    
?>