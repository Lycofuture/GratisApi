<?php

//die('滚蛋');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(71); // 调用统计函数
/* End */

require ("../../need.php");

require ("user.php");

require ("ICP/anquan.php");

require ("Exception.php");
require ("PHPMailer.php");
require ("SMTP.php");

date_default_timezone_set('PRC');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$accept = $_GET["accept"];//收件人

$msg = $_GET["msg"];//邮件内容

$title = $_GET["title"]?:"这是默认标题";//标题

$name = $_GET["name"]?:"三三酱";//发件人名字

$meat = need::json($_GET);

if(preg_match('/(.*?)('.$Violation.')(.*?)/',$meat,$meat)){

exit(need::json(array("code"=>"-4","text"=>"发送失败！邮件内容带有违禁词！")));

}

if(preg_match('/([0-9]+)@/',$accept,$qq)){

$qq=$qq[1];

if(!preg_match('/[1-9][0-9]{5,11}/',$qq)){

exit(need::json(array("code"=>"-5","text"=>"QQ号不正确！")));

}else{

if (empty($accept)){

echo need::json(array("code"=>"-1","text"=>"请输入收件人"));exit;

}

if(empty($msg)){

echo need::json(array("code"=>"-2","text"=>"请输入发件内容"));exit;

}


$mail = new PHPMailer(true); //Passing `true` enables exceptions
try {
    //服务器配置
    $mail->CharSet ="utf-8"; //设定邮件编码
    $mail->SMTPDebug = 0; //调试模式输出
    $mail->isSMTP(); //使用SMTP
    $mail->Host = "smtp.qq.com"; //SMTP服务器
    $mail->SMTPAuth = true; //允许 SMTP 认证
    $mail->Username = "sanapi@foxmail.com"; //SMTP 用户名  即邮箱的用户名
    $mail->Password = "jywvinlxktvmecaf"; //SMTP 密码  部分邮箱是授权码(例如163邮箱)
    $mail->SMTPSecure = "ssl"; //允许 TLS 或者ssl协议
    $mail->Port = 465; //服务器端口 25 或者465 具体要看邮箱服务器支持

    $mail->setFrom("sanapi@foxmail.com", $name); //发件人
    $mail->addAddress($accept); //收件人
    //$mail->addAddress("ellen@example.com"); //可添加多个收件人
    //$mail->addReplyTo("xxxx@163.com", "info"); //回复的时候回复给哪个邮箱 建议和发件人一致
    //$mail->addCC("cc@example.com"); //抄送
    //$mail->addBCC("bcc@example.com"); //密送

    //发送附件
    //$mail->addAttachment("../xy.zip"); //添加附件
    //$mail->addAttachment("../thumb-1.jpg", "new.jpg"); //发送附件并且重命名

    //Content
    $mail->isHTML(true); //是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
    /*return '<div style="background-color:#448AFF;color:#448AFF;padding:15px;">
        		<p style="font-weight:bold;color:#fff;font-size:20px;text-align:center;white-space:pre;">标 题</p>
        	</div>
        	<div style="background-color:#fff;padding:10px;border:2px solid #448AFF;">
        		<p style="color:#000;font-size:15px;">测试内容</p>
        		<p style="color:#000;font-size:15px;text-align:center;">'.date('Y-m-d h:i:s').'</p>
        	</div>';

@Ros-枫语 - A鸭*/

    $mail->Subject = "".$title."" ;//标题
    $mail->Body    = '<div style="background-color:#448AFF;color:#448AFF;padding:15px;"><p style="font-weight:bold;color:#fff;font-size:20px;text-align:center;white-space:pre;">'.$title.'</p></div><div style="background-color:#fff;padding:10px;border:2px solid #448AFF;"><p style="color:#000;font-size:15px;text-align:center;"><center><h2>'.$msg.'</h2></center></p><p style="color:#000;font-size:15px;text-align:center;">'.date('Y-m-d h:i:s').'</p></div><br /><center><a href="https://ovooa.com/">三三酱API</a></center>';//内容
    $mail->AltBody = $msg;

    $mail->need::send();
    echo need::json(array("code"=>"1","text"=>"邮件发送成功"));
} catch (Exception $e) {
    echo need::json(array("code"=>"-3","text"=>"邮件发送失败：" . $mail->ErrorInfo));
}

}}else{

if (empty($accept)){

echo need::json(array("code"=>"-1","text"=>"请输入收件人"));exit;

}

if(empty($msg)){

echo need::json(array("code"=>"-2","text"=>"请输入发件内容"));exit;

}


$mail = new PHPMailer(true); //Passing `true` enables exceptions
try {
    //服务器配置
    $mail->CharSet ="utf-8"; //设定邮件编码
    $mail->SMTPDebug = 0; //调试模式输出
    $mail->isSMTP(); //使用SMTP
    $mail->Host = "smtp.qq.com"; //SMTP服务器
    $mail->SMTPAuth = true; //允许 SMTP 认证
    $mail->Username = "sanapi@vip.qq.com"; //SMTP 用户名  即邮箱的用户名
    $mail->Password = "hcqhyvnliowhdhgc"; //SMTP 密码  部分邮箱是授权码(例如163邮箱)
    $mail->SMTPSecure = "ssl"; //允许 TLS 或者ssl协议
    $mail->Port = 465; //服务器端口 25 或者465 具体要看邮箱服务器支持

    $mail->setFrom("sanapi@vip.qq.com", $name); //发件人
    $mail->addAddress($accept); //收件人
    //$mail->addAddress("ellen@example.com"); //可添加多个收件人
    //$mail->addReplyTo("xxxx@163.com", "info"); //回复的时候回复给哪个邮箱 建议和发件人一致
    //$mail->addCC("cc@example.com"); //抄送
    //$mail->addBCC("bcc@example.com"); //密送

    //发送附件
    //$mail->addAttachment("../xy.zip"); //添加附件
    //$mail->addAttachment("../thumb-1.jpg", "new.jpg"); //发送附件并且重命名

    //Content
    $mail->isHTML(true); //是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
    /*return '<div style="background-color:#448AFF;color:#448AFF;padding:15px;">
        		<p style="font-weight:bold;color:#fff;font-size:20px;text-align:center;white-space:pre;">标 题</p>
        	</div>
        	<div style="background-color:#fff;padding:10px;border:2px solid #448AFF;">
        		<p style="color:#000;font-size:15px;">测试内容</p>
        		<p style="color:#000;font-size:15px;text-align:center;">'.date('Y-m-d h:i:s').'</p>
        	</div>';

@Ros-枫语 - A鸭*/

    $mail->Subject = "".$title."" ;//标题
    $mail->Body    = '<div style="background-color:#448AFF;color:#448AFF;padding:15px;"><p style="font-weight:bold;color:#fff;font-size:20px;text-align:center;white-space:pre;">'.$title.'</p></div><div style="background-color:#fff;padding:10px;border:2px solid #448AFF;"><p style="color:#000;font-size:15px;text-align:center;"><center><h2>'.$msg.'</h2></center></p><p style="color:#000;font-size:15px;text-align:center;">'.date('Y-m-d h:i:s').'</p></div><br /><center><a href="https://ovooa.com/">三三酱API</a></center>';//内容
    $mail->AltBody = $msg;

    $mail->need::send();
    echo need::json(array("code"=>"1","text"=>"邮件发送成功"));
} catch (Exception $e) {
    echo need::json(array("code"=>"-3","text"=>"邮件发送失败：".$mail->ErrorInfo));
}
}

?>