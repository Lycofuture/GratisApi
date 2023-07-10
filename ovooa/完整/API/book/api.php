<?php
header('Content-type:Application/Json');
require_once '../../curl.php';
require_once '../../need.php';
$name = $_REQUEST['name'];
$bookid = $_REQUEST['id'];
$type = $_REQUEST['type'];
$n = $_REQUEST['n'];
$pid = $_REQUEST['pid'];
$url = 'https://souxs.leeyegy.com/search.aspx?key='.urlencode($name).'&page=1&siteid=app2';
$data = Json_decode(need::teacher_curl($url,['ua'=>'okhttp-okgo/jeasonlzy']),true);
$code = $data['status'];
if(Empty($n) || $n < 1 || $n > count($data['data']) || !is_numEric($n) || Empty($data['data'][($n-1)])){
    Switch($code){
        case 1:
        $data = $data['data'];
        if(Empty($data)){
            Switch($type){
                case 'text':
                    need::send('返回数据为空,请稍后重试','text');
                break;
                case 'h5':
                    need::send('返回数据为空,请稍后重试','text');
                break;
                default:
                    need::send(array('code'=>-3,'text'=>'返回数据为空,请稍后重试'));
                break;
            }
        }
        foreach($data as $k=>$v){
            $name = $v['Name'];
            $id = $v['Id'];
            $author = $v['Author'];
            $desc = $v['Desc'];
            $image = $v['Img'];
            $new = $v['LastChapter'];
            Switch($type){
                case 'text':
                $echo .= $name."\n".$author."\n".$desc."\n";
                break;
                default:
                $array[] = array('id'=>$id,'name'=>$name,'author'=>$author,'desc'=>$desc,'image'=>$image,'new'=>$new);
                break;
            }
        }
        Switch($type){
            case 'text':
            need::send($echo,'text');
            break;
            default:
            need::send(array('code'=>1,'text'=>'获取成功','data'=>$array));
            break;
        }
        break;
    }
}
$n = ($n-1);
$data = $data['data'];
$id = $data[$n]['Id'];
$url = 'https://infosxs.pysmei.com/BookFiles/Html/'.(substr($id,0,3)+1).'/'.$id.'/index.html';
$data = str_replace('},]','}]',need::teacher_curl($url,['ua'=>'okhttp-okgo/jeasonlzy']));
preg_match_all('/\{"id":([0-9]+?),"name":"([\s\S]*?)","hasContent":(.*?)\}/',$data,$array);
//$data = Json_decode($data);
if(Empty($pid) || $pid < 1 || $pid > count($array[1]) || !is_numEric($pid) || Empty($array[1][($pid-1)])){
    Switch($type){
        case 'text':
        need::send('章节不存在','text');
        break;
        default:
        need::send(array('code'=>-4,'text'=>'章节不存在'));
        break;
    }
}
$pid = $array[1][($pid - 1)];
$name = $array[2][($pid - 1)];
$url = 'https://contentxs.pysmei.com/BookFiles/Html/'.(substr($id,0,3)+1).'/'.$id.'/'.$pid.'.html';
$data = need::teacher_curl($url,['ua'=>'okhttp-okgo/jeasonlzy']);
if(!$bookid){
    preg_match('/\{"id":(.*?),"name":"(.*?)","cid":(.*?),"cname":"(.*?)","pid":(.*?),"nid":(.*?),"content":"(.*?)","hasContent":(.*?)\}/',$data,$data);
}else{
    preg_match('/\{"id":(.*?),"name":"(.*?)","cid":'.$bookid.',"cname":"(.*?)","pid":(.*?),"nid":(.*?),"content":"(.*?)","hasContent":(.*?)\}/',$data,$data);
}
//print_r($data);exit;
$text = $data[7];//内容
$cid = $data[3];//当前章
$pid = $data[5];//上一章
$bookname = $data[4];//章节名字
$nid = $data[6];//下一章
$name = $data[2];
//need::send($data);
$num = $_REQUEST['num'];
if($num < 1){
    $num = 2000;
}
$page = $_REQUEST['page']?:1;
if(!$page||$page < 1||!is_numEric($page)||$page > intval(mb_strlen($text) / $num)){
    $page = 1;
}
$pages = ($page-1);
$nums = ($num*$pages);
$num = ($page*$num);
Switch($type){
    case 'text':
    $String = mb_substr($text,$nums,$num);
    need::send($String,'text');
    break;
    case 'h5':
    $text = str_replace(array('\r\n','\f\t\n'),'<br/>',$text);
    need::send(array('code'=>1,'text'=>'获取成功','data'=>array('name'=>$name,'title'=>$bookname,'pid'=>$pid,'nid'=>$nid,'cid'=>$cid,'text'=>$text)));
    break;
    case 'html':
    $text = str_replace(array('\r\n','\f\t\n'),'<br/>',$text);
    $array = json_encode(array('code'=>1,'text'=>'获取成功','data'=>array('name'=>$name,'title'=>$bookname,'pid'=>$pid,'nid'=>$nid,'cid'=>$cid,'text'=>$text)),320);
    need::send('./book_v1.php?array='.$array,'image');
    break;
    default:
    need::send(array('code'=>1,'text'=>'获取成功','data'=>array('name'=>$name,'title'=>$bookname,'pid'=>$pid,'nid'=>$nid,'cid'=>$cid,'text'=>$text)));
    break;
}
