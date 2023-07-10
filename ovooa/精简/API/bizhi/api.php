<?php
header("Content-type: Application/json; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(64); // 调用统计函数
addAccess();//调用统计函数*/
require "../../need.php";//引入封装好的函数
require '../../curl.php';
/* End */
$msg = @$_REQUEST['msg']?:@$_REQUEST['Msg'];
$type = @$_REQUEST['type']?:@$_REQUEST['Type'];
$list = need::teacher_curl("http://service.picasso.adesk.com/v1/lightwp/category?Time=".mt_rand(1111,999999999999));
function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}
$list = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $list);
$result = preg_match_all('/{\"count\": (.*?), \"ename\": \"(.*?)\", \"rname\": \"(.*?)\", \"cover_temp\": \"(.*?)\", \"name\": \"(.*?)\", \"cover\": \"(.*?)\", \"rank\": (.*?)\"id\": \"(.*?)\"/',$list,$nute);
if($msg==null){
    $Array = [];
    $echo = '';
    for ($x = 0; $x < $result && $x<=9; $x++) {
        $jec = $nute[5][$x];
        $echo .= ($x+1)."：".$jec."图-壁纸\n";
        $Array[] = $jec;
    }
    Switch($type){
        case 'text';
        need::send($echo."\n提示：发送以上序号选择",'text');
        break;
        default:
        need::send(array("code"=>"-1","text"=>'获取成功','data'=>$Array));
    }
}else{
    $b = ($msg-1);
    $Array = ['美女','动漫','风景','游戏','文字','视觉','情感','设计','明星','物语'];
    $bb = @$nute[8][$b];
    $ar = mt_rand(1,30);
    $lis = file_get_contents("http://service.picasso.adesk.com/v1/vertical/category/".$bb."/vertical?limit=100&skip=0&order=new");
    preg_match_all("/wp\": \"(.*?)\"/",$lis,$bb);
    $bb = @$bb[1][$ar];
    /*
    if(!is_dir('./images/'.$Array[$b])){
        mkdir('./images/'.$Array[$b],0777,true);
    }
    if(!is_file('./images/'.$Array[$b].'/'.md5($bb).'.jpeg')){
        $String = need::teacher_curl($bb);
        file_put_contents('./images/'.$Array[$b].'/'.md5($bb).'.jpeg',$String);
        if(filesize('./images/'.$Array[$b].'/'.md5($bb).'.jpeg') > 2000000){
            unlink('./images/'.$Array[$b].'/'.md5($bb).'.jpeg');
        }
    }*/
    Switch($type){
        case "text":
        need::send( $bb,'text');
        break;
        case 'image':
        need::send($bb,'image');
        break;
        default:
        need::send(array("code"=>"1","url"=>$bb,));
    }
}
?>