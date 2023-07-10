<?php
header('Content-type: application/json');
require ("../../Core/Database/connect.php");
//print_r($_SERVER);exit;
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(142); // 调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$Request = need::Request();
$Msg = isset($Request['msg']) ? addslashes(sprintf("%s", $Request['msg'])) : null;
$num = isset($Request['num']) ? $Request['num'] : 10;
$page = isset($Request['page']) ? $Request['page'] : 1;
$n = isset($Request['n']) ? $Request['n'] : null;
$type = isset($Request['type']) ? $Request['type'] : null;
if($page < 1 || !is_numEric($page)){
    $page = 1;
}
if(empty($Msg)){
    Switch($type){
        case 'text':
        need::send('请输入需要查询的接口','text');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'请输入需要查询的接口'));
        break;
    }
}

$query = "SELECT * FROM `mxgapi_api` WHERE `status`='1' && `name` LIKE '%" . $Msg . "%' order by 1 desc";
$result = $db->query($query);
if($result){
	$result = $result->fetch_all(MYSQLI_ASSOC);
	if(!$result){
	    Switch($type){
	        case 'text':
	        need::send('没有搜到你想要的接口','text');
	        break;
	        default:
		    need::send(array('code'=>-2, 'text'=>'没有搜到你想要的接口'));
		    break;
		}
	}else{
	    $count = count($result);//总数量
	    if(!$n || $n > $count || $n < 1){
	        $pages = intval(($count / $num) + 1);//总页数
	        if($page > $pages){
	            $page = 1;
	        }
	        $open_page = intval(($page - 1) * $num);//计算每页开始数量
	        $fclose = intval($page * $num);//计算结束数量;
	        for($i = $open_page ; $i < $fclose && $i < $count; $i++){
	            $name .= ($i+1).'.'.$result[$i]['name']."\n";
	            $array[] = $result[$i]['name'];
	        }
	        Switch($type){
	            case 'text':
	            echo '共搜索到'.$count.'个接口';
	            echo "\n";
	            echo $name;
	            need::send('第'.$page.'/'.$pages.'页','text');
	            break;
	            default:
	            need::send(array('code'=>1,'text'=>'获取成功','data'=>array('count'=>$count,'page'=>$page,'pages'=>$pages,'data'=>$array)));
	            break;
	        }
	    }else{
	        $id = $result[($n - 1)]['id'];
	        $query = "SELECT * FROM `mxgapi_api` WHERE `status`='1' && `id` = '{$id}'";
	        $return = $db->query($query)->fetch_all(MYSQLI_ASSOC);
	        if(empty($return)){
	            Switch($type){
	                case 'text':
	                need::send('选项'.$n.'不存在','text');
	                break;
	                default:
	                need::send(array('code'=>-3,'text'=>'选项'.$n.'不存在'));
	                break;
	            }
	        }else{
	            $desc = $return[0]['desc'];
	            $name = $return[0]['name'];
	            $method = $return[0]['method'];
	            $format = $return[0]['format'];
	            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/API/'.$return[0]['example_url'];//请求事例
	            $request_parameter = json_decode($return[0]['request_parameter'],true);//请求参数
	            $return_parameter = json_decode($return[0]['return_parameter'],true);//返回参数
	            $error_code = json_decode($return[0]['error_code'],true);//状态码
	            $request = $returns = $code = null;
	            $array_return = $array_request = $array_code = [];
	            foreach($request_parameter['data'] as $k => $v){
	            	// print_r($v);exit;
	                $request .= ($k+1).'.参数名称：'.$v['name']."\n是否必填：".$v['required']."\n参数解释：".$v['info']."\n参数类型：{$v['type']}\n";
	                $array_request[] = array('name'=>$v['name'],'require'=>$v['required'],'info'=>$v['info'], 'type'=>$v['type']);
	            }
	            foreach($return_parameter['data'] as $k=>$v){
	                $returns .= ($k+1).'.返回参数：'.$v['name']."\n参数讲解：".$v['msg']."\n";
	                $array_return[] = array('name'=>$v['name'],'Msg'=>$v['msg']);
	            }
	            foreach($error_code['data'] as $k=>$v){
	                $code .= ($k+1).'.状态码：'.$v['code']."\n状态讲解：".$v['msg']."\n";
	                $array_code[] = array('code'=>$v['code'],'Msg'=>$v['msg']);
	            }
	            //print_r($return);
	            Switch($type){
	                case 'text':
	                echo $name."\n";
	                echo '接口说明：'.$desc."\n";
	                echo '请求方式：'.$method."\n";
	                echo '返回格式：'.$format."\n";
	                echo '————————';
	                echo "\n";
	                echo '请求参数↓';
	                echo "\n";
	                echo $request;
	                echo "————————\n";
	                echo '返回参数↓';
	                echo "\n————————\n";
	                echo $returns;
	                echo "————————\n";
	                echo '返回状态码↓';
	                echo "\n————————\n";
	                echo $code;
	                echo "————————\n";
	                echo '请求事例↓';
	                echo "\n————————\n";
	                need::send($url, 'text');
	                break;
	                default:
	                need::send(array(
	                    'code'=>1,
	                    'text'=>'获取成功',
	                    'data'=>array(
	                        'name'=>$name,
	                        'desc'=>$desc,
	                        'method'=>$method,
	                        'format'=>$format,
	                        'request'=>$array_request,
	                        'return'=>$array_return,
	                        'code'=>$array_code,
	                        'url'=>$url
	                    )
	                ));
	                break;
	            }
	        }
	    }
	}
}else{
    Switch($type){
	    case 'text':
	    need::send('没有搜到你想要的接口','text');
	    break;
	    default:
		need::send(array('code'=>-2, 'text'=>'没有搜到你想要的接口'));
		break;
	}
}
