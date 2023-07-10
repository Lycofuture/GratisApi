<?php

header("Content-Type:Application/Json;");
/* Start */

require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(21); // 调用统计函数*/

require "../../need.php";//引入封装好的函数

/* End */

$name = @$_REQUEST['Msg']?:@$_REQUEST['msg'];
$type = @$_REQUEST['type'];
new 三日天气(Array('name'=>$name, 'type'=>$type));

class 三日天气{
    protected $info = [];
    protected $Array = [];
    protected $Msg;
    public function __construct(Array $Array){
        foreach($Array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        $name = $this->info['name'];
        if(empty($name)){
            $this->Array = Array('code'=>-1, 'text'=>'请输入地名');
            $this->send();
            return;
        }
        $this->Getdata();
        return;
    }
    protected function Getdata(){
        $name = urlencode($this->info['name']);
        $data = need::teacher_curl('http://wthrcdn.etouch.cn/weather_mini?city='.$name);
        preg_match_all("/date\":\"(.*?)\"(.*?)high\":\"(.*?)\"(.*?)fx\":\"(.*?)\"(.*?)low\":\"(.*?)\"(.*?)fl\":\"(.*?)\"(.*?)type\":\"(.*?)\"(.*?)city\":\"(.*?)\"(.*?)date\":\"(.*?)\"(.*?)high\":\"(.*?)\"(.*?)fengli\":\"(.*?)\"(.*?)low\":\"(.*?)\"(.*?)fengxiang\":\"(.*?)\"(.*?)type\":\"(.*?)\"(.*?)date\":\"(.*?)\"(.*?)high\":\"(.*?)\"(.*?)fengli\":\"(.*?)\"(.*?)low\":\"(.*?)\"(.*?)fengxiang\":\"(.*?)\"(.*?)type\":\"(.*?)\"(.*?)date\":\"(.*?)\"(.*?)high\":\"(.*?)\"(.*?)fengli\":\"(.*?)\"(.*?)low\":\"(.*?)\"(.*?)fengxiang\":\"(.*?)\"(.*?)type\":\"(.*?)\"(.*?)ganmao\":\"(.*?)\"/",$data,$c);
        $q = $c[13][0];
        if(!$q){
            $this->Msg = '获取失败，未知错误';
            $this->Array = Array('code'=>-2, 'text'=>'获取失败，未知错误');
            $this->send();
            return;
        }
        //print_r($c);exit;
        $ji = str_replace(Array('<![CDATA[', ']]>'), '', $c[19][0]);
        $w = $c[15][0];//时间
        $e = $c[17][0];//高温
        $r = $c[21][0];//低温
        $t = $c[23][0];//风向
        $y = $c[25][0];//天气
        
        $u = $c[27][0];//时间
        $i = $c[29][0];//高温
        $o = $c[33][0];//低温
        $p = $c[35][0];//风向
        $a = $c[37][0];//天气
        $speed1 = str_replace(Array('<![CDATA[', ']]>'), '', $c[31][0]);//风速
        
        $s = $c[39][0];//时间
        $d = $c[41][0];//高温
        $f = $c[45][0];//低温
        $g = $c[47][0];//风向
        $h = $c[49][0];//天气
        $speed2 = str_replace(Array('<![CDATA[', ']]>'), '', $c[43][0]);//风速
        
        $j = $c[51][0];//提示语
        //echo $ji,$j;exit;
        $this->Msg = "天气：$q\n\n$w\n$e   $r   $t   $y   $ji\n\n$u\n$i   $o   $p   $a   $speed1\n\n$s\n$d   $f   $g   $h   $speed2\n\n$j";
        $Array = [];
        $Array[] = Array('Time'=>$w, 'high_temperature'=>str_replace('高温', '', $e), 'low_temperature'=>str_replace('低温', '', $r), 'wind_direction'=>$t, 'weather'=>$y, 'wind_speed'=>$ji);
        $Array[] = Array('Time'=>$u, 'high_temperature'=>str_replace('高温', '', $i), 'low_temperature'=>str_replace('低温', '', $o), 'wind_direction'=>$p, 'weather'=>$a, 'wind_speed'=>$speed1);
        $Array[] = Array('Time'=>$s, 'high_temperature'=>str_replace('高温', '', $d), 'low_temperature'=>str_replace('低温', '', $f), 'wind_direction'=>$g, 'weather'=>$h, 'wind_speed'=>$speed2);
        $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('city'=>$q, 'data'=>$Array, 'Tips'=>$j));
        $this->send();
        return;
    }
    public function send(){
        $type = $this->info['type'];
        $data = $this->Array;
        if($data['code'] == 1){
            Switch($type){
                case 'text':
                need::send($this->Msg, 'text');
                break;
                default:
                need::send($data);
                break;
            }
        }else{
            Switch($type){
                case 'text':
                need::send($this->Msg, 'text');
                break;
                default:
                need::send($data);
                break;
            }
        }
    }
}