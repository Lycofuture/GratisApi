<?php

header("Content-type: application/json; charset=utf-8");
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(87); // 调用统计函数

addAccess();//调用统计函数

/* End */

require ('../../curl.php');//引入curl文件

require ('../../need.php');//引入bkn文件

$msg = @$_GET["msg"];

$type = @$_GET["type"];

if($msg ==''){

exit(need::json(array('code'=>-1,'text'=>'请输入需要搜索的内容')));

}
//$name = urlencode($msg);
$data = need::teacher_curl('https://pic.sogou.com/pic/searchList.jsp?uID=&v=5&statref=index_form_1&spver=0&rcer=&keyword='.urlencode($msg),[
    'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
    'refer'=>'https://pic.sogou.com/pic/index.jsp?v=5'
]);
//echo $data;
preg_match('/window\.__INITIAL_STATE__=([\s\S]*?);\(function/',$data,$data);
$data = json_decode($data[1],true);
//print_r($data['searchlist']['picData']['items']);exit();
$data = $data['searchlist']['picData']['items'];
if(empty($data)){
    Switch($type){
        case 'text':
        need::send('未搜索到有关于('.$msg.')的图片','text');
        break;
        default:
        need::send(array('code'=>-2,'text'=>'未搜索到有关于('.$msg.')的图片'));
        break;
    }
}


$rand=array_rand($data,1);

//$img = str_replace('\\','',$img[1][$rand]);

Switch($type){
    case 'text':
    need::send($data[$rand]['picUrl'],'text');
    break;
    default:
    need::send(array('code'=>1,'text'=>'获取成功','data'=>array('url'=>$data[$rand]['picUrl'])));
    break;
}
/*
if($type == "text"){

exit($img);

}else{

exit(need::json(array('code'=>1,'text'=>$img)));

}*/
