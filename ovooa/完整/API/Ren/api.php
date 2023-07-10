<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(126); // 调用统计函数
require '../../need.php';//引用封装好的函数文件
/* End */

$name = @$_REQUEST['name'];
$type = @$_REQUEST['type'];

if(empty($name)){
    Switch($type){
        case 'text':
        need::send('请输入名字','text');
        break;
        default:
        need::send(array('code'=>-1,'text'=>'请输入名字'));
        break;
    }
}

class 人品{ 

    function replace($name){
        return preg_replace('/[a-z]/','',md5($name.date('Ymd')));
    }

    function calculate($figure){
        $array = str_split($figure);
        //shuffle($array);//打乱数组
        return $array;
    }
    
    function Defcry($msg){
        $msg = need::jiami($msg);
        $msg = $this->replace($msg);
        return $msg;
    }
    
    function begin($name){
        $name = $this->replace($name);
        /*
        $num = intval(strlen($name) / 2);
        $num = substr($name,0,$num);
        $figure = (($num + date('Ymd')) / $num + date('d'));
        */
        $array = $this->calculate($name);
        /*
        $open = fopen('./1.txt','w');
        fwrite($open,need::json($array).$figure."\n{$name}\n{$num}\n".date('Ymd'));
        fclose($open);
        */
        Switch($array[0]){
            case 1:
            $text = '今天也许不会心想事成，但是肯定会有好运的！';
            break;
            case 2:
            $text = '今天运气可能有点坏，要多做好事！';
            break;
            case 3:
            $text = '咦，这…不如去写封遗书？';
            break;
            case 4:
            $text = '今天对你来说可能是个伟大的日子！';
            break;
            case 5:
            $text = '小事事与愿违，大事担其重任。';
            break;
            case 6:
            $text = '(长辈/上级/老师)会对自己发脾气！一定要忍，小不忍则乱大谋！';
            break;
            case 7:
            $text = '光头强不管被李老板骂多少次还是会砍树。坚持的人总会有结果！';
            break;
            case 8:
            $text = '也许放弃会更好，不过还是要遵从一下自己的心意。毕竟遵守心意的人运气不会太差！';
            break;
            case 9:
            $text = '运气时好时坏，心正则气顺。';
            break;
            case 0:
            $text = '空气清新，阳光灿烂，心想事成！';
            break;
        }
        return $text;
    }
}
$my = new 人品;
$my = $my->begin($name);
Switch($type){
    case 'text':
    need::send($my,'text');
    break;
    default:
    need::send(array('code'=>1,'text'=>$my));
    break;
}
