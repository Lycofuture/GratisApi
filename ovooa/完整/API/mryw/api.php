<?php
header('content-type: application/json;');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(149); // 调用统计函数
addAccess();//调用统计函数
require '../../need.php';
$type = @$_REQUEST['type'];
$time = @$_REQUEST['Time']?:date('Y-m-d');
new 每日一文(['type'=>$type, 'time'=>$time]);
class 每日一文{
    protected $info = [];
    protected $array = array();
    protected $Msg;
    public function __construct(array $array){
        foreach($array as $k=>$v){
            $this->info[$k] = $v;
        }
        $this->ParameterException();
    }
    protected function ParameterException(){
        $time = $this->info['time'];
        if($time){
            $this->getTime();
            return;
        }else{
            $this->getcontent();
            return;
        }
        return;
    }
    public function getTime(){
        $Time = $this->info['time'];
        if(preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $Time)){
            $Time = strtoTime($Time) > Time() ? Date('Y-m-d') : $Time;
            
        }else{
            $Time = Date('Y-m-d');
        }
        if(file_exists(__DIR__.'/Cache/content/'.$Time.'.json')){
            $content = @json_decode(file_get_contents(__DIR__.'/Cache/content/'.$Time), true);
            if(!$content){
                $this->getcontent($Time);
                return;
            }else{
                unset($this->array, $this->Msg);
                $this->array = ['code'=>1, 'text'=>'获取成功', 'data'=>$content];
                $this->Msg = '        '.$content['title']."\n".'        '.$content['author']."\n".$content['content'];
                $this->returns();
                return;
            }
        }else{
            $this->getcontent($Time);
            return;
        }
    }
    public function getcontent($time = ''){
        if(!$time){
            $path = __DIR__.'/Cache/content/'.Date('Y-m-d').'.json';
        }else{
            if(!strtotime($time)){
                $path = __DIR__.'/Cache/content/'.Date('Y-m-d').'.json';
            }else{
                $path = __DIR__.'/Cache/content/'.$time.'.json';
            }
        }
        //echo $path;exit;
        if(file_exists($path)){
            $content = @json_decode(file_get_contents($path), true);
            if(!$content){
                $url = 'https://interface.meiriyiwen.com/article/today?dev=1&date='.$this->info['time'];
            	$data = json_decode(need::teacher_curl($url), true);
            	$data = isset($data['data']) ? $data['data'] : false;
            	if(!$data){
            	    unset($this->array, $this->Msg);
            	    $this->array = ['code'=>-1, 'text'=>'未知错误，请稍后重试'];
            	    $this->Msg = '未知错误，请稍后重试';
            	    $this->returns();
            	    return;
            	}
            	$title = $data['title'];
            	$author = $data['author'];
            	$content = trim(str_replace(['<p>', '</p>'], '', $data['content']));
            	$array = ['title'=>$title, 'author'=>$author, 'content'=>$content, 'time'=>Date('Y-m-d')];
            	file_put_contents($path, need::json($array));
            	unset($this->array, $this->Msg);
            	$this->array = ['code'=>1, 'text'=>'获取成功', 'data'=>$array];
            	$this->Msg = '        '.$title."\n".'        '.$author."\n".$content;
            	$this->returns();
            	return;
            }else{
                unset($this->array, $this->Msg);
                $this->array = ['code'=>1, 'text'=>'获取成功', 'data'=>$content];
                $this->Msg = '        '.$content['title']."\n".'        '.$content['author']."\n".$content['content'];
                $this->returns();
                return;
            }
        }else{
            $url = 'https://meiriyiwen.com/';
            $url = 'https://interface.meiriyiwen.com/article/random?dev=1';//&date='.str_replace('-', '', $this->info['time']);
            $data = json_decode(need::teacher_curl($url, [
            	'refer'=>$url,
            	'Header'=>[
            		'Host: interface.meiriyiwen.com'
            	]
            ]), true);
            $data = isset($data['data']) ? $data['data'] : false;
            // print_r($data);exit;
            if(!$data){
                unset($this->array, $this->Msg);
                $this->array = ['code'=>-1, 'text'=>'未知错误，请稍后重试'];
                $this->Msg = '未知错误，请稍后重试';
                $this->returns();
                return;
            }
            // preg_match('/<h1>(.*?)<\/h1>/', $data, $title);
            // preg_match('/<p class="article_author"><span>(.*?)<\/span><\/p>/', $data, $author);
            // preg_match('/<div class="article_text">([\s\S]*?)<\/div>/', $data, $content);
            $title = $data['title'];
            $author = $data['author'];
            $content = trim(str_replace(['<p>', '</p>'], '', $data['content']));
            $array = ['title'=>$title, 'author'=>$author, 'content'=>$content, 'time'=>Date('Y-m-d')];
            file_put_contents($path, need::json($array));
            unset($this->array, $this->Msg);
            $this->array = ['code'=>1, 'text'=>'获取成功', 'data'=>$array];
            $this->Msg = '        '.$title."\n".'        '.$author."\n".$content;
            $this->returns();
            return;
        }
    }
    private function get($time)
    {
    	if(!$time){
            $path = __DIR__.'/Cache/content/'.Date('Y-m-d').'.json';
        }else{
            if(!strtotime($time)){
                $path = __DIR__.'/Cache/content/'.Date('Y-m-d').'.json';
            }else{
                $path = __DIR__.'/Cache/content/'.$time.'.json';
            }
        }
        if(file_exists($path))
        {
        	$file = @json_decode(file_get_Contents($path), true);
        	if(!$file)
        	{
        		unset($path);
        		return $this->get($time);
        	}
        }else{
    		$url = 'https://interface.meiriyiwen.com/article/today?dev=1&date='.$this->info['time'];
    		$data = json_decode(need::teacher_curl($url), true);
        	$data = isset($data['data']) ? $data['data'] : false;
        	if(!$data){
        	    return false;
        	}
        	$title = $data['title'];
        	$author = $data['author'];
        	$content = trim(str_replace(['<p>', '</p>'], '', $data['content']));
        	$array = ['title'=>$title, 'author'=>$author, 'content'=>$content, 'time'=>Date('Y-m-d')];
        	file_put_contents($path, need::json($array));
        	return true;
        }
    }
    protected function returns(){
        $type = $this->info['type'];
        Switch($type){
            case 'text':
            need::send($this->Msg, 'text');
            break;
            default:
            need::send($this->array, 'json');
            break;
        }
        return;
    }
}