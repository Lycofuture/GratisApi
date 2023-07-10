<?php
     $counter = intval(file_get_contents("counter.dat"));  
     $_SESSION['#'] = true;  
     $counter++;  
     $fp = fopen("counter.dat","w");  
     fwrite($fp, $counter);  
     fclose($fp); 
 ?>
<?php
header('content-Type:Application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(15); // 调用统计函数
require '../../curl.php';
require '../../need.php';
/* End */
$id = urlencode((String) @$_REQUEST["n"]);
$Msg = urlencode((String)@$_REQUEST["msg"]?:(String)@$_REQUEST['Msg']);
if(empty($Msg)){
    Switch($Msg){
        case 'text':
        need::send('请输入需要搜索的图片','text');
        break;
        default:
        need::send(Array('code'=>-1,'text'=>'请输入需要搜索的图片'));
        break;
    }
}
$num = @$_REQUEST['num']?:24;
$Type = @$_REQUEST['Type']?:@$_REQUEST['type'];
$url = 'https://www.duitang.com/napi/blog/list/by_search/?kw='.$Msg.'&type=feed&Incloude_fields=top_comments,is_root,source_link,item,buyable,root_id,status,like_count,like_id,sender,album,reply_count,favorite_blog_id&start='.$num.'&_='.Time();
$data = json_decode(need::teacher_curl($url,[
    'refer'=>'https://www.duitang.com/search/?kw='.$Msg.'&type=feed'
]),true);//"https://www.duitang.com/search/?kw=".$name."&type=feed","GET",0,0);
$status = $data['status'];
$data = $data['data']['object_list'];
if(empty($data)){
    Switch($Type){
        case 'text':
        need::send('未搜索到有关于'.urldecode($Msg).'的图片','text');
        break;
        default:
        need::send(Array('code'=>-5,'text'=>'未搜索到有关于'.urldecode($Msg).'的图片'));
        break;
    }
}
Switch($status){
    case 1:
    //成功
    Switch($Type){
        case 'text':
        $n = @$_REQUEST['n'];
        if($n > $num || $n < 1 || !is_numEric($n)){
            $n = 1;
        }
        $n = ($n - 1);
        if(empty($data[$n]['photo']['path'])){
            Switch($Type){
                case 'text':
                need::send('没有更多了','text');
                break;
                default:
                need::send(Array('code'=>-6,'text'=>'没有更多了'));
                break;
            }
        }
        if(stristr($data[$n]['photo']['path'],'gif_')){
            $image_url = str_replace(Array('gif_jpeg','gif_png','gif_webp','gif_jpg'),'gif',$data[$n]['photo']['path']);
        }else{
            $image_url = $data[$n]['photo']['path'];
        }
        echo '±img='.$image_url.'±';
        echo "\n";
        echo '规格：'.$data[$n]['photo']['width'].'*'.$data[$n]['photo']['height'];
        echo "\n";
        echo '标题：'.$data[$n]['msg'];
        echo "\n";
        echo '添加时间：'.$data[$n]['add_datetime'];
        exit();
        break;
        default:
        foreach($data as $k=>$v){
            $width = $v['photo']['width'];
            $height = $v['photo']['height'];
            $url = $v['photo']['path'];
            if(stristr($v['photo']['path'],'gif_')){
                $url = str_replace(Array('gif_jpeg','gif_png','gif_webp','gif_jpg'),'gif',$v['photo']['path']);
            }
            $Msg = $v['msg'];
            $Add_Time = $v['add_datetime'];
            $Pretty = $v['add_datetime_pretty'];
            $Array[] = Array('Width'=>$width,'Height'=>$height,'Url'=>$url,'Msg'=>$Msg,'Add_Time'=>$Add_Time,'Pretty'=>$Pretty);
        }
        need::send(Array('code'=>1,'text'=>'获取成功','data'=>$Array));
        break;
    }
    break;
    case 4:
    Switch($Type){
        case 'text':
        need::send($data['message'],'text');
        break;
        default:
        need::send(Array('code'=>-2,'text'=>$data['message']));
        break;
    }
    break;
    case 400:
    Switch($Type){
        case 'text':
        need::send($data['message'],'text');
        break;
        default:
        need::send(Array('code'=>-3,'text'=>$data['message']));
        break;
    }
    break;
    default:
    Switch($Type){
        case 'text':
        need::send('未知错误','text');
        break;
        default:
        need::send(Array('code'=>-4,'text'=>'未知错误'));
        break;
    }
}
?>