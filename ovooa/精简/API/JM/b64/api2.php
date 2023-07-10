<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数
/* End */
addAccess();//调用统计函数
$msg = $_GET['msg'];

if($msg=="")
{
echo "请输入要加密的内容";
}
if($msg!="")
{
echo base64_decode($msg);
}
else
{
echo "未知的错误";
}

?>