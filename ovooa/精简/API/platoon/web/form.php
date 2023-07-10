<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>奇门遁甲排盘</title>
<link rel="stylesheet" href="web/style/index.css">
<link rel="stylesheet" href="web/style/iosSelectdate/iosSelect.css">
<script type="text/javascript" src="web/style/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="web/style/iosSelectdate/calendar.js"></script>
<script type="text/javascript" src="web/style/iosSelectdate/iosSelect.js"></script>
<script type="text/javascript" src="web/style/getcity.js"></script>
</head>
<body>
<div class="pp_box">
<h2>在线奇门排盘</h2>
<form id="pqmform" action="index.php" method="post">
<input name="act" type="hidden" value="cdecms">
<dl class="pp_dl">
<dt>姓名性别：</dt>
<dd><input name="name" type="text" style="width:120px"><em></em><input name="usex" type="radio" value="1" checked>男<em></em><input type="radio" name="usex" value="0">女
</dd>
</dl>
<dl class="pp_dl">
<dt>起盘城市：</dt>
<dd>
<input name="area" type="text" id="area" readonly unselectable="on" onfocus="this.blur()">
<input type="hidden" name="areaid" id="areaid" value=""><em></em>
<input type="checkbox" value="1" name="zty">真太阳时<span class="help"><div class="help_zty hide">真太阳时即通过太阳与地球某坐标点的相对位置来计算的时间，相对于北京时能更准确表达一个地区的真实时间。</div></span>
</dd>
</dl>
<dl class="pp_dl">
<dt>起盘时间：</dt>
<dd>
<span class="selectdate">
<input type="text" class="datetime" readonly unselectable="on" onfocus="this.blur()" value="<?php echo date('Y-m-d H:i')?>">
<input type="hidden" name="gl_birthday" class="gl_birthday" value="<?php echo date('Y-m-d H:i')?>">
<input type="hidden" name="dateType" value="0">
</span>
</dd>
</dl>
<dl class="pp_dl">
<dt>起局方法：</dt>
<dd>
<input name="qjumode" type="radio" value="0" checked>拆补法&emsp;&emsp;
<input name="qjumode" type="radio" value="1" >置润法&emsp;&emsp;
<input name="qjumode" type="radio" value="2" >茅山道人法&emsp;&emsp;
</dd>
</dl>
<dl class="pp_dl">
<dt>盘式：</dt>
<dd>
<input name="panmode" type="radio" value="1" checked>转盘奇门&emsp;&emsp;
<input name="panmode" type="radio" value="0" >飞盘奇门
</dd>
</dl>
<dl class="pp_dl none fppaifa">
<dt>飞盘排法：</dt>
<dd><input checked name="fpsn" type="radio" value="2" checked>阴顺阳逆&emsp;&emsp;<input name="fpsn" type="radio" value="1">全部顺排
</dd>
</dl>

<dl class="pp_dl"><dt></dt><dd><input type="button" class="btn" onClick="checkpqm();"></dd></dl>
</form>

</div>
<div class="my_modal_bg"></div>
<footer class="footer"><a href="https://beian.miit.gov.cn/#/recordQuery">冀ICP备2020028864号-4</a></footer>
<script type="text/javascript" src="web/style/iosSelectdate/iosDate_index.js"></script>
<script type="text/javascript" src="web/style/ppqm.js"></script>
</body>
</html>