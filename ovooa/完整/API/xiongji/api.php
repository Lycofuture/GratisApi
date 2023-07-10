<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(66); // 调用统计函数
require "../../need.php";

/* End */

$qq = @$_REQUEST["qq"];

$type = @$_REQUEST["type"];

if(!need::is_num($qq)){
    Switch($type){
        case 'text':
        need::send('请输入正确的账号','text');
        break;
        default:
        need::send(array("code"=>"-1","text"=>"请输入正确的账号"));
        break;
    }
}
$html=curl('https://qqxiongji.bmcx.com/'.$qq.'__qqxiongji/',"GET",0,0);
if(empty($html)){
    Switch($type){
        case 'text':
        need::send('返回数据为空','text');
        break;
        default:
        need::send(array("code"=>"-2","text"=>"返回数据为空"));
        break;
    }
}
preg_match_all('/<td bgcolor="#F5F5F5" align="center" width="60">QQ号码<\/td><td bgcolor="#FFFFFF" style="font-size: 14px; font-weight: bold; color: #F00;">(.*?)<\/td>/',$html,$qq);
preg_match_all('/<tr><td bgcolor="#F5F5F5" align="center">号码凶吉<\/td><td bgcolor="#FFFFFF" style="font-size: 14px;">(.*?)<span/',$html,$x1);
preg_match_all('/<span style="color: #(.*?); font-weight: bold;">(.*?)<\/span>/',$html,$x2);
preg_match_all('/align="center">主人性格<\/td><td bgcolor="#FFFFFF" style="font-size: 14px;">(.*?)[\r\n]?
<\/td><\/tr>
<\/table>/',$html,$x3);
Switch($type){
    case 'text':
    need::send("QQ号：".$qq[1][0]."\n\n号码凶吉：".$x1[1][0]."\n\n是为：".$x2[2][0]."\n\n主人性格：".$x3[1][0],'text');
    break;
    default:
    need::send(array("code"=>"1","text"=>'获取成功','data'=>array('uin'=>$qq[1][0],"evaluate"=>$x1[1][0] ,"luck"=>$x2[2][0],"master"=>$x3[1][0])),'json');
    break;
}