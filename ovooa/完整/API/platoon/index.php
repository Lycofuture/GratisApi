<?php
require_once('./inc/common.php');
if(!isset($_POST['act'])){
	include_once(VPATH.'form.php');
}elseif($_POST['act']=='cdecms'){
	require_once(INCPATH.'Qminfo.class.php');
	$bzname=$_POST['name'];
	$sex=$_POST['usex'];
	$area=$_POST['area'];
	$areaid=$_POST['areaid'];
	$gl_birthday=$_POST['gl_birthday'];
	$datetype=$_POST['dateType'];
	$zty=isset($_POST['zty'])? $_POST['zty'] : 0;
	if($zty){
		include_once(INCPATH.'qk_area_cfg.php');
		$jd=$qk_area_pos[$areaid];
	}
	$qjumode=$_POST['qjumode'];
	if($qjumode==0){$djumode='拆补法';}elseif($qjumode==1){$djumode='置闰法';}else{$djumode='茅山法';}
	$panmode=$_POST['panmode'];
	$fpsn=isset($_POST['fpsn'])?$_POST['fpsn']:0;
	$jd = isset($jd) ? $jd : null;
	$config=array('date'=>$gl_birthday,'datetype'=>$datetype,'jd'=>$jd,'sex'=>$sex,'zty'=>$zty,'qjumode'=>$qjumode,'panmode'=>$panmode,'fpsn'=>$fpsn);
	$info=new Qminfo($config);
	$qm=$info->get_info();
	include_once(VPATH.'list.php');
}
?>