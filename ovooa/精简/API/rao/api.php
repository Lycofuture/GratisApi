<?php

/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(63); // 调用统计函数
addAccess();//调用统计函数
require "../../need.php";//引入封装好的函数

/* End */

$type = @$_REQUEST["type"];
$file = @file('rao.txt');
$rand = @array_rand($file,1);
$Data = json_decode(trim($file[$rand]),true);
Switch($type){
    case 'text':
    need::send('【'.@$Data['title'].'】'."\n".@$Data['Msg'],'text');
    break;
    default:
    need::send(array('code'=>1,'text'=>'获取成功','data'=>$Data));
    break;
}

