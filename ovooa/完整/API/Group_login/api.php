<?php

//die('滚蛋');

require ("../../need.php");

require ("Exception.php");
require ("PHPMailer.php");
require ("SMTP.php");

date_default_timezone_set('PRC');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$accept = $_REQUEST["accept"];//收件人
$id = @$_REQUEST['id'];
$name = $_REQUEST["name"]?:"独角兽";//发件人名字
$format = @$_REQUEST['format'];
$uin = explode('@', $accept)[0];
try{
	$MySQL = new MySQLi('localhost', 123456789, 123456789, 123456789, 3306);
} catch (\Exception $e) {
	die();
}
$MySQL->query("set names utf8mb4");//设置数据库集编码
$table = 'Group_login';
$query = "SHOW TABLES LIKE '{$table}'";
$result = $MySQL->query($query)->fetch_all();
// print_r($result);
if(!$result)
{
	$MySQL->query("CREATE TABLE `123456789`.`{$table}` ( `id` INT NOT NULL AUTO_INCREMENT , `uin` BIGINT NOT NULL COMMENT '账号' , `Md5` VARCHAR(32) NOT NULL COMMENT 'Md5key' , `verify` VARCHAR(5) NOT NULL COMMENT '是否验证完成', `time` BIGINT NOT NULL COMMENT '时间' , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
	// echo 123456789;
}
if(!empty($id) && $format != 1)
{
	$query = "SELECT * FROM `{$table}` WHERE `Md5` = '{$id}'";
	$uin ? $query = "SELECT * FROM `{$table}` WHERE `uin` = {$uin}" : '';
	$result = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
	// print_r($result);
	if(!$result)
	{
		die(false);
	} else if($result[0]['verify'] == 'true')
	{
		die('true');
	}else{
		if(time() - $result[0]['time'] > 180)
		{
		// echo $result[0]['Md5'];
			die(false);
		}
		if($id != 1 && $id == $result[0]['Md5'])
		{ 
			if($MySQL->query("UPDATE `{$table}` SET `verify` = 'true' WHERE `{$table}`.`id` = ".$result[0]['id'])){
				die('你已完成验证');
			}else{
				die(false);
			}
		}else{
			die(false);
		}
	}
}
$title = '入群验证';
$Md5 = Md5($accept. '+' . uniqid());
$Message = 'https://ovooa.com/Group_login.api?id='.$Md5;
if(need::is_email($accept))
{
	$result = $MySQL->query("SELECT * FROM `{$table}` WHERE `uin` = {$uin}")->fetch_all(MYSQLI_ASSOC);
	// print_r($result);
	if($result)
	{
		if(time() - $result[0]['time'] < 60)
		{
			die(false);
		}else{
			$MySQL->query("UPDATE `{$table}` SET `Md5` = '{$Md5}', `time` = '".time()."', `verify` = 'false' WHERE `{$table}`.`id` = ".$result[0]['id']);
		}
	}else{
		if(!$MySQL->query("INSERT INTO `Group_login` (`id`, `uin`, `Md5`, `verify`, `time`) VALUES (NULL, '{$uin}', '{$Md5}', 'false', '".time()."')"))
		{
			die(false);
		}
	}
} else {
	die('邮箱错误');
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
    $mail->Body    = '<div style="background-color:#448AFF;color:#448AFF;padding:15px;"><p style="font-weight:bold;color:#fff;font-size:20px;text-align:center;white-space:pre;">'.$title.'</p></div><div style="background-color:#fff;padding:10px;border:2px solid #448AFF;"><p style="color:#000;font-size:15px;text-align:center;"><center><h2>点击<a href="'.$Message.'">这里</a>进行入群验证</h2></center></p><p style="color:#000;font-size:15px;text-align:center;">'.date('Y-m-d h:i:s').'</p></div><br /><center><a href="https://ovooa.com/">三三酱API</a></center>';//内容
    $mail->AltBody = $Message;

    $mail->send();
    die(true);
} catch (Exception $e) {
    die('验证失败');
}


