<?php
header('content-type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(84); // 调用统计函数
/* End */
require ('../../need.php');//引入bkn文件
$QQ = @$_REQUEST["QQ"];
$Skey = @$_REQUEST["Skey"];
$Group = @$_REQUEST["Group"];
$Pskey = @$_REQUEST['Pskey'];
$type = @$_REQUEST["type"];

new 群信息(Array('QQ'=>$QQ, 'Group'=>$Group, 'Skey'=>$Skey, 'Pskey'=>$Pskey, 'type'=>$type));

class 群信息{
    protected $info = [];
    protected $Array = [];
    public function __construct(Array $Array){
        foreach($Array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        $QQ = $this->info['QQ'];
        if(!need::is_num($QQ)){
            $this->Array = Array('code'=>-1, 'text'=>'请输入正确的QQ');
            $this->send();
            return;
        }
        $Group = $this->info['Group'];
        if(!need::is_num($Group)){
            $this->Array = Array('code'=>-2, 'text'=>'请输入正确的群号');
            $this->send();
            return;
        }
        $Skey = $this->info['Skey'];
        if(!need::is_Skey($Skey)){
            $this->Array = Array('code'=>-3, 'text'=>'请输入正确的Skey');
            $this->send();
            return;
        }
        $Pskey = $this->info['Pskey'];
        if(!need::is_Pskey($Pskey)){
            $this->Array = Array('code'=>-4, 'text'=>'请输入正确的Pskey');
            $this->send();
            return;
        }
        $this->Getdata();
        return;
    }
    protected function Getdata(){
        $Group = $this->info['Group'];
        $Skey = $this->info['Skey'];
        $QQ = $this->info['QQ'];
        $Pskey = $this->info['Pskey'];
        $bkn = need::GTK($Skey);
        $cookie = 'uin=o'.$QQ.'; p_uin=o'.$QQ.'; skey='.$Skey.'; p_skey='.$Pskey;
        $data = json_decode(need::teacher_curl('https://admin.qun.qq.com/cgi-bin/qun_admin/get_group_brief',[
            'post'=>'&gc='.$Group.'&bkn='.$bkn,
            'refer'=>'https://admin.qun.qq.com/create/share/index.html?groupUin=820323177',
            'cookie'=>$cookie
        ]), true);
        $code = $data["ec"];
        if($code=='0'){
            $name = $data["gName"];
            $name = need::ASCII_UTF8(str_replace(array('&nbsp;','&amp;'),array(' ','&'),$name));
            $info = need::ASCII_UTF8(str_replace(array('&nbsp;','&amp;'),array(' ','&'),$data['gIntro']))?:'没有公告';
            $this->Array = array("code"=>1,"data"=>array("owner"=>$data["gOwner"],"name"=>$name,'Group'=>$Group,'Group_image'=>'http://p.qlogo.cn/gh/'.$Group.'/'.$Group.'/0',"intro"=>$info,"role"=>$data["gRole"]."","conf"=>$data["conf_group"]));
            $this->send();
            return;
        }else
        if($code == 7){
            $this->Array = array("code"=>-5,"text"=>"账号：".$QQ."并不在群".$Group."中");
            $this->send();
            return;
        }else
        if($code == 4){
            $this->Array = array("code"=>-6,"text"=>"Pskey已过期");
            $this->send();
            return;
        }else{
            $this->Array = array("code"=>-7,"text"=>"未知错误，错误码：".$code,'data'=>$data);
            $this->send();
            return;
        }
        return;
    }
    public function send(){
        $type = $this->info['type'];
        $data = $this->Array;
        if($data['code'] == 1){
            Switch($type){
                case 'text':
                echo '±img=',$data['data']['Group_image'],'±',"\n",'群名：',$data['data']['name'],"\n",'群号：',$data['data']['Group'],"\n",'群主：',$data['data']['ower'],"\n",'公告：',$data['data']['intro'];
                die();
                break;
                default:
                need::send($data, 'json');
                break;
            }
        }else{
            Switch($type){
                case 'text':
                need::send($data['text'], 'text');
                break;
                default:
                need::send($data);
                break;
            }
        }
    }
}


