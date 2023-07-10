<?php
require_once '../../curl.php';
require_once '../../need.php';
/*
$id = $_REQUEST['id'];
$name = $_REQUEST['name'];
$n = $_REQUEST['n'];
if(!$id || !is_numEric($id) || $id < 1){
    die();
}
$name = $_REQUEST['name'];
if(empty($name)){
    die();
}*/
$array = Json_decode($_REQUEST['array'],true);
$file = $array['data']['text'];
$name = $array['data']['name'];
$title = $array['data']['title'];
$pid = $array['data']['pid'];
$cid = $array['data']['cid'];

?>
<!doctype html>
<html> 
    <head> 
        <meta http-equiv="Content-Type" content="application/xhtml+xml;charset=utf-8"> 
        <title><?php echo $name; ?>-<?php echo $title; ?></title> 
        <meta name="MobileOptimized" content="240"> 
        <meta name="applicable-device" content="mobile"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"> 
        <meta http-equiv="Cache-Control" content="no-transform"> 
        <meta http-equiv="Cache-Control" content="max-age=0"> 
        <meta http-equiv="Cache-Control" content="no-siteapp"> 
        <meta name="format-detection" content="telephone=no"> 
        <meta name="apple-mobile-web-app-capable" content="yes"> 
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> 
        <link rel="stylesheet" href="https://m.biquge7.com/css/style.css"> 
    </head> 
    <body id="read" class="read"> 
        <div class="read">
                <svg class="lnr lnr-chevron-left-circle"> <use xlink:href="#lnr-chevron-left-circle"></use> 
                </svg> </a> <span class="title"><?php echo $title; ?></span> <a class="user" href="/"> 
                </svg></a> 
        </div> 
        <p class="read " style="padding:2px;height:40px; line-height:40px;"> 字体： <a id="fontbig" class="sizebg" onclick="nr_setbg('big')">大</a> <a id="fontmiddle" class="sizebg" onclick="nr_setbg('middle')">中</a> <a id="fontsmall" class="sizebg" onclick="nr_setbg('small')">小</a>&nbsp;&nbsp;&nbsp;&nbsp; <a id="huyandiv" class="button huyanon" onclick="nr_setbg('huyan')">护眼</a> <a id="lightdiv" class="button lightoff" onclick="nr_setbg('light')">关灯</a> </p> 
        <div id="chaptercontent" class="Readarea ReadAjax_content">
            <?php  echo $file; ?>
            <script type="text/javascript" src="https://m.biquge7.com/js/read.js"></script> 
        </div>
        <p class="read pagedown">
            <a href="http://ovooa.com/API/book/book_v1?id=<?php echo $pid; ?>" id="pb_prev" class="Readpage_up">上一章</a>
            <a href="http://ovooa.com/API/book/book_v1?id=<?php echo $nid.'&n='.$n; ?>" id="pb_next" class="Readpage_down js_page_down">下一章</a>
        </p>
    </body>
</html>