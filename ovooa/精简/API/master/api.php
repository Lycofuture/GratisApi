<?php
header('Content-type: application/json');
require ("../../Core/Database/connect.php");
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(67); // 调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$ts = @$_REQUEST["ts"]?:"10";//显示条数默认10条
$p = @$_REQUEST["p"]?:"1";//页数默认1页
$n = @$_REQUEST["n"];//选择查看哪个
$Type = @$_REQUEST['type'];
$s = $db->query("SELECT * FROM `mxgapi_api` order by 1 desc")->fetch_all(MYSQLI_ASSOC);//获取数据库文件
$count = count($s);//获取总数量
$sum = intval(($count/$ts)+1);//计算总页数
if($p > $sum){
    $p = 1;
}
$ps = intval($p-1);//数组0为1所以-1
$num = intval($ps*$ts);//计算当前页数显示到几个了
$numa = intval($p*$ts);//计算当前页显示几个
$echo = $requesta = $returna = $codea = $code = null;
$array = $request = $Code = [];
if(!$n){
    for($i = $num ; $i < $numa && $i < $count ; $i++){
        $echo .= ($i+1).".".$s[$i]["name"]."\n";
        $array[] = $s[$i]["name"];
    }
    Switch($Type){
        case 'text':
        need::send('本站共有'.$count.'条接口'."\n".$echo.'第'.$p.'/'.$sum.'页','text');
        break;
        default:
        need::send(array("code"=>1,"text"=>"获取成功",'data'=>array('page'=>$p,'pages'=>$sum,'data'=>$array)),'json');
    }
}
if($n > $count || $n <1){
    for($i = $num ; $i < $numa && $i < $count ; $i++){
        $echo .= ($i+1).".".$s[$i]["name"]."\n";
    }
    Switch($Type){
        case 'text':
        need::send("请按以下序列号选择！\n".$echo."第".$p."/".$sum."页",'text');
        break;
        default:
        need::send(array("code"=>1,"text"=>'获取成功','data'=>array('page'=>$p,'pages'=>$sum,'data'=>$echo)),'json');
        break;
    }
}else{
    $na = ($n - 1);
    $s3 = json_decode($s[$na]["error_code"],true);
    //exit(need::json($s3));
    $s1 = json_decode($s[$na]["request_parameter"],true);
    $s2 = json_decode($s[$na]["return_parameter"],true);
    $url = $s[$na]["url"];//请求链接
    $format = $s[$na]["format"];//请求方式
    $example_url = $s[$na]["example_url"];//请求示例
    $request_count = count($s1["data"]);//请求参数
    $return_count = count($s2["data"]);//获取返回参数总数
    $error_count = count($s3["data"]);//返回状态码数量
    for ($at = 0 ; $at < $request_count ; $at++){
        $sc = json_decode($s[$na]["request_parameter"],true);
        $requesta .= ($at+1).".参数名称：".$sc["data"][$at]["name"]."\n是否必填：".$sc["data"][$at]["required"]."\n参数说明：".$sc["data"][$at]["info"]."\n——·——·——·——·——\n";
        $request[] = array('name'=>$sc["data"][$at]["name"],'require'=>$sc["data"][$at]["required"],'info'=>$sc["data"][$at]["info"]);
    }
    for ($t = 0 ; $t < $return_count ; $t++){
        $sa = json_decode($s[$na]["return_parameter"],true);
        $returna .= ($t+1).".返回参数：".$sa["data"][$t]["name"]."\n参数解释：".$sa["data"][$t]["msg"]."\n——·——·——·——·——\n";
        $return[] = array('name'=>$sa["data"][$t]["name"],'Msg'=>$sa["data"][$t]["msg"]);
    }
    for ($i = 0 ; $i < $error_count ; $i++){
        $sb = json_decode($s[$na]["error_code"],true);
        $codea .= ($i+1).".状态码：".$sb["data"][$i]["code"]."\n状态码说明：".$sb["data"][$i]["msg"]."\n——·——·——·——·——\n";
        $Code[] = array('Code'=>$sb["data"][$i]["code"],'Msg'=>$sb["data"][$i]["msg"]);
    }
    Switch($Type){
        case 'text':
        need::send("请求链接：http://".$_SERVER['HTTP_HOST']."/API/".$url."\n——·——·——·——·——\n返回格式：".$format."\n——·——·——·——·——\n请求参数：↓\n——·——·——·——·——\n".$requesta."返回参数↓\n——·——·——·——·——\n".$returna."返回状态码↓\n——·——·——·——·——\n".$codea."请求示例：http://".$_SERVER['HTTP_HOST']."/API/".$example_url,'text');
        break;
        default:
        need::send(array(
            'Code'=>1,
            'text'=>'获取成功',
            'data'=>array(
                'request'=>$request,
                'return'=>$return,
                'Code'=>$Code
            )
        ),'json');
    }
  //  echo need::json(array("code"=>"1","text"=>"请求链接：http://".$_SERVER['HTTP_HOST']."/API/".$url."\n——·——·——·——·——\n返回格式：".$format."\n——·——·——·——·——\n请求参数：↓\n——·——·——·——·——\n".$requesta."返回参数↓\n——·——·——·——·——\n".$returna."返回状态码↓\n——·——·——·——·——\n".$codea."请求示例：http://".$_SERVER['HTTP_HOST']."/API/".$example_url));
}
