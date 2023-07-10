<?php
header('content-type:application/json');
require ("../function.php"); // 引入函数文件
addApiAccess(138); // 调用统计函数
addAccess();//调用统计函数
require '../../need.php';
$url = @$_REQUEST['url'];
$type = @$_REQUEST['type'];
$parse = @parse_url($url);
if(empty($url) || !@$parse['host']){
    Switch($type){
        case 'text':
        need::send('请输入链接', 'text');
        break;
        default:
        need::send(array('code'=>-1, 'text'=>'请输入链接'));
        break;
    }
}
$url = need::teacher_curl($url, ['loadurl'=>1])?:$url;
// echo $url;
$cpid = md5(need::Rand_IP());
$salt = md5(time());
/* 获取 Cookie */
$cookie = need::teacher_curl('https://ux.xiaoice.com/beautyv3', [
    'refer'=>'http://ux.xiaoice.com/beautyv3', 
    'Header'=>[
        'Host: ux.xiaoice.com', 
        'Connection: keep-alive', 
        'Cache-Control: max-age=0', 
        'Upgrade-Insecure-Requests: 1', 
        'Accept: text/html, application/xhtml+xml, application/xml;q=0.9, image/webp, image/apng, */*;q=0.8, application/signed-exchange;v=b3;q=0.9', 
        'X-Requested-With: mark.via', 
        'Sec-Fetch-Site: none', 
        'Sec-Fetch-Mode: navigate', 
        'Sec-Fetch-User: ?1', 
        'Sec-Fetch-Dest: document', 
        'Accept-Encoding: gzip,  deflate', 
        'Accept-Language: zh-CN, zh;q=0.9, en-US;q=0.8, en;q=0.7'
    ], 
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML,  like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36', 
    'cookie'=>'cpid='.$cpid.'; salt='.$salt.';logInfo=%7B%22pageName%22%3A%22beautyv3%22%2C%22tid%22%3A%22f1d7757803df424fb86591e0413ea26a%22%7D', 
    'GetCookie'=>1
]);
$cookie = $cookie['Cookie'][1][0];//获取cookie
if(empty($cookie)){
    Switch($type){
        case 'text':
        need::send('Cookie获取失败', 'text');
        break;
        default:
        need::send(array('code'=>-2, 'text'=>'cookie获取失败'));
        break;
    }
}
/* md5url 缓存图片到本地 */
$rand = mt_rand();
$md5 = __DIR__.'/'.md5($url.$rand);
$String = need::teacher_curl($url);
// echo $String;
if(empty($String)){
    Switch($type){
        case 'text':
        need::send('请输入正确的图片链接', 'text');
        break;
        default:
        need::send(Array('code'=>-3, 'text'=>'请输入正确的图片链接'));
        break;
    }
}
file_put_Contents($md5, $String);
try{
    $image = new imagick($md5);
}catch (\Exception $e){
    @unlink($md5);
    Switch($type){
        case 'text':
        need::send('请输入正确的图片链接', 'text');
        break;
        default:
        need::send(Array('code'=>-3, 'text'=>'请输入正确的图片链接'));
        break;
    }
}
$size = $image->getimagegeometry();//获取宽高数据
$width = $size['width'];//宽度
$height = $size['height'];//高度
if($width > 640 || $height > 640){
    if($width == $height){
        $width = 640;
        $height = 640;
    }else
    if($width > $height){
        $zhi = (640 / $width);
        $width = 640;
        $height = ($height * $zhi);
    }else
    //if($width < $height)
    {
        $zhi = (640 / $height);
        $height = 640;
        $width = ($width * $zhi);
    }
    $image->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);//修改头像框大小与头像一样
}
/*else{
    $image->minifyImage();//缩放两倍
}*/
$image->getImageBlob();
file_put_Contents($md5, $image);
$base64 = imgBase64Encode($md5, false);
if(!($base64)){
    unlink($md5);
    Switch($type){
        case 'text':
        need::send('图片获取失败', 'text');
        break;
        default:
        need::send(array('code'=>-4, 'text'=>'图片获取失败'));
        break;
    }
}
if(@unlink($md5)){
	
}else{
    @unlink($md5);
}
/* 上传到小冰网站获取链接 */
$array = json_decode(need::teacher_curl('https://ux.xiaoice.com/api/image/UploadBase64?exp=0', [
    'post'=>$base64, 
    'cookie'=>'cpid=7c82e8b670518c18d0c7e0eaf5fcc409; salt=40f97523619ef534fdf5be0d33b97d2b;'.$cookie, 
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML,  like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36', 
    'refer'=>'http://ux.xiaoice.com/beautyv3', 
    'Header'=>[
        'Host: ux.xiaoice.com', 
        'Connection: keep-alive', 
        'Content-Length: '.strlen($base64), 
        'Content-Type: application/x-www-form-urlencoded', 
        'Accept: */*', 
        'Origin: http://ux.xiaoice.com', 
        'X-Requested-With: mark.via', 
        'Sec-Fetch-Site: cross-site', 
        'Sec-Fetch-Mode: cors', 
        'Sec-Fetch-Dest: empty', 
        'Accept-Encoding: gzip,  deflate', 
        'Accept-Language: zh-CN, zh;q=0.9, en-US;q=0.8, en;q=0.7'
    ]
]), true);

//print_r($array);exit;
$imageurl = $array['Host'].$array['Url'];
if(empty($imageurl)){
    Switch($type){
        case 'text':
        need::send('图片链接获取失败', 'text');
        break;
        default:
        need::send(array('code'=>-5, 'text'=>'图片链接获取失败'));
        break;
    }
}
$uniqid = md5(uniqid(microtime(true), true).rand(1000, 9999));//随机生成一个唯一md5
/* 开始鉴定颜值 */
$post = json_encode(array('MsgId'=>need::time_sss(), 'CreateTime'=>time(), 'TraceId'=>$uniqid, 'Content'=>array('imageUrl'=>$imageurl)), 320);
$data = json_decode(need::teacher_curl('https://ux.xiaoice.com/api/imageAnalyze/Process?service=beauty', [
    'post'=>$post, 
    'Header'=>[
        'Host: ux.xiaoice.com', 
        'Connection: keep-alive', 
        'Content-Length: '.strlen($post), 
        'Content-Type: application/json;charset=UTF-8', 
        'Accept: */*', 
        'Origin: https://ux.xiaoice.com', 
        'X-Requested-With: mark.via', 
        'Sec-Fetch-Site: same-origin', 
        'Sec-Fetch-Mode: cors', 
        'Sec-Fetch-Dest: empty', 
        'Accept-Encoding: gzip,  deflate', 
        'Accept-Language: zh-CN, zh;q=0.9, en-US;q=0.8, en;q=0.7'
    ], 
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML,  like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36', 
    'refer'=>'https://ux.xiaoice.com/beautyv3', 
    'cookie'=>'cpid=7c82e8b670518c18d0c7e0eaf5fcc409; salt=40f97523619ef534fdf5be0d33b97d2b;'.$cookie
]), true);
if(empty($data)){
    Switch($type){
        case 'text':
        need::send('返回数据获取失败', 'text');
        break;
        default:
        need::send(Array('code'=>-6, 'text'=>'返回数据获取失败'));
        break;
    }
}
$data = $data['content'];
$text = $data['text'];//返回提示
$url = $data['imageUrl'];//返回图片
$metadata = $data['metadata'];//返回数据
$rep_image = $metadata['reportImgUrl'];//评价图片
if(empty($rep_image)){
    Switch($type){
        case 'text':
        need::send($text, 'text');
        break;
        default:
        need::send(array('code'=>-7, 'text'=>$text, 'data'=>array('image'=>$url)));
        break;
    }
}
$grade_key0 = $metadata['FBR_Key0'];//最受欢迎
$grade_score0 = $metadata['FBR_Score0'];//最高评分
$grade_key1 = $metadata['FBR_Key1'];
$grade_key2 = $metadata['FBR_Key2'];
$grade_score1 = $metadata['FBR_Score1'];
$grade_score2 = $metadata['FBR_Score2'];
Switch($type){
    case 'text':
    echo $text;
    echo "\n";
    /*
    echo $grade_key0;
    echo '打分：'.$grade_score0;
    echo "\n";
    echo $grade_key1;
    echo '打分：'.$grade_score2;
    echo "\n";
    echo $grade_key3;
    echo '打分：'.$grade_score3;
    echo "\n";
    */
    need::send($rep_image, 'text');
    break;
    default:
    need::send(array('code'=>1, 'text'=>'获取成功', 'data'=>array('text'=>$text, 'grade'=>array('key0'=>$grade_key0, 'score0'=>$grade_score0, 'key1'=>$grade_key1, 'score1'=>$grade_score1, 'key2'=>$grade_key2, 'score2'=>$grade_score2), 'image'=>$url, 'rep_image'=>$rep_image)));
    break;
}

//print_r($data);
/* 图片转base64方法 */
function imgBase64Encode($img,  $imgHtmlCode = true){
    //如果是本地文件
    if(strpos($img, 'http') === false && !file_exists($img)){
        return $img;
    }
    //获取文件内容
    $file_content = file_get_contents($img);
    $base64 = chunk_split(base64_encode($file_content));
    return $base64;
}