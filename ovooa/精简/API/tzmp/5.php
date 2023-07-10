<?php
$QQ=$_GET["qq"];
if ($QQ==null){
echo '请填写QQ';
}
<SCRIPT language=javascript>
　　 function click() {
　　 if (event.button==2) {
　　 alert('    ')
　　 }
　　 }
　　 document.onmousedown=click
　　 </SCRIPT>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title></title>
<script language="javascript" type="text/javascript">
var ua = navigator.userAgent;
var ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
    isIphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
    isAndroid = ua.match(/(Android)\s+([\d.]+)/),
    isMobile = isIphone || isAndroid;
 if(isIphone){
        location.href = 'mqqapi://card/show_pslcard?src_type=internal&version=1&uin=$QQ&card_type=person&source=sharecard';
    }else if(isAndroid){
        location.href = 'mqqapi://card/show_pslcard?src_type=internal&version=1&uin=$QQ&card_type=person&source=sharecard';
    }else{
        location.href = 'tencent://AddContact/?fromId=45&fromSubId=1&subcmd=all&uin=$QQ';
    }
</script></head>
<body></body>
<html>