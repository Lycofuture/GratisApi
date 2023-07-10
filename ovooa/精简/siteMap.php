<?php
//以这里明确告诉浏览器输出的格式为xml,不然浏览器显示不出xml的格式
require_once('./need.php'); //把数据源加载进来
$host = $_SERVER['HTTP_HOST'];
$data = json_decode(need::teacher_curl('http://' . $_SERVER['HTTP_HOST'] . '/Data/api.php?type=getAllApi'), true)['data'];
//print_r($data);exit;
$sitemap=$data; //这里要按照sitemap的格式构造出xml的文件,urlset url loc是规定必须有的标签
if(!file_exists(__DIR__.'/sitemap.xml')){
    file_put_Contents(__DIR__.'/sitemap.xml', '');
}
$file = @file_get_Contents(__DIR__.'/sitemap.xml');
if($file){
    $xmlold = new SimpleXMLElement(file_get_Contents(__DIR__.'/sitemap.xml'));
}else{
    $xmlold = '';
}
$xml_wrapper = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
</urlset>
XML;

//$xml = simplexml_load_string($xml_wrapper);
$xml = new SimpleXMLElement($xml_wrapper);

foreach ($sitemap as $data) {
    $item = $xml->addChild('url'); //使用addChild添加节点
    $item->addchild('loc', 'http://' . $host . '/?action=doc&amp;id='.$data['id'].'');
    $item->addchild('lastmod', ($data['time']));
    $item->addchild('changefreq', 'monthly');
    // $item->addchild('name', $data['name']);
    // $item->addchild('description', $data['desc']);
    $item->addchild('priority', $data['status'].'.0');
}
if($xml != $xmlold){
    $file = $xml->asXML(__DIR__.'/sitemap.xml'); //用asXML方法输出xml，默认只构造不输出。
}
$xml->asXML(__DIR__.'/sitemap.xml');//exit;

?>