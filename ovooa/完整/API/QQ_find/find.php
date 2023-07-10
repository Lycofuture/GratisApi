<?php
Header('content-type: application/json');
require 'need.php';

$Request = need::request();
$type = @$Request['type'];
$Uin = @$Request['Uin'];
$Skey = @$Request['Skey'];
$QQ = @$Request['QQ'];
new info_QQ(array('Skey'=>$Skey, 'Uin'=>$Uin, 'QQ'=>$QQ, 'type'=>$type));
class info_QQ{
    private $Header = [
        'Header'=>[
            'Host: cgi.find.qq.com'
        ],
        'Cookie'=>''
    ];
    private $array = array();
    private $message;
    private $info = array();
    public function __construct(array $array)
    {
        foreach($array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->Parameter();
    }
    private function delete()
    {
        unset($this->array, $this->message);
        return true;
    }
    private function evaluation(array $array, string $string)
    {
        $this->delete();
        $this->array = $array;
        $this->message = $string;
        $this->result();
        return true;
    }
    private function Parameter()
    {
        $this->delete();
        $info = $this->info;
        if(!need::is_num($info['Uin']))
        {
            $this->evaluation(array('code'=>-1, 'text'=>'错误的Uin'), '错误的Uin');
            return false;
        }
        if(!need::is_Skey($info['Skey']))
        {
            $this->evaluation(array('code'=>-2, 'text'=>'错误的Skey'), '错误的Skey');
            return false;
        }else{
            $this->Header['Cookie'] = 'uin=o'.$info['Uin'].'; skey='.$info['Skey'];
        }
        if(!need::is_num($info['QQ']))
        {
            $this->evaluation(array('code'=>-3, 'text'=>'错误的QQ'), '错误的QQ');
            return false;
        }
        $this->getinfo();
        return true;
    }
    private function getinfo()
    {
        //print_r($this->Header);
        $url = 'https://cgi.find.qq.com/qqfind/buddy/search_v3?keyword='.$this->info['QQ'];
        $data = json_decode(need::teacher_curl($url, [
            'cookie'=>$this->Header['Cookie'],
            'Header'=>$this->Header['Header']
        ]), true);
        //print_r($data);
        $retcode = @$data['retcode'];
        $result = @$data['result'];
        if(!$data)
        {
            $this->evaluation(array('code'=>-4, 'text'=>'未知错误'), '未知错误');
            return false;
        }else
        if(!$result)
        {
            $this->evaluation(array('code'=>-5, 'text'=>'Skey过期，或者查看封号用户'), 'Skey过期，或者查看封号用户');
            return false;
        }else{
            $info = $result['buddy']['info_list'][0];
            $nick = $info['nick'];
            $uin = $info['uin'];
            $city = $info['city']?:'未知地区';
            $country = $info['country']?:'未知国家';
            $gender = $info['gender'] == 1 ? '男' : ($info['gender'] == 2 ? '女' : '未知');
            $age = $info['age'] ?: '未知年龄';
            $array = array(
                'code'=>1,
                'text'=>'获取成功',
                'data'=>array(
                    'uin'=>$uin,
                    'nick'=>$nick,
                    'city'=>$city,
                    'country'=>$country,
                    'gender'=>$gender,
                    'age'=>$age
                )
            );
            $this->evaluation($array, "账号：{$uin}\n昵称：{$nick}\n国家：{$country}\n地区：{$city}\n性别：{$gender}\n年龄：{$age}");
            return true;
        }
    }
    private function result()
    {
        Switch($this->info['type'])
        {
             case 'text':
             need::send($this->message, 'text');
             break;
             default:
             need::send($this->array);
             break;
        }
        return true;
    }
}