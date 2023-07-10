<?php
header('content-type: application/json');
/* 添加回复 优化显示 */
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(107); // 调用统计函数
/* End */
require ('../../need.php');//引入bkn文件
need::send(['code'=>1, 'text'=>'漂流瓶暂停服务']);
//print_r($foreach);exit;
$Request = need::request();
$msg = @$Request['msg'];//写入内容
$title = @$Request['title']?:'一封神秘的信';//标题
$QQ = @$Request['QQ'];//账号
$Select = @$Request['Select'];//选择
$type = @$Request['type'];
$Uin = @$Request['Uin'];
//$IP = getip();//获取IP
if($Select == '1'){
  if(!($msg)){
     if($type == 'text'){
         die('请输入内容');//连接失败
      }else{
         die(need::json(array('code'=>-2,'text'=>'请输入内容')));//连接失败
     }
 }
   if(!($title)){
     if($type == 'text'){
         die('请输入标题');//连接失败
      }else{
         die(need::json(array('code'=>-1,'text'=>'请输入标题')));//连接失败
      }
   }
   if(!need::is_num($QQ)){
     if($type == 'text'){
         die('请输入正确的QQ');//连接失败
      }else{
         die(need::json(array('code'=>-3,'text'=>'请输入正确的QQ')));//连接失败
      }
   }
}
if(preg_match('/(2859024156|20605089|2242991630|2512819096|1063402828|2959844237|2772234198|1029286620|1307686413|3161245069|1762936918|54646161|1933424585|1269399731|3037317264|2470084743|18428184)/',$QQ)){
    if($type == 'text'){
         die('黑名单用户爬!');//连接失败
      }else{
         die(need::json(array('code'=>-7,'text'=>'黑名单用户爬！')));//连接失败
   }
}
//$msg = str_replace('%2B', '➕', URLencode($msg));//Urldecode(str_replace(['%5B%E7%A9%BA%E6%A0%BC%5D', '+'], [' ', '➕'],Urlencode(str_replace(' ', '[空格]', $msg))));
//need::send(Array('code'=>-1, 'text'=>$msg));
$type = $Request['type'];//输出方式
$load_url = 'localhost';//数据库链接
$load_user = 'plp';//用户账户
$load_password = 'MaeGHecxyXTKXeb4';//密码
$load_name = 'plp';//用户名
$load_Part = '3306';//端口
$MySQL = new MySQLi($load_url,$load_user,$load_password,$load_name,$load_Part);//创建数据库链接
if($MySQL->connect_error){
    die('connect error:'.$MySQL->connect_errno);//连接失败
}else{
    $MySQL->query("set names utf8mb4");  // 连接成功并设置数据库字符集
}
if($Select == '1'){
    if((preg_match('/^[a-z0-9A-Z]+$/i',$msg) || preg_match('/^[a-z0-9A-Z]+$/i',$title)) && $QQ != 2354452553){
        Switch($type){
            case 'text':
            need::send('大海的水已经够多了，不用再灌辣！','text');
            break;
            default:
            need::send(array('code'=>-20,'text'=>'大海的水已经够多了，不用再灌辣！'));
            break;
        }
    }
    $vocabulary = file(__DIR__. '/data.txt');
    foreach($vocabulary as $k=>$value){
        if(mb_stristr($msg, need::jiemi(trim($value))) && $QQ!=2354452553){
            if($type == 'text'){
                 die('请不要携带违禁词!');//连接失败
              }else{
                 die(need::json(array('code'=>-6,'text'=>'请不要携带违禁词！')));//连接失败
             }
         }
    }
    $title = str_replace(Array('.','*','%','+','#','_',"'",'"', '\\'),Array('\\.','\\*','\\%','\\+','\\#','\\_',"\\'",'\\"', '\\\\'),$title);
    $msg = str_replace(Array('.','*','%','+','#','_',"'",'"', '\\'),Array('\\.','\\*','\\%','\\+','\\#','\\_',"\\'",'\\"', '\\\\'),$msg);
    $from = "SELECT * FROM `Piao_total` WHERE `Piao_text` LIKE '%{$msg}%' AND `Piao_number` = {$QQ}";
    $data = $MySQL->query($from);
    $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
    if($data){
        Switch($type){
            case 'text':
            need::send('请不要发重复内容','text');
            break;
            default:
            need::send(Array('code'=>-7,'text'=>'请不要发重复内容'),'json');
            break;
        }
    }
    if(mb_strlen($msg) > 120 || mb_strlen($title) > 32){
        Switch($type){
            case 'text':
            need::send('写着写着发现纸不够长！原来是限制120个字以内了！！','text');
            break;
            default:
            need::send(Array('code'=>-9,'text'=>'写着写着发现纸不够长！原来是限制120个字以内了！！'),'json');
            break;
        }
    }
    $from = "INSERT INTO `Piao_total` (`id`, `Piao_name`, `Piao_text`, `Piao_number`, `Piao_time`) VALUES (NULL, '{$title}', '".$msg."', '{$QQ}', '".time()."')";
    $data = $MySQL->query($from);//写入数据
        if($data){
            if($type == 'text'){
                echo '把纸塞进瓶子里，盖上瓶盖，用力一投~啪~飞向了远处的海中';
                exit();
            }else{
                echo need::json(array('code'=>1,'text'=>'把纸塞进瓶子里，盖上瓶盖，用力一投~啪~飞向了远处的海中'));
                exit();
            }
        }else{
            if($type == 'text'){
                die('连接失败，connect error:'.$data->connect_errno);//连接失败
            }else{
                die(need::json(array('code'=>-5,'text'=>'连接失败！','connect error'=>$data->connect_errno)));//连接失败
            }
        }
    }else
    if($Select == 'password'){
        $from = "SELECT * FROM `Piao_total` WHERE `Piao_name` LIKE '%{$msg}%' OR `Piao_text` LIKE '%{$msg}%'";
        $data = $MySQL->query($from);
        $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
    //    print_r($data);
        if($data){
        $p = $Request['p']?:'1';
        $p_zong = (count($data) / 10);//总页数
        $p_num = ($p * 10);//每页显示
        $p_t = (($p - 1)*10);//从...开始
            for($k = $p_t ; $k < $p_num and $k < count($data) ; $k++){
                $Title .= ($k+1).'.'.$data[$k]['Piao_name'].'-'.$data[$k]['id']."\n";
               // $Title .= "\n";
            }
            echo $Title;
//            print_r($data);
            echo '共搜索到';
            echo count($data);
            echo '个结果';
            echo "\n当前为第";
            echo $p;
            echo '页';
        }else{
           echo '未搜索到有关于['.$msg.']的结果';
        }
    }else
    if($Request['password'] == '123456789.000' and $Select == 'Delete' and $Request['id'] and $QQ == '2354452553'){
        $form = 'SELECT * FROM `Piao_total` WHERE `id` = '.$Request['id'];
        $data = $MySQL->query($form);
        $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
        //print_r( $data);
        if($data){
            echo '删除成功！';
            $from = "DELETE FROM `Piao_total` WHERE `Piao_total`.`id` = ".$Request['id'];
            $data = $MySQL->query($from);
         }else{
             echo '删除失败ID(';
             echo $Request['id'];
             echo ')不存在';
        }
    }else
    if($Select == 'look' and $Request['password'] == '123456789.000' and $Request['id'] and $QQ=='2354452553'){
        $form = 'SELECT * FROM `Piao_total` WHERE `id` = '.$Request['id'];
        $data = $MySQL->query($form);
        $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
        if($data){
            echo 'ID：'.$Request['id'];
            echo "\n";
            echo '标题：';
            echo ($data[0]['Piao_name']);//标题
            echo "\n";
            echo '内容：';
            echo str_replace(['\n', '\\'], ["\n", ''], $data[0]['Piao_text']);
            echo "\n";
            echo '发送人：'.$data[0]['Piao_number'];
        }else{
            echo '未搜索到ID为'.$Request['id'].'的漂流瓶';
        }
    }else
    if($Select == 'num'){
        $from = 'SELECT * FROM `Piao_total`';//查询代码
        $select = $MySQL->query($from);//查询数据库
        $data = $select->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
        Switch($type){
            case 'text':
            need::send('共有'.count($data).'个漂流瓶', 'text');
            break;
            default:
            need::send(['code'=>1, 'text'=>'获取成功', 'data'=>['num'=>count($data)]]);
            break;
        }
    }
    else
    {
        $from = 'SELECT * FROM `Piao_total`';//查询代码
        $select = $MySQL->query($from);//查询数据库
        $data = $select->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
        if($data){
            $ovo_num = $Request['ovo_num']?:1;
            if($ovo_num < 0 || !is_numeric($ovo_num) || $ovo_num > 10){
                $ovo_num = 1;
            }
            if($type == 'text'){
                for($i = 0 ; $i < $ovo_num ; $i++){
                    $rand = array_rand($data,1);
                    $array = $data[$rand];
                    echo '    《'.str_replace(["\n", '\\'], ["\n", ''], $array['Piao_name']).'·'.$array['id'].'》';
                    echo "\n\n";
                    echo '        ';
                    echo str_replace(["\n", '\\'], ["\n", ''], $array['Piao_text']);
                    echo "\n\n提示：请注意言辞";
                    echo "\n\n";
                    echo '    ——'.date('Y-m-d G:i:s',$array['Piao_time']);
                    if($ovo_num > 1){
                        echo "\n————————\n";
                    }
                }
            }else{
                $array_ovo = [];
                if($Uin == 'Uin'){
                    for($i = 0 ; $i < $ovo_num ; $i++){
                        $rand = array_rand($data,1);
                        $array = $data[$rand];
                        $array_ovo[] = array('id'=>$array['id'], 'Uin'=>$array['Piao_number'], 'title'=>str_replace(["\n", '\\'], ["\n", ''], $array['Piao_name']),'text'=>str_replace(["\n", '\\'], ["\n", ''], $array['Piao_text']),'time'=>date('Y-m-d H:i:s',$array['Piao_time']));
                    }
                }else{
                    for($i = 0 ; $i < $ovo_num ; $i++){
                        $rand = array_rand($data,1);
                        $array = $data[$rand];
                        $array_ovo[] = array('id'=>$array['id'],'title'=>str_replace(["\n", '\\'], ["\n", ''], $array['Piao_name']),'text'=>str_replace(["\n", '\\'], ["\n", ''], $array['Piao_text']),'time'=>date('Y-m-d H:i:s',$array['Piao_time']));
                    }
                }
                need::send(array('code'=>1,'text'=>'获取成功','data'=>$array_ovo,'Tips'=>'请不要发送污言秽语'));
           exit();
           }
        }else{
            if($type == 'text'){
                die('还没有人丢漂流瓶呢！快来丢一个试试吧？');//连接失败
            }else{
                die(need::json(array('code'=>-4,'text'=>'还没有人丢漂流瓶呢！快来丢一个试试吧？')));//连接失败
            }
     }
}
