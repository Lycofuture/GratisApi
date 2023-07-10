<?php
/* 初始化 */
require 'init.php';

$req = $_REQUEST;
switch ($req["type"]) {

		/* 添加API */
	case 'add_api':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		preg_match_all("/\[(.*?)--(.*?)--(.*?)--(.*?)\]/", $req["request_parameter"], $a, PREG_SET_ORDER);
		foreach ($a as $k => $v) {
			$request[] = [
				"name" => $a[$k][1],
				"type" => $a[$k][2],
				"required" => $a[$k][3],
				"info" => $a[$k][4],
			];
		}
		$request2 = [
			"data" => $request
		];
		$request_json = json_encode($request2, 320);
		preg_match_all("/\[(.*?)--(.*?)--(.*?)\]/", $req["return_parameter"], $b, PREG_SET_ORDER);
		foreach ($b as $k => $v) {
			$return[] = [
				"name" => $b[$k][1],
				"type" => $b[$k][2],
				"msg" => $b[$k][3],
			];
		}
		$return2 = [
			"data" => $return
		];
		$return_json = json_encode($return2, 320);
		preg_match_all("/\[(.*?)--(.*?)\]/", $req["error_code"], $a, PREG_SET_ORDER);
		foreach ($a as $k => $v) {
			$errorcode[] = [
				"code" => $a[$k][1],
				"msg" => $a[$k][2],
			];
		}
		$errorcode2 = [
			"data" => $errorcode
		];
		$errorcode_json = json_encode($errorcode2, 320);

		$name = $req['name'];
		$enname = $req['enname'];
		$desc = $req['desc'];
		$url = $req['url'];
		$example_url = $req['example_url'];
		$format = $req['format'];
		$method = $req['method'];
		$PHP_example = str_replace("'","\\'",$req['PHP_example']);
		$return = str_replace("'","\\'",$req['return']);
		$time = time();
		if ($name && $enname && $desc && $url && $example_url && $format && $method && $request_json && $return_json && $errorcode_json && $PHP_example && $return) {
			$add_result = $db->query("INSERT INTO `mxgapi_api`(`id`, `name`, `enname`, `desc`, `url`, `format`, `method`, `example_url`, `request_parameter`, `return_parameter`, `error_code`, `PHP_example`, `return`, `time`, `access`, `status`) VALUES (NULL,'{$name}','{$enname}','{$desc}','{$url}','{$format}','{$method}','{$example_url}','{$request_json}','{$return_json}','{$errorcode_json}','{$PHP_example}','{$return}', '{$time}', 0, 1);");
			if ($add_result) {
				$arr = $db->query("SELECT * FROM `mxgapi_api` ORDER BY `mxgapi_api`.`id` DESC");
				$arr = $arr->fetch_all(MYSQLI_ASSOC);
				$id = $arr[0]['id'];
				$name = $arr[0]['name'];
				$desc = $arr[0]['desc'];
				jsonError(0, '添加成功');
			} else {
				jsonError(-1, '添加失败'.$db->content);
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 删除API */
	case 'del_api':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req["id"]);
		if ($id) {
			$arr = $db->query("SELECT * FROM `mxgapi_api` WHERE `id` = {$id}")->fetch_all(MYSQLI_ASSOC);
			$name = $arr[0]['name'];
			$desc = $arr[0]['desc'];
			$result = $db->query("DELETE FROM `mxgapi_api` WHERE `id`='{$id}';");
			if ($result) {
				jsonError(0, '删除成功');
			} else {
				jsonError(-1, '删除失败');
			}
		} else {
			jsonError(-1, '缺少参数');
		}
		break;

		/* 修改API信息 */
	case 'edit_api':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		/* 正则获取请求参数 */
		preg_match_all("/\[(.*?)--(.*?)--(.*?)--(.*?)\]/", $req["request_parameter"], $a, PREG_SET_ORDER);
		foreach ($a as $k => $v) {
			$request[] = [
				"name" => $a[$k][1],
				"type" => $a[$k][2],
				"required" => $a[$k][3],
				"info" => $a[$k][4],
			];
		}
		$request2 = [
			"data" => $request
		];
		$request_json = json_encode($request2, 320);
		/* 正则获取返回参数 */
		preg_match_all("/\[(.*?)--(.*?)--(.*?)\]/", $req["return_parameter"], $b, PREG_SET_ORDER);
		foreach ($b as $k => $v) {
			$return[] = [
				"name" => $b[$k][1],
				"type" => $b[$k][2],
				"msg" => $b[$k][3],
			];
		}
		$return2 = [
			"data" => $return
		];
		$return_json = json_encode($return2, 320);
		/*正则获取状态码 */
		preg_match_all("/\[(.*?)--(.*?)\]/", $req["error_code"], $a, PREG_SET_ORDER);
		foreach ($a as $k => $v) {
			$errorcode[] = [
				"code" => $a[$k][1],
				"msg" => $a[$k][2],
			];
		}
		$errorcode2 = [
			"data" => $errorcode
		];
		$errorcode_json = json_encode($errorcode2, 320);
		$id = intval($req['id']);
		$name = $req['name'];
		$enname = $req['enname'];
		$desc = $req['desc'];
		$url = $req['url'];
		$example_url = $req['example_url'];
		$format = $req['format'];
		$method = $req['method'];
		$request_parameter = $request_json;
		$return_parameter = $return_json;
		$error_code = $errorcode_json;
		$PHP_example = str_replace("'","\\'",$req['PHP_example']);
		$return = str_replace("'","\\'",$req['return']);
		$time = time();
		$status = $req['status'];
		if ($id && $name && $enname && $desc && $url && $example_url && $format && $method && $request_parameter && $return_parameter && $error_code && $PHP_example && $return) {
			$result = $db->query("UPDATE `mxgapi_api` SET `name`='{$name}',`enname`='{$enname}',`desc`='{$desc}',`url`='{$url}',`format`='{$format}',`method`='{$method}',`example_url`='{$example_url}',`request_parameter`='{$request_parameter}',`return_parameter`='{$return_parameter}',`error_code`='{$error_code}',`PHP_example`='{$PHP_example}',`return`='{$return}',`status`='{$status}', `time` = {$time} WHERE `id`='{$id}';");
			if ($result) {
				jsonError(0, '保存成功');
			} else {
				jsonError(-1, '保存失败');
			}
		} else {
			jsonError(-1, '缺少参数');
		}
		break;
		/* 添加广告 */
	case 'add_advertising':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$data = json_decode($_REQUEST['data'], true);
		
		$name = $data['name'];
		$url = $data['url'];
		$logo = $data['logo'];
		$description = $data['description'];
		$time = time();
		if ($name && $description && $url) {
			$add_result = $db->query("INSERT INTO `advertising` (`id`, `name`, `url`, `description`, `logo`, `access`, `time`) values (null, '{$name}', '{$url}', '{$description}', '{$logo}', '0', '{$time}');");
			if ($add_result) {
				jsonError(0, '添加成功');
			} else {
				jsonError(-1, '添加失败'.$db->content);
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 删除广告 */
	case 'del_advertising':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req["id"]);
		if ($id) {
			if($db->query("SELECT * FROM `advertising` WHERE `id` = {$id}")->fetch_all(1)) {
				if($db->query("DELETE FROM `advertising` WHERE `id`='{$id}';")) {
					jsonError(0, '删除成功');
				} else {
					jsonError(-1, '删除失败');
				}
			} else {
				jsonError(-1, '参数错误');
			}
		} else {
			jsonError(-1, '缺少参数');
		}
		break;

		/* 修改广告信息 */
	case 'edit_advertising':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$data = json_decode($_REQUEST['data'], true);
		$id = $data['id'];
		$name = $data['name'];
		$url = $data['url'];
		$logo = $data['logo'];
		$description = $data['description'];
		$time = time();
		$status = $req['status'];
		if ($id && $name && $url && $description) {
			$result = $db->query("UPDATE `advertising` SET `name` = '{$name}' ,`url` = '{$url}', `description` = '{$description}', `logo` = '{$logo}', `time` = '{$time}' WHERE `id`='{$id}';");
			if ($result) {
				jsonError(0, '保存成功');
			} else {
				jsonError(-1, '保存失败');
			}
		} else {
			jsonError(-1, '缺少参数');
		}
		break;

		/* 登录到后台 */
	case 'login':
		$result = $db->query("SELECT * FROM `mxgapi_config`")->fetch_assoc();
		//print_r($result);
		if (trim($req["username"]) && trim($req["password"])) {
		} else {
			jsonError(-1, '请输入完整');
		}
		if (trim($req["username"]) == $result["username"] && trim($req["password"]) == $result["password"]) {
			$_SESSION['login'] = 'admin';
			$ip = $_SERVER["REMOTE_ADDR"];
			$address = curl("http://opendata.baidu.com/api.php?query={$ip}&co=&resource_id=6006&t=1433920989928&ie=utf8&oe=utf-8&format=json", 'GET', 0, 0);
			$address = json_decode($address, true);
			$address = isset($data['data'][0]['location']) ? $data['data'][0]['location'] : '接口寄了';
			// $address = $address['text']?:'接口用不了了呜呜呜';
			$time = time();
			if ($address && $ip) {
				$addLog = $db->query("INSERT INTO `mxgapi_login_log` (`id`, `ip`, `address`, `time`) VALUES (NULL, '{$ip}', '{$address}', '{$time}');");
				if ($addLog) {
					jsonError(0, '登录成功');
				} else {
					jsonError(-1, '未知错误');
				}
			} else {
				jsonError(-1, '获取失败');
			}
			jsonError(0, '登录成功');
		} else {
			jsonError(-1, '用户名或密码错误');
		}
		break;

		/* 添加友情链接 */
	case 'add_link':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$name = $req["name"];
		$desc = $req["desc"];
		$url = $req["url"];
		$picurl = $req["picurl"];
		$time = time();
		if ($name && $desc && $url && $picurl) {
			$result = $db->query("INSERT INTO `mxgapi_friendlinks`(`id`, `name`, `desc`, `url`, `picurl`, `time`) VALUES (NULL,'{$name}','{$desc}','{$url}','{$picurl}','{$time}')");
			if ($result) {
				jsonError(0, '添加成功');
			} else {
				jsonError(-1, '添加失败');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 删除友情链接 */
	case 'del_link':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req["id"]);
		if (!$id) {
			jsonError(-1, '缺少参数');
		}
		$result = $db->query("DELETE FROM `mxgapi_friendlinks` WHERE `id`='{$id}';");
		if ($result) {
			jsonError(0, '删除成功');
		} else {
			jsonError(-1, '删除失败');
		}
		break;

		/* 修改友情链接 */
	case 'edit_link':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req['id']);
		$name = $req['name'];
		$desc = $req['desc'];
		$url = $req['url'];
		$picurl = $req['picurl'];
		if ($id && $name && $desc && $url && $picurl) {
			$result = $db->query("UPDATE `mxgapi_friendlinks` SET `name`='{$name}',`desc`='{$desc}',`url`='{$url}',`picurl`='{$picurl}' WHERE `id`='{$id}';");
			if ($result) {
				jsonError(0, '保存成功');
			} else {
				jsonError(-1, '保存失败');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 修改后台登录密码 */
	case 'edit_pwd':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$password = $req['password'];
		$password2 = $req['password2'];
		if (trim($password) && trim($password2)) {
			if ($password != $password2) {
				jsonError(-1, '两次密码不一致');
			}
			$result = $db->query("UPDATE `mxgapi_config` SET `password`='{$password}';");
			if ($result) {
				jsonError(0, '修改成功');
			} else {
				jsonError(1, '修改失败');
			}
		} else {
			jsonError(-1, '请输入完整！');
		}
		break;

		/* 修改网站配置信息 */
	case 'websetting':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$title = $req["title"];
		$subtitle = $req["subtitle"];
		$keywords = $req["keywords"];
		$description = $req["description"];
		$favicon = $req["favicon"];
		$url = $req["url"];
		$icp = $req["icp"];
		$copyright = $req["copyright"];
		$end_script = $req["end_script"];
		$accent = $req["accent"];
		if ($title && $subtitle && $keywords && $description && $favicon && $url && $copyright) {
			$result = $db->query("UPDATE `mxgapi_config` SET `title`='{$title}',`subtitle`='{$subtitle}',`description`='{$description}',`keywords`='{$keywords}',`favicon`='{$favicon}',`url`='{$url}',`icp`='{$icp}',`copyright`='{$copyright}',`accent`='blue',`end_script`='{$end_script}';");
			if ($result) {
				jsonError(0, '保存成功');
			} else {
				jsonError(-1, '保存失败');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 修改邮件配置信息 */
	case 'smtp_config':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$smtp_host = $req['smtp_host'];
		$smtp_username = $req['smtp_username'];
		$smtp_password = $req['smtp_password'];
		$smtp_port = $req['smtp_port'];
		$smtp_secure = $req['smtp_secure'];
		if ($smtp_host && $smtp_username && $smtp_password && $smtp_port) {
			$result = $db->query("UPDATE `mxgapi_config` SET `smtp_host`='{$smtp_host}',`smtp_username`='{$smtp_username}',`smtp_password`='{$smtp_password}',`smtp_port`='{$smtp_port}',`smtp_secure`='{$smtp_secure}';");
			if ($result) {
				jsonError(0,'保存成功');
			} else {
				jsonError(-1,'保存失败');
			}
		} else {
			jsonError(-1,'请输入完整');
		}
		break;

	case 'close_site':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$close_site = $req['close_site'];
		$now = $db->query('SELECT close_site FROM `mxgapi_config`;')->fetch_assoc();
		$now = $now['close_site'];
		$sql = "UPDATE `mxgapi_config` SET `close_site`=";

		if ($close_site == '1') {
			if ($close_site != $now) {
				$sql .= 'true';
				$msg = '开启成功';
			} else {
				jsonError(-1, '已经开启了');
			}
		} else if ($close_site == '0') {
			if ($close_site != $now) {
				$sql .= 'false';
				$msg = '关闭成功';
			} else {
				jsonError(-1, '已经关闭了');
			}
		}
		if ($result = $db->query($sql)) {
			jsonError(0, $msg);
		} else {
			jsonError(-1, '未知原因');
		}
		break;



	case 'cc_protect':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$cc_protect = $req['cc_protect'];
		$now = $db->query('SELECT cc_protect FROM `mxgapi_config`;')->fetch_assoc();
		$now = $now['cc_protect'];
		$sql = "UPDATE `mxgapi_config` SET `cc_protect`=";

		if ($cc_protect == '1') {
			if ($cc_protect != $now) {
				$sql .= 'true';
				$msg = '开启成功';
			} else {
				jsonError(-1, '已经开启了');
			}
		} else if ($cc_protect == '0') {
			if ($cc_protect != $now) {
				$sql .= 'false';
				$msg = '关闭成功';
			} else {
				jsonError(-1, '已经关闭了');
			}
		}
		if ($result = $db->query($sql)) {
			jsonError(0, $msg);
		} else {
			jsonError(-1, '未知原因');
		}
		break;


	case 'fire_wall':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$fire_wall = $req['fire_wall'];
		$now = $db->query('SELECT fire_wall FROM `mxgapi_config`;')->fetch_assoc();
		$now = $now['fire_wall'];
		$sql = "UPDATE `mxgapi_config` SET `fire_wall`=";

		if ($fire_wall == '1') {
			if ($fire_wall != $now) {
				$sql .= 'true';
				$msg = '开启成功';
			} else {
				jsonError(-1, '已经开启了');
			}
		} else if ($fire_wall == '0') {
			if ($fire_wall != $now) {
				$sql .= 'false';
				$msg = '关闭成功';
			} else {
				jsonError(-1, '已经关闭了');
			}
		}
		if ($result = $db->query($sql)) {
			jsonError(0, $msg);
		} else {
			jsonError(-1, '未知原因');
		}
		break;

		/* 修改后台用户信息 */
	case 'edit_user':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$username = $req['username'];
		$email = $req['email'];
		$qq = $req['qq'];
		$qqqrcode = $req['qqqrcode'];
		$vxqrcode = $req['vxqrcode'];
		$aliqrcode = $req['aliqrcode'];
		if ($username && $email && $qq) {
			$result = $db->query("UPDATE `mxgapi_config` SET `username`='{$username}',`email`='{$email}',`qq`='{$qq}',`qqqrcode`='{$qqqrcode}',`vxqrcode`='{$vxqrcode}',`aliqrcode`='{$aliqrcode}';");
			if ($result) {
				jsonError(0, '修改成功');
			} else {
				jsonError(-1, '修改失败');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 添加公告 */
	case 'add_post':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$title = $req["title"];
		$content = $req["content"];
		$icon = $req['icon'];
		$time = time();
		if ($title && $content && $icon) {
			$result = $db->query("INSERT INTO `mxgapi_post`(`id`, `title`, `content`, `icon`, `time`) VALUES (NULL,'{$title}','{$content}','{$icon}','{$time}')");
			if ($result) {
				jsonError(0, '添加成功');
			} else {
				jsonError(-1, '添加失败');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 修改公告信息 */
	case 'edit_post':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req['id']);
		$title = $req['title'];
		$content = $req['content'];
		$icon = $req['icon'];
		if ($id && $title && $content && $icon) {
			$result = $db->query("UPDATE `mxgapi_post` SET `title`='{$title}',`content`='{$content}',`icon`='{$icon}' WHERE `id`='{$id}';");
			if ($result) {
				jsonError(0, '修改成功');
			} else {
				jsonError(-1, '修改失败');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 删除公告 */
	case 'del_post':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req["id"]);
		if ($id) {
			if ($id == 1) {
				jsonError(-1, '默认公告不能删除');
			}
			$result = $db->query("DELETE FROM `mxgapi_post` WHERE `id`='{$id}';");
			if ($result) {
				jsonError(0, '删除成功');
			} else {
				jsonError(-1, '删除失败');
			}
		} else {
			jsonError(-1, '缺少参数');
		}
		break;

		/* 更改选择公告ID */
	case 'change_post_id':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req['id']);
		if ($id) {
			if ($id != -1) {
				$post = $db->query("select * from `mxgapi_post` where `id`='{$id}';")->fetch_assoc();
				if (!$post) {
					jsonerror(-1, '公告不存在');
				}
			}
			$result = $db->query("UPDATE `mxgapi_config` SET `post_id`='{$id}';");
			if ($result) {
				jsonError(0, '设置成功');
			} else {
				jsonError(-1, '设置失败');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 添加反馈 */
	case 'add_feedback':
	    $Cookie = $_COOKIE;
	    if(empty($Cookie['PHPSESSID'])){
	        jsonError(-2,'反馈失败');
	    }
	    $time = $_REQUEST['time'];
	    if(empty($time)){
	        jsonError(-2,'反馈失败');
	    }
	    if(md5($Cookie['PHPSESSID'].$_SERVER['REQUEST_TIME'].'000') != md5($Cookie['PHPSESSID'].$time)){
	        jsonError(-2,'反馈失败');
	    }
		$ip = $_SERVER["REMOTE_ADDR"];
		$api_id = $req['api_id'];
		$api_name = $req['api_name'];
		$title = $req["title"];
		$content = $req["content"];
		$email = $req['email'];
		$time = time();
		if (!checkEmail($email)) {
			jsonError(-1, '邮箱格式不正确');
		}
		if (trim($title) && trim($content) && $ip && $email && $api_id && $api_name) {
			if ($_SESSION['feedback'][$ip]['api_id'] != $api_id) {
				$result = $db->query("INSERT INTO `mxgapi_feedback`(`id`,`api_id`, `api_name`, `title`, `content`, `ip`, `email`, `time`) VALUES (NULL,'{$api_id}','{$api_name}','{$title}','{$content}--《{$api_name}》','{$ip}','{$email}','{$time}')");
				if ($result) {
					$_SESSION['feedback'][$ip]['api_id'] = $api_id;
					$result = $db->query("SELECT * FROM `mxgapi_config`")->fetch_all(1);
					$email = (isset($result[0]['email']) && $result[0]['email'] ? $result[0]['email'] : '2354452553@qq.com');
					$English = sendMail($email, '有新反馈！', '还不快来看看反馈了什么！<br>'."{$content}--《{$api_name}》");
					jsonError(0, '反馈成功');
				} else {
					jsonError(-1, '反馈失败');
				}
			} else {
				jsonError(-1, '你已经反馈过该接口了');
			}
		} else {
			jsonError(-1, '请输入完整');
		}
		break;

		/* 删除反馈 */
	case 'del_feedback':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$id = intval($req["id"]);
		if ($id) {
			$result = $db->query("DELETE FROM `mxgapi_feedback` WHERE `id`='{$id}';");
			if ($result) {
				jsonError(0, '删除成功');
			} else {
				jsonError(-1, '删除失败');
			}
		} else {
			jsonError(-1, '缺少参数');
		}
		break;

		/* 回复反馈 */
	case 'reply_feedback':
		if (!isAdmin()) {
			jsonError(-1, '用户未登录');
		}
		$email = $req['email'];
		$content = $req['content'];
		if ($email && $content) {
			$result = sendMail($email, '反馈接口信息', $content);
			die($result);
		} else {
			jsonError(-1, '请输入完整');
		}
		break;
}
