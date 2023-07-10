<?php
header('Content-type:Application/json');
require ("../function.php"); // 引入函数文件
require '../../need.php';
addApiAccess(20); // 调用统计函数
addAccess();//调用统计函数
$a=@$_GET['m'];
$type = @$_REQUEST['type'];

$New = New 美女;
$New->type = $type;
$New->Get美女();
class 美女{
    protected $url = 'http://service.picasso.adesk.com/v1/wallpaper/category/4e4d610cdf714d2966000000/wallpaper?limit=30&adult=false&first=1&order=hot&skip=';
    public $type;
    public function Get美女(){
        $rand = mt_rand(1, 1670);
        $data = json_decode(need::teacher_curl($this->url.$rand), true)['res']['wallpaper'];
        if(empty($data)){
        /*
            echo 1;
            return;
        */
            $this->Get美女();
        }
        $rand = array_rand($data, 1);
        $data = $data[$rand];
        $tag_array = [];
        foreach($data['tag'] as $v){
            $tag_array[] = $v;
        }
        $tags = join(' | ', $tag_array);
        $url = $data['preview']?:$data['thumb']?:$data['img']?:$data['wp'];
        Switch($this->type){
            case 'text':
            need::send($url, 'text');
            break;
            case 'image':
            need::send($url, 'image');
            break;
            default:
            need::send(array('code'=>1, 'text'=>'获取成功', 'data'=>array('tag'=>$tag_array, 'image'=>$url)),'json');
            break;
        }
        return;
    }
}