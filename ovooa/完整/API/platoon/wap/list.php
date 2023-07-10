<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>奇门排盘</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="applicable-device" content="pc,mobile">
<meta http-equiv="Cache-Control" content="no-transform">
<meta http-equiv="Cache-Control" content="no-siteapp">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" href="wap/style/list.css">
<script type="text/javascript" src="wap/style/jquery-1.10.2.min.js"></script>
</head>
<body>
<header class="header">
<h2 class="dis_flex"><span class="lf"><a href="index.php"><img src="wap/style/zjt.png" width="30"></a></span><span class="mid">CDECMS奇门排盘</span><span class="rt"><img src="wap/style/main.png" width="30"></span></h2>
</header>
<article>
<section>
<div class="hd pp_more_s"><i></i>基本信息<span data-s="0">&or;</span></div>
<div class="baseinfo">
<div class="bd">
	<div class="item dis_flex">
			<div class="flex dis_flex">
				<span class="tit">姓名：</span>
				<div class="text flex"><?php echo $bzname==''?'某人':$bzname;?></div>
			</div>
			<div class="flex dis_flex">
				<span class="tit">性别：</span>
				<div class="text flex"><?php echo $sex?'男':'女'?></div>
			</div>
		</div>
		<?php if($zty):?>
		<div class="item dis_flex">
			<span class="tit">北京时：</span>
			<div class="text flex"><small>公历<?php echo $qm['glstr']?><br>农历<?php echo $qm['nlstr']?></small></div>
		</div>
		<div class="item dis_flex">
			<span class="tit">太阳时：</span>
			<div class="text flex"><small>公历<?php echo $qm['truedatestr']?><br>农历<?php echo $qm['zty_nlstr']?></small></div>
		</div>
		<?php else:?>
		<div class="item dis_flex">
			<span class="tit">盘时：</span>
			<div class="text flex"><small>公历<?php echo $qm['glstr']?><br>农历<?php echo $qm['nlstr']?></small></div>
		</div>
		<?php endif;
		if(!empty($area)):?>
		<div class="item dis_flex">
			<span class="tit">起盘地：</span>
			<div class="text flex">
				<?php echo $area;?>
			</div>
		</div>
		<?php endif;?>
		<div class="item dis_flex">
			<span class="tit">节气：</span>
			<div class="text flex"><small><?php echo $qm['jqstr'];?></small></div>
		</div>
		<div class="item dis_flex">
			<span class="tit">四柱：</span>
			<div class="text flex"><font color="red"><?php echo $qm['ygz']?>&emsp;<?php echo $qm['mgz']?>&emsp;<?php echo $qm['dgz']?>&emsp;<?php echo $qm['hgz']?></font></div>
		</div>
		<div class="item dis_flex">
			<span class="tit">旬首：</span>
			<div class="text flex"><font color="blue">日-甲<?php echo $qm['dxs']?>旬&emsp;时-甲<?php echo $qm['hxs'],$qm['fushou']?></font></div>
		</div>
		<div class="item dis_flex">
			<span class="tit">定局：</span>
			<div class="text flex"><font color="red"><?php echo $qm['dunju'];?></font><?php echo ($qjumode==1)?'<br>':'&emsp;';?><?php echo $djumode?>定局</div>
		</div>
		<div class="item dis_flex">
			<span class="tit">值符：</span>
			<div class="text flex"><font color="red"><?php echo $qm['zhi']['zf'];?></font>落<?php echo $qm['zhi']['zfg'];?>宫&emsp;<b>值使：</b><font color="red"><?php echo $qm['zhi']['zs'];?></font>落<?php echo $qm['zhi']['zsg'];?>宫</div>
		</div>
	</div>
</div>
</section>
<section>
<div class="hd ui-cells"><i></i>奇门<?php echo ($panmode==1)?'转盘':'飞盘'?></div>
<div class="qmpp">
<?php
 if($panmode):?>
 <table width="100%" cellspacing="0" cellpadding="0" border="1" bordercolor="#FF9900" style="margin-top:10px;">
  <tr align="center">
    <td height="96" width="33%"><?php echo $qm['qmpan'][3];?></td>
    <td width="106"><?php echo $qm['qmpan'][4];?></td>
    <td width="106"><?php echo $qm['qmpan'][5];?></td>
  </tr>
  <tr align="center">
    <td height="96"><?php echo $qm['qmpan'][2];?></td>
    <td align="center" valign="middle">&emsp;&emsp;&emsp;&emsp;<font color="#aa9faa">五</font><br>&emsp;中宫&emsp;<?php echo $qm['qmpan'][8];?></td>
    <td><?php echo $qm['qmpan'][6];?></td>
  </tr>
  <tr align="center">
    <td height="96"><?php echo $qm['qmpan'][1];?></td>
    <td><?php echo $qm['qmpan'][0];?></td>
    <td><?php echo $qm['qmpan'][7];?></td>
  </tr>
</table>
 
<?php else:?>
 <table width="100%" border="1" cellspacing="0" cellpadding="0" style="margin-top:10px;">
  <tr align="center">
    <td height="96"><?php echo $qm['qmpan'][3];?></td>
    <td><?php echo $qm['qmpan'][8];?></td>
    <td><?php echo $qm['qmpan'][1];?></td>
  </tr>
  <tr align="center">
    <td height="96"><?php echo $qm['qmpan'][2];?></td>
    <td valign="middle"><?php echo $qm['qmpan'][4];?></td>
    <td><?php echo $qm['qmpan'][6];?></td>
  </tr>
  <tr align="center">
    <td height="96"><?php echo $qm['qmpan'][7];?></td>
    <td><?php echo $qm['qmpan'][0];?></td>
    <td><?php echo $qm['qmpan'][5];?></td>
  </tr>
</table>   
<?php endif;?>
</div>
</section>
</article>
<footer class="footer"><a href="https://beian.miit.gov.cn/#/recordQuery">冀ICP备2020028864号-4</a></footer>
<script>
$('.pp_more_s').click(function(){
		var that=$(this).find('span');
		var s=that.data('s');
		if(s==0) that.html("&and;");
		else that.html("&or;");
		var d=(s+1)%2;
		that.data('s',d);
		$(this).next().slideToggle(500);
	}
);
</script>
</body>
</html>