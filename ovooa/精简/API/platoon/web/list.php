<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $bzname?>奇门遁甲排盘</title>
<link rel="stylesheet" href="web/style/index.css">
</head>
<body>
<div class="pp_box">
<h2>奇门遁甲排盘</h2>
<div style="padding-bottom:20px;">
<b>姓名：</b> <?php echo $bzname?>&emsp;&emsp;<b>性别：</b> <?php echo($sex==1)?"男":"女";?>&emsp;&emsp;
<?php if(@$jutag==0):?>
<b>出生地：</b><?php echo $area; if($zty==1){?>(经度：<?php echo $jd?>度)<?php }?><br>
<?php if($zty):?>
<b>北京时间：</b>公历<?php echo $qm['glstr']?>&emsp;&emsp;<b>农历：</b><?php echo $qm['nlstr'];?><br>
<b>真太阳时：</b>公历<?php echo $qm['truedatestr']?>&emsp;&emsp;<b>农历：</b><?php echo $qm['zty_nlstr'];?><br>
<?php else:?>
<b>公历：</b><font color="#993300"><?php echo $qm['glstr'];?></font><br>
<b>农历：</b><font color="#993300"><?php echo $qm['nlstr'];?></font><br>
<?php endif;?>
<b>节气：</b><?php echo $qm['jqstr'];?><br>
<?php endif;?>
<b>四柱：</b><font color="red"><?php echo $qm['ygz']?>&emsp;&emsp;<?php echo $qm['mgz']?>&emsp;&emsp;<?php echo $qm['dgz']?>&emsp;&emsp;<?php echo $qm['hgz']?></font><br>
<b>旬空：</b><font color="blue">日空-<?php echo $qm['dxk']?></font>(甲<?php echo $qm['dxs']?>旬)&emsp;<font color="blue">时空-<?php echo $qm['hxk']?></font>(甲<?php echo $qm['hxs'],$qm['fushou']?>)<br>
<b>定局：</b><font color="red"><?php echo $qm['dunju'];?></font>&emsp;<?php echo $djumode?>定局<br>
<b>值符：</b><font color="red"><?php echo $qm['zhi']['zf'];?></font>落<?php echo $qm['zhi']['zfg'];?>宫&emsp;<b>值使：</b><font color="red"><?php echo $qm['zhi']['zs'];?></font>落<?php echo $qm['zhi']['zsg'];?>宫<br>
<b><?php echo ($panmode==1)?'转盘':'飞盘'?>：</b>--------------------------------------------------<br>
<?php
 if($panmode):?>
 <table width="318" cellspacing="0" cellpadding="0" border="1" style="margin-top:10px;">
  <tr align="center">
    <td height="96" width="106"><?php echo $qm['qmpan'][3];?></td>
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
 <table width="300" border="1" cellspacing="0" cellpadding="0" style="margin-top:10px;">
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
<div class="rebtn"><input type="button"  class="reset" onClick="(location='index.php')" /></div>
</div>
<footer class="footer"><a href="https://beian.miit.gov.cn/#/recordQuery">冀ICP备2020028864号-4</a></footer>
</body>
</html>