<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>奇门排盘-奇门遁甲排盘</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="applicable-device" content="pc,mobile">
<meta http-equiv="Cache-Control" content="no-transform">
<meta http-equiv="Cache-Control" content="no-siteapp">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" href="wap/style/index.css">
<link rel="stylesheet" href="wap/style/iosSelectdate/iosSelect.css">
<script type="text/javascript" src="wap/style/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="wap/style/iosSelectdate/calendar.js"></script>
<script type="text/javascript" src="wap/style/iosSelectdate/iosSelect.js"></script>
<script type="text/javascript" src="wap/style/getcity.js"></script>
</head>
<body>
<header class="header">
<h2 class="dis_flex"><span class="lf"><a href="index.php"><img src="wap/style/zjt.png" width="30"></a></span><span class="mid">奇门排盘</span><span class="rt"><img src="wap/style/main.png" width="30"></span></h2>
</header>
<article>
<section>
	<div class="form_box">
	<form id="pqmform" action="index.php" method="post">
	<input type="hidden" name="act" value="cdecms">
	<div class="item dis_flex">
         <div class="dis_flex flex_flow_c"><span class="icon user"></span></div>
         <div class="flex input"><input type="text" name="name" placeholder="姓名" maxlength="4"></div>
         <div class="dis_flex checked">
          <label class="dis_flex on"><input type="radio" name="usex" value="1" hidden="" checked=""><em><i></i></em><span>男</span></label>
          <label class="dis_flex"><input type="radio" name="usex" value="0" hidden=""><em><i></i></em><span>女</span></label>
          </div>
     </div>
	 <div class="item dis_flex">
          <div class="dis_flex flex_flow_c"><span class="icon site"></span></div>
          <div class="flex input">
           <input type="text" name="area" class="cs" placeholder="起盘地点" readonly unselectable="on" onfocus="this.blur()">
           <input type="hidden" name="areaid" class="harea" value="">
          </div>
          <div class="dis_flex checkbox">
            <label class="dis_flex"><input type="checkbox" name="zty" value="1" hidden><em><i></i></em><span>真太阳时</span></label>
           </div>
     </div>
	 <div class="item dis_flex">
           <div class="dis_flex flex_flow_c"><span class="icon date"></span></div>
           <div class="flex input selectdate"><input type="text" name="birth_date" class="datetime" placeholder="起盘时间" readonly unselectable="on" onfocus="this.blur()" value="<?php echo date('Y-m-d H:i')?>">
				<input type="hidden" name="gl_birthday" class="gl_birthday" value="<?php echo date('Y-m-d H:i')?>">
			 	<input type="hidden" name="dateType" value="0">
			</div>
     </div>
	 <div class="item0">
		  <div class="dis_flex morechecked">
		  <label class="flex on"><input name="qjumode" type="radio" value="0" hidden checked>拆补法</label>
		 <label class="flex"><input name="qjumode" type="radio" value="1" hidden>置闰法</label>
		 <label class="flex"><input name="qjumode" type="radio" value="2" hidden>茅山法</label>
          </div>
     </div>
	 <div class="item dis_flex">
          <div class="dis_flex flex_flow_c"><span class="icon site"></span></div>
		  <div class="dis_flex th">盘式：</div>
		  <div class="dis_flex checked">
          <label class="dis_flex on"><input type="radio" name="panmode" value="1" hidden checked><em><i></i></em><span>转盘</span></label>
          <label class="dis_flex"><input type="radio" name="panmode" value="0" hidden><em><i></i></em><span>飞盘</span></label>
          </div>
     </div>
	 <div class="item none fppaifa">
	 	<div class="dis_flex flex_flow_c"><span class="icon industry"></span></div>
	 	<div class="dis_flex checked">
	 	<label class="dis_flex on"><input checked name="fpsn" type="radio" value="2" hidden checked><em><i></i></em><span>阴顺阳逆</span></label>
	 	<label class="dis_flex"><input name="fpsn" type="radio" value="1" hidden><em><i></i></em><span>全部顺排</span></label>
		</div>
	</div>
     <div class="sub"><input type="button" name="" value="立即提交" class="href" onClick="checkpqm();"></div>
    </form>
</div>
</section>
</article>
<footer class="footer">奇门排盘系统</footer>
<footer class="footer"><a href="https://beian.miit.gov.cn/#/recordQuery">冀ICP备2020028864号-4</a></footer>
<script type="text/javascript" src="wap/style/iosSelectdate/iosDate_index.js"></script>
<script type="text/javascript" src="wap/style/ppqm.js"></script>	
</body>
</html>