<?php
header('content-type:application/json');
require '../../need.php';
$request = need::request();
$Msg = $request['Msg'];
$format = $request['format'];
$type = $request['type'];

new 违禁词(['Msg'=>$Msg, 'format'=>$format, 'type'=>$type]);

class 违禁词{
    protected $info = [];
    protected $array = array();
    protected $Msg;
    public function __construct($array){
        foreach($array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        $info = $this->info;
        $Msg = $info['Msg'];
        $format = $info['format'];
        if(!$Msg && $format == 1){
            unset($this->array, $this->Msg);
            $this->array = ['code'=>-2, 'text'=>'请输入文本'];
            $this->Msg = '请输入文本';
            $this->returns();
            return;
        }
        Switch($format){
            case 1:
            $this->检测违禁词();
            break;
            default:
            $this->违禁词列表();
            break;
        }
        return;
    }
    public function 检测违禁词(){
        $data = file(__DIR__.'/data.txt');
        $Msg = $this->info['Msg'];
        $bool = false;
        foreach($data as $v){
            if(mb_stristr($Msg, need::jiemi(trim($v)))){
                $bool = true;
                break;
            }
        }
        if($bool === true){
            unset($this->array, $this->Msg);
            $this->array = ['code'=>1, 'text'=>true];
            $this->Msg = 'true';
            $this->returns();
            return;
        }else{
            unset($this->array, $this->Msg);
            $this->array = ['code'=>-1, 'text'=>false];
            $this->Msg = 'false';
            $this->returns();
            return;
        }
        return;
    }
    public function 违禁词列表(){
        $file = file_get_contents(__DIR__.'/data.txt');
        unset($this->array, $this->Msg);
        $this->array = ['code'=>1, 'text'=>$file];
        $this->Msg = $file;
        $this->returns();
        return;
    }
    public function returns(){
        $Msg = $this->Msg;
        $array = $this->array;
        //echo $Msg;
        $type = $this->info['type'];
        Switch($type){
            case 'text':
            need::send($Msg, 'text');
            break;
            default:
            need::send($array, 'json');
            break;
        }
        return;
    }
}