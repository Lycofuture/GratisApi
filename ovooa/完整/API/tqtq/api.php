<?php
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(22); // 调用统计函数*/
require '../../need.php';
/* End */
header("Content-type: application/json");
function jiequ($txt1,$q1,$h1)
{
 $txt1=strstr($txt1,$q1);
 $cd=strlen($q1);
 $txt1=substr($txt1,$cd);
 $txt1=strstr($txt1,$h1,"TRUE");
 return $txt1;
}
$msg=@$_REQUEST['msg'];
$b=@$_REQUEST['b'];
$type = @$_REQUEST['type'];
$num = @$_REQUEST['num'];
new 三日天气多选(Array('name'=>$msg, 'n'=>$b, 'num'=>$num, 'type'=>$type));
class 三日天气多选{
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
        $n = $this->info['n'];
        if($n > 0 && is_numEric($n)){
            $this->info['n'] = $n - 1;
        }else{
            $this->info['n'] = false;
        }
        $num = $this->info['num'];
        if($num < 1 && is_numEric($num)){
            $this->info['num'] = 10;
        }else{
            $this->info['num'] = 10;
        }
        $this->Getdata();
        return;
    }
    protected function Getdata(){
        $name = ($this->info['name']);
        $data = need::teacher_curl('http://m.moji.com/api/citysearch/'.$name);
        $result = preg_match_all("/{\"cityId\":(.*?),\"city_lable\":(.*?),\"counname\":\"(.*?)\",\"id\":(.*?),\"localCounname\":\"(.*?)\",\"localName\":\"(.*?)\",\"localPname\":\"(.*?)\",\"name\":\"(.*?)\",\"pname\":\"(.*?)\"}/",$data,$nute);
        $n = $this->info['n'];
        //print_r($nute);exit;
        $Array = [];
        $num = $this->info['num'];
        if($n === false){
            for ($x=0; $x < $result && $x < $num; $x++) {
                $jec=$nute[6][$x];
                $je=$nute[7][$x];
                $echo .= ($x+1)."：".$je."-".$jec."\n";
                $Array[] = Array('city'=>$je, 'city_t'=>$jec);
            }
            $this->Msg = trim($echo);
            $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>$Array);
            $this->send();
            return;
        }
        $je=$nute[1][$n];
        $jec=$nute[6][$n];
        $data = file_Get_Contents("http://m.moji.com/api/redirect/".$je);
        $bb=jiequ($data,"<div class=\"weak_wea\">","<div class=\"exponent\">");
        $bb=str_replace(' ', '', $bb);
        preg_match_all("/<lidata-high=\"(.*?)\"data-low=\"(.*?)\">/",$bb,$aa);
        preg_match_all("/<em>(.*?)<\/em>/",$bb,$qq);
        preg_match_all("/<dd><strong>(.*?)<\/strong><\/dd>/",$bb,$cc);
        preg_match_all("/<pclass=\"(.*?)\">(.*?)<\/p><dlclass=\"wind\">/",$bb,$dd);
        preg_match_all("/<dd>(.*?)<\/dd>/",$bb,$ee);
        preg_match_all("/<dd>(.*?)<\/dd>/",$bb,$ff);
        if(empty($aa[2][0])){
            $this->Msg = '不支持';
            $this->Array = Array('code'=>-2, 'text'=>'不支持');
            $this->send();
            return;
        }
        $this->Msg =  "☁.查询：".$jec."\n☁.日期：".$qq[1][0]."\n☁.温度：".$aa[2][0]."～".$aa[1][0]."℃\n☁.天气：".$cc[1][0]."\n☁.风度：".$ee[1][2]."-".$ff[1][3]."\n☁.空气质量：".$dd[2][0]."\n\n☁.日期：".$qq[1][1]."\n☁.温度：".$aa[2][1]."～".$aa[1][1]."℃\n☁.天气：".$cc[1][1]."\n☁.风度：".$ee[1][6]."-".$ff[1][7]."\n☁.空气质量：".$dd[2][1]."\n\n☁.日期：".$qq[1][2]."\n☁.温度：".$aa[2][2]."～".$aa[1][2]."℃\n☁.天气：".$cc[1][2]."\n☁.风度：".$ee[1][10]."-".$ff[1][11]."\n☁.空气质量：".$dd[2][2]."";
        
        $this->Array = Array('code'=>1, 'text'=>'获取成功', 'data'=>Array('city'=>$jec, 'data'=>Array(Array('Time'=>$qq[1][0], 'temperature'=>$aa[2][0]."～".$aa[1][0]."℃", 'weather'=>$cc[1][0], 'bearing'=>$ee[1][2]."-".$ff[1][3], 'air_quality'=>$dd[2][0]), Array('Time'=>$qq[1][1], 'temperature'=>$aa[2][1]."～".$aa[1][1]."℃", 'weather'=>$cc[1][1], 'bearing'=>$ee[1][6]."-".$ff[1][7], 'air_quality'=>$dd[2][1]), Array('Time'=>$qq[1][2], 'temperature'=>$aa[2][2]."～".$aa[1][2]."℃", 'weather'=>$cc[1][2], 'bearing'=>$ee[1][10]."-".$ff[1][11], 'air_quality'=>$dd[2][2]))));
        $this->send();
        return;
    }
    public function send(){
        $type = $this->info['type'];
        $data = $this->Array;
        if($data['data']['city']){
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