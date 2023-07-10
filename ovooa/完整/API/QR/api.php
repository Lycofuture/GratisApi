<?php
//header("Content-Type:application/json;charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(77); // 调用统计函数
addAccess();//调用统计函数*/
require "../../need.php";//引入封装好的函数
//require './ICP/anquan.php';//引入安全函数
require '../../curl.php';

/* End */
need::delfile(__DIR__.'/img/', 5);
$URL = urldecode((String) @$_REQUEST["url"]);
$type = @$_REQUEST['type'];
if(!$URL){
    exit(need::json(array("code"=>"-1","text"=>"请输入二维码链接！")));
}

if(preg_match('/http(.*?)[jpg|png|jpeg|webp|bmp|0|2](.*?)/i',$URL)){
    if($URL){
        require('./lib/QrReader.php');
        $path = 'img/';
        //deldir($path);
      //  $url = $_REQUEST['url'];
        $md5 = __DIR__.'/'.$path.md5($_REQUEST['url']);
        $data = need::teacher_curl($URL);
        file_put_Contents($md5,$data);
        try{
            $image = new imagick($md5);
            //unlink($md5);
        } catch (\Exception $e){
            unlink($md5);
            Switch($type){
                case 'text':
                need::send('未识别到有效内容','text');
                break;
                default:
                need::send(Array('code'=>-2,'text'=>'未识别到有效内容1'));
                break;
            }
        } catch (\Error $E){
            unlink($md5);
            Switch($type){
                case 'text':
                need::send('未识别到有效内容','text');
                break;
                default:
                need::send(Array('code'=>-2,'text'=>'未识别到有效内容2'));
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
            $image->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);//修改头像框大小与头像一样
        }
        $image->setformat('png');
        $image->getImageBlob();
        file_put_Contents($md5,$image);
        if(filesize($md5) > 3200000){
            unlink($md5);
            Switch($type){
                case 'text':
                need::send('文件过大','text');
                break;
                default:
                need::send(Array('code'=>-5,'text'=>'文件过大'));
                break;
            }
        }
        // $url = 'http://'.$_SERVER['HTTP_HOST'].'/API/QR/img/'.$md5;
        //echo need::teacher_curl($url);
        $url = $md5;
        try{
            $qrcode = new QrReader($url);//(__DIR__."./{$Time}".'.jpeg');
            //unlink ($md5);
        } catch (\Exception $e){
        //echo($e);exit;
            unlink($md5);
            Switch($type){
                case 'text':
                need::send('未识别到有效内容！','text');
                break;
                default:
                need::send(array("code"=>-2,"text"=>"未识别到有效内容！3"));
                break;
            }
        }
        $str = $qrcode->text();
        unlink($url);
        if(!$str){
            //unlink($md5);
            Switch($type){
                case 'text':
                need::send('未识别到有效内容！','text');
                break;
                default:
                need::send(array("code"=>"-2","text"=>"未识别到有效内容！4"));
                break;
            }
        }else{
            //unlink($md5);
            Switch($type){
                case 'text':
                need::send($str,'text');
                break;
                default:
                need::send(array("code"=>1,"text"=>$str));
                break;
            }
        }
    }else{
      //  unlink(__DIR__.'./'.$Time);
        Switch($type){
            case 'text':
            need::send('链接验证超时！','text');
            break;
            default:
            need::send(array("code"=>-4,"text"=>"链接验证超时！"));
            break;
        }
    }
}else{
  //  unlink(__DIR__.'./'.$Time);
    Switch($type){
        case 'text':
        need::send('请输入有效的图片链接','text');
        break;
        default:
        need::send(array("code"=>"-3","text"=>"请输入有效的图片链接"));
        break;
    }
}

//unlink(__DIR__.'./'.$Time);
function code($url){

  $ch = curl_init();

  $timeout = 3;

  curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);

  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

  curl_setopt($ch, CURLOPT_HEADER, 1);

  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

  curl_setopt($ch,CURLOPT_URL,$url);

  curl_exec($ch);

  return $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

  curl_close($ch);

}
function deldir($path){
   //如果是目录则继续
   if(is_dir($path)){
    //扫描一个文件夹内的所有文件夹和文件并返回数组
   $p = scandir($path);
   foreach($p as $val){
    //排除目录中的.和..
    if($val !="." && $val !=".."){
     //如果是目录则递归子目录，继续操作
     if(is_dir($path.$val)){
      //子目录中操作删除文件夹和文件
      deldir($path.$val.'/');
      //目录清空后删除空文件夹
      @rmdir($path.$val.'/');
     }else{
      //如果是文件直接删除
      unlink($path.$val);
     }
    }
   }
  }
  }
 //调用函数，传入路径
?>