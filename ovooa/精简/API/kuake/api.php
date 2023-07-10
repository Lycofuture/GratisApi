<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(94); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$msg = @$_REQUEST["msg"];

$type = @$_REQUEST["type"];

if(empty($msg)){
    Switch($type){
        case 'text':
        die("缺少必填参数");
        break;
        default:
        need::send(array("code"=>"-1","text"=>"缺少必填参数"),'json');
        exit();
        break;
    }
}

$date = need::teacher_curl('https://ai.sm.cn/quark/1/ai?&format=json&dn=38063285669-7ba306c9&nt=6&q='.urlencode($msg).'&query_source=text&activity_id=undefined&scene_name=&origin=115.207055%2C37.164012',[
'refer'=>' ',
'ua'=>'PCLM10(Android/10) (com.quark.browser/4.6.6.164) Weex/0.26.1.19 1080x2332']
);//curl进行访问
// print_r($date);
if(empty($date)){
    Switch($type){
        case 'text':
        need::send('抱歉,获取失败','text');
        exit();
        break;
        default:
        need::send(array("code"=>"-2","text"=>"抱歉,获取失败"),'json');
        exit();
        break;
    }
}

$data = str_replace('\\n','\\r',$date);

$data = str_replace('\\r\\r','\\r',$data);

$json = json_decode($data);//json格式化

$code = $json->status;//状态码

$data = $json->data[0];

if(empty($data)){
    Switch($type){
        case 'text':
        need::send('未知错误','text');
        exit();
        break;
        default:
        need::send(array("code"=>"-3","text"=>"未知错误"),'json');
        exit();
        break;
    }
}
$skill = $data->skill_name;
if($skill=="baike"){
    $desc = $data->value->desc;//内容
    $picture = $data->value->pic;//图片
    $url = $data->value->url;//链接
    Switch($type){
        case 'text':
        need::send($desc);
        break;
        default:
        need::send(array("code"=>"1","text"=>"生活愉快","data"=>array("skill"=>$skill,"desc"=>$desc,"picture"=>$picture,"url"=>$url)),'json');
    }
}else
if($skill == 'chat' || $skill == 'script' || $skill == 'calendar' || $skill == 'fallback' || $skill == 'kg'){
    $desc = $data->value->answer;//内容
    Switch($type){
        case 'text':
        need::send($desc);
        break;
        default:
        need::send(array("code"=>"1","text"=>"生活愉快","data"=>array("skill"=>$skill,"desc"=>$desc)),'json');
    }
}else
if($skill == 'qrs'){
    $desc = $data->value->list[0]->desc;
    $title = $data->value->list[0]->title;
    Switch($type){
        case 'text':
        need::send($title."：\r".$desc);
        break;
        default:
        need::send(array("code"=>"1","text"=>"生活愉快","data"=>array("silk"=>$skill,"title"=>$title,"desc"=>"：\\r".$desc)),'json');
    }
}else
if($skill=="general_qa" || $skill =="maq"){
    $desc = $data->value->desc;//内容
    Switch($type){
        case 'text':
        need::send($desc);
        break;
        default:
        need::send(array("code"=>"1","text"=>"生活愉快","data"=>array("skill"=>$skill,"desc"=>$desc)),'json');
    }
}else
if($skill=="instruction"){
    $desc = $data->value->url;//内容
    Switch($type){
        case 'text':
        need::send("点击立即前往".$desc);
        break;
        default:
        need::send(array("code"=>"1","text"=>"生活愉快","data"=>array("skill"=>$skill,"desc"=>"点击立即前往".$desc)),'json');
        break;
    }
}else
$date = JSON_decode($date);
$data = $date->data[0];
$skill = $date->data[0]->type;
if($skill=="sc"){
    $guide = $data->guide;//标语
    $value = $data->value;
    foreach($value->list as $k=>$v){
        $title = $data->value->list[$k]->title;//类名
        $score = $data->value->list[$k]->score;//度数
        $title_name .= $title . "：" . $score . "\n";
    }
    $url = $value->url;
    $text = $value->text;
    Switch($type){
        case 'text':
        need::send($guide."\n".$title_name."提示：".$text."\n显示更多：".$url,'text');
        break;
        default:
        $desc = $guide."\n".$title_name."提示：".$text."\n显示更多：".$url;
        need::send(array("code"=>"1","text"=>"生活愉快","data"=>array("skill"=>$skill,"desc"=>$desc)),'json');
    }
}
if($skill=="skill"){
    $desc = $data->value->answer;//内容
    Switch($type){
        case 'text':
        need::send($desc);
        break;
        default:
        need::send(array('code'=>1,'text'=>'身体健康','data'=>array('skill'=>$skill,'desc'=>$desc)),'json');
    }
}else{
    Switch($type){
        case 'text':
        exit("未捕获分类，类名：".$skill." ".@$data->data[0]->type);
        break;
        default:
        need::send(array("code"=>"-4","text"=>"生活愉快","data"=>array("desc"=>"当前分类未捕获,类名：".$skill)),'json');
    }
}

