<?php
/* Start */
require '../../need.php';
require '../../curl.php';
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(135); // 调用统计函数
/* End */
//获取QQ昵称QQ头像
$QQ = @$_REQUEST['QQ'];
$type = @$_REQUEST['type'];
if(!need::is_num($QQ)){
    Switch($type){
        case 'text':
        need::send('请输入正确的账号','text');
        break;
        default:
        need::send(Array('code'=>-1,'text'=>'请输入正确的账号'));
        break;
    }
}
if (need::is_num($QQ)) {
//    $qq = $_GET['qq'];
    //向接口发起请求获取json数据
    $get_info = need::teacher_curl('https://r.qzone.qq.com/fcg-bin/cgi_get_score.fcg?mask=7&uins='.$QQ,[
        'Host: r.qzone.qq.com',
        'Connection: keep-alive',
        'Content-type: UTF-8',
        'Cache-Control: max-age=0',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'dnt: 1',
        'X-Requested-With: mark.via',
        'Sec-Fetch-Site: none',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-User: ?1',
        'Sec-Fetch-Dest: document',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
    ]);
//    echo $get_info;
    //need::send($get_info);
    //转换编码
    //$get_info = mb_convert_encoding($get_info, "UTF-8", "GBK");
    //对获取的json数据进行截取并解析成数组
    $name = json_decode(substr($get_info,17,-2), true);
    //need::send(substr($get_info,17,-2));
    if($name){ 
        $txUrl = 'https://q.qlogo.cn/headimg_dl?dst_uin='.$QQ.'&spec=5';
        $name = need::ASCII_UTF8($name[$QQ][6]);
        Switch($type){
            case 'text':
            need::send($txUrl."\n".$name,'text');
            break;
            default:
            $arr = array(
                'code' => 1,
                'data'=>Array(
                'imgurl' => $txUrl,
                'name' => $name
            ));
            need::send($arr);
            break;
        }
    }else{
        Switch($type){
            case 'text':
            need::send('获取失败请重试','text');
            break;
            $arr = array(
                'code' => -2,
                'text' => '获取失败请重试'
            );
            need::send($arr);
            break;
        }
    }
}else{
    Switch($type){
        case 'text':
        need::send('获取失败请重试','text');
        break;
        $arr = array(
            'code' => -3,
            'text' => '未知错误请重试'
        );
        need::send($arr);
        break;
    }
}
?>