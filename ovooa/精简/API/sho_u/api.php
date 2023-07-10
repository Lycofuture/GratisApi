<?php
header('content-type: application/json');
require '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(150); // 调用统计函数
addAccess();//调用统计函数*/
$msg = @$_REQUEST['msg'];
$format = @$_REQUEST['format'];
$type = @$_REQUEST['type'];
$new = new 兽语();
Switch($format){
    case 1:
    $message = $new->decode($msg);
    //echo $message,';';
    if($message === false){
        Switch($type){
            case 'text':
            need::send('请传递正确的参数', 'text');
            break;
            default:
            need::send(Array('code'=>-1, 'text'=>'请传递正确的参数'), 'json');
            break;
        }
        break;
    }
    Switch($type){
        case 'text':
        need::send($message, 'text');
        break;
        default:
        need::send(Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('Message'=>$message)), 'json');
        break;
    }
    break;
    default:
    $message = $new->encode($msg);
    if($message === false){
        Switch($type){
            case 'text':
            need::send('请传递正确的参数', 'text');
            break;
            default:
            need::send(Array('code'=>-1, 'text'=>'请传递正确的参数'), 'json');
            break;
        }
        break;
    }
    Switch($type){
        case 'text':
        need::send($message, 'text');
        break;
        default:
        need::send(Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('Message'=>$message)), 'json');
        break;
    }
    break;
}



class 兽语{
    public static $beast = ['嗷', '呜', '啊', '～'];
    /*public function __construct($type, $str){
        Switch($type){
            case 'encode':
            self::encode($str);
            break;
            default:
            self::decode($str);
            break;
        }
    }*/
    public static function encode($str){
        $beast = self::$beast;
        if(!$str){
            return false;
        }
        $code = null;
        $hex = self::str_split_unicode(bin2hex($str));
        if(!$hex){
            return false;
        }
        foreach($hex as $k=>$v){
            $value = base_convert($v, 16, 10) + $k % 16;
            $value >= 16 ? $value -= 16 : $value = $value;
            // $value = intval($value);
            // echo $value;
            // print_r($beast);exit();
            $code .= $beast[intval($value / 4)] . '' .$beast[$value % 4];
            //$value % 4 == 0 ? $code .= $beast[$value % 4] : $code = $code;
        }
        if(!$code){
            return false;
        }
        if(mb_strpos($code, '～') == 0){
            $code = preg_replace('/^～/', '', $code);
        }
        return $code;
    }
    public static function decode($str, $type = true){
        $beast = self::$beast;
        if(!$str){
            return false;
        }
        $code = '';
        $hex = [];
        $type === true ? $hex = self::str_split_unicode('～'.$str) : $hex = self::str_split_unicode($str);
        if(!$hex){
            return false;
        }
        //print_r($hex);
        $n = count($hex);
        for ($i = 0; $i < $n; $i++) {
            if ($i % 2 == 0) {
                if (empty($hex[$i + 1])) {
                    break;
                }
                $A = array_search($hex[$i], $beast);
                $B = array_search($hex[$i + 1], $beast);
                //echo $A,';',$B;exit;
                $x = (($A * 4) + $B) - (($i / 2) % 16);
                //echo $x;exit;
                $x < 0 ? $x += 16 : $x = $x;
                //echo $x;exit;
                $code .= dechex($x);
                //echo $code;exit;
            }
        }
        $code = pack("H*", $code);
        if(!$code){
            return false;
        }
        if(!json_encode($code, 320) && (strstr($str, '～') && strstr($str, '嗷'))){
            return self::decode(preg_replace('/^～/', '', $str), false);
        }
        //echo base_convert($code, 2, 16);exit;
        return $code;
    }
    public static function str_split_unicode($str, $l = 0){
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }
}