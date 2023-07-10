<?php
#接受所有请求源
header('Access-Control-Allow-Origin:*');
#指定返回数据为json utf-8
header('Content-type:application/json; charset=utf-8');
/* Start */
require ('../../need.php');//引用封装好的函数文件

/* End */

$url = @$_REQUEST['url'];
//echo $url;exit;
if(empty($url)){

    exit(need::json(array("code"=>-1,"text"=>"解析地址不得为空！")));

}
if(preg_match('/video\/([0-9]+)\//',$url,$id)){;
    $id = $id[1];
}else
if(preg_match('/video\/([0-9]+)\?/',$url,$id)){;
    $id = $id[1];
}else{
//preg_match_all('/(http[s]?):\/\/([\w-.%#?\/\\\]+)/i',$url,$url);
    preg_match_all('/(http[s]?):\/\/([^\s]*)/i',$url,$url);
    $url = $url[1][0].'://'.$url[2][0];
    $dyItem = need::teacher_curl($url,['loadurl'=>1]); //获取301跳转后地址
    // print_r($dyItem);exit;
//echo $dyItem;
//echo 1;
    preg_match_all('/video\/(.*?)\//', $dyItem, $dyArr); //正则提取item_ids
    $id =$dyArr[1][0];
}

// echo $id;exit;
if (($id)) {
    $url = "https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids={$id}&dytk=";
    $VideoTemp = need::getResponseBody($url);
    echo $url;
    if (!empty($VideoTemp)) {
        $videoArr = json_decode($VideoTemp, true);
        $video = $videoArr['item_list'][0]['video'];//播放
        print_r($videoArr);exit;
        preg_match_all('/"digg_count":(.*?),/',$VideoTemp,$count_digg);//点赞
        preg_match_all('/"nickname":"(.*?)"/',$VideoTemp,$author);//作者
        preg_match_all('/"unique_id":"(.*?)"/',$VideoTemp,$unique_id);//作者id
        $playUrl = $video['play_addr']['url_list'][0];//播放
        // echo $playUrl;exit;
        $play_url = str_replace("playwm", "play", $playUrl);
        $play_url = need::getResponseBody($play_url);
        // echo $play_url;
        preg_match_all('/<a href="(.*?)">/',$play_url,$play_url);
        $play_url=$play_url[1][0];
        $play_url = str_replace('&amp;','&',$play_url)?:$playUrl;//无水印
        //echo $play_url;exit;
        //$play_url = $play_url["location"];//need::teacher_curl($play_url,[
        //'locaurl'=>1]);
        $data = array(
        'code'=>1,
        'text'=>'解析成功！',
        'data'=>array(
            'desc' => $videoArr['item_list']['0']['desc'],
            'author'=>$author[1][0],
            'unique_id'=>$unique_id[1][0],
            'cover' => $video['origin_cover']['url_list'][0],
            'count_digg'=>$count_digg[1][0],
         'play_url' => $play_url,
        ));
        echo need::json($data);
        exit();
/*        echo "封面地址：".$data['cover']."<br/>";
		echo "封面描述：".$data['desc']."<br/>";
		echo "播放地址：".$data['play_url']."<br/>";
		echo "无水印地址：".$data['play_url2']."<br/>";*/
    }
} else {
    exit(need::json(array("code"=>-2, "text"=>"网址获取失败")));
}
function get_location($url) {
    $headers = @get_headers($url, TRUE);
    if (!empty($headers['location'])) {
        return $headers['location'];
    }
    return '';
}


?>