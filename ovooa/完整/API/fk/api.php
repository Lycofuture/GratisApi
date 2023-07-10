<?php
header('content-type:application/json');
/* Start */
require '../../curl.php';
require '../../need.php';
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(27); // 调用统计函数
/* End */
$QQ = @$_REQUEST['qq'];
$type = @$_REQUEST['type'];
if(!need::is_num($QQ)){
    Switch($type){
        case 'text':
        need::send('请输入正确的QQ','text');
        break;
        default:
        need::send(Array('code'=>-1,'text'=>'请输入正确的QQ'));
        break;
    }
}
if(is_numeric($QQ)){
    $data = file_get_contents("compress.zlib://http://h5.qzone.qq.com/p/r/cgi-bin/qzone_dynamic_v7.cgi?uin=".$QQ."&param=848&format=json");
    preg_match_all('/"todaycount":(.*?),/',$data,$j);
    $j=$j[1][0];
    preg_match_all('/"totalcount":(.*?)}/',$data,$z);
    $z=$z[1][0];
    if(empty($j) && empty($z)){
        Switch($type){
            case 'text':
            need::send( "搜索不到与【".$QQ."】的相关信息，请稍后重试。,可能是因为QQ空间未开放所有人访问",'text');
            break;
            default:
            need::send(array('code'=>-2,'text'=>'未搜索到有关于'.$QQ.'的访客信息,请开启所有人访问'));
            break;
        }
    }else{
        Switch($type){
            case 'text':
            need::send("QQ为：".$QQ."\n今日访客：".$j."\n空间总访客：".$z."",'text');
            break;
            default:
            need::send(array('code'=>1,'text'=>'获取成功','data'=>array('today'=>$j,'all'=>$z)));
            break;
        }
    }
}else{
    Switch($type){
        case 'text':
        need::send( "您输入的不是QQ号。",'text');
        break;
        default:
        need::send(array('code'=>-3,'text'=>'请输入QQ号'));
        break;
    }
}
?>