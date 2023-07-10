<?php
header('Content-type:Application/Json');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../../curl.php';
require_once '../../need.php';
require ("./Exception.php");
require ("./PHPMailer.php");
require ("./SMTP.php");
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(139); // 调用统计函数*/
$Uin = $_REQUEST['Uin'];
$token = $_REQUEST['token']?:$_REQUEST['Token'];
$Word = $_REQUEST['Word'];
$type = $_REQUEST['type'];
$format = $_REQUEST['format'];
$host = 'localhost';
$user = 123456789;
$password = 123456789;
$name = 123456789;
$port = 3306;
try{
    $MySQL = new MySQLi($host,$user,$password,$name,$port);
} catch (\Exception $e){
    die($e);
}
$MySQL->query("set names utf8mb4");  // 连接成功并设置数据库字符集
$table = 'Business_card_praise';
$initialization_query = "SHOW TABLES LIKE '{$table}'";
$initialization = $MySQL->query($initialization_query);
$initialization = $initialization->fetch_All();
if(empty($initialization)){
    $query = "CREATE TABLE `{$name}`.`{$table}` ( `id` INT NOT NULL AUTO_INCREMENT , `Uin` BIGINT NOT NULL, `token` VARCHAR(32) NOT NULL COMMENT 'token' , `quantity` INT NOT NULL DEFAULT '10000' COMMENT '数量' , `pay_quantity` INT NOT NULL DEFAULT '0' COMMENT '购买数量', `time` VARCHAR(30) NOT NULL COMMENT '时间' , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    $return = $MySQL->query($query);
}
if($Word == '123456789.000' && !$format){
    if(!need::is_num($Uin)){
        Switch($type){
            case 'text':
            need::send('Uin错误','text');
            break;
            default:
            need::send(Array('code'=>-1,'text'=>'Uin错误'));
            break;
        }
    }
    $user_cache = @file_get_contents('cache/user.txt');
    if(strstr($user_cache,$Uin)){
        Switch($type){
            case 'text':
            die('您已经注册过了');
            break;
            default:
            need::send(Array('code'=>-2,'text'=>'您已经注册过了'));
            break;
        }
    }
    $query = "SELECT * FROM `{$table}` WHERE `Uin` = {$Uin}";
    $return = $MySQL->query($query)->fetch_all();
    if(!empty($return)){
        Switch($type){
            case 'text':
            die('您已经注册过了');
            break;
            default:
            need::send(Array('code'=>-2,'text'=>'您已经注册过了'));
            break;
        }
    }
    $query = "SELECT * FROM `{$table}`";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(count($return) >= 100){
        Switch($type){
            case 'text':
            die('注册数量已达上限');
            break;
            default:
            need::send(Array('code'=>-3,'text'=>'注册数量已达上限'));
            break;
        }
    }
    $date = date('Y-m-d',time());
    $token = md5($Uin.rand(10000000,99999999).time());
    $query = "INSERT INTO `{$table}` (`id`, `Uin`, `token`, `quantity`, pay_quantity, `time`) VALUES (NULL, '{$Uin}', '{$token}', '10000', '0', '{$date}');";
    $return = $MySQL->query($query);
    if($return){
        sendemail($Uin.'@qq.com','注册Token','亲爱的'.$Uin.'您好<br/><br/>欢迎您注册Business card praise的Token。<br /><br/>您的Token是：'.$token.'<br/><br/> 食用地址：http://ovooa.com/API/praise/?Uin=领取账号&token=你的token<br/> <br/>提示：每日免费限量1w，每次领取默认100<br/>您可以添加参数num来修改数量<br/><br/>————————<br/><br/>如何增加num？<br/><br/><a href="http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$token.'&num=200">http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$token.'&num=200</a><br/>点击上面的链接你就可以给自己领200Z_A_N了！<br/><br/><a href="http://ovooa.com/?action=doc&id=139">点击这里</a>查看各类参数</br></br><a href="http://ovooa.com/?action=doc&id=139">点击这里</a>查看各类参数</br></br>__End__');
        Switch($type){
            case 'text':
            echo '注册成功';
            exit();
            break;
            default:
            need::send(Array('code'=>1,'text'=>'注册成功'));
            break;
        }
    }else{
        Switch($type){
            case 'text':
            need::send('注册失败','text');
            break;
            default:
            need::send(Array('code'=>-4,'text'=>'注册失败'));
        }
    }
}else
if($format == 1 && $Word == '123456789.000'){
    if(!need::is_num($Uin)){
        Switch($type){
            case 'text':
            need::send('Uin错误','text');
            break;
            default:
            need::send(Array('code'=>-1,'text'=>'Uin错误'));
            break;
        }
    }
    $query = "SELECT * FROM `{$table}` WHERE `Uin` = {$Uin}";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(empty($return)){
        Switch($type){
            case 'text':
            die('Token查询失败，该账号未注册');
            break;
            default:
            need::send(Array('code'=>-13,'text'=>'Token查询失败，该账号未注册'));
            break;
        }
    }else{
        Switch($type){
            case 'text':
            need::send(1,'text');
            break;
            default:
            need::send(array('code'=>1,'text'=>'该账号已注册'));
            break;
        }
    }
}else
if($format == 2){
    $query = "SELECT * FROM `{$table}`";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    Switch($type){
        case 'text':
        need::send('数据库中已有'.count($return).'个token','text');
        break;
        default:
        need::send(Array('code'=>1,'text'=>'获取成功','data'=>Array('num'=>count($return))));
        break;
    }
}else
if($Word == '123456789.000' && $format == 3){
    $num = $_REQUEST['num'];
    if(!need::is_num($Uin)){
        Switch($type){
            case 'text':
            need::send('Uin错误','text');
            break;
            default:
            need::send(Array('code'=>-1,'text'=>'Uin错误'));
            break;
        }
    }
    if(empty($num) || $num < 100){
        Switch($type){
            case 'text':
            die('做个人吧');
            break;
            default:
            need::send(Array('code'=>-5,'text'=>'num错误'));
        }
    }
    if(empty($token)){
        $query = "SELECT * FROM `{$table}` WHERE `Uin` = {$Uin}";
        $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
        if(empty($return)){
            Switch($type){
                case 'text':
                need::send('您还没有注册token','text');
                break;
                default:
                need::send(Array('code'=>-12,'text'=>'您还没有注册token'));
                break;
            }
        }
        $token = $return[0]['token'];
    }
    $query = "SELECT * FROM `{$table}` WHERE `token` LIKE '{$token}'";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(empty($return)){
        Switch($type){
            case 'text':
            die('token无效');
            break;
            default:
            need::send(Array('code'=>-6,'text'=>'Token错误'));
            break;
        }
    }
    $pay = $return[0]['pay_quantity']?:0;
    $date = date('Y-m-d',time());
    if($return[0]['time'] != $date){
        $a = "UPDATE `{$table}` SET `quantity` = '10000', `pay_quantity` = '{$pay}', `time` = '{$date}' WHERE `{$table}`.`id` = ".$return[0]['id'];
        $a = $MySQL->query($a);
        unset($a,$query,$return);
    }
    $query = "SELECT * FROM `{$table}` WHERE `token` LIKE '{$token}'";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    $id = $return[0]['id'];
    $quantity = ($pay + $num);
    $a = "UPDATE `{$table}` SET `pay_quantity` = '{$quantity}' WHERE `{$table}`.`id` = ".$return[0]['id'];
    $a = $MySQL->query($a);
    if($a){
        sendemail($Uin.'@qq.com','购买成功通知','亲爱的'.$Uin.'<br/><br/>您成功购买了'.$num.'个Z_A_N，感谢您的支持！<br/><br/>当前付费余额为：'.$quantity.'个Z_A_N<br/><br/>免费余额为：'.$return[0]['quantity'].'个Z_A_N<br/><br/>您的token是：'.$token.'<br/> <br/>食用地址：http://ovooa.com/API/praise/?Uin=领取账号&token=你的token<br/> 提示：每日免费额度限量1w，每次领取默认数量100<br/>您可以添加参数num来修改数量<br/><br/>————————<br/><br/>如何增加num？<br/><br/><a href="http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$token.'&num=200">http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$token.'&num=200</a><br/>点击上面的链接你就可以给自己领200Z_A_N了！<br/><br/><a href="http://ovooa.com/?action=doc&id=139">点击这里</a>查看各类参数</br></br>__End__');
        Switch($type){
            case 'text':
            die('1');
            break;
            default:
            need::send(Array('code'=>1,'text'=>'成功'));
            break;
        }
    }else{
        Switch($type){
            case 'text':
            die('0');
            break;
            default:
            need::send(Array('code'=>-7,'text'=>'失败'));
            break;
        }
    }
}else
if($Word == '123456789.000' && $format == 4){
    if(!need::is_num($Uin)){
        Switch($type){
            case 'text':
            need::send('Uin错误','text');
            break;
            default:
            need::send(Array('code'=>-1,'text'=>'Uin错误'));
            break;
        }
    }
    $query = "SELECT * FROM `{$table}` WHERE `Uin` = {$Uin}";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(empty($return)){
        Switch($type){
            case 'text':
            die('0');
            break;
            default:
            need::send(Array('code'=>-7,'text'=>'失败'));
            break;
        }
    }else{
        $id = $return[0]['id'];
        $query = "DELETE FROM `{$table}` WHERE `{$table}`.`id` = {$id}";
        $return = $MySQL->query($query);
        if($return){
            $user_cache = @file_get_contents('./cache/user.txt');
            if(!strstr($user_cache,$Uin) || empty($user_cache)){
                $open = fopen('./cache/user.txt','a');
                Fwrite($open,$Uin.',');
                fclose($open);
            }
            Switch($type){
                case 'text':
                die('1');
                break;
                default:
                need::send(Array('code'=>1,'text'=>'成功'));
                break;
            }
        }else{
            Switch($type){
                case 'text':
                die('0');
                break;
                default:
                need::send(Array('code'=>-7,'text'=>'失败'));
                break;
            }
        }
    }
}else
if($format == 5 && need::is_num($Uin) && $Word == '123456789.000'){
    if(!need::is_num($Uin)){
        Switch($type){
            case 'text':
            need::send('Uin错误','text');
            break;
            default:
            need::send(Array('code'=>-1,'text'=>'Uin错误'));
            break;
        }
    }
    $query = "SELECT * FROM `{$table}` WHERE `Uin` = {$Uin}";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(empty($return)){
        Switch($type){
            case 'text':
            die('Token查询失败，该账号未注册');
            break;
            default:
            need::send(Array('code'=>-13,'text'=>'Token查询失败，该账号未注册'));
            break;
        }
    }else{
        $Token = $return[0]['token'];
        sendemail($Uin.'@qq.com','Token寻找通知','亲爱的'.$Uin.'<br/><br/>您成功的在数据库中找到了被遗忘的Token，在这里说一声憨批！！<br/>Token：'.$Token.'<br/><br/>食用地址：http://ovooa.com/API/praise/?Uin=领取账号&token=你的token<br/> 提示：每日免费额度限量1w，每次领取默认数量100<br/>您可以添加参数num来修改数量<br/><br/>————————<br/><br/>如何增加num？<br/><br/><a href="http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$Token.'&num=200">http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$Token.'&num=200</a><br/>点击上面的链接你就可以给自己领200Z_A_N了！<br/><br/><a href="http://ovooa.com/?action=doc&id=139">点击这里</a>查看各类参数</br></br>__End__');
        Switch($type){
            case 'text':
            die('Token查询成功，您的Token是：'.$Token);
            break;
            default:
            need::send(Array('code'=>1,'text'=>'Token查询成功','data'=>Array('Uin'=>$Uin,'Token'=>$Token)));
            break;
        }
    }
}else
if($format == 6 && strlen($token) == 32){
    $query = "SELECT * FROM `{$table}` WHERE `token` LIKE '{$token}'";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(empty($return)){
        Switch($type){
            case 'text':
            need::send('该Token不存在，请查证后再查询','text');
            break;
            default:
            need::send(array('code'=>-6,'text'=>'Token不存在，请查证'));
            break;
        }
    }else{
        $quantity = $return[0]['quantity'];
        $pay = $return[0]['pay_quantity'];
        if($quantity < 1 && $pay < 1){
            Switch($type){
                case 'text':
                need::send('[余额]：0','text');
                break;
                default:
                need::send(array('code'=>1,'text'=>'查询成功','data'=>array('quantity'=>0,'pay_quantity'=>0)));
                break;
            }
        }else
        if($quantity > 1 && $pay < 1){
            Switch($type){
                case 'text':
                need::send('[余额]：'.$quantity."\n[免费]：".$quantity,'text');
                break;
                default:
                need::send(array('code'=>1,'text'=>'查询成功','data'=>array('quantity'=>$quantity,'pay_quantity'=>$pay)));
                break;
            }
        }else
        if($quantity < 1 && $pay > 1){
            Switch($type){
                case 'text':
                need::send('[余额]：'.$pay."\n[付费]：".$pay,'text');
                break;
                default:
                need::send(array('code'=>1,'text'=>'查询成功','data'=>array('quantity'=>$quantity,'pay_quantity'=>$pay)));
                break;
            }
        }else{
            Switch($type){
                case 'text':
                need::send('[余额]：'.($quantity + $pay)."\n[免费]：".$quantity."\n[付费]：".$pay,'text');
                break;
                default:
                need::send(array('code'=>1,'text'=>'查询成功','data'=>array('quantity'=>$quantity,'pay_quantity'=>$pay)));
                break;
            }
        }
    }
}else
if($format == 7 && $Word == '123456789.000'){
    if(!need::is_num($Uin)){
        Switch($type){
            case 'text':
            need::send('Uin错误','text');
            break;
            default:
            need::send(Array('code'=>-1,'text'=>'Uin错误'));
            break;
        }
    }
    $query = "SELECT * FROM `{$table}` WHERE `Uin` = {$Uin}";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(empty($return)){
        Switch($type){
            case 'text':
            die('Token修改失败，该账号未注册');
            break;
            default:
            need::send(Array('code'=>-13,'text'=>'Token修改失败，该账号未注册'));
            break;
        }
    }else{
        $Token = md5($Uin.rand(10000000,99999999).time());
        $query = "UPDATE `{$table}` SET `token` = '{$Token}' WHERE `{$table}`.`id` = ".$return[0]['id'];
        $return = $MySQL->query($query);
        if($return){
            sendemail($Uin.'@qq.com','Token修改通知','亲爱的'.$Uin.'<br/><br/>您成功的修改了你的Token。<br/>您当前Token是：'.$Token.'<br/><br/>食用地址：http://ovooa.com/API/praise/?Uin=领取账号&token=你的token<br/> 提示：每日免费额度限量1w，每次领取默认数量100<br/>您可以添加参数num来修改数量<br/><br/>————————<br/><br/>如何增加num？<br/><br/><a href="http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$Token.'&num=200">http://ovooa.com/API/praise/?Uin='.$Uin.'&token='.$Token.'&num=200</a><br/>点击上面的链接你就可以给自己领200Z_A_N了！<br/><br/><a href="http://ovooa.com/?action=doc&id=139">点击这里</a>查看各类参数</br></br>__End__');
            Switch($type){
                case 'text':
                need::send('Token修改成功，新的Token：'.$Token, 'text');
                break;
                default:
                need::send(array('code'=>1, 'text'=>'修改成功', 'data'=>array('Token'=>$Token)));
                break;
            }
        }else{
            Switch($type){
                case 'text':
                need::send('Token修改失败，未知错误', 'text');
                break;
                default:
                need::send(array('code'=>-14, 'text'=>'修改失败'));
                break;
            }
        }
    }
}else
if($format == 8 && $Word == '123456789.000'){
    $query = "SELECT * FROM `{$table}`";
    $data = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(!$data){
        need::send('失败','text');
    }
    foreach($data as $v){
        if($v['pay_quantity'] < 1 && strtotime($v['time']) <= strtotime('last month')){
            $id = $v['id'];
            $query = "DELETE FROM `{$table}` WHERE `{$table}`.`id` = {$id}";
            $return = $MySQL->query($query);
            if($return){
                $array[] = $id;
                $Msg .= $id.',';
            }else{
                $array[] = 'Not：'.$id;
                $Msg .= 'Not：'.$id.',';
            }
        }
    }
    if(empty($array)){
        Switch($type){
            case 'text':
            need::send('删除失败，并没有一个月未使用且未付费的Token', 'text');
            break;
            default:
            need::send(array('code'=>-15, 'text'=>'删除失败，并没有一个月未使用且未付费的Token'));
            break;
        }
    }
    Switch($type){
        case 'text':
        need::send(trim($Msg, ','), 'text');
        break;
        default:
        need::send(array('code'=>1, 'text'=>'删除成功', 'data'=>array('id'=>$array, 'count'=>count($array))));
        break;
    }
}else{
    require_once './Run.php';
    $num = $_REQUEST['num']?:100;
    if(!is_numEric($num) || ($num < 100) || floor($num) != $num){
        $num = 100;
    }
    $date = date('Y-m-d',time());
    $query = "SELECT * FROM `{$table}` WHERE `token` LIKE '{$token}'";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if(empty($return)){
        Switch($type){
            case 'text':
            die('token无效');
            break;
            default:
            need::send(Array('code'=>-6,'text'=>'token错误'));
            break;
        }
    }
    $pay = $return[0]['pay_quantity']?:0;
    if($return[0]['time'] != $date){
        $a = "UPDATE `{$table}` SET `quantity` = '10000', `pay_quantity` = '{$pay}', `time` = '{$date}' WHERE `{$table}`.`id` = ".$return[0]['id'];
        $a = $MySQL->query($a);
        unset($a,$query,$return);
    }
    $query = "SELECT * FROM `{$table}` WHERE `token` LIKE '{$token}'";
    $return = $MySQL->query($query)->fetch_all(MYSQLI_ASSOC);
    if($return[0]['quantity'] >= $num){
         $quantity = ($return[0]['quantity'] - $num);
         $pay_quantity = $return[0]['pay_quantity'];
         $praise = new 名片赞;
         $data = $praise->getcard($Uin, $num);
         if(empty($data) || !$data){
             Switch($type){
                 case 'text':
                 need::send($praise->put($Uin, null, true),'text');//'未知错误,请稍后重试'
                 break;
                 default:
                 need::send(Array('code'=>-11,'text'=>$praise->put($Uin, null, true)));
                 break;
             }
         }
        $query = "UPDATE `{$table}` SET `quantity` = '{$quantity}' WHERE `{$table}`.`id` = ".$return[0]['id'];
        if($data['code'] == 0){
            $return = $MySQL->query($query);
            if($return){
                Switch($type){
                    case 'text':
                    echo '[账号]：'.$Uin;
                    echo "\n";
                    echo '[数量]：'.$num;
                    echo "\n";
                    echo '[余额]：'.($quantity + $pay_quantity);
                    if($quantity){
                        echo "\n";
                        echo '[免费]：'.$quantity;
                    }
                    if($pay_quantity){
                        echo "\n";
                        echo "[付费]：{$pay_quantity}";
                    }
                    echo "\n";
                    need::send('[提示]：请开启附近人点Z_A_N','text');
                    break;
                    default:
                    need::send(Array('code'=>1,'text'=>'领取成功','data'=>Array('num'=>$num,'quantity'=>($quantity + $pay_quantity),'order'=>$data['id'],'Tips'=>'请开启允许附近人点Z_A_N')));
                    break;
                }
            }else{
                Switch($type){
                    case 'text':
                    need::send($data['msg'],'text');
                    break;
                    default:
                    need::send(Array('code'=>-8,'text'=>'领取失败','data'=>Array('num'=>$num,'quantity'=>$quantity,'msg'=>$data['msg'])));
                    break;
                }
            }
        }else{
            Switch($type){
                case 'text':
                need::send('领取失败','text');
                break;
                default:
                need::send(Array('code'=>-9,'text'=>'领取失败'));
                break;
            }
        }
    }else
    if($return[0]['pay_quantity'] >= $num && $return[0]['quantity'] < $num){
        $quantity = ($return[0]['pay_quantity'] - $num);
        $pay_quantity = $return[0]['quantity'];
        $praise = new 名片赞;
         $data = $praise->getcard($Uin, $num);
         if(empty($data) || !$data){
             Switch($type){
                 case 'text':
                 need::send($praise->put($Uin, null, true),'text');//'未知错误,请稍后重试'
                 break;
                 default:
                 need::send(Array('code'=>-11,'text'=>$praise->put($Uin, null, true)));
                 break;
             }
         }
        $query = "UPDATE `{$table}` SET `pay_quantity` = '{$quantity}' WHERE `{$table}`.`id` = ".$return[0]['id'];
        $return = $MySQL->query($query);
        if($return){
            if($data['code'] == 0){
                Switch($type){
                    case 'text':
                    echo '[账号]：'.$Uin;
                    echo "\n";
                    echo '[数量]：'.$num;
                    echo "\n";
                    echo '[余额]：'.($quantity + $pay_quantity);
                    echo "\n";
                    echo '[免费]：'.$pay_quantity;
                    if($quantity){
                        echo "\n";
                        echo "[付费]：{$quantity}";
                    }
                    echo "\n";
                    need::send('[提示]：请开启附近人点Z_A_N','text');
                    break;
                    default:
                    need::send(Array('code'=>1,'text'=>'领取成功','data'=>Array('num'=>$num,'quantity'=>($quantity + $pay_quantity),'order'=>$data['id'])));
                    break;
                }
            }else{
                Switch($type){
                    case 'text':
                    need::send($data['msg'],'text');
                    break;
                    default:
                    need::send(Array('code'=>-8,'text'=>'领取失败','data'=>Array('num'=>$num,'quantity'=>$quantity,'msg'=>$data['msg'])));
                    break;
                }
            }
        }else{
            Switch($type){
                case 'text':
                need::send('领取失败','text');
                break;
                default:
                need::send(Array('code'=>-9,'text'=>'领取失败'));
                break;
            }
        }
    }else{
        Switch($type){
            case 'text':
            echo '[提示]：今天的额度不够了';
            echo "\n";
            echo '[额度]：'.($return[0]['quantity'] + $return[0]['pay_quantity']);
            echo "\n";
            if($return[0]['quantity']){
                echo '[免费]：'.$return[0]['quantity'];
                echo "\n";
            }
            if($return[0]['pay_quantity']){
                echo '[付费]：'.$return[0]['pay_quantity'];
                echo "\n";
            }
            echo '[注意]：免费额度与付费额度分别计算';
            die();
            break;
            default:
            $array = array('quantity'=>$return[0]['quantity'],'pay_quantity'=>$return[0]['pay_quantity'],'total'=>($return[0]['quantity'] + $return[0]['pay_quantity']),'Tips'=>'免费额度与付费额度分别计算');
            need::send(Array('code'=>-10,'text'=>'今天的额度不够了','data'=>$array),'json');
            break;
        }
    }
}

function sendemail($email,$name,$Content){
    $mail = new PHPMailer(true); //Passing `true` enables exceptions
    try {
        //服务器配置
        $mail->CharSet ="utf-8"; //设定邮件编码
        $mail->SMTPDebug = 0; //调试模式输出
        $mail->isSMTP(); //使用SMTP
        $mail->Host = "smtp.qq.com"; //SMTP服务器
        $mail->SMTPAuth = true; //允许 SMTP 认证
        $mail->Username = "sanapi@foxmail.com"; //SMTP 用户名  即邮箱的用户名
        $mail->Password = "jywvinlxktvmecaf"; //SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = "ssl"; //允许 TLS 或者ssl协议
        $mail->Port = 465; //服务器端口 25 或者465 具体要看邮箱服务器支持
    
        $mail->setFrom("sanapi@foxmail.com", $name); //发件人
        $mail->addAddress($email); //收件人
        $mail->isHTML(true); //是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = $name ;//标题
        $mail->Body    = '<div style="background-color:#448AFF;color:#448AFF;padding:15px;"><p style="font-weight:bold;color:#fff;font-size:20px;text-align:center;white-space:pre;">'.$mail->Subject.'</p></div><div style="background-color:#fff;padding:10px;border:2px solid #448AFF;"><p style="color:#000;font-size:15px;text-align:center;"><center><h2>'.$Content.'</h2></center></p><p style="color:#000;font-size:15px;text-align:center;">'.date('Y-m-d h:i:s').'</p></div><br /><center><a href="https://ovooa.com/">三三酱API</a></center>';//内容
        $mail->AltBody = $Content;
        $mail->send();
        //die('token已发送至邮箱');
        return '';
    } catch (Exception $e) {
        return '';
        //die('token发送失败');
    }
}
