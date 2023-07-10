<?php 
/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(29); // 调用统计函数
/* End */
$yy=file_get_contents('http://lkaa.top/API/yiyan/api.php');//这里是随机一言，可以自己改
?>
<!DOCTYPE html>
<html lang="zh-cn">
 <head>
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="shortcut icon" type="image/x-icon" href="css/favicon.ico" />
  <title><?php echo @$_REQUEST['name'] ?>主页</title>
  <meta name="keywords" content="<?php echo $_GET['name'] ?>主页,百度<?php echo $_GET['name'] ?>主机,百度<?php echo $_GET['name'] ?>互联,绚丽彩虹播放器,明月浩空播放器,wordpress音乐插件,emlog音乐插件,typecho音乐插件,z-blog音乐插件,HTML5音乐播放器,网页播放器,<?php echo $_GET['name'] ?>网页播放器,<?php echo $_GET['name'] ?>免费播放器" />
  <meta name="description" content="本来想弄个博客的,后来想想本来也就没什么时间,就弄了个<?php echo $_GET['name'] ?>主页,电脑端还没开始写...因为实在是太懒了" />
  <link rel="stylesheet" href="css/1.css" /><!--放我博客了，不喜欢自己改成css/1.css--->
</script>
 </head>
 <body>
   <section class="top">
    <img src="http://lkaa.top/API/sjbz/api.php?lx=dongman" class="top__bg"/><!---这里是随机图片，可以自己改接口--->
    <div class="top__cont">
     <div class="top__avatar">
      <img src="http://q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $_GET['qq'] ?>&spec=100" class="top__avatar_img"/>
     </div>
     <div class="top__tit" id="js_name_wrap">
      <h2 class="top__tit_txt" id="js_name"><span style="display:block;transform:translateX(0px);"><?php echo $_GET['name'] ?></span></h2>
     </div>
      <div class="top__other_bd">
       <div class="top__date">
        <span class="top__date_item">QQ: <?php echo $_GET['qq'] ?></span>
      </div>
     </section>
     <header class="mod_tit">
      <h2 class="tit__txt">签名</h2>
     </header>
     <section class="basic_info">
      <div class="basic_info__txt">
       <p><?php echo @$_REQUEST['wd'] ?></p>
      </div>
     </section>
     <header class="mod_tit">
      <?php echo $yy;?>
     </header>
     <li class="mui_cell_list__item">
        <div class="mui_cell_list__box">
        <script>misaka()</script>
   </div>
   <header class="mod_tit">
       <img src="http://ovooa.com/API/sjbz/api.php?lx=dongman&rand=123456789" width="298" height="156"></header><!---这个是那个乱七八糟的图片，自己改吧>
     <header class="mod_tit">
     <header class="mod_tit">
      <h2 class="tit__txt">版权所有 <?php echo $_GET['name'] ?>blog </h2>
      <style>#circle{opacity:0;filter:alpha(opacity='0') background-color:#ffffff;border:5px solid #FF0000;opacity:.9;border-right:5px solid rgba(0,0,0,0);border-left:5px solid rgba(0,0,0,0);border-radius:50px;box-shadow:0 0 35px #FF0000;width:60px;height:60px;margin:0 auto;position:fixed;left:30px;bottom:30px;-moz-animation:spinPulse 1s infinite linear;-webkit-animation:spinPulse 1s infinite linear;-o-animation:spinPulse 1s infinite linear;-ms-animation:spinPulse 1s infinite linear;}#circle1{opacity:0;filter:alpha(opacity='0') background-color:#ffffff;border:6px solid #FF0000;opacity:.9;border-left:6px solid rgba(0,0,0,0);border-right:6px solid rgba(0,0,0,0);border-radius:50px;box-shadow:0 0 15px #FF0000;width:40px;height:40px;margin:0 auto;position:fixed;left:39px;bottom:39px;-moz-animation:spinoffPulse 1s infinite linear;-webkit-animation:spinoffPulse 1s infinite linear;-o-animation:spinoffPulse 1s infinite linear;-ms-animation:spinoffPulse 1s infinite linear;}#circletext{opacity:0;filter:alpha(opacity='0') width:46px;height:20px;margin:0 auto;position:fixed;left:46px;bottom:53px;color:#F00}@-moz-keyframes spinPulse{0%{-moz-transform:rotate(160deg);opacity:0;box-shadow:0 0 1px #505050;}50%{-moz-transform:rotate(145deg);opacity:1;}100%{-moz-transform:rotate(-320deg);opacity:0;}}@-moz-keyframes spinoffPulse{0%{-moz-transform:rotate(0deg);}100%{-moz-transform:rotate(360deg);}}@-webkit-keyframes spinPulse{0%{-webkit-transform:rotate(160deg);opacity:0;box-shadow:0 0 1px #505050;}50%{-webkit-transform:rotate(145deg);opacity:1;}100%{-webkit-transform:rotate(-320deg);opacity:0;}}@-webkit-keyframes spinoffPulse{0%{-webkit-transform:rotate(0deg);}100%{-webkit-transform:rotate(360deg);}}@-o-keyframes spinPulse{0%{-o-transform:rotate(160deg);opacity:0;box-shadow:0 0 1px #505050;}50%{-o-transform:rotate(145deg);opacity:1;}100%{-o-transform:rotate(-320deg);opacity:0;}}@-o-keyframes spinoffPulse{0%{-o-transform:rotate(0deg);}100%{-o-transform:rotate(360deg);}}@-ms-keyframes spinPulse{0%{-ms-transform:rotate(160deg);opacity:0;box-shadow:0 0 1px #505050;}50%{-ms-transform:rotate(145deg);opacity:1;}100%{-ms-transform:rotate(-320deg);opacity:0;}}@-ms-keyframes spinoffPulse{0%{-ms-transform:rotate(0deg);}100%{-ms-transform:rotate(360deg);}}</style>
<div id="circle" style="opacity: 1;"></div>
<div id="circletext" style="opacity: 1;"></div>
<div id="circle1" style="opacity: 1;"></div>
 </body>
</html>