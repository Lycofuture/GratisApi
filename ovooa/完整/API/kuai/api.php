<?php
header('content-type:application/json');
/* Start */
/*
require ("../function.php"); // 引入函数文件
addApiAccess(1); // 调用统计函数
addAccess();//调用统计函数*/
require ('../../need.php');//引用封装好的函数文件
/* End */
$url = @need::request()['url'];
if(strstr($url, ' ')){
	preg_match('/(http[s]*(.*?)[^\s]*)/i', $url, $url);
	$url = $url[1];
}
$parse = parse_url($url);
if(!@$parse['scheme']){
	$scheme = 'https://';
}
if(!@$parse['host'] || !stristr(@$parse['host'], 'kuai')){
	need::send(['code'=>-8, 'text'=>'请输入正确的快手链接']);
}
if(!@$parse['path'] || substr(@$parse['path'], 0, 1) != '/'){
	need::send(['code'=>-9, 'text'=>'请输入正确的快手链接']);
}
$url = $scheme.$parse['host'].$parse['path'];

// echo $originurl;exit;
#生成随机did,用于请求快手链接的cookie
$did = md5(time() . mt_rand(1,1000000));
#每次请求生成一个随机ip
$rip = need::Rand_IP();
$originurl = need::loadurl($url); // 获取跳转后的链接
// echo $originurl;exit;
#方便用户使用，如用户传递一个包含链接的文本，将链接正则出来
preg_match("~[a-zA-z]+://[^\s]*~", $originurl, $originurlmatches);
if (empty($originurl)) {
	#没有正则到要解析的地址
	exit(need::json(array("code"=>"-4","text"=>"没有检测到要解析的地址，如果有“# @”请将其删掉".$originurl)));
}else{
	if(stristr($originurl, 'm.'))
	{
		$location = need::teacher($originurl, ['loadurl'=>1]);
		$url2 = $location;
	} else {
		$url2 = $originurl;
	}
	// echo $url2;
	preg_match('/video\/(.*?)\?/', $url2 , $urls); //正则视频Id
	$id = $urls[1];
	// echo $id;exit;
	$Idempty = False;
	if(empty($id)){
		// 没有正则到
		preg_match('/photo\/(.*?)\?/', $url2, $urls);
		// 第二种正则方法
		$id = $urls[1];
		$Idempty = true;
		if(empty($id)){
			need::send(Array('code'=>-10, 'text'=>'获取失败'));
		}
	}
	if($Idempty == false)
	{
	//
		$url2 = 'https://www.kuaishou.com/video/'.$id;
		#获取302重定向地址页面的响应体
		$content2 = need::getResponseBody($url2);
		// echo $content2;exit;
		preg_match("~__APOLLO_STATE__=(.*?);\(function~", $content2, $matches);
		// print_r($matches);
		if (count($matches) < 1) {
			#没有正则到关键数据切换电脑模式
			exit(need::json(array("code"=>"-6","text"=>"解析失败002")));
		}else{
			$pagedata = $matches[1];
			#关键:将html实体转回字符串(如&#34;转")
			$pagedata= htmlspecialchars_decode($pagedata);
			// echo $pagedata;exit;
			#解析json为数组(去除pom头3空白字符 防止解析json失败)
			$pagedata_json = json_decode(trim($pagedata,chr(239).chr(187).chr(191)),true);
			// print_r($pagedata_json);//['rawPhoto']['soundTrack']['audioUrls']);exit;
			if($pagedata_json == null){
				#关键数据解析为json失败
				exit(need::json(array("code"=>"-7","text"=>"解析失败003")));
			}else{
				if($pagedata_json['status']==1){
					$sharetype = $pagedata_json['share']['type'];

					$data = [];
					$data['bgm'] = $pagedata_json['rawPhoto']['soundTrack']['audioUrls'][0]['url']?$pagedata_json['rawPhoto']['soundTrack']['audioUrls'][1]['url']:$pagedata_json['rawPhoto']['soundTrack']['audioUrls'][0]['url'];
					$data["type"] = $sharetype;
					$data["title"] = $pagedata_json['share']['title'];;
					$data["username"] = $pagedata_json['user']['name'];
					$data["poster"] = $pagedata_json['video']['poster'];
					if($sharetype=="video"){
						#视频
						$mp4url = $pagedata_json['video']['srcNoMark'];
						$data["mp4url"] = $mp4url;
						exit(need::json(array("code"=>"1","text"=>"请求成功","data"=>$data)));
					}elseif($sharetype=="images"||$sharetype=="image_long"){
						#图组或长图
						$data["images"] = $pagedata_json['video']['images'];
						$imageCdn = $pagedata_json['video']['imageCDN'];
						for ($i=0; $i < count($data["images"]); $i++) {
							$data["images"][$i]['path'] = "http://".$imageCdn.$data["images"][$i]['path'];
						}
						$data["audio"] = "http://".$imageCdn.$pagedata_json['video']['audio'];
						exit(need::json(array("code"=>"1","text"=>"请求成功","data"=>$data)));
					}elseif($sharetype=="image"){
						#图片
						$data["image"] = $data["poster"];
						$imageCdn = $pagedata_json['video']['imageCDN'];
						$data["audio"] = "http://".$imageCdn.$pagedata_json['video']['audio'];
						exit(need::json(array("code"=>"1","text"=>"请求成功","data"=>$data)));
					}else{
						#暂时写了图片、图组、长图、视频的解析。其他作品类型可自行测试添加
						exit(need::json(array("code"=>"-10","text"=>"该作品类型暂不支持，敬请期待")));
					}
				}else{
					#如果状态码不为1，看下是否有错误并输出错误信息
					if($pagedata_json['error']==True){
						#有时会返回错误：快手验证码 经测试，使用作品最新分享链接即可正常获取
						if($pagedata_json['error_msg']=="快手验证码"){
							exit(need::json(array("code"=>"-11","text"=>"请用作品最新分享链接重试")));
						}else{
							exit(need::json(array("code"=>"-8","text"=>$pagedata_json['error_msg'])));
						}
					}else {
					/*
						// preg_match('/\/\/(.*?)\//', $originurl, $host);
						// preg_match('/\?(.*)/', $originurl, $parameter);
						echo $id;
						$url = 'https://m.gifshow.com/rest/wd/photo/info?kpn=undefined&captchaToken=';
						preg_match('/fid=(.*?)&/', $originurl, $fid);
                		preg_match('/shareId=(.*?)&/', $originurl, $shareid);
                		preg_match('/shareObjectId=(.*?)&/', $originurl, $object);
                		preg_match('/shareToken=(.*?)&/', $originurl, $token);
                		$post = '{"env":"SHARE_VIEWER_ENV_TX_TRICK","h5Domain":"m.gifshow.com","photoId":"'.$id.'","isLongVideo":false}';
                		// print_r($host);
                		// parse_str($parameter[1], $post);//'{"fid":"'.$fid[1].'","shareToken":"'.$token[1].'","shareObjectId":"'.$object[1].'","shareMethod":"MINI_PROGRAM","shareId":"'.$shareid[1].'","shareResourceType":"PHOTO_OTHER","shareChannel":"share_qqms","kpn":"NEBULA","subBiz":"BROWSE_SLIDE_PHOTO","env":"SHARE_VIEWER_ENV_TX_TRICK","h5Domain":"","photoId":"'.$id.'","isLongVideo":true}';
                		// $post = json_encode($post);
                		// print_r($post);exit;
                		$content2 = json_decode(need::teacher_curl($url, [
                			'Header'=>[
                				'Host: m.gifshow.com',
                				'Content-Length: '.strlen($post),
                				'content-type: application/json',
                				'Accept: *//*',
                				'Origin: https://m.gifshow.com',
                				'X-Requested-With: mark.via',
                				'Sec-Fetch-Site: same-origin',
                				'Sec-Fetch-Mode: cors',
                				'Sec-Fetch-Dest: empty',
                				'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
                				'Referer: https://m.gifshow.com/fw/photo/'.$id,
                				'Accept-Encoding: gzip, deflate',
                				'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
                				'Cookie: did=web_'.$did.'; didv='.time().'; '
                			],
                			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
                			'cookie'=>'did=web_c4d2e1271741457f9dd662f9c848c453; _did=web_732424581F04601F; sid=f3ad8a698f74da6eb3339dc6; Hm_lvt_86a27b7db2c5c0ae37fee4a8a35033ee=1669646433; Hm_lpvt_86a27b7db2c5c0ae37fee4a8a35033ee=1669646433',//'did=web_'.$did.'; didv='.time().'; ',
                			'refer'=>'https://m.gifshow.com/fw/photo/'.$id,
                			'post'=>$post
                		]));
                		print_r($content2);exit;
                		$photo = [];
                		if(isset($content2->atlas))
                		{
                			foreach($content2->atlas->list as $v)
                			{
                				$photo[] = 'https://'. $content2->atlas->cdnList[0]->cdn.$v;
                			}
                		}*/
							exit(need::json(array("code"=>"-9","text"=>"解析失败004")));
					}
				}
			}
		}
	} else {
		$url = 'https://v.m.chenzhongtech.com/rest/wd/photo/info?kpn=KUAISHOU&captchaToken=';
		// echo $originurl;
		preg_match('/fid=(.*?)&/', $originurl, $fid);
		preg_match('/shareId=(.*?)&/', $originurl, $shareid);
		preg_match('/shareObjectId=(.*?)&/', $originurl, $object);
		preg_match('/shareToken=(.*?)&/', $originurl, $token);
		$post = '{"fid":"'.$fid[1].'","shareToken":"'.$token[1].'","shareObjectId":"'.$object[1].'","shareMethod":"TOKEN","shareId":"'.$shareid[1].'","shareResourceType":"PHOTO_OTHER","shareChannel":"share_copylink","kpn":"KUAISHOU","subBiz":"BROWSE_SLIDE_PHOTO","env":"SHARE_VIEWER_ENV_TX_TRICK","h5Domain":"v.m.chenzhongtech.com","photoId":"'.$id.'","isLongVideo":false}';
		// echo $post;
		$content2 = json_decode(need::teacher_curl($url, [
			'Header'=>[
				'Host: m.yxixy.com',
				'Content-Length: '.strlen($post),
				'content-type: application/json',
				'Accept: */*',
				'Origin: https://m.yxixy.com',
				'X-Requested-With: mark.via',
				'Sec-Fetch-Site: same-origin',
				'Sec-Fetch-Mode: cors',
				'Sec-Fetch-Dest: empty',
				'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
				'Accept-Encoding: gzip, deflate',
				'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
				'Cookie: did=web_'.$did.'; didv='.time().'; '
			],
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'cookie'=>'did=web_'.$did.'; didv='.time().'; ',
			'refer'=>$originurl,
			'post'=>$post
		]));
		// print_r($content2);
		$photo = [];
		if(isset($content2->atlas))
		{
			foreach($content2->atlas->list as $v)
			{
				$photo[] = 'https://'. $content2->atlas->cdnList[0]->cdn.$v;
			}
		}
		// print_r($content2);
		$array = array(
			'title'=>$content2->shareInfo->shareTitle,
			'cover'=>$content2->photo->webpCoverUrls[0]->url,
			'user'=>$content2->photo->userName,
			'userId'=>$content2->photo->userId,
			'header'=>$content2->photo->headUrls[0]->url,
			'url'=>$content2->photo->mainMvUrls[0]->url ?: (isset($content2->atlas) ? 'https://'.$content2->atlas->musicCdnList[0]->cdn.$content2->atlas->music : null),
			'photos'=>$photo
		);
		need::send(array('code'=>1, 'text'=>'获取成功', 'data'=>$array));
	}
}


function URL(){
	//获取当前完整url,为了清晰，多定义几个变量,分几行写
	$scheme = $_SERVER['REQUEST_SCHEME']; //协议
	$domain = $_SERVER['HTTP_HOST']; //域名/主机
	$requestUri = $_SERVER['REQUEST_URI']; //请求参数
	//将得到的各项拼接起来
	$currentUrl = $scheme . "://" . $domain . $requestUri;
	return $currentUrl; //传回当前url
 
}

#数据返回
function retn($code,$str,$data=null){
	if($data==null){
		exit(need::json(array(
			"code"=>$code,
			"msg"=>$str
		)));
	}else{
		exit(need::json(array(
			"code"=>$code,
			"msg"=>$str,
			"data"=>$data
		)));
	}  
}
?>