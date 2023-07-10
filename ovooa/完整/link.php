<?php
// require __DIR__.'/need.php';
require './config.php';
$format = $_REQUEST['format'];
//die();
//print_r($_REQUEST);
$name = $_REQUEST['name'];
$url = $_REQUEST['url'];
$desc = $_REQUEST['desc'];
$picurl = $_REQUEST['picurl'];
//echo http($url);exit;
if($_REQUEST['password'] !== 'fff123'){
    die('Are You OK？');
}
require './need.php';

$time = time();
if($format == 1){
    // geturlcode();
    $new_url = $_REQUEST['new_url'];
    if(empty($new_url)){
        die('你汤姆在逗我？');
    }
    $html = need::teacher_curl($new_url);
    if(preg_match('/代刷|代挂|刷赞|码支付|翼支付/',$html)){
        die('暂不收录此类站点');
    }
    $query = "SELECT * FROM `mxgapi_friendlinks` WHERE `url` LIKE '{$url}'";
    $query = $db->query($query);
    $data = $query->fetch_all(MYSQLI_ASSOC);
    if(empty($data)){
        die('你没有友链,换个锤子？还不快添加？');
    }
    $id = $data[0]['id'];
    unset($data,$query);
    $query = "UPDATE `mxgapi_friendlinks` SET `url` = '{$new_url}' WHERE `mxgapi_friendlinks`.`id` = {$id}";
    $code = $db->query($query);
    if($code){
        die('修改成功');
    }else{
        die('修改失败');
    }
}else
if($format == 2){
    $query = "SELECT * FROM `mxgapi_friendlinks` WHERE `name` LIKE '{$name}%'";
    $data = $db->query($query);
    $data = $data->fetch_all(MYSQLI_ASSOC);
    //print_r($data);
    if(empty($data)){
        die('未添加'.$name.'网站');
    }else{
        $id = $data[0]['id'];
        $query = "DELETE FROM `mxgapi_friendlinks` WHERE `mxgapi_friendlinks`.`id` = {$id}";;
        $code = $db->query($query);
        if($code){
            die('删除成功');
        }else{
            die('删除失败');
        }
    }
}else{
    if(!$name || !$desc || !$picurl || !$url){
        die('Are You OK？');
    }
    $i = geturlcode($picurl);
    if($i !== true || geturlcode($url) !== true){
        echo 'url：'.$html['code'];
        echo "\n";
        echo 'image：'.$html['code'];
        echo "\n";
        die('请将本站添加至白名单或寻找站长手动添加');
    }//*/
    $from = "SELECT * FROM `mxgapi_friendlinks` WHERE `url` LIKE '%{$url}%' OR `name` = '{$name}%'";//SELECT * FROM `mxgapi_friendlinks` WHERE `url` LIKE '%yaya%' OR `name` = '十二'
    $data = $db->query($from);
    $data = $data->fetch_all(MYSQLI_ASSOC);
    if(!empty($data)){
        echo $data[0]['name'];
        echo "\n";
        echo $data[0]['url'];
        echo "\n";
        die('您已经添加过相同的了！');
    }else
    if ($name && $desc && $url && $picurl) {
	    $result = $db->query("INSERT INTO `mxgapi_friendlinks`(`id`, `name`, `desc`, `url`, `picurl`, `time`) VALUES (NULL,'{$name}[官]','{$desc}','{$url}','{$picurl}','{$time}')");
	    if ($result) {
		    die('添加成功');
	    } else {
		    die('添加失败');
		}
	}else{
	    die('Not have a time');
	}
}
function http($url){
    $ch = curl_init();
    $timeout = 3;
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $Header[] = "Accept:*/*";
    $Header[] = "Accept-Encoding:gzip,deflate,sdch";
    $Header[] = "Accept-Language:zh-CN,zh;q=0.8";
    $Header[] = "Connection:close";
    curl_setopt($ch, CURLOPT_HEADER, $Header);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    $ot = curl_exec($ch);
  //  $code = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    return $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
   // return $code;
    //return $ot;
    curl_close($ch);
}
function geturlcode($url){
    $html = need::teacher_curl($url,['GetCookie'=>1,'rtime'=>10, 'nobody'=>1]);
    if(($html['code'] != 200)){
        die('链接校验失败，请开启对本站的白名单');
    }else{
        return true;
    }
    if(preg_match('/代刷|代挂|刷赞|码支付|翼支付/',$html['body'])){
        die('暂不收录此类站点');
    }
}