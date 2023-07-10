<?php
// header('content-type: text/html; charset="utf-8";');
// die('跑路了，江湖再见');
// error_reporting(false);//关闭报错 防止MySQL被打自闭api不能运行
header('Access-Control-Allow-Origin: *'); // *代表允许任何网址请求
header('Access-Control-Allow-Methods: POST,GET'); // 允许请求的类型
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin'); // 设置允许自定义请求头的字段

//require __DIR__ . '/Api_Lock_CC.php';
require_once '../../r.php';
/*
 * 添加访问记录
 * @return bool 添加是否成功
 */
function addAccess()
{
	require __DIR__ . '../../Core/Database/connect.php';
	// print_r($_SERVER);exit;
	$host = $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . '?' . $db->real_escape_string(urldecode($_SERVER['QUERY_STRING']));
	$protocol = $_SERVER["SERVER_PROTOCOL"];
	$method = $_SERVER["REQUEST_METHOD"];
	$ip = $_SERVER["REMOTE_ADDR"];
	$time = $_SERVER["REQUEST_TIME"];
	$result = $db->query("INSERT INTO `mxgapi_access`(`id`, `host`, `protocol`, `method`, `ip`, `time`) VALUES (NULL,'{$host}','{$protocol}','{$method}','{$ip}','{$time}');");
	if ($result) {
		return true;
	} else {
		return false;
	}
}

/* 
 * 添加接口统计函数
 * @param int $id 接口id
 * @return bool 添加成功为true，失败则为false
 */
function addApiAccess($id)
{
	require __DIR__ . '../../Core/Database/connect.php';
	if(!$db->query("SELECT * FROM information_schema.TABLES WHERE table_name = 'mxgapi_access_api';")->fetch_array(1))
	{
		$db->query("CREATE TABLE `123456789`.`mxgapi_access_api` (`id` INT NOT NULL AUTO_INCREMENT , `access` BIGINT NOT NULL , `day` VARCHAR(20) NOT NULL , `time` VARCHAR(25) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
	}
	/*
	if (intval($id)) {
		$query = "SELECT status FROM `mxgapi_api` WHERE `id` = {$id}";
		$data = $db->query($query);
		$data = $data->fetch_assoc();
		if($data['status'] == 0){
			Switch($_REQUEST['type']?:$_REQUEST['Type']){
				case 'text':
				die('接口在维护中哦~请客观稍等亿下~');
				break;
				default:
				echo json_encode(array('code'=>-100,'text'=>'接口在维护中哦~请稍等亿下~'),320);
				die();
				break;
			}
		}else{
			$get_access = $db->query("SELECT access FROM `mxgapi_api` WHERE `id` = '{$id}';");
			if ($get_access) {
				$get_access = $get_access->fetch_assoc();
				$update_access = $get_access['access'] + 1;
				$update_result = $db->query("UPDATE `mxgapi_api` SET `access` = '{$update_access}' WHERE `id` = '{$id}';");
				if ($update_result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	} else {
		return false;
	}*/
	
	if($id = intval($id))
	{
		if($result = $db->query("select * from `mxgapi_api` where `id` = {$id}")->fetch_array(1))
		{
			if($result['status'] == 0)
			{
				switch((isset($_REQUEST['type']) ? $_REQUEST['type'] : 'json'))
				{
					case 'text':
						exit('接口维护中…');
					break;
					default:
						exit(json_encode(array('code'=>-100, 'text'=>'接口维护中…'), 460));
					break;
				}
				return false;
			} else {
				if($result = $db->query("select * from `mxgapi_access_api` where `day` = '". date('Ymd', time()) . "';")->fetch_array(1))
				{
					$access = ($result['access']);
					$access++;
					$db->query("update `mxgapi_access_api` set `access` = '{$access}' where `id` = {$result['id']}");
				} else {
					$day = date('Ymd', time());
					$time = time();
					$db->query("INSERT INTO `mxgapi_access_api` (`id`, `access`, `day`, `time`) VALUES (NULL, '1', '{$day}', '{$time}')");
				}
				
				if ($get_access = $db->query("SELECT access FROM `mxgapi_api` WHERE `id` = '{$id}';"))
				{
					$get_access = $get_access->fetch_assoc();
					$update_access = $get_access['access'] + 1;
					$db->query("UPDATE `mxgapi_api` SET `access` = '{$update_access}' WHERE `id` = '{$id}';");
				}
			}
		}
	}
}
/*
* 添加访问数量
*/
function addaccessapi()
{
	$result = $db->query("SELECT * FROM `mxgapi_access_api` WHERE `time` LIKE '".date('Ymd')."'")->fetch_all(MYSQLI_ASSOC);
	if(!$result)
	{
		$result = $db->query("INSERT INTO `mxgapi_access_api` (`id`, `access`, `time`) VALUES (NULL, '1', '".date('Ymd')."')");
	} else {
		$num = ($result[0]['access'] + 1);
		$result = $db->query("UPDATE `mxgapi_access_api` SET `access` = '{$num}' WHERE `mxgapi_access_api`.`id` = ".$result[0]['id']);
	}
	if ($result) {
		return true;
	} else {
		return false;
	}
}
//Curl请求，参数：地址，方法，头，参数
function curl($url, $method, $headers, $params){
	if (is_array($params)) {
		$requestString = http_build_query($params);
	} else {
		$requestString = $params ? : '';
	}
	if (empty($headers)) {
		$headers = array('Content-type: text/json'); 
	} elseif (!is_array($headers)) {
		parse_str($headers,$headers);
	}
	// setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	// setting the POST FIELD to curl
	switch ($method){  
		case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, 1);break;  
		case "POST": curl_setopt($ch, CURLOPT_POST, 1);
					 curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
		case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
					 curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
		case "DELETE":  curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");   
						curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
	}
	// getting response from server
	$response = curl_exec($ch);
	
	//close the connection
	curl_close($ch);
	
	//return the response
	if (stristr($response, 'HTTP 404') || $response == '') {
		return array('Error' => '请求错误');
	}
	return $response;
} 